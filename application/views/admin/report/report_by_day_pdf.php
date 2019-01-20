    <?php
    $direction = $this->session->userdata('direction');
    if (!empty($direction) && $direction == 'rtl') {
        $RTL = 'on';
    } else {
        $RTL = config_item('RTL');
    }?>
<div style="border-bottom: 2px solid black;">
    <table style="width: 100%; vertical-align: middle;" cellspacing="0" cellpadding="4" border="0" width="100%">
        <tr>
        <td>
                <img style="width: 120px;"
                     src="<?php echo base_url();?><?php 
					 if(!empty(config_item('invoice_logo')) and file_exists(getcwd(). "/".config_item('invoice_logo'))){
						  echo config_item('invoice_logo'); }else{ echo config_item('company_logo'); }?>" alt="<?= config_item('company_name') ?>">
        </td>

        <td></td>
            <td>
                <p style="margin-left: 10px; font: 14px lighter;"><?= config_item('company_name') ?></p>
            </td>
        </tr>
    </table>
</div>
<br/>
<div style="">
    <div style="width: 100%; background-color: rgb(224, 224, 224); padding: 1px 0px 5px 15px;">
        <table style="width: 100%;" cellspacing="0" cellpadding="4" border="0" >
            <tr style="font-size: 20px;  text-align: center">
                <td style="padding: 10px;"><?= lang('report_by_month_for') ?> <?php echo $monthyaer ?></td>
            </tr>
        </table>
    </div>
    <br/>
    <table cellspacing="0" cellpadding="4" border="0" width="100%">
        <tr class="table_tr1">
            <th width="15%" style="border:1px solid #333333; text-align:center;" bgcolor="#dddddd"><?= lang('date') ?></th>
            <th width="15%" style="border:1px solid #333333; text-align:center;" bgcolor="#dddddd"><?= lang('account') ?></th>
            <th style="border:1px solid #333333; text-align:center;" bgcolor="#dddddd"><?= lang('type') ?></th>
            <th style="border:1px solid #333333; text-align:center;" bgcolor="#dddddd"><?= lang('notes') ?></th>
            <th style="border:1px solid #333333; text-align:center;" bgcolor="#dddddd"><?= lang('amount') ?></th>
            <th style="border:1px solid #333333; text-align:center;" bgcolor="#dddddd"><?= lang('credit') ?></th>
            <th style="border:1px solid #333333; text-align:center;" bgcolor="#dddddd"><?= lang('debit') ?></th>
            <th style="border:1px solid #333333; text-align:center;" bgcolor="#dddddd"><?= lang('balance') ?></th>
        </tr>
        <?php
        $total_amount = 0;
        $total_debit = 0;
        $total_credit = 0;
        $total_balance = 0;
        $curency = $this->report_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
        if (!empty($report_list)): foreach ($report_list as $v_month) :
            $account_info = $this->report_model->check_by(array('account_id' => $v_month->account_id), 'tbl_accounts');
            ?>
            <tr class="table_tr2">
                <td style="border:1px solid #333333; text-align:center;"><?= strftime(config_item('date_format'), strtotime($v_month->date)); ?></td>
                <td style="border:1px solid #333333; text-align:center;"><?= $account_info->account_name ?></td>
                <td style="border:1px solid #333333; text-align:center;"><?= lang($v_month->type) ?> </td>
                <td style="border:1px solid #333333; text-align:center;"><?= $v_month->notes ?></td>
                <td style="border:1px solid #333333; text-align:center;"><?= display_money($v_month->amount, $curency->symbol) ?></td>
                <td style="border:1px solid #333333; text-align:center;"><?= display_money($v_month->credit, $curency->symbol) ?></td>
                <td style="border:1px solid #333333; text-align:center;"><?= display_money($v_month->debit, $curency->symbol) ?></td>
                <td style="border:1px solid #333333; text-align:center;"><?= display_money($v_month->total_balance, $curency->symbol) ?></td>
            </tr>
            <?php
            $total_amount += $v_month->amount;
            $total_debit += $v_month->debit;
            $total_credit += $v_month->credit;
            $total_balance += $v_month->total_balance;
            ?>
        <?php endforeach; ?>
            <tr class="table_tr2">
                <td style="border:1px solid #333333; text-align:center;" colspan="4"><strong><?= lang('total') ?>:</strong></td>
                <td style="border:1px solid #333333; text-align:center;">
                    <strong><?= display_money($total_amount, $curency->symbol) ?></strong>
                </td>
                <td style="border:1px solid #333333; text-align:center;">
                    <strong><?= display_money($total_credit, $curency->symbol) ?></strong>
                </td>
                <td style="border:1px solid #333333; text-align:center;">
                    <strong><?= display_money($total_debit, $curency->symbol) ?></strong>
                </td>
                <td style="border:1px solid #333333; text-align:center;">
                    <strong><?= display_money($total_credit - $total_debit, $curency->symbol) ?></strong>
                </td>
            </tr>
        <?php endif; ?>
    </table>
</div>
