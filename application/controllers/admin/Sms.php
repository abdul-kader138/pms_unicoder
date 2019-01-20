<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sms extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('estimates_model');
        $this->load->model('invoice_model');
        $this->load->model('proposal_model');
        $this->load->library('google_url_api');
    }
	public function new_sms(){
            $data['modal_subview'] = $this->load->view('admin/invoice/new_sms');
            $this->load->view('admin/_layout_modal', $data);
	}
	public function send_invoice_sms($id){
		if(!empty($id)){
			$data['invoice_info'] = $this->invoice_model->check_by(array('invoices_id' => $id), 'tbl_invoices');
			$data['modal_subview'] = $this->load->view('admin/invoice/modal_sms', $data, FALSE);
			$this->load->view('admin/_layout_modal', $data);
		}
	}
    public function send_sms()
    {
        $this->google_url_api->enable_debug(FALSE);
        if($this->input->post('type', TRUE) == "invoice"){
			$invoice_id = $this->input->post('invoice_id', TRUE);
			$client_info = $this->invoice_model->check_by(array('client_id' => $invoice_id), 'tbl_client');
			$message = $this->input->post('message', TRUE);
			$ref = $this->input->post('ref', TRUE);
			$client = ucfirst($client_info->name);
			$amount = $this->input->post('amount', true);
			$currency = $this->input->post('currency', TRUE);
			if(!empty($invoice_id) && !empty($ref)  && !empty($client_info)){
				$invoice_info = $this->invoice_model->check_by(array('invoices_id' => $invoice_id), 'tbl_invoices');
				if(!empty($client_info->mobile)){
					$client_name = str_replace("{CLIENT}", $client, $message);
					$Ref = str_replace("{REF}", $ref, $client_name);
					$Amount = str_replace("{AMOUNT}", $amount, $Ref);
					$Currency = str_replace("{CURRENCY}", $currency, $Amount);
					$url = "".base_url() . 'client/invoice/manage_invoice/invoice_details/' . $invoice_id."";
					$short_url = $this->google_url_api->shorten($url);
					if($this->google_url_api->get_http_status() == "200"){
						$link = str_replace("{INVOICE_LINK}", $short_url->id, $Currency);
					}else{
						$link = str_replace("{INVOICE_LINK}", base_url() . 'client/invoice/manage_invoice/invoice_details/' . $invoice_id, $Currency);
					}
					$message = str_replace("{SITE_NAME}", config_item('company_name'), $link);
			
					$send = $this->send_sms_Api($client_info->mobile, $message); // Email Invoice
		
					$data = array('status' => 'sent', 'emailed' => 'Yes', 'date_sent' => date("Y-m-d H:i:s", time()));
			
					$this->invoice_model->_table_name = 'tbl_invoices';
					$this->invoice_model->_primary_key = 'invoices_id';
					$this->invoice_model->save($data, $invoice_id);
			
					// Log Activity
					$activity = array(
						'user' => $this->session->userdata('user_id'),
						'module' => 'invoice',
						'module_field_id' => $invoice_id,
						'activity' => ('activity_invoice_sent'),
						'icon' => 'fa-shopping-cart',
						'link' => 'admin/invoice/manage_invoice/invoice_details/' . $invoice_id,
						'value1' => $ref,
						'value2' => $this->input->post('currency', TRUE) . ' ' . $this->input->post('amount'),
					);
					$this->invoice_model->_table_name = 'tbl_activities';
					$this->invoice_model->_primary_key = 'activities_id';
					$this->invoice_model->save($activity);
					// messages for user
					$type = "success";
					$imessage = lang('invoice_sent');
					set_message($type, $imessage);
			
					echo $send;
				}
			}
		}elseif($this->input->post('type', TRUE) == "estimate"){
            $estimates_id = $this->input->post('estimates_id', TRUE);
            $message = $this->input->post('message', TRUE);
            $ref = $this->input->post('ref', TRUE);
            $client = $this->input->post('client_id', TRUE);
            $amount = $this->input->post('amount', true);
            $currency = $this->input->post('currency', TRUE);
			if(!empty($estimates_id) && !empty($message) && !empty($ref)  && !empty($client)){
				$estimates_info = $this->estimates_model->check_by(array('estimates_id' => $estimates_id), 'tbl_estimates');
				$client_info = $this->estimates_model->check_by(array('client_id' => $client), 'tbl_client');
				$clientt = ucfirst($client_info->name);
				if(!empty($client_info->mobile)){
					$client_name = str_replace("{CLIENT}", $clientt, $message);
					$Ref = str_replace("{ESTIMATE_REF}", $ref, $client_name);
					$Amount = str_replace("{AMOUNT}", $amount, $Ref);
					$Currency = str_replace("{CURRENCY}", $currency, $Amount);
					$url = "".base_url() . 'client/estimates/index/estimates_details/' . $estimates_id."";
					$short_url = $this->google_url_api->shorten($url);
					if($this->google_url_api->get_http_status() == "200"){
						$link = str_replace("{ESTIMATE_LINK}", $short_url->id, $Currency);
					}else{
						$link = str_replace("{ESTIMATE_LINK}", base_url() . 'client/estimates/index/estimates_details/' . $estimates_id, $Currency);
					}
					$message = str_replace("{SITE_NAME}", config_item('company_name'), $link);
			
					$send = $this->send_sms_Api($client_info->mobile, $message);
			
					$data = array('status' => 'sent', 'emailed' => 'Yes', 'date_sent' => date("Y-m-d H:i:s", time()));
			
					$this->estimates_model->_table_name = 'tbl_estimates';
					$this->estimates_model->_primary_key = 'estimates_id';
					$this->estimates_model->save($data, $estimates_id);
			
					// Log Activity
					$activity = array(
						'user' => $this->session->userdata('user_id'),
						'module' => 'estimates',
						'module_field_id' => $estimates_id,
						'activity' => 'activity_estimates_sent',
						'icon' => 'fa-shopping-cart',
						'link' => 'admin/estimates/index/estimates_details/' . $estimates_id,
						'value1' => $ref
					);
					$this->estimates_model->_table_name = 'tbl_activities';
					$this->estimates_model->_primary_key = 'activities_id';
					$this->estimates_model->save($activity);
			
					$type = 'success';
					$text = lang('estimate_email_sent');
					set_message($type, $text);
					echo $send;
				}
			}
		}elseif($this->input->post('type', TRUE) == "proposal"){
            $proposals_id = $this->input->post('proposals_id', TRUE);
            $message = $this->input->post('message', TRUE);
            $ref = $this->input->post('ref', TRUE);
            $subject = $this->input->post('subject', TRUE);
            $client = $this->input->post('client_id', TRUE);
            $amount = $this->input->post('amount', true);
            $currency = $this->input->post('currency', TRUE);
			
			if(!empty($proposals_id) &&!empty($message) && !empty($ref)  && !empty($client)){
				$proposal_info = $this->proposal_model->check_by(array('proposals_id' => $id), 'tbl_proposals');
				$client_info = $this->estimates_model->check_by(array('client_id' => $client), 'tbl_client');
				$clientt = ucfirst($client_info->name);
				if(!empty($client_info->mobile)){
					$client_name = str_replace("{CLIENT}", $clientt, $message);
					$Ref = str_replace("{PROPOSAL_REF}", $ref, $client_name);
					$Amount = str_replace("{AMOUNT}", $amount, $Ref);
					$Currency = str_replace("{CURRENCY}", $currency, $Amount);

					$url = "".base_url() . 'client/proposals/index/proposals_details/' . $proposals_id."";
					$short_url = $this->google_url_api->shorten($url);
					if($this->google_url_api->get_http_status() == "200"){
						$link = str_replace("{PROPOSAL_LINK}", $short_url->id, $Currency);
					}else{
						$link = str_replace("{PROPOSAL_LINK}", base_url() . 'client/proposals/index/proposals_details/' . $proposals_id, $Currency);
					}

					$message = str_replace("{SITE_NAME}", config_item('company_name'), $link);
			
					$send = $this->send_sms_Api($client_info->mobile, $message);
			
					$data = array('status' => 'sent', 'emailed' => 'Yes', 'date_sent' => date("Y-m-d H:i:s", time()));
			
					$this->proposal_model->_table_name = 'tbl_proposals';
					$this->proposal_model->_primary_key = 'proposals_id';
					$this->proposal_model->save($data, $proposals_id);
			
					// Log Activity
					$activity = array(
						'user' => $this->session->userdata('user_id'),
						'module' => 'proposals',
						'module_field_id' => $proposals_id,
						'activity' => 'activity_proposals_sent',
						'icon' => 'fa-shopping-cart',
						'link' => 'admin/proposals/index/proposals_details/' . $proposals_id,
						'value1' => $ref
					);
					$this->proposal_model->_table_name = 'tbl_activities';
					$this->proposal_model->_primary_key = 'activities_id';
					$this->proposal_model->save($activity);
			
					$type = 'success';
					$text = lang('proposals_email_sent');
					set_message($type, $text);
					echo $send;
				}
			}
		}elseif($this->input->post('type', TRUE) == "bulksms"){
			$smsto = $this->input->post('sms_to', TRUE);
			$message = $this->input->post('message', TRUE);
			if(isset($message) && $message != ""){
				if($smsto == "all_clients"){
					$ClientsSms = $this->db->where('mobile !=', '')->get("tbl_client")->result();
					if(count($ClientsSms) > 0){
						$mobiles = "";
						foreach($ClientsSms as $usr){
							$preg_replace = preg_replace('/[^0-9]/', '', $usr->mobile);
							$mobile1 = '966'.substr($preg_replace,1);
							if(strlen($mobile1) == 12){
								$mobiles .= $mobile1.",";
							}
						}
						$send = $this->send_sms_Api($mobiles, $message, 'bulk');
						echo '<div class="alert alert-success"><i class="fa fa-check"></i>'.$send.'</div>';
					}else{
						echo '<div class="alert alert-danger"><i class="fa fa-times"></i> '.lang('check_userlist').'</div>';
					}
				}elseif($smsto == "select_c"){
					$explode = explode(',',$this->input->post('clients', TRUE));
					if(count($explode) > 0){
						$mobiles = "";
						foreach($explode as $ex){
							$ClientSms = $this->db->where('client_id', $ex)->where('mobile !=', '')->get("tbl_client")->row();
							if($ClientSms){
								$preg_replace = preg_replace('/[^0-9]/', '', $ClientSms->mobile);
								$mobile1 = '966'.substr($preg_replace,1);
								if(strlen($mobile1) == 12){
									$mobiles .= $mobile1.",";
								}
							}
						}
						$send = $this->send_sms_Api($mobiles, $message, 'bulk');
						echo '<div class="alert alert-success"><i class="fa fa-check"></i>'.$send.'</div>';
					}
				}elseif($smsto == "all_users"){
					$UsersSms = $this->db->select('tbl_users.*, tbl_account_details.mobile')->from("tbl_users")->join('tbl_account_details', 'tbl_account_details.user_id = tbl_users.user_id')->where('tbl_account_details.mobile !=', '')->get()->result();;
					if(count($UsersSms) > 0){
						$mobiles = "";
						foreach($UsersSms as $usr){
							$preg_replace = preg_replace('/[^0-9]/', '', $usr->mobile);
							$mobile1 = '966'.substr($preg_replace,1);
							if(strlen($mobile1) == 12){
								$mobiles .= $mobile1.",";
							}
						}
						$send = $this->send_sms_Api($mobiles, $message, 'bulk');
						echo '<div class="alert alert-success"><i class="fa fa-check"></i>'.$send.'</div>';
					}else{
						echo '<div class="alert alert-danger"><i class="fa fa-times"></i> '.lang('check_userlist').'</div>';
					}
				}elseif($smsto == "select_u"){
					$explode = explode(',',$this->input->post('users', TRUE));
					if(count($explode) > 0){
						$mobiles = "";
						foreach($explode as $ex){
							$UserSms = $this->db->select('tbl_users.*, tbl_account_details.mobile')->from("tbl_users")->join('tbl_account_details', 'tbl_account_details.user_id = tbl_users.user_id')->where('tbl_users.user_id', $ex)->where('tbl_account_details.mobile !=', '')->get()->row();
							if($UserSms){
								$preg_replace = preg_replace('/[^0-9]/', '', $UserSms->mobile);
								$mobile1 = '966'.substr($preg_replace,1);
								if(strlen($mobile1) == 12){
									$mobiles .= $mobile1.",";
								}
							}
						}
						$send = $this->send_sms_Api($mobiles, $message, 'bulk');
						echo '<div class="alert alert-success"><i class="fa fa-check"></i>'.$send.'</div>';
					}
				}elseif($smsto == "mobile"){
					$mobile = $this->input->post('mobile', TRUE);
					if(isset($mobile) && $mobile != ""){
						$send = $this->send_sms_Api($mobile, $message);
						echo '<div class="alert alert-success"><i class="fa fa-check"></i>'.$send.'</div>';
					}
				}
			}else{
				echo '<div class="alert alert-danger"><i class="fa fa-times"></i> '.lang('check_mesg').'</div>';
			}
		}
    }
	function send_sms_Api($Numbers,$Message, $action = "", $return = "string"){
	//username=fekraserv&password=welcome@ids&message=XXXXXXX&numbers=966531666337&sender=mohammedzia&unicode=e&return=full
//		$url = "http://www.idsms.net/api/sendsms.php?";
        $url = "http://platform.imissive.com/api/sendsms/?";
		if(!$url || $url==""){
			return "No URL";
		}else{
			if($action == "bulk"){
				$mobile = $Numbers;
			}else{
				$mobile1 = substr($Numbers,1);
				$mobile = '966'.$mobile1;
                                // $mobile = $mobile1;

			}
			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt ($ch, CURLOPT_HEADER, false);
			curl_setopt ($ch, CURLOPT_POST, true);
			$postData = '';
//			$dataPOST = array('user' => config_item('sms_username'), 'pass' => config_item('sms_password'), 'to' => $mobile,'message' => $Message,  'sender' => config_item('sms_sender'));
//			$dataPOST = array('username' => config_item('sms_username'), 'password' => config_item('sms_password'), 'numbers' => $mobile,'message' => $Message,  'sender' => config_item('sms_sender'), 'date' => date("Y-m-d"),'time'=>date("H:i:s"));
			$dataPOST = array('user' => config_item('sms_username'), 'pass' => config_item('sms_password'), 'to' => $mobile, 'message' => $Message, 'sender' => config_item('sms_sender'));
			foreach($dataPOST as $k => $v)
			{ 
				  $postData .= $k . '='.$v.'&'; 
			}
			$postData = rtrim($postData, '&');


			curl_setopt ($ch, CURLOPT_POSTFIELDS, $postData); 
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_VERBOSE, 0);  
			curl_setopt($ch, CURLE_HTTP_NOT_FOUND,1); 
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$FainalResult = curl_exec ($ch);
			$curl_errno = curl_errno($ch);
			$curl_error = curl_error($ch);
			curl_close($ch);
			if ($curl_errno > 0) {
                return "cURL Error ($curl_errno): $curl_error\n";
        	} else {
                return $FainalResult;
        	}
		}

	}
}
