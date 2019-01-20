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
<div style="width: 100%;">
    <table cellspacing="0" cellpadding="4" border="0" width="100%">
        <tr>
            <th bgcolor="#dddddd" style="border:1px solid #333333;"><?= lang('account') ?></th>
            <th style="border:1px solid #333333;"><?= lang('balance') ?></th>
        </tr>
        <?php
        $curency = $this->transactions_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
        $total_amount = 0;
        $all_account = $this->db->get('tbl_accounts')->result();
        if ($all_account):
            foreach ($all_account as $v_account):
                ?>

                <tr style="width: 100%;">
                    <td bgcolor="#dddddd" style="border:1px solid #333333;">ddd<?php
                        if (!empty($v_account->account_name)) {
                            echo $v_account->account_name;
                        } else {
                            echo '-';
                        }
                        ?></td>
                    <td style="border:1px solid #333333;"><?= display_money($v_account->balance, $curency->symbol) ?></td>
                </tr>

                <?php
                $total_amount += $v_account->balance;
                ?>
            <?php endforeach; ?>
            <tr class="custom-color-with-td">
                <th bgcolor="#dddddd" style="border:1px solid #333333;" colspan="1"><strong><?= lang('total') ?>:</strong></th>
                <td style="border:1px solid #333333;"><strong><?= display_money($total_amount, $curency->symbol) ?></strong></td>
            <tr>
        <?php else: ?>
            <tr>
                <td colspan="2" style="border:1px solid #333333; text-align:center">
                    <strong>There is no Report to display</strong>
                </td>
            </tr>
        <?php endif; ?>
    </table>

</div>
