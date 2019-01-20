    <?php
    $direction = $this->session->userdata('direction');
    if (!empty($direction) && $direction == 'rtl') {
        $RTL = 'on';
    } else {
        $RTL = config_item('RTL');
    }
    ?>
<?php
$client_info = $this->estimates_model->check_by(array('client_id' => $estimates_info->client_id), 'tbl_client');

$client_lang = $client_info->language;
unset($this->lang->is_loaded[5]);
$language_info = $this->lang->load('sales_lang', $client_lang, TRUE, FALSE, '', TRUE);
$currency = $this->estimates_model->client_currency_sambol($estimates_info->client_id);
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


<table cellspacing="0" cellpadding="4" border="0" width="100%">
    <tr>
        <td>
            <div id="client">
                <h2 class="name"><?= $client_info->name ?></h2>
                <div class="address"><?= $client_info->address ?></div>
                <div class="address"><?= $client_info->city ?>, <?= $client_info->zipcode ?>
                    ,<?= $client_info->country ?></div>
                <div class="address"><?= $client_info->phone ?></div>
                <div class="email"><a href="mailto:<?= $client_info->email ?>"><?= $client_info->email ?></a></div>
                <?php if (!empty($client_info->vat)) { ?>
                    <div class="email"><?= lang('vat_number') ?>: <?= $client_info->vat ?></div>
                <?php } ?>
            </div>
        </td>
        <td>
            <div id="invoice">
                <h1><?= $estimates_info->reference_no ?></h1>
                <div class="date"><?= $language_info['estimate_date'] ?>
                    :<?= strftime(config_item('date_format'), strtotime($estimates_info->estimate_date)); ?></div>
                <div class="date"><?= $language_info['valid_until'] ?>
                    :<?= strftime(config_item('date_format'), strtotime($estimates_info->due_date)); ?></div>
                <div class="date"><?= $language_info['estimate_status'] ?>: <?= lang($estimates_info->status) ?></div>
                <?php if (!empty($estimates_info->user_id)) { ?>
                    <div class="date">
                        <?= lang('sales') . ' ' . lang('agent') ?><?php
                        $profile_info = $this->db->where('user_id', $estimates_info->user_id)->get('tbl_account_details')->row();
                        if (!empty($profile_info)) {
                            echo $profile_info->fullname;
                        }
                        ?>
                    </div>
                <?php } ?>
            </div>
        </td>
    </tr>
</table>

<table cellspacing="0" cellpadding="4" border="0" width="100%">
    <thead>
    <tr>
        <th bgcolor="#dddddd" style="border:1px solid #333333;text-align:center;"><?= $language_info['items'] ?></th>
        <?php
        $colspan = 3;
        $invoice_view = config_item('invoice_view');
        if (!empty($invoice_view) && $invoice_view == '2') {
            $colspan = 4;
            ?>
            <th bgcolor="#dddddd" style="border:1px solid #333333;text-align:center;"><?= lang('hsn_code') ?></th>
        <?php } ?>
        <th bgcolor="#dddddd" style="border:1px solid #333333;text-align:center;"><?= $language_info['qty'] ?></th>
        <th bgcolor="#dddddd" style="border:1px solid #333333;text-align:center;"><?= $language_info['price'] ?></th>
        <th bgcolor="#dddddd" style="border:1px solid #333333;text-align:center;"><?= $language_info['tax'] ?></th>
        <th bgcolor="#dddddd" style="border:1px solid #333333;text-align:center;"><?= $language_info['total'] ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $invoice_items = $this->estimates_model->ordered_items_by_id($estimates_info->estimates_id);

    if (!empty($invoice_items)) :
        foreach ($invoice_items as $key => $v_item) :
            $item_name = $v_item->item_name ? $v_item->item_name : $v_item->item_desc;
            $item_tax_name = json_decode($v_item->item_tax_name);
            ?>
            <tr>
                <td style="border:1px solid #333333;text-align:center;"><h3><?= $item_name ?></h3><?= nl2br($v_item->item_desc) ?></td>
                <?php
                $invoice_view = config_item('invoice_view');
                if (!empty($invoice_view) && $invoice_view == '2') {
                    ?>
                    <td style="border:1px solid #333333;text-align:center;"><?= $v_item->hsn_code ?></td>
                <?php } ?>
                <td style="border:1px solid #333333;text-align:center;"><?= $v_item->quantity . '   ' . $v_item->unit ?></td>
                <td style="border:1px solid #333333;text-align:center;"><?= display_money($v_item->unit_cost) ?></td>
                <td style="border:1px solid #333333;text-align:center;"><?php
                    if (!empty($item_tax_name)) {
                        foreach ($item_tax_name as $v_tax_name) {
                            $i_tax_name = explode('|', $v_tax_name);
                            echo '<small class="pr-sm">' . $i_tax_name[0] . ' (' . $i_tax_name[1] . ' %)' . '</small>' . display_money($v_item->total_cost / 100 * $i_tax_name[1]) . ' <br>';
                        }
                    }
                    ?></td>
                <td style="border:1px solid #333333;text-align:center;"><?= display_money($v_item->total_cost) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif ?>

    </tbody>
    <tfoot>
    <tr class="total">
        <td colspan="<?= $colspan ?>" style="border:1px solid #333333;"></td>
        <td colspan="1" style="border:1px solid #333333;"><?= $language_info['sub_total'] ?></td>
        <td style="border:1px solid #333333;"><?= display_money($this->estimates_model->estimate_calculation('estimate_cost', $estimates_info->estimates_id)) ?></td>
    </tr>
    <?php if ($estimates_info->discount_total > 0): ?>
        <tr class="total">
            <td style="border:1px solid #333333;" colspan="<?= $colspan ?>"></td>
            <td style="border:1px solid #333333;" colspan="1"><?= $language_info['discount'] ?>(<?php echo $estimates_info->discount_percent; ?>%)</td>
            <td style="border:1px solid #333333;"> <?= display_money($this->estimates_model->estimate_calculation('discount', $estimates_info->estimates_id)) ?></td>
        </tr>
    <?php endif;
    $tax_info = json_decode($estimates_info->total_tax);
    $tax_total = 0;
    if (!empty($tax_info)) {
        $tax_name = $tax_info->tax_name;
        $total_tax = $tax_info->total_tax;
        if (!empty($tax_name)) {
            foreach ($tax_name as $t_key => $v_tax_info) {
                $tax = explode('|', $v_tax_info);
                $tax_total += $total_tax[$t_key];
                ?>
                <tr class="total">
                    <td style="border:1px solid #333333;" colspan="<?= $colspan ?>"></td>
                    <td style="border:1px solid #333333;" colspan="1"><?= $tax[0] . ' (' . $tax[1] . ' %)' ?></td>
                    <td style="border:1px solid #333333;"> <?= display_money($total_tax[$t_key]); ?></td>
                </tr>
            <?php }
        }
    } ?>
    <?php if ($tax_total > 0): ?>
        <tr class="total">
            <td style="border:1px solid #333333;" colspan="<?= $colspan ?>"></td>
            <td style="border:1px solid #333333;" colspan="1"><?= $language_info['total'] . ' ' . $language_info['tax'] ?></td>
            <td style="border:1px solid #333333;"><?= display_money($tax_total); ?></td>
        </tr>
    <?php endif;
    if ($estimates_info->adjustment > 0): ?>
        <tr class="total">
            <td style="border:1px solid #333333;" colspan="<?= $colspan ?>"></td>
            <td style="border:1px solid #333333;" colspan="1"><?= $language_info['adjustment'] ?></td>
            <td style="border:1px solid #333333;"><?= display_money($estimates_info->adjustment); ?></td>
        </tr>
    <?php endif ?>
    <tr class="total">
        <td style="border:1px solid #333333;" colspan="<?= $colspan ?>"></td>
        <td style="border:1px solid #333333;" colspan="1"><?= $language_info['total'] ?></td>
        <td style="border:1px solid #333333;"><?= display_money($this->estimates_model->estimate_calculation('total', $estimates_info->estimates_id), $currency->symbol); ?></td>
    </tr>
    </tfoot>
