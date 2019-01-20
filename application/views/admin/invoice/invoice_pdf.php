 <?php
    $direction = $this->session->userdata('direction');
    if (!empty($direction) && $direction == 'rtl') {
        $RTL = 'on';
    } else {
        $RTL = config_item('RTL');
    }
    ?>
<?php
$paid_amount = $this->invoice_model->calculate_to('paid_amount', $invoice_info->invoices_id);
$client_info = $this->invoice_model->check_by(array('client_id' => $invoice_info->client_id), 'tbl_client');
if (!empty($client_info)) {
    $currency = $this->invoice_model->client_currency_sambol($invoice_info->client_id);
    $client_lang = $client_info->language;
} else {
    $client_lang = 'english';
    $currency = $this->invoice_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
}

unset($this->lang->is_loaded[5]);
$language_info = $this->lang->load('sales_lang', $client_lang, TRUE, FALSE, '', TRUE);
$payment_status = $this->invoice_model->get_payment_status($invoice_info->invoices_id);

$uri = $this->uri->segment(3);
if ($uri == 'invoice_email') {
    $img = base_url() . config_item('invoice_logo');
} else {
    $img = $_SERVER['DOCUMENT_ROOT'] . '/' . config_item('invoice_logo');
    $a = file_exists($img);
    if (empty($a)) {
        $img = base_url() . config_item('invoice_logo');
    }
}
?>
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
<br />

<hr />

<table cellspacing="0" cellpadding="4" border="0" style="width:100%;"<?php if(!empty($RTL)){?> dir="rtl"<?php }else{?>  dir="rtl"<?php }?>>
    <tr>
        <td style="width: 50%;" valign="top">
            <div>
                <?php
                if (!empty($client_info)) {
                    $client_name = $client_info->name;
                    $address = $client_info->address;
                    $city = $client_info->city;
                    $zipcode = $client_info->zipcode;
                    $country = $client_info->country;
                    $phone = $client_info->phone;
                    $email = $client_info->email;
                } else {
                    $client_name = '';
                    $address = '';
                    $city = '';
                    $zipcode = '';
                    $country = '';
                    $phone = '';
                    $email = '';
                }
                ?>
				<h3 style="font-size:20px;"><?= $client_name ?></h3>
                <div><?= $address ?></div>
                <div class="address"><?= $city ? $city.', ' : ''?><?= $zipcode ? $zipcode.', ' : '' ?> <?= $country ?></div>
                <div class="address"><?= $phone ?></div>
                <div class="email"><a href="mailto:<?= $email ?>"><?= $email ?></a></div>
                <?php if (!empty($client_info->vat)) { ?>
                    <div class="email"><?= lang('vat_number') ?>: <?= $client_info->vat ?></div>
                <?php } ?>
            </div>
        </td>
        <td style="width: 50%;">
				 <div>
                <table cellspacing="0" cellpadding="5" border="0">
					<tr>
						<td bgcolor="#EEEEEE" style="border:1px solid #e3e3e3;"><h5 style="margin: 0;">INVOICE# </h5></td>
						<td bgcolor="#ffffff" style="border:1px solid #e3e3e3;"><h5 style="margin: 0;"><?= $invoice_info->reference_no ?> </h5></td>
					</tr>
					<tr>
						<td bgcolor="#EEEEEE" style="border:1px solid #e3e3e3; font-size:10px;"><?= $language_info['invoice_date'] ?> </td>
						<td bgcolor="#ffffff" style="border:1px solid #e3e3e3;"><?= strftime(config_item('date_format'), strtotime($invoice_info->invoice_date)); ?></td>
					</tr>
					<tr>
						<td bgcolor="#EEEEEE" style="border:1px solid #e3e3e3;"><?= $language_info['due_date'] ?> </td>
						<td bgcolor="#ffffff" style="border:1px solid #e3e3e3;"><?= strftime(config_item('date_format'), strtotime($invoice_info->due_date)); ?></td>
					</tr>
					<?php if (!empty($invoice_info->user_id)) { ?>
					<tr>
						<td bgcolor="#EEEEEE" style="border:1px solid #e3e3e3;"><?= lang('sales') . ' ' . lang('agent') ?> </td>
						<td bgcolor="#ffffff" style="border:1px solid #e3e3e3;">
								<?php
								$profile_info = $this->db->where('user_id', $invoice_info->user_id)->get('tbl_account_details')->row();
								if (!empty($profile_info)) {
									echo $profile_info->fullname;
								}
								?>
						</td>
					</tr>
					<?php } ?>
					<tr>
						<td bgcolor="#EEEEEE" style="border:1px solid #e3e3e3;"><?= $payment_status ?></td>
						<td bgcolor="#ffffff" style="border:1px solid #e3e3e3;"><?= $payment_status ?></td>
					</tr>
				</table>
               </div>
        </td>
    </tr>
