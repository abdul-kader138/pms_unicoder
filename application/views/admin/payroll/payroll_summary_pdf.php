    <?php
    $direction = $this->session->userdata('direction');
    if (!empty($direction) && $direction == 'rtl') {
        $RTL = 'on';
    } else {
        $RTL = config_item('RTL');
    }
    ?>
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
<?php if (!empty($search_type)) {

    ?>
    <div style="width: 100%;">
        <div style="background: #E0E5E8;padding: 5px;">
            <!-- Default panel contents -->
            <div style="font-size: 15px;padding: 0px 0px 0px 0px">
                <strong><?= lang('payroll_summary') ?><?= $by ?> </strong></div>
        </div>
        <table cellspacing="0" cellpadding="4" border="0" width="100%">
            <tr>
                <th bgcolor="#dddddd" style="border:1px solid #333333; text-align:center"><?= lang('month') ?></th>
                <th bgcolor="#dddddd" style="border:1px solid #333333; text-align:center"><?= lang('date') ?></th>
                <th bgcolor="#dddddd" style="border:1px solid #333333; text-align:center"><?= lang('gross_salary') ?></th>
                <th bgcolor="#dddddd" style="border:1px solid #333333; text-align:center"><?= lang('total_deduction') ?></th>
                <th bgcolor="#dddddd" style="border:1px solid #333333; text-align:center"><?= lang('net_salary') ?></th>
                <th bgcolor="#dddddd" style="border:1px solid #333333; text-align:center"><?= lang('fine_deduction') ?></th>
                <th bgcolor="#dddddd" style="border:1px solid #333333; text-align:center"><?= lang('amount') ?></th>
            </tr>
            <?php
            $currency = $this->payroll_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
            if (!empty($employee_payroll)) {
                foreach ($employee_payroll as $index => $v_payroll) {
                    $salary_payment_history = $this->db->where('salary_payment_id', $v_payroll->salary_payment_id)->get('tbl_salary_payment_details')->result();
                    $total_salary_amount = 0;
                    if (!empty($salary_payment_history)) {
                        foreach ($salary_payment_history as $v_payment_history) {
                            if (is_numeric($v_payment_history->salary_payment_details_value)) {
                                if ($v_payment_history->salary_payment_details_label == lang('overtime_salary') . ' <small>( ' . lang('per_hour') . ')</small>') {
                                    $rate = $v_payment_history->salary_payment_details_value;
                                } elseif ($v_payment_history->salary_payment_details_label == lang('hourly_rates')) {
                                    $rate = $v_payment_history->salary_payment_details_value;
                                }
                                $total_salary_amount += $v_payment_history->salary_payment_details_value;
                            }
                        }
                    }
                    $salary_allowance_info = $this->db->where('salary_payment_id', $v_payroll->salary_payment_id)->get('tbl_salary_payment_allowance')->result();
                    $total_allowance = 0;
                    if (!empty($salary_allowance_info)) {
                        foreach ($salary_allowance_info as $v_salary_allowance_info) {
                            $total_allowance += $v_salary_allowance_info->salary_payment_allowance_value;
                        }
                    }
                    if (empty($rate)) {
                        $rate = 0;
                    }
                    $salary_deduction_info = $this->db->where('salary_payment_id', $v_payroll->salary_payment_id)->get('tbl_salary_payment_deduction')->result();
                    $total_deduction = 0;
                    if (!empty($salary_deduction_info)) {
                        foreach ($salary_deduction_info as $v_salary_deduction_info) {
                            $total_deduction += $v_salary_deduction_info->salary_payment_deduction_value;
                        }
                    }

                    $total_paid_amount = $total_salary_amount + $total_allowance - $rate;
                    $gross = 0;
                    $deduction = 0;
                    ?>

                    <tr style="width: 100%;">
                        <td style="border:1px solid #333333; text-align:center"><?php echo date('F-Y', strtotime($v_payroll->payment_month)); ?></td>
                        <td style="border:1px solid #333333; text-align:center"><?php echo strftime(config_item('date_format'), strtotime($v_payroll->paid_date)); ?></td>
                        <td style="border:1px solid #333333; text-align:center"><?php echo display_money($total_paid_amount, $currency->symbol); ?></td>
                        <td style="border:1px solid #333333; text-align:center"><?php echo display_money($total_deduction, $currency->symbol); ?></td>
                        <td style="border:1px solid #333333; text-align:center"><?php echo display_money($net_salary = $total_paid_amount - $total_deduction, $currency->symbol); ?></td>
                        <td style="border:1px solid #333333; text-align:center"><?php
                            if (!empty($v_payroll->fine_deduction)) {
                                echo display_money($fine_deduction = $v_payroll->fine_deduction, $currency->symbol);
                            } else {
                                $fine_deduction = 0;
                            }
                            ?></td>
                        <td style="border:1px solid #333333; text-align:center"><?php echo display_money($net_salary - $fine_deduction, $currency->symbol); ?></td>
                    </tr>
                <?php }; ?>
            <?php } else { ?>
                <tr>
                    <td colspan="7" style="border:1px solid #333333; text-align:center">
                        <strong><?= lang('nothing_to_display') ?></strong>
                    </td>
                </tr>
            <?php }; ?>
        </table>
    </div>
<?php } ?>
