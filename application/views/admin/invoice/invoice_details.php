<?= message_box('success') ?>
<?= message_box('error');
$edited = can_action('13', 'edited');
$deleted = can_action('13', 'deleted');
$paid_amount = $this->invoice_model->calculate_to('paid_amount', $invoice_info->invoices_id);
?>

<div class="row mb">
    <div class="col-sm-12 mb">
        <div class="pull-left">
            <?= lang('copy_unique_url') ?>
        </div>
        <div class="col-sm-10">
            <input style="width: 100%"
                   value="<?= base_url() ?>frontend/view_invoice/<?= url_encode($invoice_info->invoices_id); ?>"
                   type="text" id="foo"/>
        </div>
    </div>
    <script type="text/javascript">
        var textBox = document.getElementById("foo");
        textBox.onfocus = function () {
            textBox.select();
            // Work around Chrome's little problem
            textBox.onmouseup = function () {
                // Prevent further mouseup intervention
                textBox.onmouseup = null;
                return false;
            };
        };
    </script>
    <div class="col-sm-10">

        <?php
        $payment_status = $this->invoice_model->get_payment_status($invoice_info->invoices_id);

        $where = array('user_id' => $this->session->userdata('user_id'), 'module_id' => $invoice_info->invoices_id, 'module_name' => 'invoice');
        $check_existing = $this->invoice_model->check_by($where, 'tbl_pinaction');
        if (!empty($check_existing)) {
            $url = 'remove_todo/' . $check_existing->pinaction_id;
            $btn = 'danger';
            $title = lang('remove_todo');
        } else {
            $url = 'add_todo_list/invoice/' . $invoice_info->invoices_id;
            $btn = 'warning';
            $title = lang('add_todo_list');
        }

        $client_info = $this->invoice_model->check_by(array('client_id' => $invoice_info->client_id), 'tbl_client');
        if (!empty($client_info)) {
            $currency = $this->invoice_model->client_currency_sambol($invoice_info->client_id);
            $client_lang = $client_info->language;
        } else {
            $client_lang = 'english';
            $currency = $this->invoice_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
        }

        unset($this->lang->is_loaded[5]);
        $language_info = $this->lang->load('sales_lang', $client_lang, TRUE, FALSE, '', TRUE);
        ?>
        <?php $can_edit = $this->invoice_model->can_action('tbl_invoices', 'edit', array('invoices_id' => $invoice_info->invoices_id));
        if (!empty($can_edit) && !empty($edited)) { ?>
            <span data-toggle="tooltip" data-placement="top" title="<?= lang('from_items') ?>">
            <a data-toggle="modal" data-target="#myModal_lg"
               href="<?= base_url() ?>admin/invoice/insert_items/<?= $invoice_info->invoices_id ?>"
               title="<?= lang('item_quick_add') ?>" class="btn btn-xs btn-primary">
                <i class="fa fa-pencil text-white"></i> <?= lang('add_items') ?></a>
                </span>
        <?php }
        ?>
        <?php
        if (!empty($can_edit) && !empty($edited)) { ?>
            <?php if ($invoice_info->show_client == 'Yes') { ?>
            <a class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top"
               href="<?= base_url() ?>admin/invoice/change_status/hide/<?= $invoice_info->invoices_id ?>"
               title="<?= lang('hide_to_client') ?>"><i class="fa fa-eye-slash"></i> <?= lang('hide_to_client') ?>
                </a><?php } else { ?>
            <a class="btn btn-xs btn-warning" data-toggle="tooltip" data-placement="top"
               href="<?= base_url() ?>admin/invoice/change_status/show/<?= $invoice_info->invoices_id ?>"
               title="<?= lang('show_to_client') ?>"><i class="fa fa-eye"></i> <span class="hidden-xs"><?= lang('show_to_client') ?></span>
                </a><?php }
        } ?>

        <?php
        if (!empty($can_edit) && !empty($edited)) { ?>
            <?php if ($this->invoice_model->get_invoice_cost($invoice_info->invoices_id) > 0) {
                ?>
                <?php if ($invoice_info->status == 'Cancelled') {
                    $disable = 'disabled';
                    $p_url = '';
                } else {
                    $disable = false;
                    $p_url = base_url() . 'admin/invoice/manage_invoice/payment/' . $invoice_info->invoices_id;
                } ?>
                <a class="btn btn-xs btn-danger <?= $disable ?>" data-toggle="tooltip" data-placement="top"
                   href="<?= $p_url ?>"
                   title="<?= lang('add_payment') ?>"><i class="fa fa-credit-card"></i> <?= lang('pay_invoice') ?>
                </a>
                <?php
            }
            if (!empty($all_payments_history)) {
                ?>
                <a class="btn btn-xs btn-info" data-toggle="tooltip" data-placement="top"
                   href="<?= base_url('admin/invoice/manage_invoice/payment_history/' . $invoice_info->invoices_id) ?>"
                   title="<?= lang('payment_history_for_this_invoice') ?>"><i
                        class="fa fa fa-money"></i> <?= lang('histories') ?>
                </a>
            <?php }
        }
        ?>
        <?php
        if (!empty($can_edit) && !empty($edited)) { ?>
            <span data-toggle="tooltip" data-placement="top" title="<?= lang('clone') . ' ' . lang('invoice') ?>">
            <a data-toggle="modal" data-target="#myModal"
               href="<?= base_url() ?>admin/invoice/clone_invoice/<?= $invoice_info->invoices_id ?>"
               class="btn btn-xs btn-purple">
                <i class="fa fa-copy"></i> <?= lang('clone') ?></a>
            </span>
            <?php
        }
        ?>


        <div class="btn-group">
            <button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
                <?= lang('more_actions') ?>
                <span class="caret"></span></button>
            <ul class="dropdown-menu animated zoomIn">
                <?php if ($this->invoice_model->get_invoice_cost($invoice_info->invoices_id) > 0) { ?>
                    <li>
                        <a href="<?= base_url() ?>admin/invoice/manage_invoice/email_invoice/<?= $invoice_info->invoices_id ?>"
                           title="<?= lang('email_invoice') ?>"><?= lang('email_invoice') ?></a>
                    </li>

                    <li>
                        <a href="<?= base_url() ?>admin/invoice/manage_invoice/send_reminder/<?= $invoice_info->invoices_id ?>"
                           title="<?= lang('send_reminder') ?>"><?= lang('send_reminder') ?></a>
                    </li>
                    <li>
                        <a href="javascript:;" data-toggle="modal" class="getaction" id="sendddd" data-target="#send_Sms" data-type="send"
                           title="<?= lang('sms_invoice') ?>"><?= lang('sms_invoice') ?></a>
                    </li>
                    <li>
                        <a href="javascript:;" data-toggle="modal" class="getaction" data-target="#send_Sms" data-type="reminder"
                           title="<?= lang('send_sms_reminder') ?>"><?= lang('send_sms_reminder') ?></a>
                    </li>
                    <?php if (strtotime($invoice_info->due_date) < time() AND $payment_status != lang('fully_paid')) { ?>
                        <li>
                            <a href="<?= base_url() ?>admin/invoice/manage_invoice/send_overdue/<?= $invoice_info->invoices_id ?>"
                               title="<?= lang('send_invoice_overdue') ?>"><?= lang('send_invoice_overdue') ?></a>
                        </li>
                    <?php } ?>
                    <?php if ($invoice_info->emailed != 'Yes') { ?>
                        <li>
                            <a href="<?= base_url() ?>admin/invoice/change_invoice_status/mark_as_sent/<?= $invoice_info->invoices_id ?>"
                               title="<?= lang('mark_as_sent') ?>"><?= lang('mark_as_sent') ?></a>
                        </li>
                    <?php }
                    if ($paid_amount <= 0) {
                        ?>
                        <?php if ($invoice_info->status != 'Cancelled') { ?>
                            <li>
                                <a href="<?= base_url() ?>admin/invoice/change_invoice_status/mark_as_cancelled/<?= $invoice_info->invoices_id ?>"
                                   title="<?= lang('mark_as_cancelled') ?>"><?= lang('mark_as_cancelled') ?></a>
                            </li>
                        <?php } ?>
                        <?php if ($invoice_info->status == 'Cancelled') { ?>
                            <li>
                                <a href="<?= base_url() ?>admin/invoice/change_invoice_status/unmark_as_cancelled/<?= $invoice_info->invoices_id ?>"
                                   title="<?= lang('unmark_as_cancelled') ?>"><?= lang('unmark_as_cancelled') ?></a>
                            </li>
                        <?php }
                    }
                    ?>
                    <li>
                        <a href="<?= base_url() ?>admin/invoice/manage_invoice/invoice_history/<?= $invoice_info->invoices_id ?>"><?= lang('invoice_history') ?></a>
                    </li>
                <?php } ?>

                <li class="divider"></li>
                <?php
                if (!empty($can_edit) && !empty($edited)) { ?>
                    <li>
                        <a href="<?= base_url() ?>admin/invoice/manage_invoice/create_invoice/<?= $invoice_info->invoices_id ?>"><?= lang('edit_invoice') ?></a>
                    </li>
                <?php } ?>
            </ul>
        </div>

        <?php
        if (!empty($can_edit) && !empty($edited)) { ?>
            <?php if ($invoice_info->recurring == 'Yes') { ?>
                <a onclick="return confirm('<?= lang('stop_recurring_alert') ?>')" class="btn btn-xs btn-warning"
                   href="<?= base_url() ?>admin/invoice/stop_recurring/<?= $invoice_info->invoices_id ?>"
                   title="<?= lang('stop_recurring') ?>"><i class="fa fa-retweet"></i> <?= lang('stop_recurring') ?>
                </a>
            <?php }
        } ?>
        <?php
        if (!empty($invoice_info->project_id)) {
            $project_info = $this->db->where('project_id', $invoice_info->project_id)->get('tbl_project')->row();
            ?>
            <strong><?= lang('project') ?>:</strong>
            <a
                href="<?= base_url() ?>admin/projects/project_details/<?= $invoice_info->project_id ?>"
                class="">
                <?= $project_info->project_name ?>
            </a>
        <?php }
        ?>

        <?php
        $notified_reminder = count($this->db->where(array('module' => 'invoice', 'module_id' => $invoice_info->invoices_id, 'notified' => 'No'))->get('tbl_reminders')->result());
        ?>
        <a class="btn btn-xs btn-green" data-toggle="modal" data-target="#myModal_lg"
           href="<?= base_url() ?>admin/invoice/reminder/invoice/<?= $invoice_info->invoices_id ?>"><?= lang('reminder') ?>
            <?= !empty($notified_reminder) ? '<span class="badge ml-sm" style="border-radius: 50%">' . $notified_reminder . '</span>' : '' ?>
        </a>
        <a type="button" onclick="convert_rtl()" class="btn btn-xs btn-success"
           href="#"><?= lang('rtl') ?>
        </a>
    </div>
    <div class="col-sm-2 pull-right">
        <a
            href="<?= base_url() ?>admin/invoice/send_invoice_email/<?= $invoice_info->invoices_id . '/' . true ?>"
            data-toggle="tooltip" data-placement="top" title="<?= lang('send_email') ?>"
            class="btn btn-xs btn-primary pull-right">
            <i class="fa fa-envelope-o"></i>
        </a>
        <a onclick="print_invoice('print_invoice')" href="#" data-toggle="tooltip" data-placement="top" title=""
           data-original-title="Print" class="mr-sm btn btn-xs btn-danger pull-right">
            <i class="fa fa-print"></i>
        </a>
        <a href="<?= base_url() ?>admin/invoice/pdf_invoice/<?= $invoice_info->invoices_id ?>"
           data-toggle="tooltip" data-placement="top" title="" data-original-title="PDF"
           class="btn btn-xs btn-success pull-right mr-sm">
            <i class="fa fa-file-pdf-o"></i>
        </a>
        <a data-toggle="tooltip" data-placement="top" title="<?= $title ?>"
           href="<?= base_url() ?>admin/projects/<?= $url ?>"
           class="mr-sm btn pull-right  btn-xs  btn-<?= $btn ?>"><i class="fa fa-thumb-tack"></i></a>
    </div>
