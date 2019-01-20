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
    <div>
        <div style="width: 100%; background: #E3E3E3;padding: 1px 0px 1px 10px; color: black; vertical-align: middle; ">
            <p style="margin-left: 10px; font-size: 15px; font-weight: lighter;">
                <strong><?= lang('view_circular_details') ?></strong></p>
        </div>

        <table cellspacing="0" cellpadding="4" border="0" style="width: 100%; font-size: 13px;margin-top: 20px">
            <tr>
                <td width="30%" bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('job_title') ?> :</strong>
                </td>

                <td style="border:1px solid #333333;">&nbsp; <?php
                    echo $job_posted->job_title;
                    ?></td>
            </tr>
            <tr>
                <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('designation') ?> :</strong></td>

                <td style="border:1px solid #333333;">&nbsp; <?php
                    if (!empty($job_posted->designations_id)) {
                        $design_info = $this->db->where('designations_id', $job_posted->designations_id)->get('tbl_designations')->row();
                        $designation = $design_info->designations;
                    } else {
                        $designation = '-';
                    }
                    echo $designation;
                    ?></td>
            </tr>
            <tr>
                <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('experience') ?> :</strong>
                </td>

                <td style="border:1px solid #333333;">&nbsp; <?php
                    echo $job_posted->experience;
                    ?></td>
            </tr>
            <tr>
                <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('age') ?> :</strong>
                </td>

                <td style="border:1px solid #333333;">&nbsp; <?php
                    echo $job_posted->age;
                    ?></td>
            </tr>
            <tr>
                <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('salary_range') ?> :</strong>
                </td>

                <td style="border:1px solid #333333;">&nbsp; <?php
                    echo $job_posted->salary_range;
                    ?></td>
            </tr>
            <tr>
                <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('vacancy_no') ?> :</strong>
                </td>

                <td style="border:1px solid #333333;">&nbsp; <?php
                    echo $job_posted->vacancy_no;
                    ?></td>
            </tr>
            <tr>
                <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('posted_date') ?> :</strong></td>

                <td style="border:1px solid #333333;">&nbsp; <?php
                    echo strftime(config_item('date_format'), strtotime($job_posted->posted_date));
                    ?></td>
            </tr>
            <tr>
                <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= lang('last_date_to_apply') ?> :</strong>
                </td>

                <td style="border:1px solid #333333;">&nbsp; <?php
                    echo strftime(config_item('date_format'), strtotime($job_posted->last_date));
                    ?></td>
            </tr>
            <?php $show_custom_fields = custom_form_label(14, $job_posted->job_circular_id);

            if (!empty($show_custom_fields)) {
                foreach ($show_custom_fields as $c_label => $v_fields) {
                    if (!empty($v_fields)) {
                        ?>
                        <tr>
                            <td bgcolor="#dddddd" style="border:1px solid #333333;"><strong><?= $c_label ?> :</strong>
                            </td>

                            <td style="border:1px solid #333333;">&nbsp; <?= $v_fields ?></td>
                        </tr>
                    <?php }
                }
            }
            ?>
            <tr>
            <td colspan="2" style="border:1px solid #333333;text-align:center;">
                <span style="word-wrap: break-word;"><?php echo $job_posted->description; ?></span>
                </td>
            </tr>

        </table>

    </div>
</div><!-- ***************** Salary Details  Ends *********************-->