</table>

<br />
<table border="0" cellspacing="0" cellpadding="5">
    <thead>
    <tr>
        <td bgcolor="#DDDDDD" style="border:1px solid #333333; text-align:center;"><?= $language_info['items'] ?></td>
        <?php
        $colspan = 3;
        $invoice_view = config_item('invoice_view');
        if (!empty($invoice_view) && $invoice_view == '2') {
            $colspan = 4;
            ?>
            <td style="border:1px solid #333333; text-align:center;"><?= lang('hsn_code') ?></td>
        <?php } ?>
		<td bgcolor="#DDDDDD" style="border:1px solid #333333; text-align:center;"><?= $language_info['qty'] ?></td>
        <td bgcolor="#DDDDDD" style="border:1px solid #333333; text-align:center;"><?= $language_info['price'] ?></td>
        <td bgcolor="#DDDDDD" style="border:1px solid #333333; text-align:center;"><?= $language_info['tax'] ?></td>
        <td bgcolor="#DDDDDD" style="border:1px solid #333333; text-align:center;"><?= $language_info['total'] ?></td>
    </tr>
    </thead>
    <tbody>
    <?php
    $invoice_items = $this->invoice_model->ordered_items_by_id($invoice_info->invoices_id);

    if (!empty($invoice_items)) :
        foreach ($invoice_items as $key => $v_item) :
            $item_name = $v_item->item_name ? $v_item->item_name : $v_item->item_desc;
            $item_tax_name = json_decode($v_item->item_tax_name);
            ?>
            <tr>
                <td style="border:1px solid #333333; text-align:center;"><h3><?= $item_name ?></h3><?= nl2br($v_item->item_desc) ?></td>
                <?php
                $invoice_view = config_item('invoice_view');
                if (!empty($invoice_view) && $invoice_view == '2') {
                    ?>
                    <td style="border:1px solid #333333; text-align:center;"><?= $v_item->hsn_code ?></td>
                <?php } ?>
				<td style="border:1px solid #333333; text-align:center;"><?= $v_item->quantity . '   ' . $v_item->unit ?></td>
                <td style="border:1px solid #333333; text-align:center;"><?= display_money($v_item->unit_cost) ?></td>
                <td style="border:1px solid #333333; text-align:center;"><?php
                    if (!empty($item_tax_name)) {
                        foreach ($item_tax_name as $v_tax_name) {
                            $i_tax_name = explode('|', $v_tax_name);
                            echo '<small class="pr-sm">' . $i_tax_name[0] . ' (' . $i_tax_name[1] . ' %)' . '</small>' . display_money($v_item->total_cost / 100 * $i_tax_name[1]) . ' <br>';
                        }
                    }
                    ?></td>
                <td style="border:1px solid #333333; text-align:center;"><?= display_money($v_item->total_cost) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif ?>

    </tbody>
    <tfoot>
	<tr>
        <td colspan="<?= $colspan ?>" rowspan="2"></td>
        <td colspan="1" style="border:1px solid #333333; text-align:center;"><?= $language_info['sub_total'] ?></td>
        <td style="border:1px solid #333333; text-align:center;"><?= display_money($this->invoice_model->calculate_to('invoice_cost', $invoice_info->invoices_id)) ?></td>
    </tr>
	
    <?php if ($invoice_info->discount_total > 0): ?>
        <tr>
        <td colspan="<?= $colspan ?>" rowspan="2"></td>
           <td style="border:1px solid #333333; text-align:center;"><?= $language_info['discount'] ?>(<?php echo $invoice_info->discount_percent; ?>%)</td>
            <td style="border:1px solid #333333; text-align:center;"> <?= display_money($this->invoice_model->calculate_to('discount', $invoice_info->invoices_id)) ?></td>
        </tr>
    <?php endif;
	
    $tax_info = json_decode($invoice_info->total_tax);
    $tax_total = 0;
    if (!empty($tax_info)) {
        $tax_name = $tax_info->tax_name;
        $total_tax = $tax_info->total_tax;
        if (!empty($tax_name)) {
            foreach ($tax_name as $t_key => $v_tax_info) {
                $tax = explode('|', $v_tax_info);
                $tax_total += $total_tax[$t_key];
                ?>
                <tr>
                    <td colspan="1" style="border:1px solid #333333; text-align:center;"><?= $tax[0] . ' (' . $tax[1] . ' %)' ?></td>
                    <td style="border:1px solid #333333; text-align:center;"> <?= display_money($total_tax[$t_key]); ?></td>
                </tr>
            <?php }
        }
    } ?>
				
    <?php if ($tax_total > 0): ?>
        <tr>
            <td colspan="1" style="border:1px solid #333333; text-align:center;"><?= $language_info['total'] . ' ' . $language_info['tax'] ?></td>
            <td style="border:1px solid #333333; text-align:center;"><?= display_money($tax_total); ?></td>
        </tr>
    <?php endif;
	
    if ($invoice_info->adjustment > 0): ?>
        <tr class="total">
			<td  style="border:1px solid #333333; text-align:center;"><?= $language_info['adjustment'] ?></td>
            <td style="border:1px solid #333333; text-align:center;"><?= display_money($invoice_info->adjustment); ?></td>
        </tr>
    <?php endif ?>
		
    <tr>
        <td colspan="1" style="border:1px solid #333333; text-align:center;"><?= $language_info['total'] ?></td>
        <td style="border:1px solid #333333; text-align:center;"><?= display_money($this->invoice_model->calculate_to('total', $invoice_info->invoices_id), $currency->symbol); ?></td>
    </tr>
	
    <?php
    $paid_amount = $this->invoice_model->calculate_to('paid_amount', $invoice_info->invoices_id);
    if ($paid_amount > 0) {
        $total = $language_info['total_due'];
        if ($paid_amount > 0) {
            $text = 'style="color:red"';
            ?>
            <tr>
               <td colspan="<?= $colspan ?>" rowspan="2"></td>
                <td colspan="1" style="border:1px solid #333333; text-align:center;"><?= $language_info['paid_amount'] ?></td>
                <td style="border:1px solid #333333; text-align:center;"><?= $paid_amount ?></td>
            </tr>
        <?php } else {
            $text = '';
        } ?>
        <tr>
            <td colspan="1" style="border:1px solid #333333; text-align:center;"><span <?= $text ?>><?= $total ?></span></td>
            <td style="border:1px solid #333333; text-align:center;"><?= display_money($this->invoice_model->calculate_to('invoice_due', $invoice_info->invoices_id), $currency->symbol); ?></td>
        </tr>
    <?php } ?>
    </tfoot>