</div>
<?php if (strtotime($invoice_info->due_date) < time() AND $payment_status != lang('fully_paid')) {
    $start = strtotime(date('Y-m-d'));
    $end = strtotime($invoice_info->due_date);

    $days_between = ceil(abs($end - $start) / 86400);
    ?>
    <div class="alert bg-danger-light hidden-print">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <i class="fa fa-warning"></i>
        <?= lang('invoice_overdue') . ' ' . lang('by') . ' ' . $days_between . ' ' . lang('days') ?>
    </div>
    <?php
}
?>

<div class="panel" id="print_invoice">
    <style>
        .invoice-title h4, .invoice-title h4 {
            display: inline-block;
        }
        
        .table > tbody > tr > .no-line {
            border-top: none;
        }
        
        .table > thead > tr > .no-line {
            border-bottom: none;
        }
        
        .table > tbody > tr > .thick-line,.table > thead > tr > .thick-line {
            border: 1px solid;
        }
        @media print {
            .show_print {display:none;}
            .label-danger,.label-success,.label-warning,.label-info,.label-default{background:white;border:none;font-weight: normal;}
        }
    </style>
    <div class="show_print">
        <div class="col-xs-12">
            <h4 class="page-header">
                <img class="mr" style="width: 60px;margin-top: -10px;"
                     src="<?= base_url() . config_item('company_logo') ?>"><?= config_item('company_name') ?>
            </h4>
        </div><!-- /.col -->
    </div>
    <div class="panel-body" id="invoice_body">
        <div class="row">
            <div class="col-xs-12">
        		<div class="row">
        		    <div class="col-xs-6">
        		        <img style="width: 120px;"
                     src="<?php echo base_url();?><?php 
					 if(!empty(config_item('invoice_logo')) and file_exists(getcwd(). "/".config_item('invoice_logo'))){
						  echo config_item('invoice_logo'); }else{ echo config_item('company_logo'); }?>" alt="<?= config_item('company_name') ?>">
        				<h4>INVOICE # <?= $invoice_info->reference_no ?></h4>
        			</div>
        			<div class="col-xs-6 text-right">
        			    <address>
        			        <strong><?= (config_item('company_legal_name_' . $client_lang) ? config_item('company_legal_name_' . $client_lang) : config_item('company_legal_name')) ?></strong><br>
                <?= (config_item('company_address_' . $client_lang) ? config_item('company_address_' . $client_lang) : config_item('company_address')) ?>

                <br /><?= (config_item('company_city_' . $client_lang) ? config_item('company_city_' . $client_lang) : config_item('company_city')) ?>
                    <?php if(config_item('company_zip_code')){  ?>, <?= config_item('company_zip_code') ?><?php } ?>

                , <?= (config_item('company_country_' . $client_lang) ? config_item('company_country_' . $client_lang) : config_item('company_country')) ?>
                <?php if(config_item('company_phone')){ ?><br /> <?= config_item('company_phone') ?><?php } ?>
                <?php if(config_item('company_email')){ ?><br /><?= config_item('company_email') ?><?php } ?>
                <?php if(config_item('company_vat')){ ?><br /><?=lang('company_vat');?> : <?= config_item('company_vat') ?><?php } ?>
        				</address>
        			</div>
        		</div>
        		<hr>
        		<div class="row">
        			<div class="col-xs-6">
        				<?php

                        if (!empty($client_info)) {
                            $client_name = $client_info->name;
                            $mobile = $client_info->mobile;
                            $email = $client_info->email;
                        } else {
                            $client_name = '';
                            $mobile= '';
                            $email = '';
                        }
                        ?>
        				<address>
            			<strong><?= $client_name ?></strong               
        				></address>
        				<br>&nbsp;
        				&nbsp;
        				<?php if( $email){ ?><address>
        				<?= lang('email') ?> : <?= $email ?>
        				</address><?php }?>
        				<?php if( $mobile){ ?><address>
        				<?= lang('mobile') ?> : <?= $mobile ?>
        				</address><?php }?>
        			</div>
        			<div class="col-xs-offset-2 col-xs-4 text-right">
        				<table class="table table-xs" style="border:1px solid;">
        				    <tr>
        				        <td class="thick-line" style="text-align:left;background: #eee;">INVOICE # </td>
        				        <td class="thick-line" ><?= $invoice_info->reference_no ?></td>
        				    </tr>
        				    <tr>
        				        <td class="thick-line" style="text-align:left;background: #eee;"><?= $language_info['invoice_date'] ?></td>
        				        <td class="thick-line"><?= strftime(config_item('date_format'), strtotime($invoice_info->invoice_date)); ?></td>
        				    </tr>
        				   <tr>
        				        <td class="thick-line" style="text-align:left;background: #eee;"><?= lang('sales') . ' ' . lang('agent') ?></td>
        				        <td class="thick-line"><?php echo fullname($invoice_info->user_id);?></td>
        				    </tr> 
        				    <tr>
        				        <td class="thick-line" style="text-align:left;background: #eee;"><?= $language_info['due_date'] ?></td>
        				        <td class="thick-line"><?= strftime(config_item('date_format'), strtotime($invoice_info->due_date)); ?></td>
        				    </tr> 
        				    <?php

                            if ($payment_status == lang('fully_paid')) {
                                $label = "success";
                            } elseif ($payment_status == lang('draft')) {
                                $label = "default";
                                $text = lang('status_as_draft');
                            } elseif ($payment_status == lang('cancelled')) {
                                $label = "danger";
                            } elseif ($payment_status == lang('partially_paid')) {
                                $label = "warning";
                            } elseif ($invoice_info->emailed == 'Yes') {
                                $label = "info";
                                $payment_status = lang('sent');
                            } else {
                                $label = "danger";
                            }
                            ?>
                            <tr>
        				        <td class="thick-line" style="text-align:left;background: #eee;"><?= $language_info['payment_status'] ?></td>
        				        <td class="thick-line"><span class="label label-<?= $label ?>"><?= $payment_status ?></span></td>
        				    </tr> 
        				</table>
        			</div>
        		</div>
        	</div>
        </div>
        
         <div class="row" style="margin-top: 20px;">
        	<div class="col-md-12">
				<table class="table table-responsive table-condensed" style="border:1px solid;">
					<thead style="background: #eee;">
                        <tr>
                            <th class="thick-line col-md-1" >#</th>
							<th class="text-center thick-line col-md-6"><?= $language_info['items'] ?></th>
							<?php
                            $invoice_view = config_item('invoice_view');
                            if (!empty($invoice_view) && $invoice_view == '2') {
                                ?>
                                <th class="text-center thick-line col-md-1"><?= $language_info['hsn_code'] ?></th>
                            <?php } ?>
                            <?php
                            $qty_heading = $language_info['qty'];
                            if (isset($invoice_info) && $invoice_info->show_quantity_as == 'hours' || isset($hours_quantity)) {
                                $qty_heading = lang('hours');
                            } else if (isset($invoice_info) && $invoice_info->show_quantity_as == 'qty_hours') {
                                $qty_heading = lang('qty') . '/' . lang('hours');
                            }
                            ?>
							<th class="text-center thick-line col-md-1"><?php echo $qty_heading; ?></th>
							<th class="text-center thick-line col-md-1"><?= $language_info['price'] ?></th>
							<th class="text-center thick-line col-md-1"><?= $language_info['tax'] ?></th>
							<th class="text-right thick-line col-md-1"><?= $language_info['total'] ?></th>
                        </tr>
					</thead>
					<tbody>
						<?php
                        $invoice_items = $this->invoice_model->ordered_items_by_id($invoice_info->invoices_id);
        
                        if (!empty($invoice_items)) :
                            foreach ($invoice_items as $key => $v_item) :
                                $item_name = $v_item->item_name ? $v_item->item_name : $v_item->item_desc;
                                $item_tax_name = json_decode($v_item->item_tax_name);
                                ?>
                                <tr class="sortable item" data-item-id="<?= $v_item->items_id ?>">
                                    <td class="item_no dragger pl-lg thick-line"><?= $key + 1 ?></td>
                                    <td class="text-center thick-line"><?= $item_name ?>
                                        <?= nl2br($v_item->item_desc) ?>
                                    </td>
                                    <?php
                                    $invoice_view = config_item('invoice_view');
                                    if (!empty($invoice_view) && $invoice_view == '2') {
                                        ?>
                                        <t class="text-center thick-line"d><?= $v_item->hsn_code ?></td>
                                    <?php } ?>
                                    <td class="text-center thick-line"><?= $v_item->quantity . '   &nbsp' . $v_item->unit ?></td>
                                    <td class="text-center thick-line"><?= display_money($v_item->unit_cost) ?></td>
                                    <td class="text-center thick-line"><?php
                                        if (!empty($item_tax_name)) {
                                            foreach ($item_tax_name as $v_tax_name) {
                                                $i_tax_name = explode('|', $v_tax_name);
                                                echo '<small class="pr-sm">' . $i_tax_name[0] . ' (' . $i_tax_name[1] . ' %)' . '</small>' . display_money($v_item->total_cost / 100 * $i_tax_name[1]) . ' <br>';
                                            }
                                        }
                                        ?></td>
                                    <td class="text-right thick-line"><?= display_money($v_item->total_cost) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="thick-line"><?= lang('nothing_to_display') ?></td>
                            </tr>
                        <?php endif ?>
                        <tr>
						<td class="thick-line" colspan="2" rowspan="8"></td>
						<td class="thick-line text-center" colspan="3"><strong><?= $language_info['sub_total'] ?></strong></td>
						<td class="thick-line text-right">
						    <?= display_money($this->invoice_model->calculate_to('invoice_cost', $invoice_info->invoices_id)); ?>
						</td>
					</tr>
					<?php if ($invoice_info->discount_total > 0): ?>
                    <tr>
                        <td class="thick-line text-center" colspan="3"><strong><?= $language_info['discount'] ?>(<?php echo $invoice_info->discount_percent; ?>
                            %)</strong>
                            </td>
                        <td class="thick-line text-right">
                            <?= display_money($this->invoice_model->calculate_to('discount', $invoice_info->invoices_id)); ?>
                        </td>
                    </tr>
                    <?php endif ?>
                    <?php
                    $tax_info = json_decode($invoice_info->total_tax);
                    $tax_total = 0;
                    if (!empty($tax_info)) {
                        $tax_name = $tax_info->tax_name;
                        $total_tax = $tax_info->total_tax;
                        if (!empty($tax_name)) {
                            foreach ($tax_name as $t_key => $v_tax_info) {
                                $tax = explode('|', $v_tax_info);
                                $tax_total += $total_tax[$t_key];
                                ?>
                                <tr>
                                    <td class="thick-line text-center" colspan="3"><strong><?= $tax[0] . ' (' . $tax[1] . ' %)' ?></strong></td>
                                    <td class="thick-line text-right">
                                        <?= display_money($total_tax[$t_key]); ?>
                                    </td>
                                </tr>
                            <?php }
                        }
                    } ?>
                    <?php if ($tax_total > 0): ?>
                        <tr>
                            <td class="thick-line text-center" colspan="3"><strong><?= $language_info['total'] . ' ' . $language_info['tax'] ?></strong></td>
                            <td class="thick-line text-right">
                                <?= display_money($tax_total); ?>
                            </td>
                        </tr>
                    <?php endif ?>
					<?php if ($invoice_info->adjustment > 0): ?>
                        <tr>
                            <td class="thick-line text-center" colspan="3"><strong><?= $language_info['adjustment'] ?></strong></td>
                            <td class="thick-line text-right">
                                <?= display_money($invoice_info->adjustment); ?>
                            </td>
                        </tr>
                    <?php endif ?>
    
                    <tr>
                        <td class="thick-line text-center" colspan="3"><strong><?= $language_info['total'] ?></strong></td>
                        <td class="thick-line text-right">
                            <?= display_money($this->invoice_model->calculate_to('total', $invoice_info->invoices_id), $currency->symbol); ?>
                        </td>
                    </tr>
    
                    <?php
    
                    if ($paid_amount > 0) {
                        $total = $language_info['total_due'];
                        if ($paid_amount > 0) {
                            $text = 'text-danger';
                            ?>
                            <tr>
                                <td class="thick-line text-center" colspan="3"><strong><?= $language_info['paid_amount'] ?> </strong></td>
                                <td class="thick-line text-right">
                                    <?= display_money($paid_amount, $currency->symbol); ?>
                                </td>
                            </tr>
                        <?php } else {
                            $text = '';
                        } ?>
                        <tr>
                            <td class="thick-line text-center" colspan="3"><strong><?= $total ?> </strong></td>
                            <td class="thick-line text-right"><?= display_money($this->invoice_model->calculate_to('invoice_due', $invoice_info->invoices_id), $currency->symbol); ?></td>
                        </tr>
                    <?php } ?>
					</tbody>
				</table>
        	</div>
        </div>
        <div class="row" style="margin-top:20px;">
            <div class="col-xs-12">
                <p>
                    <?= $invoice_info->notes ?>
                </p>
            </div>
        </div>
        <style type="text/css">
            .dragger {
                background: url(../../../../assets/img/dragger.png) 0px 11px no-repeat;
                cursor: pointer;
            }

            .table > tbody > tr > td {
                vertical-align: initial;
            }
        </style>
    </div>
    
    
    
    <?= !empty($invoice_view) && $invoice_view > 0 ? $this->gst->summary($invoice_items) : ''; ?>
