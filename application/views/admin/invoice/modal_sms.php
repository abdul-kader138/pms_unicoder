
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?= lang('new_sms') ?></h4>
      </div>
      <div class="modal-body">
      <?php
	  $client_info = $this->invoice_model->check_by(array('client_id' => $invoice_info->client_id), 'tbl_client');
	   if(!empty($client_info->mobile)){ ?>
       <form id="sendSMSMail" method="post">
       <div id="resultSend"></div>
                    <input type="hidden" name="type" value="invoice">
                    <input type="hidden" name="invoice_id" value="<?= $invoice_info->invoices_id ?>">
                    <input type="hidden" name="ref" value="<?= $invoice_info->reference_no ?>">
                    <input type="hidden" name="client_id" value="<?= $client_info->client_id ?>">
                    <input type="hidden" name="currency" value="<?= $invoice_info->currency; ?>">

                    <input type="hidden" name="amount"
                           value="<?= ($this->invoice_model->calculate_to('invoice_due', $invoice_info->invoices_id)) ?>">
       
        <div class="form-group clearfix">
                            <label for="field-1"
                                   class="col-sm-3 control-label"><?= lang('mobile') ?></label>
                            <div class="col-sm-8"><input type="text" name="mobile" class="form-control" readonly="readonly" disabled="disabled" value="<?=$client_info->mobile?>"></div>
                        </div>
                        <hr />
        <div class="form-group clearfix">
                            <label for="field-1"
                                   class="col-sm-3 control-label"><?= lang('message')?></label>
                            <div class="col-sm-8">
                            <?php if($this->input->get('type', TRUE) == "reminder"){ ?>
                                                        <textarea id="message" name="message" class="form-control"
                                                            rows="6"><?= lang('reminder_sms_template') ?></textarea>
                            <?php }else{ ?>
                            <textarea id="message" name="message" class="form-control"
                                                            rows="6"><?= lang('send_sms_template') ?></textarea>
                            <?php } ?>
                                                            </div>
                        </div>
                        <hr />
      <div class="form-group clearfix">
       			<button type="submit" name="sbtn" value="1" class="btn btn-primary"><?= lang('send')?></button>
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal"><?= lang('close')?></button>
      </div>
      </form>
      <?php }else{ ?>
      <div class="alert alert-warning"><?=lang('edit__user')?> <a href="<?= base_url() ?>admin/client/manage_client/<?= $invoice_info->client_id ?>"><?=lang('edit__user2')?></a></div>
      <?php } ?>
    </div>

  </div>
<script type="text/javascript">
   $(document).ready(function () {
		
		$(document).on('submit', "#sendSMSMail",function (ev) {
			ev.preventDefault();
			var dataForm = new FormData(this);
			$.ajax({
				type: "POST",
				url: "<?= base_url() ?>admin/sms/send_sms",
				data: dataForm,
				cache: false,
				processData: false,
				contentType: false,
				success: function(rep){
					if(rep){
						$('#resultSend').html('<div class="alert alert-success"><i class="fa fa-check"></i>'+rep+'</div>');
					}
				}
			});
		});

    });
</script>