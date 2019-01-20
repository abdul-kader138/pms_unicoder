    <?php
    $direction = $this->session->userdata('direction');
    if (!empty($direction) && $direction == 'rtl') {
        $RTL = 'on';
    } else {
        $RTL = config_item('RTL');
    }
    ?>
<?php
if (!empty($all_invoices_info)) {
    foreach ($all_invoices_info as $v_invoice) {
        if (!empty($v_invoice)) {
            $all_payment_info = $this->db->where('invoices_id', $v_invoice->invoices_id)->get('tbl_payments')->result();

            if (!empty($all_payment_info)):foreach ($all_payment_info as $v_payments_info):
                $client_info = $this->invoice_model->check_by(array('client_id' => $v_payments_info->paid_by), 'tbl_client');
                ?>

                <?php
            endforeach;
            endif;
        }

    }
}
?>
<?php
$invoice_info = $this->invoice_model->check_by(array('invoices_id' => $payments_info->invoices_id), 'tbl_invoices');
$client_info = $this->invoice_model->check_by(array('client_id' => $payments_info->paid_by), 'tbl_client');
$payment_methods = $this->invoice_model->check_by(array('payment_methods_id' => $payments_info->payment_method), 'tbl_payment_methods');

?>
<div style="width: 100%; border-bottom: 2px solid black;">
    <table cellspacing="0" cellpadding="4" border="0" style="width:100%; margin-top:-25px; vertical-align: middle; ">
        <tr>

            <td>
                <img style="width: 120px;margin-top: -10px;margin-right: 10px;"
                     src="<?php echo base_url();?><?php 
					 if(!empty(config_item('invoice_logo')) and file_exists(getcwd(). "/".config_item('invoice_logo'))){
						  echo config_item('invoice_logo'); }else{ echo config_item('company_logo'); }?>">
            </td>
            <td>
                <p style="margin-left: 10px; font: 22px lighter;"><?= config_item('company_name') ?></p>
                <p style="color:#999;"><?= $this->config->item('company_address') ?></p>
            </td>
        </tr>
    </table>
</div>
<br/>

<table style="width:100%;margin-bottom:35px;" cellpadding="4" cellspacing="0" border="0">
    <tr>
      <td rowspan="2" valign="middle" bgcolor="#1B9BA0" style="border:1px solid #e3e3e3;color:white;background:#1B9BA0;">
        <?= lang('payments_received') ?></td>
        <td bgcolor="#EEEEEE" style="border:1px solid #e3e3e3;"><?= lang('payment_date') ?></td>
        <td style="border:1px solid #e3e3e3;"><?= strftime(config_item('date_format'), strtotime($payments_info->payment_date)); ?></td>
    </tr>
    <tr>
      <td  bgcolor="#EEEEEE" style="border:1px solid #e3e3e3;"><?= lang('transaction_id') ?></td>
        <td style="border:1px solid #e3e3e3;"><?= $payments_info->trans_id ?></td>
    </tr>


    <tr>
      <td rowspan="4" valign="middle" bgcolor="#1B9BA0" style="border:1px solid #e3e3e3;color:white;background:#1B9BA0; text-align:center">
	  <?= lang('amount_received') ?><br>
        <?php
        $currency = $this->invoice_model->client_currency_sambol($invoice_info->client_id);
        ?>
        <span style="font-size:16pt;"><?= display_money($payments_info->amount, $currency->symbol); ?></span>      </td>
      <td bgcolor="#EEEEEE" style="border:1px solid #e3e3e3;"><?= lang('received_from') ?></td>
      <td style="border:1px solid #e3e3e3;"><strong>
        <?= ucfirst($client_info->name) ?>
      </strong></td>
    </tr>
    <?php
    $role = $this->session->userdata('user_type');
    if ($role == 1 && $payments_info->account_id != 0) {
        $account_info = $this->invoice_model->check_by(array('account_id' => $payments_info->account_id), 'tbl_accounts');
        if (!empty($account_info)) {
            ?>
            <tr>
              <td bgcolor="#EEEEEE" style="border:1px solid #e3e3e3;"><?= lang('received_account') ?></td>
                <td style="border:1px solid #e3e3e3;"><strong><?= $account_info->account_name ?></strong></td>
            </tr>
        <?php }
    } ?>
    <tr>
      <td bgcolor="#EEEEEE" style="border:1px solid #e3e3e3;"><?= lang('payment_mode') ?></td>
        <td style="border:1px solid #e3e3e3;"><?= $payment_methods->method_name ?></td>
    </tr>
    <tr>
      <td bgcolor="#EEEEEE" style="border:1px solid #e3e3e3;"><?= lang('notes') ?></td>
        <td style="border:1px solid #e3e3e3;"><?= $payments_info->notes ?></td>
    </tr>

</table>
<br/>

<h1 style="font-size:20px;"><?= lang('payment_for') ?></h1>

<table style="width:100%;margin-bottom:35px;table-layout:fixed;" cellpadding="4"
       cellspacing="0" border="0">
    <thead>
    <tr>
        <td bgcolor="#EEEEEE" style="border:1px solid #e3e3e3; text-align:center;">
            <?= lang('invoice_code') ?>
        </td>
        <td bgcolor="#EEEEEE" style="border:1px solid #e3e3e3; text-align:center;">
            <?= lang('invoice_date') ?>
        </td>
        <td bgcolor="#EEEEEE" style="border:1px solid #e3e3e3; text-align:center;">
            <?= lang('invoice_amount') ?>
        </td>
        <td bgcolor="#EEEEEE" style="border:1px solid #e3e3e3; text-align:center;">
            <?= lang('paid_amount') ?>
        </td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td style="border:1px solid #e3e3e3; text-align:center;"><?= $invoice_info->reference_no ?></td>
        <td style="border:1px solid #e3e3e3; text-align:center;">
            <?= strftime(config_item('date_format'), strtotime($invoice_info->date_saved)) ?>
        </td>
        <td  style="border:1px solid #e3e3e3; text-align:center;"><span>
                <?= display_money($this->invoice_model->get_invoice_cost($payments_info->invoices_id), $currency->symbol); ?>
                (- <?= lang('tax') ?>) </span>
        </td>
        <td style="border:1px solid #e3e3e3; text-align:center;">
            <span><?= display_money($payments_info->amount, $currency->symbol); ?></span>
        </td>
    </tr>
    </tbody>
</table>
