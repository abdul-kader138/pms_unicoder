    <?php
    $direction = $this->session->userdata('direction');
    if (!empty($direction) && $direction == 'rtl') {
        $RTL = 'on';
    } else {
        $RTL = config_item('RTL');
    }
    ?>
    <style type="text/css">
        .table_tr1 th{
            background-color: rgb(224, 224, 224);
            height: 40px;}

        .table_tr1 td {
            padding: 7px 0px 7px 8px;
            font-weight: bold;
            border: 1px solid black;}

        .table_tr2 td {
            padding: 7px 0px 7px 8px;}

        .total_amount {
            background-color: rgb(224, 224, 224);
            font-weight: bold;
        <?php if(!empty($RTL)){?> text-align: left;<?php }else{?>text-align: right;<?php }?>

        }

        .total_amount td {
            padding: 7px 8px 7px 0px;
            border: 1px solid black;
            font-size: 15px;}
    </style>
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
    <div style="width: 100%; background-color: rgb(224, 224, 224); padding: 1px 0px 5px 15px;">
        <table style="width: 100%;">
            <tr style="font-size: 20px;  text-align: center">
                <td style="padding: 10px;"><?= lang('provident_found_report') . ' ' . lang('for') ?><?php echo ' ' . $monthyaer ?></td>
            </tr>
        </table>
    </div>
    <br/>
    <table cellspacing="0" cellpadding="4" border="0" width="100%">
        <tr class="table_tr1">
            <th bgcolor="#dddddd" style="border:1px solid #333333; text-align:center"><?= lang('emp_id') ?></th>
            <th bgcolor="#dddddd" style="border:1px solid #333333; text-align:center"><?= lang('name') ?></th>
            <th bgcolor="#dddddd" style="border:1px solid #333333; text-align:center"><?= lang('payment_date') ?></th>
            <th bgcolor="#dddddd" style="border:1px solid #333333; text-align:center"><?= lang('amount') ?></th>
        </tr>
        <?php
        $key = 1;
        $total_amount = 0;
        $curency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
        if (!empty($provident_fund_info)): foreach ($provident_fund_info as $provident_fund) : ?>
            <tr class="table_tr2">
                <td style="border:1px solid #333333; text-align:center"><?php echo $provident_fund->employment_id ?></td>
                <td style="border:1px solid #333333; text-align:center"><?php echo $provident_fund->fullname ?></td>
                <td style="border:1px solid #333333; text-align:center"><?= strftime(config_item('date_format'), strtotime($provident_fund->paid_date)) ?></td>
                <td style="border:1px solid #333333; text-align:center"><?php echo display_money($provident_fund->salary_payment_deduction_value, $curency->symbol);
                    $total_amount += $provident_fund->salary_payment_deduction_value;
                    ?></td>

            </tr>
            <?php
            $key++;
        endforeach;
            ?>
            <tr class="total_amount">
                <td colspan="3" style="text-align: right;border:1px solid #333333;">
                    <strong><?= lang('total') . ' ' . lang('provident_fund') ?>
                        : </strong></td>
                <td colspan="3" style="padding-left: 8px;border:1px solid #333333;"><strong><?php
                        echo display_money($total_amount, $curency->symbol);
                        ?></strong></td>
            </tr>
        <?php else : ?>
            <tr>
                <td style="border:1px solid #333333;" colspan="4">
                    <strong><?= lang('nothing_to_display') ?></strong>
                </td>
            </tr>
        <?php endif; ?>
    </table>
</div>
</body>
</html>