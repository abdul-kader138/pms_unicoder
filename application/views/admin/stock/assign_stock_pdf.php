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
    <div style="width: 100%; background-color: rgb(224, 224, 224); padding: 1px 0px 5px 15px;">
        <table cellspacing="0" cellpadding="4" border="0" style="width: 100%;">
            <tr style="font-size: 20px;  text-align: center">
                <td style="border:1px solid #333333; text-align:center;"><?= lang('assign_stock_list_for') . ' -' ?><strong><?php
                        if (!empty($employee)) {
                            echo $employee->fullname . ' (' . $employee->employment_id . ')';
                        }
                        ?></strong></td>
            </tr>
        </table>
    </div>
    <br/>
    <?php if (!empty($assign_list)): foreach ($assign_list as $sub_category => $v_assign_list) : ?>
        <?php if (!empty($v_assign_list)): ?>
            <div style="width: 100%; background-color: rgb(224, 224, 224); padding: 1px 0px 5px 15px;">
                <table cellspacing="0" cellpadding="4" border="0" style="width: 100%;">
                    <tr style="font-size: 20px;">
                        <td style="border:1px solid #333333; text-align:center;" bgcolor="#dddddd"><?php echo $sub_category ?></td>
                    </tr>
                </table>
            </div>
            <table cellspacing="0" cellpadding="4" border="0" width="100%">
                <tr class="table_tr1">
                    <td style="border:1px solid #333333; text-align:center;" bgcolor="#dddddd"><?= lang('sl') ?></td>
                    <td style="border:1px solid #333333; text-align:center;" bgcolor="#dddddd"><?= lang('item_name') ?></td>
                    <td style="border:1px solid #333333; text-align:center;" bgcolor="#dddddd"><?= lang('assign_quantity') ?></td>
                    <td style="border:1px solid #333333; text-align:center;" bgcolor="#dddddd"><?= lang('assign_date') ?></td>
                </tr>

                <?php foreach ($v_assign_list as $key => $v_assign_stock) : ?>
                    <tr class="table_tr2">
                        <td style="border:1px solid #333333; text-align:center;" ><?php echo $key + 1 ?></td>
                        <td style="border:1px solid #333333; text-align:center;" ><?php echo $v_assign_stock->item_name ?></td>
                        <td style="border:1px solid #333333; text-align:center;" ><?php echo $v_assign_stock->assign_inventory ?></td>
                        <td style="border:1px solid #333333; text-align:center;" ><?= strftime(config_item('date_format'), strtotime($v_assign_stock->assign_date)); ?></td>
                    </tr>
                    <?php
                endforeach;
                ?>
            </table>
        <?php endif; ?>
    <?php endforeach; ?>
    <?php else : ?>
        <div class="panel-body">
            <strong><?= lang('nothing_to_display') ?></strong>
        </div>
    <?php endif; ?>
</div>
