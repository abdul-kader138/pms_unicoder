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
                <h2 style="font-size: 20px;font-weight: bold;margin: 0px; padding:0px;"><?= (config_item('company_legal_name_' . $client_lang) ? config_item('company_legal_name_' . $client_lang) : config_item('company_legal_name')) ?></h2>

                <?= (config_item('company_address_' . $client_lang) ? config_item('company_address_' . $client_lang) : config_item('company_address')) ?>

                <br /><?= (config_item('company_city_' . $client_lang) ? config_item('company_city_' . $client_lang) : config_item('company_city')) ?>
                    <?php if(config_item('company_zip_code')){  ?>, <?= config_item('company_zip_code') ?><?php } ?>

                , <?= (config_item('company_country_' . $client_lang) ? config_item('company_country_' . $client_lang) : config_item('company_country')) ?>
                <?php if(config_item('company_phone')){ ?><br /> <?= config_item('company_phone') ?><?php } ?>
                <?php if(config_item('company_email')){ ?><br /><?= config_item('company_email') ?><?php } ?>
                <?php if(config_item('company_vat')){ ?><br /><?=lang('company_vat');?> : <?= config_item('company_vat') ?><?php } ?>
        </td>
    </tr>
</table>
</div>
<br/>
<div style="padding: 5px 0; width: 100%;">
    <div>
        <table style="width: 100%;" cellspacing="0" cellpadding="4" border="0">
            <tr>
                <td>
                                <?php if ($training_info->avatar): ?>
                                    <img src="<?php echo base_url() . $training_info->avatar; ?>"
                                         style="width: 138px; height: 144px; border-radius: 3px;">
                                <?php else: ?>
                                    <img alt="Employee_Image">
                                <?php endif; ?>
                </td>
                
                <td>
                    <table cellspacing="0" cellpadding="4" border="0">
                        <tr>
                            <td colspan="2" bgcolor="#dddddd" style="border:1px solid #333333;"><h2><?php echo "$training_info->fullname"; ?></h2></td>
                        </tr>
                        <tr>
                            <td width="30%" bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('emp_id') ?> : </strong></td>
                            <td width="70%" style="border:1px solid #333333;"><?php echo "$training_info->employment_id "; ?></td>
                        </tr>
                        <?php
                        $design_info = $this->db->where('designations_id', $training_info->designations_id)->get('tbl_designations')->row();
                        $dept_info = $this->db->where('departments_id', $design_info->departments_id)->get('tbl_departments')->row();

                        ?>
                        <tr>
                            <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('departments') ?> : </strong></td>
                            <td style="border:1px solid #333333;"><?php echo "$dept_info->deptname"; ?></td>
                        </tr>
                        <tr>
                            <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('designation') ?> :</strong></td>
                            <td style="border:1px solid #333333;"><?php echo "$design_info->designations"; ?></td>
                        </tr>
                        <tr>
                            <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('joining_date') ?>: </strong></td>
                            <td style="border:1px solid #333333;"><?= strftime(config_item('date_format'), strtotime($training_info->joining_date)) ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div>
        <div style="width: 100%; background: #dddddd;padding: 1px 0px 1px 10px; color: black; vertical-align: middle; ">
            <p style="margin-left: 10px; font-size: 15px; font-weight: lighter;">
                <strong><?= lang('training_details') ?></strong></p>
        </div>

        <table style="width: 100%; font-size: 13px;margin-top: 20px" cellspacing="0" cellpadding="4" border="0">
            <tr>
                <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('course_training') ?> :</strong>
                </td>

                <td style="border:1px solid #333333;">&nbsp; <?php
                    echo $training_info->training_name;
                    ?></td>
            </tr>
            <tr>
                <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('vendor') ?> :</strong></td>

                <td style="border:1px solid #333333;">&nbsp; <?php
                    echo $training_info->vendor_name;
                    ?></td>
            </tr>
            <tr>
                <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('start_date') ?> :</strong></td>

                <td style="border:1px solid #333333;">&nbsp; <?php
                    echo strftime(config_item('date_format'), strtotime($training_info->start_date));
                    ?></td>
            </tr>
            <tr>
                <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('finish_date') ?> :</strong>
                </td>

                <td style="border:1px solid #333333;">&nbsp; <?php
                    echo strftime(config_item('date_format'), strtotime($training_info->finish_date));
                    ?></td>
            </tr>
            <tr>
                <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('training_cost') ?> :</strong>
                </td>

                <td style="border:1px solid #333333;">&nbsp; <?php
                    $curency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
                    echo display_money($training_info->training_cost, $curency->symbol);
                    ?></td>
            </tr>
            <tr>
                <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('status') ?> :</strong>
                </td>

                <td style="border:1px solid #333333;">&nbsp; <?php
                    if ($training_info->status == '0') {
                        echo '<span class="label label-warning">' . lang('pending') . ' </span>';
                    } elseif ($training_info->status == '1') {
                        echo '<span class="label label-info">' . lang('started') . '</span>';
                    } elseif ($training_info->status == '2') {
                        echo '<span class="label label-success"> ' . lang('completed') . ' </span>';
                    } else {
                        echo '<span class="label label-danger"> ' . lang('terminated ') . '</span>';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('performance') ?> :</strong>
                </td>

                <td style="border:1px solid #333333;">&nbsp; <?php
                    if ($training_info->performance == '0') {
                        echo '<span class="label label-warning">' . lang('not_concluded') . ' </span>';
                    } elseif ($training_info->performance == '1') {
                        echo '<span class="label label-info">' . lang('satisfactory') . '</span>';
                    } elseif ($training_info->performance == '2') {
                        echo '<span class="label label-primary"> ' . lang('average') . ' </span>';
                    } elseif ($training_info->performance == '3') {
                        echo '<span class="label label-danger"> ' . lang('poor') . ' </span>';
                    } else {
                        echo '<span class="label label-success"> ' . lang('excellent ') . '</span>';
                    }
                    ?>
                </td>
            </tr>
            <tr>
            <td colspan="2" style="border:1px solid #333333;">
                <span style="word-wrap: break-word;"><?php echo $training_info->remarks; ?></span>
              </td>
          </tr>

        </table>