</div>
<?php $all_payment_info = $this->db->where('invoices_id', $invoice_info->invoices_id)->get('tbl_payments')->result();
if (!empty($all_payment_info)) { ?>
    <div class="panel panel-custom">
        <div class="panel-heading">
            <div class="panel-title"> <?= lang('payment') . ' ' . lang('details') ?></div>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th><?= lang('transaction_id') ?></th>
                    <th><?= lang('payment_date') ?></th>
                    <th><?= lang('amount') ?></th>
                    <th><?= lang('payment_mode') ?></th>
                    <th><?= lang('action') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($all_payment_info as $v_payments_info) {
                    $payment_methods = $this->invoice_model->check_by(array('payment_methods_id' => $v_payments_info->payment_method), 'tbl_payment_methods');
                    ?>
                    <tr>
                        <td>
                            <a href="<?= base_url() ?>admin/invoice/manage_invoice/payments_details/<?= $v_payments_info->payments_id ?>"> <?= $v_payments_info->trans_id; ?></a>
                        </td>
                        <td>
                            <a href="<?= base_url() ?>admin/invoice/manage_invoice/payments_details/<?= $v_payments_info->payments_id ?>"><?= strftime(config_item('date_format'), strtotime($v_payments_info->payment_date)); ?></a>
                        </td>
                        <td><?= display_money($v_payments_info->amount, $currency->symbol) ?></td>
                        <td><?php if($payment_methods){ ?><?= $payment_methods->method_name ?> <?php } ?></td>
                        <?php if (!empty($edited) || !empty($deleted)) { ?>
                            <td>
                                <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                    <?= btn_edit('admin/invoice/all_payments/' . $v_payments_info->payments_id) ?>
                                <?php }
                                if (!empty($can_delete) && !empty($deleted)) {
                                    ?>
                                    <?= btn_delete('admin/invoice/delete/delete_payment/' . $v_payments_info->payments_id) ?>
                                <?php } ?>
                                <a data-toggle="tooltip" data-placement="top"
                                   href="<?= base_url() ?>admin/invoice/send_payment/<?= $v_payments_info->payments_id . '/' . $v_payments_info->amount ?>"
                                   title="<?= lang('send_email') ?>"
                                   class="btn btn-xs btn-success">
                                    <i class="fa fa-envelope"></i> </a>
                                <a data-toggle="tooltip" data-placement="top"
                                   href="<?= base_url() ?>admin/invoice/payments_pdf/<?= $v_payments_info->payments_id ?>"
                                   title="<?= lang('pdf') ?>"
                                   class="btn btn-xs btn-warning">
                                    <i class="fa fa-file-pdf-o"></i></a>
                            </td>
                        <?php } ?>
                    </tr>
                    <?php
                } ?>
                </tbody>
            </table>
        </div>
    </div>
