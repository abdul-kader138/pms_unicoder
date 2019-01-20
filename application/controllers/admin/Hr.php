<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Hr extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('application_model');

        $this->load->helper('ckeditor');
        $this->data['ckeditor'] = array(
            'id' => 'ck_editor',
            'path' => 'asset/js/ckeditor',
            'config' => array(
                'toolbar' => "Full",
                'width' => "99.8%",
                'height' => "400px"
            )
        );
    }

    public function index($action = NULL, $id = NULL)
    {
        $data['title'] = lang('residence');

        $data['active'] = 1;
        $data['leave_active'] = 1;
        if ($action == 'view_details') {
            $data['view'] = true;
            $data['active'] = 4;
        }
        if ($action == 'edit') {
            $data['leave_active'] = 2;
        }
        if ($id) {
            $data['application_info'] = $this->application_model->check_by(array('id_resd' => $id), 'tbl_residences');

        } else {
            $data['active'] = 1;
            $data['leave_active'] = 1;
        }

        $data['subview'] = $this->load->view('admin/Hr/residence_list', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
		
    }

    public function change_residence()
    {
        $data['title'] = lang('add_residence');
        $data['modal_subview'] = $this->load->view('admin/hr/change_residence', $data, FALSE);
		$this->load->view('admin/_layout_modal', $data);
    }

    public function get_holiday_list($year)
    {// this function is to create get monthy recap report
        for ($i = 1; $i <= 12; $i++) { // query for months
            if ($i >= 1 && $i <= 9) { // if i<=9 concate with Mysql.becuase on Mysql query fast in two digit like 01.
                $start_date = $year . "-" . '0' . $i . '-' . '01';
                $end_date = $year . "-" . '0' . $i . '-' . '31';
            } else {
                $start_date = $year . "-" . $i . '-' . '01';
                $end_date = $year . "-" . $i . '-' . '31';
            }
            $get_holiday_list[$i] = $this->global_model->get_holiday_list_by_date($start_date, $end_date); // get all report by start date and in date
        }
        return $get_holiday_list; // return the result
    }

    public function save_holiday($id = NULL)
    {
        $this->application_model->_table_name = "tbl_leave_application"; // table name
        $this->application_model->_primary_key = "leave_application_id"; // $id
        //receive form input by post
        $data['user_id'] = $this->input->post('user_id', true);
        if (empty($data['user_id'])) {
            $data['user_id'] = $this->session->userdata('user_id');
        }
        $data['leave_category_id'] = $this->input->post('leave_category_id', true);
        $data['leave_type'] = $this->input->post('leave_type', true);
        if (!empty($data['leave_type'])) {
            if ($data['leave_type'] == 'single_day') {
                $start_date = $this->input->post('single_day_start_date', true);
                $end_date = null;
                $hours = null;
            }
            if ($data['leave_type'] == 'multiple_days') {
                $start_date = $this->input->post('multiple_days_start_date', true);
                $end_date = $this->input->post('multiple_days_end_date', true);;
                $hours = null;
            }
            if ($data['leave_type'] == 'hours') {
                $start_date = $this->input->post('hours_start_date', true);
                $end_date = null;
                $hours = $this->input->post('hours', true);
            }
        }

        $data['leave_start_date'] = $start_date;
        $data['leave_end_date'] = $end_date;
        $data['hours'] = $hours;

        if (!empty($data['leave_end_date']) && strtotime($data['leave_start_date']) > strtotime($data['leave_end_date'])) {
            $type = "error";
            $message = lang('end_date_less_than_error');
        } else {
            $check_validation = $this->check_available_leave($data['user_id'], $data['leave_start_date'], $data['leave_end_date'], $data['leave_category_id']);

            if (!empty($check_validation)) {
                $type = "error";
                $message = $check_validation;
            } else {
                $data['reason'] = $this->input->post('reason');

                //  File upload
                $upload_file = array();
                $files = $this->input->post("files");
                $target_path = getcwd() . "/uploads/";
                //process the fiiles which has been uploaded by dropzone
                if (!empty($files) && is_array($files)) {
                    foreach ($files as $key => $file) {
                        if (!empty($file)) {
                            $file_name = $this->input->post('file_name_' . $file);
                            $new_file_name = move_temp_file($file_name, $target_path);
                            $file_ext = explode(".", $new_file_name);
                            $is_image = check_image_extension($new_file_name);
                            $size = $this->input->post('file_size_' . $file) / 1000;
                            if ($new_file_name) {
                                $up_data = array(
                                    "fileName" => $new_file_name,
                                    "path" => "uploads/" . $new_file_name,
                                    "fullPath" => getcwd() . "/uploads/" . $new_file_name,
                                    "ext" => '.' . end($file_ext),
                                    "size" => round($size, 2),
                                    "is_image" => $is_image,
                                );
                                array_push($upload_file, $up_data);
                            }
                        }
                    }
                }
                if (!empty($upload_file)) {
                    $data['attachment'] = json_encode($upload_file);
                } else {
                    $data['attachment'] = null;
                }
                //save data in database
                $id = $this->application_model->save($data);

                save_custom_field(17, $id);

                $appl_info = $this->application_model->check_by(array('leave_application_id' => $id), 'tbl_leave_application');
                $profile_info = $this->application_model->check_by(array('user_id' => $appl_info->user_id), 'tbl_account_details');
                $leave_category = $this->application_model->check_by(array('leave_category_id' => $appl_info->leave_category_id), '	tbl_leave_category');

                // save into activities
                $activities = array(
                    'user' => $this->session->userdata('user_id'),
                    'module' => 'leave_management',
                    'module_field_id' => $id,
                    'activity' => 'activity_leave_save',
                    'icon' => 'fa-ticket',
                    'value1' => $profile_info->fullname . ' -> ' . $leave_category->leave_category,
                    'value2' => strftime(config_item('date_format'), strtotime($appl_info->leave_start_date)) . ' TO ' . strftime(config_item('date_format'), strtotime($appl_info->leave_end_date)),
                );
                // Update into tbl_project
                $this->application_model->_table_name = "tbl_activities"; //table name
                $this->application_model->_primary_key = "activities_id";
                $this->application_model->save($activities);

                // send email to departments head
                if ($appl_info->application_status == 1) {
                    // get departments head user id
                    if (!empty($profile_info->designations_id)) {
                        $designation_info = $this->application_model->check_by(array('designations_id' => $profile_info->designations_id), 'tbl_designations');
                        if (!empty($designation_info)) {
                            // get departments head by departments id
                            $dept_head = $this->application_model->check_by(array('departments_id' => $designation_info->departments_id), 'tbl_departments');
                            if (!empty($dept_head->department_head_id)) {
                                $leave_email = config_item('leave_email');
                                if (!empty($leave_email) && $leave_email == 1) {
                                    $email_template = $this->application_model->check_by(array('email_group' => 'leave_request_email'), 'tbl_email_templates');
                                    $user_info = $this->application_model->check_by(array('user_id' => $dept_head->department_head_id), 'tbl_users');
                                    if (!empty($user_info)) {
                                        $message = $email_template->template_body;
                                        $subject = $email_template->subject;
                                        $username = str_replace("{NAME}", $profile_info->fullname, $message);
                                        $Link = str_replace("{APPLICATION_LINK}", base_url() . 'admin/leave_management/index/view_details/' . $id, $username);
                                        $message = str_replace("{SITE_NAME}", config_item('company_name'), $Link);
                                        $data['message'] = $message;
                                        $message = $this->load->view('email_template', $data, TRUE);

                                        $params['subject'] = $subject;
                                        $params['message'] = $message;
                                        $params['resourceed_file'] = '';
                                        $params['recipient'] = $user_info->email;
                                        $this->application_model->send_email($params);
                                    }
                                }
                                $notifyUser = array($dept_head->department_head_id);
                                if (!empty($notifyUser)) {
                                    foreach ($notifyUser as $v_user) {
                                        if (!empty($v_user)) {
                                            if ($v_user != $this->session->userdata('user_id')) {
                                                add_notification(array(
                                                    'to_user_id' => $v_user,
                                                    'description' => 'not_leave_request',
                                                    'icon' => 'clock-o',
                                                    'link' => 'admin/leave_management/index/view_details/' . $id,
                                                    'value' => lang('by') . ' ' . $profile_info->fullname,
                                                ));
                                            }
                                        }
                                    }
                                }
                                if (!empty($notifyUser)) {
                                    show_notification($notifyUser);
                                }
                            }
                        }
                    }

                }


                // messages for user
                $type = "success";
                $message = lang('leave_successfully_save');
            }
        }
        set_message($type, $message);
        redirect('admin/leave_management');
    }

    public function delete_holiday($id)
    { // delete holiday list by id
        $deleted = can_action('71', 'deleted');
        if (!empty($deleted)) {
            $check_holiday = $this->global_model->check_by(array('holiday_id' => $id), 'tbl_holiday');
            $this->global_model->_table_name = "tbl_holiday"; //table name
            $this->global_model->_primary_key = "holiday_id";    //id
            $this->global_model->delete($id);
            // save into activities
            $activities = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'holiday',
                'module_field_id' => $id,
                'activity' => 'activity_delete_holiday',
                'icon' => 'fa-ticket',
                'value1' => $check_holiday->event_name,
            );
// Update into tbl_project
            $this->global_model->_table_name = "tbl_activities"; //table name
            $this->global_model->_primary_key = "activities_id";
            $this->global_model->save($activities);

            $type = "success";
            $message = lang('holoday_information_delete');
            set_message($type, $message);
        }
        redirect('admin/holiday'); //redirect page
    }
}
