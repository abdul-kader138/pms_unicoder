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
            font-weight: bold;
            font-size: 14px;
            border: 1px solid black;}

        .table_tr2 td {
            padding: 7px 0px 7px 8px;
            border: 1px solid black;
            font-size: 12px;}
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
                <td style="padding: 10px;"><?= lang('employee_award_list') ?></td>
            </tr>
        </table>
    </div>
    <br/>
    <table cellspacing="0" cellpadding="4" border="0" width="100%">
        <tr class="table_tr1">
            <td><?= lang('emp_id') ?></td>
            <td><?= lang('name') ?></td>
            <td><?= lang('award_name') ?></td>
            <td><?= lang('gift') ?></td>
            <td><?= lang('amount') ?></td>
            <td><?= lang('month') ?></td>
            <td><?= lang('award_date') ?></td>
        </tr>
        <?php
        $curency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
        if (!empty($employee_award_info)):foreach ($employee_award_info as $v_award_info):
            $emp_info = $this->db->where('user_id', $v_award_info->user_id)->get('tbl_account_details')->row()
            ?>
            <tr class="table_tr2">
                <td><?php echo $emp_info->employment_id ?></td>
                <td><?php echo $emp_info->fullname ?></td>
                <td><?php echo $v_award_info->award_name; ?></td>
                <td><?php echo $v_award_info->gift_item; ?></td>
                <td><?php echo display_money($v_award_info->award_amount, $curency->symbol); ?></td>
                <td><?= strftime(date('M Y'), strtotime($v_award_info->award_date)) ?></td>
                <td><?= strftime(config_item('date_format'), strtotime($v_award_info->award_date)) ?></td>
            </tr>
        <?php endforeach; ?>
        <?php endif; ?>
    </table>
</div>
