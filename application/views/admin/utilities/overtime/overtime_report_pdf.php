    <?php
    $direction = $this->session->userdata('direction');
    if (!empty($direction) && $direction == 'rtl') {
        $RTL = 'on';
    } else {
        $RTL = config_item('RTL');
    }
    ?>
    <style type="text/css">
        .table_tr1 {
            background-color: rgb(224, 224, 224);}

        .table_tr1 td {
            padding: 7px 0px 7px 8px;
            font-weight: bold;}

        .table_tr2 td {
            padding: 7px 0px 7px 8px;
            border: 1px solid black;}

        .total_amount {
            background-color: rgb(224, 224, 224);
            font-weight: bold;}

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
                <td style="padding: 10px;"><?= lang('overtime_report') ?><?php echo $monthyaer ?></td>
            </tr>
        </table>
    </div>
    <br/>
    <table cellspacing="0" cellpadding="4" border="0" width="100%">
        <tr class="table_tr1">
            <td style="border: 1px solid black;"><?= lang('sl') ?></td>
            <td style="border: 1px solid black;"><?= lang('name') ?></td>
            <td style="border: 1px solid black;"><?= lang('overtime_date') ?></td>
            <td style="border: 1px solid black;"><?= lang('overtime_hour') ?></td>
        </tr>
        <?php
        $key = 1;
        $hh = 0;
        $mm = 0;
        ?>
        <?php
        if (!empty($overtime_info)):
            foreach ($overtime_info as $v_overtime) :
                ?>
                <tr class="table_tr2">
                    <td><?php echo $key ?></td>
                    <td><?php echo $v_overtime->fullname ?></td>
                    <td><?php echo strftime(config_item('date_format'), strtotime($v_overtime->overtime_date)); ?></td>
                    <td><?php echo display_time($v_overtime->overtime_hours); ?></td>
                    <?php $hh += date('h', strtotime($v_overtime->overtime_hours)); ?>
                    <?php $mm += date('i', strtotime($v_overtime->overtime_hours)); ?>

                </tr>
                <?php
                $key++;
            endforeach;
            ?>
            <tr class="total_amount">
                <td colspan="3" style="text-align: right"><span><?= lang('total_overtime_hour') ?>:</span></td>
                <td colspan="1" style="padding-left: 8px;"><?php
                    if ($hh >= 1 && $hh <= 9 || $mm >= 1 && $mm <= 9) { // if i<=9 concate with Mysql.becuase on Mysql query fast in two digit like 01.
                        $total_hh = '0' . $hh;
                        $total_mm = '0' . $mm;
                    } else {
                        $total_hh = $hh;
                        $total_mm = $mm;
                    }
                    if ($total_mm > 59) {
                        $total_hh += intval($total_mm / 60);
                        $total_mm = intval($total_mm % 60);
                    }
                    echo $total_hh . " : " . $total_mm . " m";
                    ?></td>
            </tr>
        <?php else : ?>
            <tr>
                <td style="border: 1px solid black;" colspan="7">
                    <strong><?= lang('nothing_to_display') ?></strong>
                </td>
            </tr>
        <?php endif; ?>
    </table>
</div>

