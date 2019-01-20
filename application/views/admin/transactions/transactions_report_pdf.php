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
        <br />
        <div style="width: 100%;">
            <table cellspacing="0" cellpadding="4" border="0" width="100%">
                <tr>
                    <th width="15%" bgcolor="#dddddd" style="border:1px solid #333333; text-align:center;"><?= lang('date') ?></th>
                    <th width="15%" bgcolor="#dddddd" style="border:1px solid #333333;"><?= lang('account') ?></th>
                    <th bgcolor="#dddddd" style="border:1px solid #333333; text-align:center;"><?= lang('type') ?></th>
                    <th bgcolor="#dddddd" style="border:1px solid #333333; text-align:center;"><?= lang('name') . '/' . lang('title') ?></th>
                    <th bgcolor="#dddddd" style="border:1px solid #333333; text-align:center;"><?= lang('amount') ?></th>
                    <th bgcolor="#dddddd" style="border:1px solid #333333; text-align:center;"><?= lang('credit') ?></th>
                    <th bgcolor="#dddddd" style="border:1px solid #333333; text-align:center;"><?= lang('debit') ?></th>
                    <th bgcolor="#dddddd" style="border:1px solid #333333; text-align:center;"><?= lang('balance') ?></th>
                </tr>
                <?php
                $total_amount = 0;
                $total_debit = 0;
                $total_credit = 0;
                $total_balance = 0;
                $curency = $this->transactions_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                $all_transaction_info = $this->db->order_by('transactions_id','DESC')->get('tbl_transactions')->result();
                if (!empty($all_transaction_info)): foreach ($all_transaction_info as $v_transaction) :
                        $account_info = $this->transactions_model->check_by(array('account_id' => $v_transaction->account_id), 'tbl_accounts');
                        ?>

                        <tr style="width: 100%;">
                            <td style="border:1px solid #333333; text-align:center;"><?= strftime(config_item('date_format'), strtotime($v_transaction->date)); ?></td>
                            <td style="border:1px solid #333333; text-align:center;"><?
                                if (!empty($account_info->account_name)) {
                                    echo $account_info->account_name;
                                } else {
                                    echo '-';
                                }
                                ?></td>
                            <td style="border:1px solid #333333; text-align:center;"><?= lang($v_transaction->type) ?> </td>
                            <td style="border:1px solid #333333; text-align:center;"><?= ($v_transaction->name ? $v_transaction->name : '-'); ?></td>
                            <td style="border:1px solid #333333; text-align:center;"><?= display_money($v_transaction->amount, $curency->symbol) ?></td>
                            <td style="border:1px solid #333333; text-align:center;"><?= display_money($v_transaction->credit, $curency->symbol) ?></td>
                            <td style="border:1px solid #333333; text-align:center;"><?= display_money($v_transaction->debit, $curency->symbol) ?></td>
                            <td style="border:1px solid #333333; text-align:center;"><?= display_money($v_transaction->total_balance, $curency->symbol) ?></td>
                        </tr>

                        <?php
                        $total_amount +=$v_transaction->amount;
                        $total_debit +=$v_transaction->debit;
                        $total_credit +=$v_transaction->credit;
                        $total_balance +=$v_transaction->total_balance;
                        ?>
                    <?php endforeach; ?>
                    <tr class="custom-color-with-td">
                        <td style="border:1px solid #333333; text-align:center;" colspan="4"><strong><?= lang('total') ?>:</strong></td>
                        <td style="border:1px solid #333333; text-align:center;"><strong><?= display_money($total_amount, $curency->symbol) ?></strong></td>
                        <td style="border:1px solid #333333; text-align:center;"><strong><?= display_money($total_credit, $curency->symbol) ?></strong></td>
                        <td style="border:1px solid #333333; text-align:center;"><strong><?= display_money($total_debit, $curency->symbol) ?></strong></td>
                        <td style="border:1px solid #333333; text-align:center;"><strong><?= display_money($total_credit - $total_debit, $curency->symbol) ?></strong></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="border:1px solid #333333; text-align:center;">
                            <strong>There is no Report to display</strong>
                        </td>
                    </tr>
                <?php endif; ?>
            </table>

        </div>
