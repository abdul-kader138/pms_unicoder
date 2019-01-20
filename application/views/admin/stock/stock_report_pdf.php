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
    <div style="background: #E0E5E8;padding: 5px;">
        <!-- Default panel contents -->
        <div
            style="font-size: 15px;padding: 5px 0px 0px 0px"><?= lang('stock') . ' ' . lang('report_from') ?>
            :<strong>
            <?= strftime(config_item('date_format'), strtotime($start_date)); ?></div>
        <div
            style="font-size: 15px;padding: 0px 0px 0px 0px"><strong><?= lang('stock') . ' ' . lang('report_to') ?>
            : <strong><?= strftime(config_item('date_format'), strtotime($end_date)); ?></strong></div>
    </div>
    <?php
    if (!empty($assign_stock) && !empty($purchase_stock)) {
        $col = 'col-md-6';
    } else {
        $col = 'col-md-12';
    }
    if (!empty($assign_stock) || !empty($purchase_stock)) { ?>
        <?php if (!empty($assign_stock)) { ?>

            <div class="<?= $col ?>">
            <div class="custom-bg p text-center"><strong><?= lang('assign_stock_list') ?></strong></div>
            <table cellspacing="0" cellpadding="4" border="0" width="100%">
            <?php foreach ($assign_stock as $item_name => $v_assign_report) { ?>
                <tr style="width: 100%; background-color: rgb(224, 224, 224);margin-top: 15px;padding-bottom: 5px">
                    <td colspan="3" style="border:1px solid #333333; text-align:center;"><strong><?php echo $item_name; ?></strong></td>
                </tr>
                <tr>
                    <th bgcolor="#dddddd" style="border:1px solid #333333; text-align:center;"><?= lang('assigned_user') ?></th>
                    <th bgcolor="#dddddd" style="border:1px solid #333333; text-align:center;"><?= lang('assign_date') ?></th>
                    <th bgcolor="#dddddd" style="border:1px solid #333333; text-align:center;"><?= lang('assign_quantity') ?></th>
                </tr>
                <?php
                $total_assign_inventory = 0;
                if (!empty($v_assign_report)) {
                    foreach ($v_assign_report as $v_report) {
                        ?>

                        <tr style="width: 100%;">
                            <td style="border:1px solid #333333; text-align:center;"><?php echo $v_report->fullname ?></td>
                            <td style="border:1px solid #333333; text-align:center;"><?= strftime(config_item('date_format'), strtotime($v_report->assign_date)); ?></td>
                            <td style="border:1px solid #333333; text-align:center;"><?php echo $v_report->assign_inventory; ?> </td>
                            <?php
                            $total_assign_inventory += $v_report->assign_inventory;
                            ?>
                        </tr>
                    <?php } ?>
                    <tr>
                        <th style="border:1px solid #333333; text-align:center;"colspan="2"><strong
                                style="margin-right: 5px"> <?= lang('total') ?> <?php echo $v_report->item_name ?>
                                : </strong></th>
                        <td style="border:1px solid #333333; text-align:center;"><span><?php
                                echo $total_assign_inventory;
                                ?></span>
                        <span
                            style="padding-right: 20px;text-align: right;display: inline-block;overflow: hidden; "> <?= lang('available_stock') ?>
                            : <strong> <?php echo $v_report->total_stock; ?> </strong></span>
                    </td>
                </tr>
            <?php }; ?>
        <?php }; ?>
    <?php } else { ?>
        <tr>
            <td colspan="3" style="border:1px solid #333333; text-align:center;">
                <strong><?= lang('nothing_to_display') ?></strong>
            </td>
        </tr>
        </table>

    <?php } ?>
</div>
<?php if (!empty($purchase_stock)) { ?>
<div class="<?= $col ?>">
    <div class="custom-bg p text-center"><strong><?= lang('stock_list') ?></strong></div>
    <table cellspacing="0" cellpadding="4" border="0" width="100%">
        <?php foreach ($purchase_stock as $item_name => $v_purchase_stoc) { ?>

            <tr style="width: 100%; background-color: rgb(224, 224, 224);margin-top: 15px;padding-bottom: 5px">
                <td colspan="3" style="border:1px solid #333333; text-align:center;"><strong><?php echo $item_name; ?></strong></td>
            </tr>
            <tr>
                <th  style="border:1px solid #333333; text-align:center;" bgcolor="#dddddd"><?= lang('item_name') ?></th>
                <th style="border:1px solid #333333; text-align:center;" bgcolor="#dddddd"><?= lang('inventory') ?></th>
                <th style="border:1px solid #333333; text-align:center;" bgcolor="#dddddd"><?= lang('buying_date') ?></th>
            </tr>
            <?php
            $total_inventory = 0;
            if (!empty($v_purchase_stoc)) {
                foreach ($v_purchase_stoc as $v_stock) {
                    ?>
                    <tr style="width: 100%;">
                        <td style="border:1px solid #333333; text-align:center;"><?php echo $v_stock->item_name ?></td>
                        <td style="border:1px solid #333333; text-align:center;"><?php echo $v_stock->inventory; ?> </td>
                        <td style="border:1px solid #333333; text-align:center;"><?= strftime(config_item('date_format'), strtotime($v_stock->purchase_date)); ?></td>
                        <?php
                        $total_inventory += $v_stock->inventory;
                        ?>
                    </tr>
                <?php }
            ; ?>
                <tr>
                    <th style="border:1px solid #333333; text-align:center;"><strong
                            style="margin-right: 5px"> <?= lang('total') . ' ' . lang('assigned') . ': ' ?> </strong>
                    </th>
                    <td style="border:1px solid #333333; text-align:center;"><?= $total_inventory - $v_stock->total_stock ?></td>
                    <td style="border:1px solid #333333; text-align:center;">
                        <span
                            style="padding-right: 20px;text-align: right;display: inline-block;overflow: hidden; "> <?= lang('available_stock') ?>
                            : <strong> <?php echo $v_report->total_stock; ?> </strong></span>
                    </td>
                </tr>
            <?php }; ?>
        <?php }; ?>
        <?php } else { ?>
        <tr>
            <td colspan="3" style="border:1px solid #333333; text-align:center;">
                <strong><?= lang('nothing_to_display') ?></strong>
            </td>
        </tr>
    </table>
    <?php } ?>
</div>
<?php }; ?>
