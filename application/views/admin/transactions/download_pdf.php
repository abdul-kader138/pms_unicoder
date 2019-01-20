<div style="width: 100%; border-bottom: 2px solid black;">
    <table cellspacing="0" cellpadding="4" border="0" style="width:100%; margin-top:-25px ">
    <tr>
        <td>
                <img style="width: 120px;"
                     src="<?php echo base_url();?><?php 
					 if(!empty(config_item('invoice_logo')) and file_exists(getcwd(). "/".config_item('invoice_logo'))){
						  echo config_item('invoice_logo'); }else{ echo config_item('company_logo'); }?>" alt="<?= config_item('company_name') ?>">
        </td>

        <td></td>
        <td>
                <h2 style="font-size: 20px;font-weight: bold;margin: 0px; padding:0px;"><?= config_item('company_name') ?></h2>

                
        </td>
    </tr>
</table>

</div>
<br/>
<?php
$account_info = $this->db->where(array('account_id' => $expense_info->account_id))->get('tbl_accounts')->row();
$currency = $this->db->where(array('code' => config_item('default_currency')))->get('tbl_currencies')->row();
$category_info = $this->db->where('expense_category_id', $expense_info->category_id)->get('tbl_expense_category')->row();
if (!empty($category_info)) {
    $category = $category_info->expense_category;
} else {
    $category = lang('undefined_category');
}
$client_name = $this->db->where('client_id', $expense_info->paid_by)->get('tbl_client')->row();
?>
<div style="width: 100%;">
    <div>
        <div style="width: 100%; background: #E3E3E3;padding: 1px 0px 1px 10px; color: black; vertical-align: middle; ">
            <p style="margin-left: 10px; font-size: 15px; font-weight: lighter;">
                <strong><?php echo $title;
                    if (!empty($expense_info->reference)) {
                        echo '#' . $expense_info->reference;
                    }
                    ?></strong></p>
        </div>

        <table cellspacing="0" cellpadding="4" border="0" style="width: 100%; font-size: 13px;margin-top: 20px">
            <?php
            if ($expense_info->invoices_id != 0) {
                $payment_status = $this->invoice_model->get_payment_status($expense_info->invoices_id);
                $invoice_info = $this->db->where('invoices_id', $expense_info->invoices_id)->get('tbl_invoices')->row();
                if ($payment_status == lang('fully_paid')) {
                    $p_label = "success";
                } elseif ($payment_status == lang('draft')) {
                    $p_label = "default";
                    $text = lang('status_as_draft');
                } elseif ($payment_status == lang('cancelled')) {
                    $p_label = "danger";
                } elseif ($payment_status == lang('partially_paid')) {
                    $p_label = "warning";
                }
                $paid_amount = $this->invoice_model->calculate_to('paid_amount', $expense_info->invoices_id);
                ?>
                <tr>
                    <td width="30%" bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('reference_no') ?> :</strong>
                    </td>

                    <td style="border:1px solid #333333;">&nbsp; <?php
                        echo $invoice_info->reference_no;
                        ?></td>
                </tr>
                <tr>
                    <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('payment_status') ?> :</strong>
                    </td>

                    <td style="border:1px solid #333333;">&nbsp; <?php
                        echo $payment_status;
                        ?></td>
                </tr>
                <?php if ($paid_amount > 0) { ?>
                    <tr>
                        <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('paid_amount') ?> :</strong>
                        </td>

                        <td style="border:1px solid #333333;">&nbsp; <?= display_money($paid_amount, $currency->symbol); ?></td>
                    </tr>
                <?php } ?>
            <?php } ?>
            <?php
            if ($expense_info->project_id != 0) {
                $project = $this->db->where('project_id', $expense_info->project_id)->get('tbl_project')->row();
                ?>
                <tr>
                    <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('project_name') ?> :</strong>
                    </td>

                    <td style="border:1px solid #333333;">&nbsp; <?php
                        echo($project->project_name ? $project->project_name : '-');
                        ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('name') . '/' . lang('title') ?> :</strong>
                </td>

                <td style="border:1px solid #333333;">&nbsp; <?php
                    echo(!empty($expense_info->name) ? $expense_info->name : '-');
                    ?></td>
            </tr>
            <tr>
                <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('date') ?> :</strong></td>

                <td style="border:1px solid #333333;">&nbsp; <?php
                    echo strftime(config_item('date_format'), strtotime($expense_info->date));
                    ?></td>
            </tr>
            <tr>
                <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('account') ?> :</strong></td>

                <td style="border:1px solid #333333;">&nbsp; <?php
                    if (!empty($account_info->account_name)) {
                        echo $account_info->account_name;
                    } else {
                        echo '-';
                    }
                    ?></td>
            </tr>
            <tr>
                <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('amount') ?> :</strong>
                </td>

                <td style="border:1px solid #333333;">&nbsp; <?php
                    echo display_money($expense_info->amount, $currency->symbol);
                    ?></td>
            </tr>
            <tr>
                <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('categories') ?> :</strong>
                </td>
                <td style="border:1px solid #333333;">&nbsp; <?php
                    echo $category;
                    ?></td>
            </tr>
            <tr>
                <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('paid_by') ?> :</strong>
                </td>
                <td style="border:1px solid #333333;">&nbsp; <?php
                    echo(!empty($client_name->name) ? $client_name->name : '-');
                    ?></td>
            </tr>
            <tr>
                <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('payment_method') ?> :</strong>
                </td>
                <td style="border:1px solid #333333;">&nbsp; <?php
                    if (!empty($expense_info->payment_methods_id)) {
                        $payment_methods = $this->db->where('payment_methods_id', $expense_info->payment_methods_id)->get('tbl_payment_methods')->row();
                    }
                    if (!empty($payment_methods)) {
                        echo $payment_methods->method_name;
                    } else {
                        echo '-';
                    }
                    ?></td>
            </tr>
            <?php if ($expense_info->type == 'Expense') { ?>
                <tr>
                    <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('status') ?> :</strong>
                    </td>

                    <td style="border:1px solid #333333;">&nbsp; <?php
                        $status = $expense_info->status;
                        if ($expense_info->project_id != 0) {
                            if ($expense_info->billable == 'No') {
                                $status = 'not_billable';
                                $label = 'primary';
                                $title = lang('not_billable');
                                $action = '';
                            } else {
                                $status = 'billable';
                                $label = 'success';
                                $title = lang('billable');
                                $action = '';
                            }
                        } else {
                            if ($expense_info->status == 'non_approved') {
                                $label = 'danger';
                                $title = lang('get_approved');
                                $action = 'approved';
                            } elseif ($expense_info->status == 'unpaid') {
                                $label = 'warning';
                                $title = lang('get_paid');
                                $action = 'paid';
                            } else {
                                $label = 'success';
                                $title = '';
                                $action = '';
                            }
                        } ?>
                        <span class="label label-<?= $label ?>"><?= lang($status); ?></span>

                    </td>
                </tr>
            <?php } ?>
            <?php
            if ($expense_info->type == 'Income') {
                $view = 1;
            } else {
                $view = 2;
            }
            $show_custom_fields = custom_form_label($view, $expense_info->transactions_id);

            if (!empty($show_custom_fields)) {
                foreach ($show_custom_fields as $c_label => $v_fields) {
                    if (!empty($v_fields)) {
                        ?>
                        <tr>
                            <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= $c_label ?> :</strong>
                            </td>
                            <td style="border:1px solid #333333;">&nbsp;
                                <span style="word-wrap: break-word;"><?= $v_fields ?></span>
                            </td>
                        </tr>
                    <?php }
                }
            }
            ?>
            <tr>
                <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('notes') ?> :</strong>
                </td>
                <td style="border:1px solid #333333;">&nbsp;
                    <span style="word-wrap: break-word;"><?php echo $expense_info->notes; ?></span>
                </td>
            </tr>

        </table>

    </div>
</div><!-- ***************** Salary Details  Ends *********************-->
