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
        <table style="width: 100%;" cellspacing="0" cellpadding="4" border="0">
            <tr style="font-size: 20px;  text-align: center">
                <td style="padding: 10px;"><?= lang('provident_found_report') . ' ' . lang('for') . ' ' . $user_info->fullname ?><?php echo ' ' . $monthyaer ?></td>
            </tr>
        </table>
    </div>
    <br/>
    <table cellspacing="0" cellpadding="4" border="0" width="100%">
        <tr class="table_tr1">
            <th><?= lang('payment_month') ?></th>
            <th><?= lang('payment_date') ?></th>
            <th><?= lang('amount') ?></th>
        </tr>
        <?php
        $total_amount = 0;
        $curency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
        ?>
        <?php if (!empty($provident_fund_info)) {
            foreach ($provident_fund_info as $key => $v_provident_fund) {
                $month_name = date('F', strtotime($monthyaer . '-' . $key)); // get full name of month by date query

                $curency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
                if (!empty($v_provident_fund)) {
                    foreach ($v_provident_fund as $provident_fund) { ?>
                        <tr>
                            <td><?php echo $month_name ?></td>
                            <td><?= strftime(config_item('date_format'), strtotime($provident_fund->paid_date)) ?></td>
                            <td><?php echo display_money($provident_fund->salary_payment_deduction_value, $curency->symbol);
                                $total_amount += $provident_fund->salary_payment_deduction_value;
                                ?></td>

                        </tr>
                        <?php
                        $key++;
                    };
                    $total_amount = $total_amount;
                };

                ?>


            <?php } ?>
            <tr class="total_amount">
                <td colspan="2" style="text-align: right;">
                    <strong><?= lang('total') . ' ' . lang('provident_fund') ?>
                        : </strong></td>
                <td colspan="3" style="padding-left: 8px;"><strong><?php
                        echo display_money($total_amount, $curency->symbol);
                        ?></strong></td>
            </tr>
        <?php }; ?>
    </table>
</div>
