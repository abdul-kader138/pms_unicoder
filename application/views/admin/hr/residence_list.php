<?php include_once 'asset/admin-ajax.php';
$created = can_action('146', 'created');
$edited = can_action('146', 'edited');
$deleted = can_action('146', 'deleted');
$office_hours = config_item('office_hours');

?>
<?= message_box('success'); ?>
<?= message_box('error'); ?>
    <div class=" mt-lg">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="<?= $active == 1 ? 'active' : '' ?>"><a href="#pending_approval"
                                                                   data-toggle="tab"><?= lang('pending') . ' ' . lang('approval') ?></a>
                </li>

                <li class="<?= $active == 2 ? 'active' : '' ?>"><a href="#my_residence"
                                                                   data-toggle="tab"><?= lang('my_residences') ?></a></li>

                <?php if ($this->session->userdata('user_type') == 1) { ?>
                    <li class="<?= $active == 3 ? 'active' : '' ?>"><a href="#all_residence"
                                                                       data-toggle="tab"><?= lang('residence') ?></a>
                    </li>
                <?php } ?>
                <li class="<?= $active == 4 ? 'active' : '' ?>"><a href="#report_residence"
                                                                   data-toggle="tab"><?= lang('statistic_residence') ?></a>
                </li>
                <li class="pull-right">
                    <a href="<?= base_url() ?>admin/hr/change_residence"
                       class="bg-info"
                       data-toggle="modal" data-placement="top" data-target="#myModal_extra_lg">
                        <i class="fa fa-plus "></i> <?= lang('add_residence') ?>
                    </a>
                </li>
            </ul>
            <div class="tab-content" style="border: 0;padding:0;">
                <div class="tab-pane <?= $active == 1 ? 'active' : '' ?>" id="pending_approval"
                     style="position: relative;">
                    <div class="panel panel-custom">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped DataTables " id="DataTables" cellspacing="0"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th><?= lang('name') ?></th>
                                        <th><?= lang('leave_category') ?></th>
                                        <th><?= lang('date') ?></th>
                                        <th><?= lang('duration') ?></th>
                                        <th><?= lang('status') ?></th>
                                        <?php $show_custom_fields = custom_form_table(17, null);
                                        if (!empty($show_custom_fields)) {
                                            foreach ($show_custom_fields as $c_label => $v_fields) {
                                                if (!empty($c_label)) {
                                                    ?>
                                                    <th><?= $c_label ?> </th>
                                                <?php }
                                            }
                                        }
                                        ?>
                                        <?php if ($this->session->userdata('user_type') == 1) { ?>
                                            <th class="col-sm-2"><?= lang('action') ?></th>
                                        <?php } ?>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if ($this->session->userdata('user_type') == 1) {
                                        $all_resds = $this->db->get('tbl_residences')->result();
                                    } else {
                                        $all_resds = $this->db->where('userid', $this->session->userdata('user_id'))->get('tbl_residences')->result();
                                    }

                                    if (!empty($all_resds)) {
                                        foreach ($all_resds as $v_pending):
                                            if ($v_pending->application_status == '1') {
                                                $p_profile = $this->db->where('user_id', $v_pending->userid)->get('tbl_account_details')->row();
                                                ?>
                                                <tr id="table_leave_m_<?= $v_pending->id_resd ?>">
                                                    <td><?= $p_profile->fullname ?></td>
                                                    <td>dd</td>
                                                    <td><?= strftime(config_item('date_format'), strtotime($v_pending->date_change)) ?>
                                                       
                                                    </td>
                                                    <td>
                                                    </td>
                                                    <td></td>
                                                    <?php $show_custom_fields = custom_form_table(17, $v_pending->leave_application_id);
                                                    if (!empty($show_custom_fields)) {
                                                        foreach ($show_custom_fields as $c_label => $v_fields) {
                                                            if (!empty($c_label)) {
                                                                ?>
                                                                <td><?= $v_fields ?> </td>
                                                            <?php }
                                                        }
                                                    }
                                                    ?>
                                                    <td>
                                                        <?php echo btn_view_modal('admin/leave_management/view_details/' . $v_pending->leave_application_id) ?>
                                                        <?php if ($v_pending->application_status != '2') { ?>
                                                            <?php echo ajax_anchor(base_url("admin/leave_management/delete_application/" . $v_pending->leave_application_id), "<i class='btn btn-xs btn-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table_leave_m_" . $v_pending->leave_application_id)); ?>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        endforeach;
                                    }
                                    ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane <?= $active == 2 ? 'active' : '' ?>" id="my_residence" style="position: relative;">
                    <div class="panel panel-custom">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped DataTables " id="DataTables" cellspacing="0"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th><?= lang('name') ?></th>
                                        <th><?= lang('leave_category') ?></th>
                                        <th><?= lang('date') ?></th>
                                        <th><?= lang('duration') ?></th>
                                        <th><?= lang('status') ?></th>
                                        <?php $show_custom_fields = custom_form_table(17, null);
                                        if (!empty($show_custom_fields)) {
                                            foreach ($show_custom_fields as $c_label => $v_fields) {
                                                if (!empty($c_label)) {
                                                    ?>
                                                    <th><?= $c_label ?> </th>
                                                <?php }
                                            }
                                        }
                                        ?>
                                        <?php if ($this->session->userdata('user_type') == 1) { ?>
                                            <th class="col-sm-2"><?= lang('action') ?></th>
                                        <?php } ?>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $my_residences = $this->db->where('userid', $this->session->userdata('user_id'))->get('tbl_residences')->result();
                                    if (!empty($my_residences)) {
                                        foreach ($my_residences as $v_my_resd):
                                            $my_profile = $this->db->where('user_id', $v_my_resd->userid)->get('tbl_account_details')->row();
                                            ?>
                                            <tr id="table_leave_my_<?= $v_my_resd->id_resd ?>">
                                                <td><?= $my_profile->fullname ?></td>
                                                <td>ss</td>
                                                <td><?= strftime(config_item('date_format'), strtotime($v_my_leave->date_change)) ?></td>
                                                <td>gg
                                                </td>
                                                <td><?php
                                                    if ($v_my_leave->application_status == '1') {
                                                        echo '<span class="label label-warning">' . lang('pending') . '</span>';
                                                    } elseif ($v_my_leave->application_status == '2') {
                                                        echo '<span class="label label-success">' . lang('accepted') . '</span>';
                                                    } else {
                                                        echo '<span class="label label-danger">' . lang('rejected') . '</span>';
                                                    }
                                                    ?></td>
                                                <?php $show_custom_fields = custom_form_table(17, $v_my_leave->leave_application_id);
                                                if (!empty($show_custom_fields)) {
                                                    foreach ($show_custom_fields as $c_label => $v_fields) {
                                                        if (!empty($c_label)) {
                                                            ?>
                                                            <td><?= $v_fields ?> </td>
                                                        <?php }
                                                    }
                                                }
                                                ?>
                                                <?php if ($this->session->userdata('user_type') == 1) { ?>
                                                    <td>
                                                        <?php echo btn_view_modal('admin/leave_management/view_details/' . $v_my_leave->leave_application_id) ?>
                                                        <?php if ($v_my_leave->application_status != '2') { ?>
                                                            <?php echo ajax_anchor(base_url("admin/leave_management/delete_application/" . $v_my_leave->leave_application_id), "<i class='btn btn-xs btn-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table_leave_my_" . $v_my_leave->leave_application_id)); ?>
                                                        <?php } ?>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                            <?php
                                        endforeach;
                                    }
                                    ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <?php if ($this->session->userdata('user_type') == 1) { ?>
                    <div class="tab-pane <?= $active == 2 ? 'active' : '' ?>" id="all_residence"
                         style="position: relative;">
                        <div class="panel panel-custom">
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped DataTables " id="DataTables" cellspacing="0"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th><?= lang('name') ?></th>
                                            <th><?= lang('leave_category') ?></th>
                                            <th><?= lang('date') ?></th>
                                            <th><?= lang('duration') ?></th>
                                            <th><?= lang('status') ?></th>
                                            <?php $show_custom_fields = custom_form_table(17, null);
                                            if (!empty($show_custom_fields)) {
                                                foreach ($show_custom_fields as $c_label => $v_fields) {
                                                    if (!empty($c_label)) {
                                                        ?>
                                                        <th><?= $c_label ?> </th>
                                                    <?php }
                                                }
                                            }
                                            ?>
                                            <?php if ($this->session->userdata('user_type') == 1) { ?>
                                                <th class="col-sm-2"><?= lang('action') ?></th>
                                            <?php } ?>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $all_leave_application = $this->db->get('tbl_leave_application')->result();
                                        if (!empty($all_leave_application)) {
                                            foreach ($all_leave_application as $v_all_leave):
                                                $my_profile = $this->db->where('user_id', $v_all_leave->user_id)->get('tbl_account_details')->row();
                                                $a_leave_category = $this->db->where('leave_category_id', $v_all_leave->leave_category_id)->get('tbl_leave_category')->row();
                                                ?>
                                                <tr id="table_leave_all_<?= $v_all_leave->leave_application_id ?>">
                                                    <td><?= $my_profile->fullname ?></td>
                                                    <td><?= $a_leave_category->leave_category ?></td>
                                                    <td><?= strftime(config_item('date_format'), strtotime($v_all_leave->leave_start_date)) ?>
                                                        <?php
                                                        if ($v_all_leave->leave_type == 'multiple_days') {
                                                            if (!empty($v_all_leave->leave_end_date)) {
                                                                echo lang('TO') . ' ' . strftime(config_item('date_format'), strtotime($v_all_leave->leave_end_date));
                                                            }
                                                        } ?>
                                                    </td>
                                                    <td><?php
                                                        if ($v_all_leave->leave_type == 'single_day') {
                                                            echo ' 1 ' . lang('day') . ' (<span class="text-danger">' . $office_hours . '.00' . lang('hours') . '</span>)';
                                                        }
                                                        if ($v_all_leave->leave_type == 'multiple_days') {
                                                            $ge_days = 0;
                                                            $m_days = 0;

                                                            $month = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($v_all_leave->leave_start_date)), date('Y', strtotime($v_all_leave->leave_start_date)));
                                                            $datetime1 = new DateTime($v_all_leave->leave_start_date);
                                                            if (empty($v_all_leave->leave_end_date)) {
                                                                $v_all_leave->leave_end_date = $v_all_leave->leave_start_date;
                                                            }
                                                            $datetime2 = new DateTime($v_all_leave->leave_end_date);
                                                            $difference = $datetime1->diff($datetime2);
                                                            if ($difference->m != 0) {
                                                                $m_days += $month;
                                                            } else {
                                                                $m_days = 0;
                                                            }
                                                            $ge_days += $difference->d + 1;
                                                            $total_token = $m_days + $ge_days;
                                                            echo $total_token . ' ' . lang('days') . ' (<span class="text-danger">' . $total_token * $office_hours . '.00' . lang('hours') . '</span>)';
                                                        }
                                                        if ($v_all_leave->leave_type == 'hours') {
                                                            $total_hours = ($v_all_leave->hours / $office_hours);
                                                            echo number_format($total_hours, 2) . ' ' . lang('days') . ' (<span class="text-danger">' . $v_all_leave->hours . '.00' . lang('hours') . '</span>)';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php
                                                        if ($v_all_leave->application_status == '1') {
                                                            echo '<span class="label label-warning">' . lang('pending') . '</span>';
                                                        } elseif ($v_all_leave->application_status == '2') {
                                                            echo '<span class="label label-success">' . lang('accepted') . '</span>';
                                                        } else {
                                                            echo '<span class="label label-danger">' . lang('rejected') . '</span>';
                                                        }
                                                        ?></td>
                                                    <?php $show_custom_fields = custom_form_table(17, $v_all_leave->leave_application_id);
                                                    if (!empty($show_custom_fields)) {
                                                        foreach ($show_custom_fields as $c_label => $v_fields) {
                                                            if (!empty($c_label)) {
                                                                ?>
                                                                <td><?= $v_fields ?> </td>
                                                            <?php }
                                                        }
                                                    }
                                                    ?>
                                                    <?php if ($this->session->userdata('user_type') == 1) { ?>
                                                        <td>
                                                            <?php echo btn_view_modal('admin/leave_management/view_details/' . $v_all_leave->leave_application_id) ?>
                                                            <?php if ($v_all_leave->application_status != '2') { ?>
                                                                <?php echo ajax_anchor(base_url("admin/leave_management/delete_application/" . $v_all_leave->leave_application_id), "<i class='btn btn-xs btn-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table_leave_all_" . $v_all_leave->leave_application_id)); ?>
                                                            <?php } ?>
                                                        </td>
                                                    <?php } ?>
                                                </tr>
                                                <?php
                                            endforeach;
                                        }
                                        ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="tab-pane <?= $active == 4 ? 'active' : '' ?>" id="report_residence" style="position: relative;">
                    <div class="panel panel-custom">
                        <div class="panel-body">
                            <?php if ($this->session->userdata('user_type') == 1) { ?>
                                <div id="panelChart5">
                                    <div class="row panel-title pl-lg pb-sm"
                                         style="border-bottom: 1px solid #a0a6ad"><?= lang('all') . ' ' . lang('leave_report') ?></div>
                                    <div class="chart-pie flot-chart"></div>
                                </div>
                            <?php } ?>

                            <div id="panelChart5">
                                <div class="row panel-title pl-lg pb-sm"
                                     style="border-bottom: 1px solid #a0a6ad"><?= lang('my_leave') . ' ' . lang('report') ?></div>
                                <div class="chart-pie-my flot-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
