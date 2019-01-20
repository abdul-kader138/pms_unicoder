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
            width: 100%;}

        .table_tr1 .th {
            border: 1px solid #aaaaaa;
            background-color: #dddddd;
            font-size: 12px;}

        .table_tr2 th, .table_tr3 th, .table_tr1 .th, .table_tr3 td {
            padding: 3px 0px 3px 5px;}

        .table_tr3 th {
            border-bottom: 1px solid #aaaaaa;}

        .table_tr3 td {
            border-bottom: 1px solid #dad3d3;
            font-size: 12px;}

        .table_tr3 .td {
            font-size: 13px;
            background: #dee0e4;}

        .th1 {
            text-align: center;
            border: 1px solid #aaaaaa;

        }
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
        <table cellspacing="0" cellpadding="4" border="0" style="width: 100%;">
            <tr style="font-size: 20px;  text-align: center">
                <td style="padding: 10px;">
                    <strong><?= lang('attendance_list') . ' ' . lang('of') . ' ' ?><?php echo $month; ?></strong>
                    <p><strong><?= lang('department') . ' : ' . $dept_name->deptname ?></strong></p>
                </td>
            </tr>
        </table>
    </div>
    <br/>

    <table cellspacing="0" cellpadding="4" border="0" class="table_tr1">
        <tr>
            <th style="width: 20%" class="th"><?= lang('name') ?></th>
            <?php foreach ($dateSl as $edate) : ?>
                <th class="th th1"><?php echo $edate ?></th>
            <?php endforeach; ?>
        </tr>
        <?php

        foreach ($attendance as $key => $v_employee) { ?>
            <tr>
                <td style="width: 20%;border: 1px solid #aaaaaa;"><?php echo $employee[$key]->fullname ?></td>
                <?php

                foreach ($v_employee as $v_result) {
                    ?>
                    <?php foreach ($v_result as $emp_attendance) { ?>
                        <td class="th1">
                            <?php
                            if ($emp_attendance->attendance_status == 1) {
                                echo '<span  style="padding:2px; 4px" class="label label-success std_p">' . lang('p') . '</span>';
                            }
                            if ($emp_attendance->attendance_status == '0') {
                                echo '<span style="padding:2px; 4px" class="label label-danger std_p">' . lang('a') . '</span>';
                            }
                            if ($emp_attendance->attendance_status == 'H') {
                                echo '<span style="padding:2px; 4px" class="label label-info std_p">' . lang('h') . '</span>';
                            }
                            if ($emp_attendance->attendance_status == 3) {
                                echo '<span style="padding:2px; 4px" class="label label-warning std_p">' . lang('l') . '</span>';
                            }
                            ?>
                        </td>
                    <?php }; ?>


                <?php }; ?>
            </tr>
        <?php }; ?>
    </table>
</div>
