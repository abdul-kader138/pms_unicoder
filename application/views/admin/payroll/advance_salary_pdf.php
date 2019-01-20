    <?php
    $direction = $this->session->userdata('direction');
    if (!empty($direction) && $direction == 'rtl') {
        $RTL = 'on';
    } else {
        $RTL = config_item('RTL');
    }
    ?>
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
                <td style="padding: 10px;"><?= lang('advance_salary_report') . ' ' . lang('for') . ' ' ?><?php echo $monthyaer ?></td>
            </tr>
        </table>
    </div>
    <br/>
    <table cellspacing="0" cellpadding="4" border="0" width="100%">
        <tr class="table_tr1">
            <th bgcolor="#dddddd" style="border:1px solid #333333;text-align:center;"><?= lang('emp_id') ?></th>
            <th bgcolor="#dddddd" style="border:1px solid #333333;text-align:center;"><?= lang('name') ?></th>
            <th bgcolor="#dddddd" style="border:1px solid #333333;text-align:center;"><?= lang('deduct_month') ?></th>
            <th bgcolor="#dddddd" style="border:1px solid #333333;text-align:center;"><?= lang('request_date') ?></th>
            <th bgcolor="#dddddd" style="border:1px solid #333333;text-align:center;"><?= lang('amount') ?></th>
            <th bgcolor="#dddddd" style="border:1px solid #333333; text-align:center;"><?= lang('status') ?></th>
        </tr>
        <?php
        $curency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
        $total_amount = 0;
        ?>
        <?php if (!empty($advance_salary_info)): foreach ($advance_salary_info as $advance_salary) : ?>
            <tr class="table_tr2">
                <td style="border:1px solid #333333; text-align:center;"><?php echo $advance_salary->employment_id ?></td>
                <td style="border:1px solid #333333; text-align:center;"><?php echo $advance_salary->fullname ?></td>
                <td style="border:1px solid #333333; text-align:center;"><?php echo date('Y M', strtotime($advance_salary->deduct_month)) ?></td>
                <td style="border:1px solid #333333; text-align:center;"><?= strftime(config_item('date_format'), strtotime($advance_salary->request_date)) ?></td>
                <td style="border:1px solid #333333; text-align:center;"><?php echo display_money($advance_salary->advance_amount, $curency->symbol);
                    $total_amount += $advance_salary->advance_amount;
                    ?></td>
                <td style="border:1px solid #333333; text-align:center;"><?php
                    if ($advance_salary->status == '0') {
                        echo '<span class="label label-warning">' . lang('pending') . '</span>';
                    } elseif ($advance_salary->status == '1') {
                        echo '<span class="label label-success"> ' . lang('accepted') . '</span>';
                    } elseif ($advance_salary->status == '2') {
                        echo '<span class="label label-danger">' . lang('rejected') . '</span>';
                    } else {
                        echo '<span class="label label-info">' . lang('paid') . '</span>';
                    }
                    ?></td>
            </tr>
            <?php
        endforeach;
            ?>
            <tr class="total_amount">
                <td colspan="4" style="text-align: right;border:1px solid #333333;"><strong><?= lang('total') . ' ' . lang('advance_salary') ?>
                        : </strong></td>
                <td colspan="2" style="padding-left: 8px;border:1px solid #333333;">
                    <strong><?php echo display_money($total_amount, $curency->symbol); ?></strong></td>
            </tr>
        <?php else : ?>
            <tr>
                <td style="border:1px solid #333333; text-align:center" colspan="7">
                    <strong><?= lang('nothing_to_display') ?></strong>
                </td>
            </tr>
        <?php endif; ?>
    </table>
</div>