<?php } ?>
<?php include_once 'assets/js/sales.php'; ?>
<!-- Modal -->
<div id="send_Sms" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?= lang('new_sms') ?></h4>
      </div>
      <div class="modal-body">
      <?php
	  $client_info = $this->invoice_model->check_by(array('client_id' => $invoice_info->client_id), 'tbl_client');
	   if(!empty($client_info->mobile)){ ?>
       <form id="sendSMSMail" method="post">
       <div id="resultSend"></div>
                    <input type="hidden" name="type" value="invoice">
                    <input type="hidden" name="invoice_id" value="<?= $invoice_info->invoices_id ?>">
                    <input type="hidden" name="ref" value="<?= $invoice_info->reference_no ?>">
                    <input type="hidden" name="client_id" value="<?= $client_info->client_id ?>">
                    <input type="hidden" name="currency" value="<?= $invoice_info->currency; ?>">

                    <input type="hidden" name="amount"
                           value="<?= ($this->invoice_model->calculate_to('invoice_due', $invoice_info->invoices_id)) ?>">
       
        <div class="form-group clearfix">
                            <label for="field-1"
                                   class="col-sm-3 control-label"><?= lang('mobile') ?></label>
                            <div class="col-sm-8"><input type="text" name="mobile" class="form-control" readonly="readonly" disabled="disabled" value="<?=$client_info->mobile?>"></div>
                        </div>
                        <hr />
        <div class="form-group clearfix">
                            <?php
                            $email_template = $this->invoice_model->check_by(array('email_group' => 'invoice_message'), 'tbl_email_templates');
                            $message = $email_template->template_body;
                            ?>
                            <label for="field-1"
                                   class="col-sm-3 control-label"><?= lang('message')?></label>
                            <div class="col-sm-8">
                            <textarea id="message" name="message" class="form-control"
                                                            rows="6"><?= lang('send_sms_template') ?></textarea>
                            <textarea style="display:none;" id="message2" name="message2" class="form-control"
                                                            rows="6"><?= lang('reminder_sms_template') ?></textarea>
                                                            </div>
                        </div>
                        <hr />
      <div class="form-group clearfix">
       			<button type="submit" name="sbtn" value="1" class="btn btn-primary"><?= lang('send')?></button>
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal"><?= lang('close')?></button>
      </div>
      </form>
      <?php }else{ ?>
      <div class="alert alert-warning"><?=lang('edit__user')?> <a href="<?= base_url() ?>admin/client/manage_client/<?= $client_info->client_id ?>"><?=lang('edit__user2')?></a></div>
      <?php } ?>
    </div>

  </div>