</table>
<br />
<?php //<div id="thanks" style="font-size: 1.5em;margin-bottom: 20px;"><?= lang('thanks') !</div>?>
<div id="notices" style="margin-top:15px; <?php if(empty($RTL)){ echo 'border-left: 6px solid #0087C3;'; }else{ echo 'border-right: 6px solid #0087C3;'; } ?>">
    <div class="notice" style="font-size: 1em;color: #777;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $invoice_info->notes ?></div>
</div>
<?php
$invoice_view = config_item('invoice_view');
if (!empty($invoice_view) && $invoice_view > 0) {
    ?>
    <style type="text/css">
        .panel {
			padding:10px;
            margin-bottom: 5px;
            background-color: #ffffff;
            border: 1px solid transparent;
        }

        .panel-custom .panel-heading {
            border-bottom: 2px solid #2b957a;
        }

        .panel .panel-heading {
            border-bottom: 0;
            font-size: 14px;
        }

        .panel-heading {
            padding: 10px 15px;
            border-bottom: 1px solid transparent;
            border-top-right-radius: 3px;
            border-top-left-radius: 3px;
        }

        .panel-title {
            margin-top: 0;
            margin-bottom: 0;
            font-size: 16px;
        }
    </style>
    <div>
        <div style="border-bottom: 2px solid #57B223; padding:10px">
            <div class="panel-title"><?= lang('tax_summary') ?></div>
        </div>
        <br /><br />
        <table border="0" cellspacing="0" cellpadding="4" style="border:1px solid #333333;" width="100%">
            <thead>
            <tr>
                <th bgcolor="#DDDDDD" style="border:1px solid #333333;"><?= $language_info['items'] ?></th>
                <?php
                $invoice_view = config_item('invoice_view');
                if (!empty($invoice_view) && $invoice_view == '2') {
                    ?>
                    <th bgcolor="#DDDDDD" style="border:1px solid #333333;"><?= lang('hsn_code') ?></th>
                <?php } ?>
				<th bgcolor="#DDDDDD" style="border:1px solid #333333;"><?= $language_info['qty'] ?></th>
                <th bgcolor="#DDDDDD" style="border:1px solid #333333;"><?= $language_info['tax'] ?></th>
                <th bgcolor="#DDDDDD" style="border:1px solid #333333;"><?= $language_info['total_tax'] ?></th>
                <th bgcolor="#DDDDDD" style="border:1px solid #333333;"><?= $language_info['tax_excl_amt'] ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $total_tax = 0;
            $total_cost = 0;
            if (!empty($invoice_items)) :
                foreach ($invoice_items as $key => $v_item) :
                    $item_tax_name = json_decode($v_item->item_tax_name);
                    $tax_amount = 0;
                    ?>
                    <tr>
                        <td style="border:1px solid #333333;"><?= $v_item->item_name ?></td>
                        <?php
                        $invoice_view = config_item('invoice_view');
                        if (!empty($invoice_view) && $invoice_view == '2') {
                            ?>
                            <td style="border:1px solid #333333;"><?= $v_item->hsn_code ?></td>
                        <?php } ?>
                        <td style="border:1px solid #333333;"><?= $v_item->quantity . '   ' . $v_item->unit ?></td>
                        <td style="border:1px solid #333333;"><?php
                            if (!empty($item_tax_name)) {
                                foreach ($item_tax_name as $v_tax_name) {
                                    $i_tax_name = explode('|', $v_tax_name);
                                    $tax_amount += $v_item->total_cost / 100 * $i_tax_name[1];
                                    echo '<small class="pr-sm">' . $i_tax_name[0] . ' (' . $i_tax_name[1] . ' %)' . '</small>' . display_money($v_item->total_cost / 100 * $i_tax_name[1]) . ' <br>';
                                }
                            }
                            $total_cost += $v_item->total_cost;
                            $total_tax += $tax_amount;
                            ?></td>
                        <td style="border:1px solid #333333;"><?= display_money($tax_amount) ?></td>
                        <td style="border:1px solid #333333;"><?= display_money($v_item->total_cost) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif ?>

            </tbody>
            <tfoot>
            <tr class="total">
                <td style="border:1px solid #333333;" colspan="<?= $colspan ?>"></td>
                <td style="border:1px solid #333333;"><?= $language_info['total'] ?></td>
                <td style="border:1px solid #333333;"><?= display_money($total_tax) ?></td>
                <td style="border:1px solid #333333;"><?= display_money($total_cost) ?></td>
            </tr>
            </tfoot>
        </table>
    </div>
<?php } ?>
<?php /*$all_payment_info = $this->db->where('invoices_id', $invoice_info->invoices_id)->get('tbl_payments')->result();
if (!empty($all_payment_info)) { 

    <div style="margin-top:20px">
        <div style="width:100%">
            <div style="width:50%;float:left"><h4><?= lang('payment') . ' ' . lang('details') ?></h4></div>
            <div style="clear:both;"></div>
        </div>

        <table style="width:100%;margin-bottom:35px;table-layout:fixed;" cellpadding="0" cellspacing="0" border="0">
            <thead>
            <tr style="height:40px;background:#f5f5f5">
                <td style="padding:5px 10px 5px 10px;word-wrap: break-word;">
                    <?= lang('transaction_id') ?>
                </td>
                <td style="padding:5px 10px 5px 5px;word-wrap: break-word;"
                    align="right">
                    <?= lang('payment_date') ?>
                </td>
                <td style="padding:5px 10px 5px 5px;word-wrap: break-word;"
                    align="right">
                    <?= lang('amount') ?>
                </td>
                <td style="padding:5px 10px 5px 5px;word-wrap: break-word;"
                    align="right">
                    <?= lang('payment_mode') ?>
                </td>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($all_payment_info as $v_payments_info) {
                $payment_methods = $this->invoice_model->check_by(array('payment_methods_id' => $v_payments_info->payment_method), 'tbl_payment_methods');
                ?>
                <tr style="border-bottom:1px solid #ededed">
                    <td style="padding: 10px 0px 10px 10px;"
                        valign="top"><?= $v_payments_info->trans_id; ?>
                    </td>
                    <td style="padding: 10px 10px 5px 10px;text-align:right;word-wrap: break-word;"
                        valign="top">
                        <?= strftime(config_item('date_format'), strtotime($v_payments_info->payment_date)); ?>
                    </td>
                    <td style="padding: 10px 10px 5px 10px;text-align:right;word-wrap: break-word;"
                        valign="top"><?= display_money($v_payments_info->amount, $currency->symbol) ?>
                    </td>
                    <td style="text-align:right;padding: 10px 10px 10px 5px;word-wrap: break-word;"
                        valign="top">
                        <?= $payment_methods->method_name ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
<?php }*/ ?>
			<br /><br /><br /><br />
			<div style="color: #777777;width: 100%;border-top: 1px solid #eeeeee;padding: 8px 0;text-align: center;"><br />
				<?= config_item('invoice_footer') ?>
			</div>
