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
<div style="padding: 5px 0; width: 100%;">
    <div>
        <table cellspacing="0" cellpadding="4" border="0" style="width: 100%; border-radius: 3px;">
            <tr>
                <td style="width: 150px;">
                    <table cellspacing="0" cellpadding="4" border="0" style="border: 1px solid grey;">
                        <tr>
                            <td style="background-color: lightgray; border-radius: 2px;">
                                <?php if ($emp_salary_info->avatar): ?>
                                    <img src="<?php echo base_url() . $emp_salary_info->avatar; ?>"
                                         style="width: 138px; height: 144px; border-radius: 3px;">
                                <?php else: ?>
                                    <img alt="Employee_Image">
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table cellspacing="0" cellpadding="4" border="0" style="width: 500px; margin-left: 10px; margin-bottom: 10px; font-size: 13px;">
                        <tr>
                            <td colspan="2" style="width: 30%;border:1px solid #333333;"><h2><?php echo "$emp_salary_info->fullname"; ?></h2></td>
                        </tr>
                        <tr>
                            <td bgcolor="#dddddd" style="width: 30%;border:1px solid #333333;"><strong><?= lang('emp_id') ?> : </strong></td>
                            <td style="width: 70%;border:1px solid #333333;"><?php echo "$emp_salary_info->employment_id "; ?></td>
                        </tr>
                        <tr>
                            <td bgcolor="#dddddd" style="width: 30%;border:1px solid #333333;"><strong><?= lang('departments') ?> : </strong></td>
                            <td style="border:1px solid #333333;width: 70%"><?php echo "$emp_salary_info->deptname"; ?></td>
                        </tr>
                        <tr>
                            <td bgcolor="#dddddd" style="border:1px solid #333333;width: 30%;"><strong><?= lang('designation') ?> :</strong></td>
                            <td style="border:1px solid #333333;width: 70%"><?php echo "$emp_salary_info->designations"; ?></td>
                        </tr>
                        <tr>
                            <td bgcolor="#dddddd" style="border:1px solid #333333;width: 30%;"><strong><?= lang('joining_date') ?>: </strong></td>
                            <td style="border:1px solid #333333;width: 70%"><?= strftime(config_item('date_format'), strtotime($emp_salary_info->joining_date)) ?></td>
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
                            <td width="30%" bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('salary_grade') ?> :</strong></td>

                            <td style="border:1px solid #333333;">&nbsp; <?php
                                echo $emp_salary_info->salary_grade;
                                ?></td>
                        </tr>
                        <tr>
                            <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('basic_salary') ?> :</strong></td>

                            <td style="border:1px solid #333333;">&nbsp; <?php
                                $curency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
                                echo display_money($emp_salary_info->basic_salary, $curency->symbol);
                                ?></td>
                        </tr>
                        <tr>
                            <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('overtime') ?>
                                    <small>(<?= lang('per_hour') ?>)</small>
                                    :</strong></td>

                            <td style="border:1px solid #333333;">
                                &nbsp; <?php echo display_money($emp_salary_info->overtime_salary, $curency->symbol) ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div><!-- ***************** Salary Details  Ends *********************-->

<!-- ******************-- Allowance Panel Start **************************-->
<div style="width: 100%;">

    <div>
        <div style="width: 100%; background: #E3E3E3;padding: 1px 0px 1px 10px; color: black; vertical-align: middle; ">
            <p style="margin-left: 10px; font-size: 15px; font-weight: lighter;">
                <strong><?= lang('allowances') ?></strong></p>
        </div>
        <table cellspacing="0" cellpadding="4" border="0" style="width: 100%;  padding: 10px 0;">
            <tr>
                <td>
                    <table cellspacing="0" cellpadding="4" border="0" style="width: 100%; font-size: 13px;">
                        <?php
                        $total_salary = 0;
                        if (!empty($salary_allowance_info)):foreach ($salary_allowance_info as $v_allowance_info):
                            ?>
                            <tr>
                                <td bgcolor="#dddddd" style="border:1px solid #333333;" width="30%">
                                    <strong><?php echo $v_allowance_info->allowance_label; ?> :</strong></td>

                                <td style="border:1px solid #333333;">
                                    &nbsp; <?php echo display_money($v_allowance_info->allowance_value, $curency->symbol) ?></td>
                            </tr>
                            <?php $total_salary += $v_allowance_info->allowance_value; ?>
                        <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" style="border:1px solid #333333;"><?= lang('nothing_to_display') ?></td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div><!-- ********************Allowance End ******************-->

<!-- ************** Deduction Panel Column  **************-->
<div style="width: 100%;">

    <div>
        <div style="width: 100%; background: #E3E3E3;padding: 1px 0px 1px 10px; color: black; vertical-align: middle; ">
            <p style="margin-left: 10px; font-size: 15px; font-weight: lighter;">
                <strong><?= lang('deductions') ?></strong></p>
        </div>
        <br/>
        <table cellspacing="0" cellpadding="4" border="0" style="width: 100%;  padding: 10px 0;">
            <tr>
                <td>
                    <table cellspacing="0" cellpadding="4" border="0" style="width: 100%; font-size: 13px;">
                        <?php
                        $total_deduction = 0;
                        if (!empty($salary_deduction_info)):foreach ($salary_deduction_info as $v_deduction_info):
                            ?>
                            <tr>
                                <td width="30%" bgcolor="#dddddd" style="border:1px solid #333333;">
                                    <strong><?php echo $v_deduction_info->deduction_label; ?> :</strong></td>

                                <td style="border:1px solid #333333;">&nbsp; <?php
                                    echo display_money($v_deduction_info->deduction_value, $curency->symbol);
                                    ?></td>
                            </tr>
                            <?php $total_deduction += $v_deduction_info->deduction_value ?>
                        <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" style="border:1px solid #333333;"><?= lang('nothing_to_display') ?></td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div><!-- ****************** Deduction End  *******************-->

<!-- ************** Total Salary Details Start  **************-->

<div style="width: 100%;">

    <div>
        <div style="width: 100%; background: #E3E3E3;padding: 1px 0px 1px 10px; color: black; vertical-align: middle; ">
            <p style="margin-left: 10px; font-size: 15px; font-weight: lighter;">
                <strong><?= lang('total_salary_details') ?></strong>
            </p>
        </div>
        <table cellspacing="0" cellpadding="4" border="0" style="width: 100%;  padding: 10px 0;">
            <tr>
                <td>
                    <table cellspacing="0" cellpadding="4" border="0" style="width: 100%; font-size: 13px;">
                        <tr>
                            <td width="30%" bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('gross_salary') ?>:</strong></td>

                            <td style="border:1px solid #333333;">&nbsp; <?php
                                if (!empty($total_salary) || !empty($emp_salary_info->basic_salary)) {
                                    $total = $total_salary + $emp_salary_info->basic_salary;
                                    echo display_money($total, $curency->symbol);
                                }
                                ?></td>
                        </tr>
                        <tr>
                            <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('total_deduction') ?> :</strong></td>

                            <td style="border:1px solid #333333;">&nbsp; <?php
                                if (!empty($total_deduction)) {
                                    echo display_money($total_deduction, $curency->symbol);
                                }
                                ?></td>
                        </tr>
                        <tr>
                            <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('net_salary') ?> :</strong></td>

                            <td style="border:1px solid #333333;">&nbsp; <?php
                                $net_salary = $total - $total_deduction;
                                echo display_money($net_salary, $curency->symbol);
                                ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div><!-- ****************** Total Salary Details End  *******************-->
