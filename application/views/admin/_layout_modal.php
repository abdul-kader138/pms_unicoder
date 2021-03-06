<!-- Modal -->
<style type="text/css">
    .datepicker {
        z-index: 1050 !important;
    }

    .bootstrap-timepicker-widget.dropdown-menu.open {
        display: inline-block;
        z-index: 99999 !important;
    }
</style>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

        </div>
    </div>
</div>
<!-- SELECT2-->
<?php $direction = $this->session->userdata('direction');
if (!empty($direction) && $direction == 'rtl') {
    $RTL = 'on';
} else {
    $RTL = config_item('RTL');
}
?>
<script type="text/javascript">
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();

        $('.select_box').select2({
            theme: 'bootstrap',
            <?php
            if (!empty($RTL)) {?>
            dir: "rtl",
            <?php }
            ?>
        });
        $('.select_multi').select2({
            theme: 'bootstrap',
            <?php
            if (!empty($RTL)) {?>
            dir: "rtl",
            <?php }
            ?>
        });
        $('.datepicker').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
            todayBtn: "linked",
        });
        $('.monthyear').datepicker({
            autoclose: true,
            startView: 1,
            format: 'yyyy-mm',
            minViewMode: 1,
        });
        $('.timepicker').timepicker();

        $('.timepicker2').timepicker({
            minuteStep: 1,
            showSeconds: false,
            showMeridian: false,
            defaultTime: false
        });
        $('.textarea').summernote({
            codemirror: {// codemirror options
                theme: 'monokai'
            }
        });
        $('.note-toolbar .note-fontsize,.note-toolbar .note-help,.note-toolbar .note-fontname,.note-toolbar .note-height,.note-toolbar .note-table').remove();

        $('input.select_one').on('change', function () {
            $('input.select_one').not(this).prop('checked', false);
        });
    });

    $(document).on('hide.bs.modal', '#myModal', function () {
        $('#myModal').removeData('bs.modal');
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {

        $('#permission_user').hide();
        $("div.action").hide();
        $("input[name$='permission']").click(function () {
            $("#permission_user").removeClass('show');
            if ($(this).attr("value") == "custom_permission") {
                $("#permission_user").show();
            } else {
                $("#permission_user").hide();
            }
        });

        $("input[name$='assigned_to[]']").click(function () {
            var user_id = $(this).val();
            $("#action_" + user_id).removeClass('show');
            if (this.checked) {
                $("#action_" + user_id).show();
            } else {
                $("#action_" + user_id).hide();
            }

        });
    });
</script>
