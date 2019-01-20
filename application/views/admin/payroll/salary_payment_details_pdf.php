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
<div style="padding: 5px 0; width: 100%;">
    <div>
        <table cellspacing="0" cellpadding="4" border="0" style="width: 100%; border-radius: 3px;">
            <tr>
                <td style="width: 150px;">
                    <table cellspacing="0" cellpadding="4" border="0" style="border: 1px solid grey;">
                        <tr>
                            <td style="background-color: lightgray; border-radius: 2px;">
                                <?php if ($salary_payment_info->avatar): ?>
                                    <img src="<?php echo base_url() . $salary_payment_info->avatar; ?>"
                                         style="width: 132px; height: 138px; border-radius: 3px;">
                                <?php else: ?>
                                    <img alt="Employee_Image">
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table cellspacing="0" cellpadding="4" border="0" style="width: 300px; margin-left: 10px; margin-bottom: 10px; font-size: 13px;">
                        <tr>
                            <td colspan="2" style="border:1px solid #333333;">
                                <h2><?php echo "$salary_payment_info->fullname "; ?></h2>
                            </td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #333333;width: 120px"><strong><?= lang('emp_id') ?> : </strong></td>
                            <td style="border:1px solid #333333;">&nbsp; <?php echo "$salary_payment_info->employment_id"; ?></td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #333333;width: 120px"><strong><?= lang('departments') ?> : </strong></td>
                            <td style="border:1px solid #333333;">&nbsp; <?php echo "$salary_payment_info->deptname"; ?></td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #333333;width: 120px"><strong><?= lang('designation') ?> :</strong></td>
                            <td style="border:1px solid #333333;">&nbsp; <?php echo "$salary_payment_info->designations"; ?></td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #333333;width: 120px"><strong><?= lang('joining_date') ?> : </strong></td>
                            <td style="border:1px solid #333333;">
                                &nbsp; <?= strftime(config_item('date_format'), strtotime($salary_payment_info->joining_date)) ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div>
<br/>
<div style="width: 100%;">
    <div>
        <div style="width: 100%; background: #E3E3E3;padding: 1px 0px 1px 10px; color: black; vertical-align: middle; ">
            <p style="margin-left: 10px; font-size: 15px; font-weight: lighter;">
                <strong><?= lang('salary_details') ?></strong></p>
        </div>
        <table cellspacing="0" cellpadding="4" border="0" style="width: 100%;  padding: 10px 0;">
            <tr>
                <td>
                    <table cellspacing="0" cellpadding="4" border="0" style="width: 100%; font-size: 13px;">
                        <tr>
                            <td width="30%" bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('salary_month') ?> :</strong></td>
                            <td style="border:1px solid #333333;">
                                &nbsp; <?php echo date('F Y', strtotime($salary_payment_info->payment_month)); ?></td>
                        </tr>
                        <?php
                        $rate = 0;
                        $curency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
                        $total_hours_amount = 0;
                        foreach ($salary_payment_details_info as $v_payment_details) :
                            ?>
                            <tr>
                                <td style="border:1px solid #333333;">
                                    <strong><?php echo $v_payment_details->salary_payment_details_label; ?> :</strong>
                                </td>

                                <td style="border:1px solid #333333;">&nbsp; <?php
                                    if (is_numeric($v_payment_details->salary_payment_details_value)) {
                                        if ($v_payment_details->salary_payment_details_label == lang('overtime_salary') . ' <small>( ' . lang('per_hour') . ')</small>') {
                                            $rate = $v_payment_details->salary_payment_details_value;
                                        } elseif ($v_payment_details->salary_payment_details_label == lang('hourly_rates')) {
                                            $rate = $v_payment_details->salary_payment_details_value;
                                        }
                                        $total_hours_amount += $v_payment_details->salary_payment_details_value;
                                        echo display_money($v_payment_details->salary_payment_details_value, $curency->symbol);
                                    } else {
                                        echo $v_payment_details->salary_payment_details_value;
                                    }
                                    ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div><!-- ***************** Salary Details  Ends *********************-->

<!-- ******************-- Allowance Panel Start **************************-->
<?php
$total_allowance = 0;
if (!empty($allowance_info)):
    ?>
    <div style="width: 100%;">
        <div style="width: 100%; background: #E3E3E3;padding: 1px 0px 1px 10px; color: black; vertical-align: middle; ">
            <p style="margin-left: 10px; font-size: 15px; font-weight: lighter;">
                <strong><?= lang('allowances') ?></strong></p>
        </div>
        <table cellspacing="0" cellpadding="4" border="0" style="width: 100%;  padding: 10px 0;">
            <tr>
                <td>
                    <table cellspacing="0" cellpadding="4" border="0" style="width: 100%; font-size: 13px;">
                        <?php
                        foreach ($allowance_info as $v_allowance) :
                            ?>
                            <tr>
                                <td width="30%" bgcolor="#dddddd" style="border:1px solid #333333;">
                                    <strong><?php echo $v_allowance->salary_payment_allowance_label ?> :</strong></td>

                                <td style="border:1px solid #333333;">&nbsp;<?php
                                    echo display_money($v_allowance->salary_payment_allowance_value, $curency->symbol);
                                    ?>
                                </td>
                            </tr>
                            <?php
                            $total_allowance += $v_allowance->salary_payment_allowance_value;
                        endforeach;
                        ?>
                    </table>
                </td>
            </tr>
        </table>
    </div><!-- ********************Allowance End ******************-->
