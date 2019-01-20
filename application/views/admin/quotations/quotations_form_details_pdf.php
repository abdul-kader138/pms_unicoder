<!DOCTYPE html>
<html>
<head>
    <title><?= lang('overtime_report') ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
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
            background-color: rgb(224, 224, 224);
        }

        .table_tr1 td {
            padding: 7px 0px 7px 8px;
            font-weight: bold;
        }

        .table_tr2 td {
            padding: 7px 0px 7px 8px;
            border: 1px solid black;
        }

        .total_amount td {
            padding: 7px 8px 7px 0px;
            border: 1px solid black;
            font-size: 15px;
        }
    </style>
</head>
<body style="min-width: 100%; min-height: 100%; overflow: hidden; alignment-adjust: central;">
<br/>
<div style="width: 100%; border-bottom: 2px solid black;"><table cellspacing="0" cellpadding="4" border="0" style="width:100%; margin-top:-25px ">
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
<div style="width: 100%;">
    <div style="width: 100%; background-color: rgb(224, 224, 224); padding: 1px 0px 5px 15px;">
        <table style="width: 100%;" cellspacing="0" cellpadding="4" border="0">
            <tr style="font-size: 20px;  text-align: center">
                <td style="padding: 10px;"><?= $quotationforms_info->quotationforms_title ?></td>
            </tr>
        </table>
    </div>
    <br/>
    <?php
    if (!empty($formbuilder_data)) {
        foreach ($formbuilder_data as $value) {
            if (!empty($value)) {
                $field_type = $value['field_type'];
                $field_options = $value['field_options'];
                if ($value['required'] == 1) {
                    $required = 'true';
                }
                ?>
                <?php if ($field_type == 'paragraph'): ?>
                    <label
                        class="control-label"><?php echo $value['label'] ?> <?php if (!empty($required)) { ?>
                            <span class="text-danger">*</span><?php } ?></label>
                    <div class="">
                        <?php
                        if ($field_options['size'] == 'small') {
                            $height = "<br/><br/><br/>";
                        } elseif ($field_options['size'] == 'medium') {
                            $height = "<br/><br/><br/><br/><br/>";
                        } else {
                            $height = "<br/><br/><br/><br/><br/><br/><br/><br/>";
                        }
                        echo $height;
                        ?>
                    </div>
                    <br/>
                <?php endif; ?>
                <?php if ($field_type == 'dropdown'): ?>
                    <label
                        class="control-label"><?php echo $value['label'] ?> <?php if (!empty($required)) { ?>
                            <span class="text-danger">*</span><?php } ?></label>
                    <div class="">
                        <?php
                        $options = $field_options['options'];
                        foreach ($options as $dr => $v_option) {
                            if ($v_option['checked'] == 1) {
                                $checked = 'selected';
                            } else {
                                $checked = '';
                            }
                            echo $dr + 1 . '.' . $v_option['label'] . '  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                            ?>
                        <?php } ?>
                    </div>
                    <br/>
                <?php endif; ?>
                <?php if ($field_type == 'text'): ?>
                    <label
                        class="control-label"><?php echo $value['label'] ?> <?php if (!empty($required)) { ?>
                            <span class="text-danger">*</span><?php } ?></label>
                    <div class="">
                        <?php $height = "<br/><br/><br/>";
                        echo $height;
                        ?>
                    </div>
                    <br/>
                <?php endif; ?>
                <?php if ($field_type == 'checkboxes'): ?>
                    <label
                        class="control-label"><?php echo $value['label'] ?> <?php if (!empty($required)) { ?>
                            <span class="text-danger">*</span><?php } ?></label>
                    <div class="">
                        <?php
                        $options = $field_options['options'];
                        foreach ($options as $ch => $v_option) {
                            if ($v_option['checked'] == 1) {
                                $checked = 'checked';
                            } else {
                                $checked = '';
                            }
                            echo $ch + 1 . '.' . $v_option['label'] . '  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                            ?>

                        <?php } ?>
                    </div>
                    <br/>
                <?php endif; ?>
                <?php if ($field_type == 'radio'): ?>
                    <label
                        class="control-label"><?php echo $value['label'] ?> <?php if (!empty($required)) { ?>
                            <span class="text-danger">*</span><?php } ?></label>
                    <div class="">
                        <?php
                        $options = $field_options['options'];
                        foreach ($options as $r => $v_option) {
                            if ($v_option['checked'] == 1) {
                                $checked = 'checked';
                            } else {
                                $checked = '';
                            }
                            echo $r + 1 . '.' . $v_option['label'] . '  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                            ?>
                        <?php } ?>
                    </div>
                    <br/>
                <?php endif; ?>

                <?php
            }
        }
    }
    ?>
</div>
