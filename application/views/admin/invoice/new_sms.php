<style>
.select2-dropdown{
	z-index:9999999 !important;
}
</style>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?= lang('new_sms') ?></h4>
      </div>
      <div class="modal-body">
       <form id="SendBulkSMS" method="post">
       <input type="hidden" name="type" value="bulksms" />
       <div id="resultSend"></div>
        <div class="form-group clearfix">
			<label for="field-1" class="col-sm-3 control-label"><?= lang('send_sms_to') ?></label>
			<div class="col-sm-9">
              <select name="sms_to" id="sms_to" class="form-control" style="padding-top:0px; padding-bottom:0px;">
                <option value="all_clients" selected="selected"><?= lang('send_sms_to1') ?></option>
                <option value="select_c"><?= lang('send_sms_to6') ?></option>
                <option value="all_users"><?= lang('send_sms_to2') ?></option>
                <option value="select_u"><?= lang('send_sms_to5') ?></option>
                <option value="mobile"><?= lang('send_sms_to4') ?></option>
              </select>
            </div>
		</div>
		<hr />
        <div class="form-group clearfix" id="toclient" style="display:none;">
			<label class="col-sm-3 control-label"><?= lang('send_sms_to') ?></label>
			<div class="col-sm-9 clearfix">
              <?php  $ClientsSms = $this->db->where('mobile !=', '')->get("tbl_client")->result(); ?>
              <select name="to_clients" id="to_clients" class="form-control select_multi" multiple="multiple" style=" width:100%;padding-top:0px; padding-bottom:0px;">
              <?php if(count($ClientsSms) > 0){
				  		foreach($ClientsSms as $usr){ ?>
                <option value="<?= $usr->client_id ?>"><?= $usr->name ?></option>
              <?php } } ?>
              </select>
            </div>
            <div class="clearfix"></div>
            <hr />
		</div>
        <div class="form-group clearfix" id="tousers" style="display:none;">
			<label class="col-sm-3 control-label"><?= lang('select_tosms') ?></label>
			<div class="col-sm-9">
              <?php  $usersSms = $this->db->select('tbl_users.*, tbl_account_details.mobile')->from("tbl_users")->join('tbl_account_details', 'tbl_account_details.user_id = tbl_users.user_id')->where('tbl_account_details.mobile !=', '')->get()->result(); ?>
              <select name="to_users" id="to_users" class="form-control select_multi" multiple="multiple" style=" width:100%;padding-top:0px; padding-bottom:0px;">
              <?php if(count($usersSms) > 0){
				  		foreach($usersSms as $usr){
			 ?>
                <option value="<?= $usr->user_id ?>"><?= $usr->username ?></option>
              <?php } } ?>
              </select>
            </div>
            <div class="clearfix"></div>
            <hr />
		</div>
        <div class="form-group clearfix" id="tomobile" style="display:none;">
			<label class="col-sm-3 control-label"><?= lang('mobile') ?></label>
			<div class="col-sm-9"><input type="text" placeholder="05xxxxxxxx" name="mobile" class="form-control"></div>
            <div class="clearfix"></div>
            <hr />
		</div>
        <div class="form-group clearfix">
                            <label for="field-1"
                                   class="col-sm-3 control-label"><?= lang('message')?></label>
                            <div class="col-sm-9">
                            <textarea id="message" required="required" name="message" class="form-control"
                                                            rows="6"></textarea>
                                                            </div>
                        </div>
                        <hr />
      <div class="form-group clearfix">
       			<button type="submit" name="sbtn" value="1" class="btn btn-primary"><?= lang('send')?></button>
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal"><?= lang('close')?></button>
      </div>
      </form>
    </div>

  </div>
<script>
$(document).ready(function () {
        $('.select_multi').select2({
			placeholder: '<?= lang('send_sms_to') ?>'
            <?php
            if (!empty($RTL)) {?>
            dir: "rtl",
            <?php }
          ?>
    });
	$(document).on('submit', "#SendBulkSMS",function (ev) {
		ev.preventDefault();
		var dataForm = new FormData(this);
		dataForm.append('clients', $('#to_clients').val());
		dataForm.append('users', $('#to_users').val());
		$.ajax({
			type: "POST",
			url: "<?= base_url() ?>admin/sms/send_sms",
			data: dataForm,
			cache: false,
			processData: false,
			contentType: false,
			success: function(rep){
				if(rep){
					$('#resultSend').html(rep);
				}
			}
		});
	});
	$(document).on('change','#sms_to',function(e){
		var smstoo = $(this).val();
		if(smstoo == "mobile"){
			$('#toclient').hide('fast');
			$('#tousers').hide('fast');
			$('#tomobile').show('slow');
		}else if(smstoo == "select_c"){
			$('#tomobile').hide('fast');
			$('#tousers').hide('fast');
			$('#toclient').show('slow');
		}else if(smstoo == "select_u"){
			$('#tomobile').hide('fast');
			$('#toclient').hide('fast');
			$('#tousers').show('slow');
		}else{
			$('#tomobile').hide('fast');
			$('#tousers').hide('fast');
			$('#toclient').hide('fast');
		}
	});
});
</script>