<?php endif; ?>

<!-- ************** Deduction Panel Column  **************-->
<?php
$deduction = 0;
if (!empty($deduction_info)):
    ?>
    <div style="width: 100%;">
        <div style="width: 100%; background: #E3E3E3;padding: 1px 0px 1px 10px; color: black; vertical-align: middle; ">
            <p style="margin-left: 10px; font-size: 15px; font-weight: lighter;">
                <strong><?= lang('deductions') ?></strong></p>
        </div>
        <table cellspacing="0" cellpadding="4" border="0" style="width: 100%;  padding: 10px 0;">
            <tr>
                <td>
                    <table cellspacing="0" cellpadding="4" border="0" style="width: 100%; font-size: 13px;">
                        <?php
                        if (!empty($deduction_info)):foreach ($deduction_info as $v_deduction):
                            ?>
                            <tr>
                                <td width="30%" bgcolor="#dddddd" style="border:1px solid #333333;">
                                    <strong><?php echo $v_deduction->salary_payment_deduction_label; ?> :</strong></td>

                                <td style="border:1px solid #333333;">&nbsp; <?php
                                    echo display_money($v_deduction->salary_payment_deduction_value, $curency->symbol);
                                    ?></td>
                            </tr>
                            <?php
                            $deduction += $v_deduction->salary_payment_deduction_value;
                        endforeach;
                            ?>
                        <?php endif; ?>
                    </table>
                </td>
            </tr>
        </table>
    </div><!-- ****************** Deduction End  *******************-->
<?php endif; ?>
<!-- ************** Total Salary Details Start  **************-->
<div style="width: 100%;">
    <div style="width: 100%; background: #E3E3E3;padding: 1px 0px 1px 10px; color: black; vertical-align: middle; ">
        <p style="margin-left: 10px; font-size: 15px; font-weight: lighter;">
            <strong><?= lang('total_salary_details') ?></strong></p>
    </div>
    <table cellspacing="0" cellpadding="4" border="0" style="width: 100%;  padding: 10px 0;">
        <tr>
            <td>
                <table cellspacing="0" cellpadding="4" border="0" style="width: 100%; font-size: 13px;">
                    <tr>
                        <td width="30%" bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('gross_salary') ?> :</strong></td>

                        <td style="border:1px solid #333333;">&nbsp; <?php
                            $gross = $total_hours_amount + $total_allowance - $rate;
                            echo display_money($gross, $curency->symbol);
                            ?></td>
                    </tr>

                    <tr>
                        <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('total_deduction') ?> :</strong></td>

                        <td style="border:1px solid #333333;">&nbsp; <?php
                            $total_deduction = $deduction;
                            echo display_money($total_deduction, $curency->symbol);
                            ?></td>
                    </tr>

                    <tr>
                        <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('net_salary') ?> :</strong></td>

                        <td style="border:1px solid #333333;">&nbsp;<?php
                            $net_salary = $gross - $total_deduction;
                            echo display_money($net_salary, $curency->symbol);
                            ?></td>
                    </tr>
                    <?php if (!empty($salary_payment_info->fine_deduction)): ?>
                        <tr>
                            <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('fine_deduction') ?> :</strong></td>

                            <td style="border:1px solid #333333;">&nbsp; <?php
                                echo display_money($salary_payment_info->fine_deduction, $curency->symbol);
                                ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('paid_amount') ?> :</strong></td>
                        <td style="border:1px solid #333333;">&nbsp; <?php
                            if (!empty($salary_payment_info->fine_deduction)) {
                                $paid_amount = $net_salary - $salary_payment_info->fine_deduction;
                            } else {
                                $paid_amount = $net_salary;
                            }
                            echo display_money($paid_amount, $curency->symbol);
                            ?></td>
                    </tr>
                    <?php if (!empty($salary_payment_info->payment_type)): ?>
                        <tr>
                            <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('payment_method') ?> :</strong></td>

                            <td style="border:1px solid #333333;">&nbsp; <?php
                                $payment_method = $this->db->where('payment_methods_id', $salary_payment_info->payment_type)->get('tbl_payment_methods')->row();
                                if (!empty($payment_method->method_name)) {
                                    echo $payment_method->method_name;
                                }
                                ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (!empty($salary_payment_info->comments)): ?>
                        <tr>
                            <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('comments') ?> :</strong></td>

                            <td style="border:1px solid #333333;">&nbsp; <?php
                                echo $salary_payment_info->comments;
                                ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php
                    $role = $this->session->userdata('user_type');
                    if ($role == 1 && $salary_payment_info->deduct_from != 0) {
                        $account_info = $this->payroll_model->check_by(array('account_id' => $salary_payment_info->deduct_from), 'tbl_accounts');
                        if (!empty($account_info)) {
                            ?>
                            <tr>
                                <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('deduct_from') ?> :</strong></td>

                                <td style="border:1px solid #333333;">&nbsp; <?php
                                    echo $account_info->account_name;
                                    ?></td>
                            </tr>
                        <?php }
                    } ?>
                </table>
            </td>
        </tr>
    </table>
</div><!-- ****************** Total Salary Details End  *******************-->
