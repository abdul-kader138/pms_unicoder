    <?php
    $direction = $this->session->userdata('direction');
    if (!empty($direction) && $direction == 'rtl') {
        $RTL = 'on';
    } else {
        $RTL = config_item('RTL');
    }?>
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

<h5><strong><?= lang('expense_summary') ?></strong></h5>
<hr>
<p><?= lang('total_expense') ?>: <?php
    $curency = $this->report_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
    $mdate = date('Y-m-d');
    //first day of month
    $first_day_month = date('Y-m-01');
    //first day of Weeks
    $this_week_start = date('Y-m-d', strtotime('previous sunday'));
    // 30 days before
    $before_30_days = date('Y-m-d', strtotime('today - 30 days'));

    $total_income = $this->db->select_sum('debit')->get('tbl_transactions')->row();
    $this_month = $this->db->where(array('date >=' => $first_day_month, 'date <=' => $mdate))->select_sum('debit')->get('tbl_transactions')->row();
    $this_week = $this->db->where(array('date >=' => $this_week_start, 'date <=' => $mdate))->select_sum('debit')->get('tbl_transactions')->row();
    $this_30_days = $this->db->where(array('date >=' => $before_30_days, 'date <=' => $mdate))->select_sum('debit')->get('tbl_transactions')->row();
    echo display_money($total_expense->debit, $curency->symbol);
    ?></p>
<p><?= lang('total_expense_this_month') ?> : <?= display_money($this_month->debit, $curency->symbol) ?></p>
<p><?= lang('total_expense_this_week') ?> : <?= display_money($this_week->debit, $curency->symbol) ?></p>
<p><?= lang('total_expense_last_30') ?> : <?= display_money($this_30_days->debit, $curency->symbol) ?></p>

<br/>
<h4><?= lang('last_deposit_expense') ?></h4>
<hr>
<div style="width: 100%;">
    <table cellspacing="0" cellpadding="4" border="0" width="100%">
        <tr>
            <th style="border:1px solid #333333; text-align:center;" bgcolor="#dddddd"><?= lang('date') ?></th>
            <th style="border:1px solid #333333; text-align:center;" bgcolor="#dddddd"><?= lang('account') ?></th>
            <th style="border:1px solid #333333; text-align:center;" bgcolor="#dddddd"><?= lang('deposit_category') ?></th>
            <th style="border:1px solid #333333; text-align:center;" bgcolor="#dddddd"><?= lang('paid_by') ?></th>
            <th style="border:1px solid #333333; text-align:center;" bgcolor="#dddddd"><?= lang('description') ?></th>
            <th style="border:1px solid #333333; text-align:center;" bgcolor="#dddddd"><?= lang('amount') ?></th>
            <th style="border:1px solid #333333; text-align:center;" bgcolor="#dddddd"><?= lang('credit') ?></th>
            <th style="border:1px solid #333333; text-align:center;" bgcolor="#dddddd"><?= lang('balance') ?></th>
        </tr>
        <?php
        $total_amount = 0;
        $total_credit = 0;
        $total_balance = 0;
        $all_deposit_info = $this->db->where(array('type' => 'Expense'))->limit(20)->order_by('transactions_id', 'DESC')->get('tbl_transactions')->result();

        foreach ($all_deposit_info as $v_deposit) :
            $account_info = $this->report_model->check_by(array('account_id' => $v_deposit->account_id), 'tbl_accounts');
            $client_info = $this->report_model->check_by(array('client_id' => $v_deposit->paid_by), 'tbl_client');

            $category_info = $this->report_model->check_by(array('expense_category_id' => $v_deposit->category_id), 'tbl_expense_category');
            if (!empty($client_info)) {
                $client_name = $client_info->name;
            } else {
                $client_name = '-';
            }
            ?>
            <tr>
                <td style="border:1px solid #333333; text-align:center;"><?= strftime(config_item('date_format'), strtotime($v_deposit->date)); ?></td>
                <td style="border:1px solid #333333; text-align:center;"><?= !empty($account_info->account_name) ? $account_info->account_name : '-' ?></td>
                <td style="border:1px solid #333333; text-align:center;"><?php
                    if (!empty($category_info)) {
                        echo $category_info->expense_category;
                    } else {
                        echo '-';
                    }
                    ?></td>
                <td style="border:1px solid #333333; text-align:center;"><?= $client_name ?></td>
                <td style="border:1px solid #333333; text-align:center;"><?= $v_deposit->notes ?></td>
                <td style="border:1px solid #333333; text-align:center;"><?= display_money($v_deposit->amount, $curency->symbol) ?></td>
                <td style="border:1px solid #333333; text-align:center;"><?= display_money($v_deposit->debit, $curency->symbol) ?></td>
                <td style="border:1px solid #333333; text-align:center;"><?= display_money($v_deposit->total_balance, $curency->symbol) ?></td>

            </tr>
            <?php
            $total_amount += $v_deposit->amount;
            $total_credit += $v_deposit->debit;
            $total_balance += $v_deposit->total_balance;
            ?>
            <?php
        endforeach;
        ?>
        <tr class="custom-color-with-td">
            <td style="border:1px solid #333333; text-align:center;" colspan="5"><strong><?= lang('total') ?>:</strong></td>
            <td style="border:1px solid #333333; text-align:center;"><strong><?= display_money($total_amount, $curency->symbol) ?></strong></td>
            <td style="border:1px solid #333333; text-align:center;"><strong><?= display_money($total_credit, $curency->symbol) ?></strong></td>
            <td style="border:1px solid #333333; text-align:center;"><strong><?= display_money($total_balance, $curency->symbol) ?></strong></td>
        </tr>
    </table>

</div>
