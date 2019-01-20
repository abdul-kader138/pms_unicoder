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
                                <h2><?php echo "$emp_salary_info->fullname" ?></h2>
                            </td>
                        </tr>
                        <tr>
                            <td width="40%" bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('departments') ?></strong> :</td>
                            <td colspan="2" style="border:1px solid #333333;"><?php echo "$emp_salary_info->deptname"; ?></td>
                        </tr>
                        <tr>
                            <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('designation') ?></strong> :</td>
                            <td colspan="2" style="border:1px solid #333333;"><?php echo "$emp_salary_info->designations"; ?></td>
                        </tr>
                        <tr>
                            <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('joining_date') ?></strong> :</td>
                            <td colspan="2" style="border:1px solid #333333;"><?= strftime(config_item('date_format'), strtotime($emp_salary_info->joining_date)) ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div>
<div style="width: 100%; margin-top: 55px;">
    <div>
        <div style="width: 100%; background: #E3E3E3;padding: 1px 0px 1px 10px; color: black; vertical-align: middle; ">
            <p style="margin-left: 10px; font-size: 15px; font-weight: lighter;">
                <strong><?= lang('salary_details') ?></strong></p>
        </div>
        <br/>
        <table cellspacing="0" cellpadding="4" border="0" style="width: 100%;padding: 10px 0;">
            <tr class="payment_history">
                <th bgcolor="#dddddd" style="border:1px solid #333333; text-align:center"><?= lang('month') ?></th>
                <th bgcolor="#dddddd" style="border:1px solid #333333; text-align:center"><?= lang('date') ?></th>
                <th bgcolor="#dddddd" style="border:1px solid #333333; text-align:center"><?= lang('gross_salary') ?></th>
                <th bgcolor="#dddddd" style="border:1px solid #333333; text-align:center"><?= lang('total_deduction') ?></th>
                <th bgcolor="#dddddd" style="border:1px solid #333333; text-align:center"><?= lang('net_salary') ?></th>
                <th bgcolor="#dddddd" style="border:1px solid #333333; text-align:center"><?= lang('fine_deduction') ?></th>
                <th bgcolor="#dddddd" style="border:1px solid #333333; text-align:center"><?= lang('amount') ?></th>
            </tr>
            <?php
            if (!empty($payment_history)): foreach ($payment_history as $v_payment_history) :
                ?>
                <tr class="payment_history">
                    <td style="border:1px solid #333333; text-align:center"><?php echo date('F-Y', strtotime($v_payment_history->payment_month)); ?></td>
                    <td style="border:1px solid #333333; text-align:center"><?php echo date('d-M-y', strtotime($v_payment_history->paid_date)); ?></td>
                    <td style="border:1px solid #333333; text-align:center"><?php echo display_money($total_paid_amount[$index], $currency->symbol); ?></td>
                    <td style="border:1px solid #333333; text-align:center"><?php echo display_money($total_deduction[$index], $currency->symbol); ?></td>
                    <td style="border:1px solid #333333; text-align:center"><?php echo display_money($net_salary = $gross - $deduction, $currency->symbol); ?></td>
                    <td style="border:1px solid #333333; text-align:center"><?php
                        if (!empty($v_payment_history->fine_deduction)) {
                            echo display_money($fine_deduction = $v_payment_history->fine_deduction, $currency->symbol);
                        } else {
                            $fine_deduction = 0;
                        }
                        ?></td>
                    <td style="border:1px solid #333333; text-align:center"><?php echo display_money($net_salary - $fine_deduction, $currency->symbol); ?></td>
                </tr>
                <?php
            endforeach;
                ?>
            <?php else : ?>
                <tr>
                    <td colspan="9" style="border:1px solid #333333; text-align:center">
                        <strong><?= lang('no_data_to_display') ?></strong>
                    </td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</div>