</div>
</div>
<script type="text/javascript">
   $(document).ready(function () {
        init_items_sortable(true);
		$(document).on('click', ".getaction",function (e) {
			$_thh = $(this);
			if($_thh.attr('data-type') == 'reminder'){
				$('#message').attr("name", 'message3');
				$('#message2').attr("name", 'message');
				$('#message').hide('fast');
				$('#message2').show('fast');
			}else{
				$('#message2').attr("name", 'message3');
				$('#message').attr("name", 'message');
				$('#message2').hide('fast');
				$('#message').show('fast');
			}
		});
		
		$(document).on('submit', "#sendSMSMail",function (ev) {
			ev.preventDefault();
			var dataForm = new FormData(this);
			$.ajax({
				type: "POST",
				url: "<?= base_url() ?>admin/sms/send_sms",
				data: dataForm,
				cache: false,
				processData: false,
				contentType: false,
				success: function(rep){
					if(rep){
						$('#resultSend').html('<div class="alert alert-success"><i class="fa fa-check"></i>'+rep+'</div>');
					}
				}
			});
		});

    });
    function print_invoice(print_invoice) {
        var printContents = document.getElementById(print_invoice).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
    
    function convert_rtl(){
        document.getElementById('invoice_body').setAttribute("dir", "rtl");
    }
</script>