</table>
<div id="thanks"><?= lang('thanks') ?>!</div>
<div id="notices">
    <div class="notice"><?= $estimates_info->notes ?></div>
</div>
<?php
$invoice_view = config_item('invoice_view');
if (!empty($invoice_view) && $invoice_view > 0) {
    ?>
    <div class="panel panel-custom" style="margin-top: 20px">
        <div class="panel-heading" style="border:1px solid #dde6e9;border-bottom: 2px solid #57B223;">
            <div class="panel-title"><?= lang('tax_summary') ?></div>
        </div>
        <br>
        <table cellspacing="0" cellpadding="4" border="0" width="100%">
            <thead>
            <tr>
                <th bgcolor="#dddddd" style="border:1px solid #333333;text-align:center;"><?= $language_info['items'] ?></th>
                <?php
                $invoice_view = config_item('invoice_view');
                if (!empty($invoice_view) && $invoice_view == '2') {
                    ?>
                    <th bgcolor="#dddddd" style="border:1px solid #333333;text-align:center;"><?= lang('hsn_code') ?></th>
                <?php } ?>
                <th bgcolor="#dddddd" style="border:1px solid #333333;text-align:center;"><?= $language_info['qty'] ?></th>
                <th bgcolor="#dddddd" style="border:1px solid #333333;text-align:center;"><?= $language_info['tax'] ?></th>
                <th bgcolor="#dddddd" style="border:1px solid #333333;text-align:center;"><?= $language_info['total_tax'] ?></th>
                <th bgcolor="#dddddd" style="border:1px solid #333333;text-align:center;"><?= $language_info['tax_excl_amt'] ?></th>
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
                        <td style="border:1px solid #333333;text-align:center;"><?= $v_item->item_name ?></td>
                        <?php
                        $invoice_view = config_item('invoice_view');
                        if (!empty($invoice_view) && $invoice_view == '2') {
                            ?>
                            <td style="border:1px solid #333333;text-align:center;"><?= $v_item->hsn_code ?></td>
                        <?php } ?>
                        <td style="border:1px solid #333333;text-align:center;"><?= $v_item->quantity . '   ' . $v_item->unit ?></td>
                        <td style="border:1px solid #333333;text-align:center;"><?php
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
                        <td style="border:1px solid #333333;text-align:center;"><?= display_money($tax_amount) ?></td>
                        <td style="border:1px solid #333333;text-align:center;"><?= display_money($v_item->total_cost) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif ?>

            </tbody>
            <tfoot>
            <tr class="total">
                <td colspan="3" style="border:1px solid #333333;text-align:center;"></td>
                <td style="border:1px solid #333333;text-align:center;"><?= $language_info['total'] ?></td>
                <td style="border:1px solid #333333;text-align:center;"><?= display_money($total_tax) ?></td>
                <td style="border:1px solid #333333;text-align:center;"><?= display_money($total_cost) ?></td>
            </tr>
            </tfoot>
        </table>
    </div>
<?php } ?>
<div>
    <?= config_item('estimate_footer') ?>
</div>
