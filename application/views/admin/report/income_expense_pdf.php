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
<br/>

<h5><strong><?= lang('income_expense') ?></strong></h5>
<?php
$curency = $this->report_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
$mdate = date('Y-m-d');
//first day of month
$first_day_month = date('Y-m-01');
//first day of Weeks
$this_week_start = date('Y-m-d', strtotime('previous sunday'));
// 30 days before
$before_30_days = date('Y-m-d', strtotime('today - 30 days'));

$total_income = $this->db->select_sum('credit')->get('tbl_transactions')->row();
$total_expense = $this->db->select_sum('debit')->get('tbl_transactions')->row();

$income_this_month = $this->db->where(array('date >=' => $first_day_month, 'date <=' => $mdate))->select_sum('credit')->get('tbl_transactions')->row();
$income_this_week = $this->db->where(array('date >=' => $this_week_start, 'date <=' => $mdate))->select_sum('credit')->get('tbl_transactions')->row();
$income_this_30_days = $this->db->where(array('date >=' => $before_30_days, 'date <=' => $mdate))->select_sum('credit')->get('tbl_transactions')->row();

$expense_this_month = $this->db->where(array('date >=' => $first_day_month, 'date <=' => $mdate))->select_sum('debit')->get('tbl_transactions')->row();
$expense_this_week = $this->db->where(array('date >=' => $this_week_start, 'date <=' => $mdate))->select_sum('debit')->get('tbl_transactions')->row();
$expense_this_30_days = $this->db->where(array('date >=' => $before_30_days, 'date <=' => $mdate))->select_sum('debit')->get('tbl_transactions')->row();
?>
<hr>
<p><?= lang('total_income') ?>
    : <?= display_money($total_income->credit, $curency->symbol) ?></p>
<p><?= lang('total_expense') ?>
    : <?= display_money($total_expense->debit, $curency->symbol) ?></p>
<hr>
<p><strong><?= lang('Income') ?>
        - <?= lang('Expense') ?> </strong>: <?= display_money($total_income->credit - $total_expense->debit, $curency->symbol); ?>
</p>
<hr>
<p><?= lang('total_income_this_month') ?>
    : <?= display_money($income_this_month->credit, $curency->symbol) ?>
</p>
<p><?= lang('total_expense_this_month') ?>
    : <?= display_money($expense_this_month->debit, $curency->symbol) ?>
</p>
<p>
    <strong><?= lang('total') ?></strong>:
    <?= display_money($income_this_month->credit - $expense_this_month->debit, $curency->symbol) ?>
</p>
<hr>
<p><?= lang('total_income_this_week') ?>
    :<?= display_money($income_this_week->credit, $curency->symbol) ?></p>
<p><?= lang('total_expense_this_week') ?>
    : <?= display_money($expense_this_week->debit, $curency->symbol) ?></p>
<p>
    <strong><?= lang('total') ?></strong>: <?= display_money($income_this_week->credit - $expense_this_week->debit, $curency->symbol) ?>
</p>
<hr>
<p><?= lang('total_income_last_30') ?>
    : <?= display_money($income_this_30_days->credit, $curency->symbol) ?></p>
<p><?= lang('total_expense_last_30') ?>
    : <?= display_money($expense_this_30_days->debit, $curency->symbol) ?></p>
<p>
    <strong><?= lang('total') ?></strong>: <?= display_money($income_this_30_days->credit - $expense_this_30_days->debit, $curency->symbol) ?>
</p>

