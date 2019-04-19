<table width="100%" style="margin-bottom: 20px; padding-left: 15px; padding-right: 15px"  cellpadding="0" border="0" >
    <tbody>
        <tr style="margin-top: 10px">
            <td style="color: #777; font-size: 17px; width: 10%">
                Name :
            </td>
            <td  style="color: #4e6031; font-size: 17px;width: 20%">
                <?php echo isset($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?>
                <?php echo isset($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?>
            </td>
            <td style="color: #777; font-size: 17px; width: 10%">
                Gender :
            </td>
            <td  style="color: #4e6031; font-size: 17px;width: 15%">
                <?php
                if (!empty($YP_details[0]['gender'])) {
                    if ($YP_details[0]['gender'] == 'M') {
                        ?> Male<?php } else { ?>Female <?php
                    }
                } else {
                    ?> N/A <?php } ?>
            </td>
            <td style="color: #777; font-size: 17px; width: 10%">
                Year :
            </td>
            <td  style="color: #4e6031; font-size: 17px;width: 20%" colspan="2">
                <?php echo isset($year_pdf) ? $year_pdf : '' ?>
            </td>
            <td style="color: #777; font-size: 17px; width: 10%">
                Month :
            </td>
            <td  style="color: #4e6031; font-size: 17px;width: 20%" colspan="2">
                <?php echo isset($month) ? $month : '' ?>
            </td>
        </tr>
    </tbody>
</table>
<table width="100%" cellspacing="0" cellpadding="0" border="0" autosize="1">
    <tbody>
        <tr>
            <td>
                <table width="80%" align="center" cellspacing="0" cellpadding="0" autosize="1">
                    <tbody>
                        <tr>
                            <td style="padding: 2px;margin:0;text-align:left;height: auto; padding-bottom: 60px;">
                                <img src='<?php echo base_url() . "/uploads/cse_pdf_export/inrep_$line_cse_chartName.png" ?>'>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
<table width="100%" cellspacing="0" cellpadding="0" border="0" autosize="1">
    <tbody>
        <tr>
            <td>
                <table width="80%" align="center" cellspacing="0" cellpadding="0" style="" autosize="1">
                    <tbody>
                        <tr>
                            <td>
                                <table width="100%" align="center">
                                    <tr>
                                        <td align="left"  style="padding: 2px;margin:0;text-align:left;height: auto;"> <img src='<?php echo base_url() . "/uploads/cse_pdf_export/inrep_$pie_total_chartName.png" ?>'></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
<table width="100%" cellspacing="0" cellpadding="0" border="0" autosize="1">
    <tbody>
        <tr>
            <td>
                <table width="100%" align="center" cellspacing="0" cellpadding="0" style="" autosize="1">
                    <tbody>
                        <tr>
                            <td>
                                <table width="100%" align="center">
                                    <tr>
                                        <td width="50%" align="left"  style="padding: 2px;margin:0;text-align:left;height: auto; padding-bottom: 100px;"> <img src='<?php echo base_url() . "/uploads/cse_pdf_export/inrep_$pie_health_chartName.png" ?>'></td>
                                        <td width="50%" align="right" style="padding: 2px;margin:0;text-align:right;height: auto; padding-bottom: 100px;">
                                            <img src='<?php echo base_url() . "/uploads/cse_pdf_export/inrep_$pie_behav_chartName.png" ?>'></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
<table width="100%" cellspacing="0" cellpadding="0" border="0" autosize="1">
    <tbody>
        <tr>
            <td>
                <table width="100%" align="center" cellspacing="0" cellpadding="0" style="" autosize="1">
                    <tbody>
                        <tr>
                            <td>
                                <table width="100%" align="center">
                                    <tr>
                                        <td width="50%" align="left"  style="padding: 2px;margin:0;text-align:left;height: auto; padding-bottom: 100px;">
                                            <img src='<?php echo base_url() . "/uploads/cse_pdf_export/inrep_$pie_groom_chartName.png" ?>'>
                                        </td>
                                        <td width="50%" align="right" style="padding: 2px;margin:0;text-align:right;height: auto; padding-bottom:100px;">
                                            <img src='<?php echo base_url() . "/uploads/cse_pdf_export/inrep_$pie_child_chartName.png" ?>'>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
<table width="100%" cellspacing="0" cellpadding="0" border="0" autosize="1">
    <tbody>
        <tr>
            <td>
                <table width="100%" align="center" cellspacing="0" cellpadding="0" style="" autosize="1">
                    <tbody>
                        <tr>
                            <td>
                                <table width="100%" align="center">
                                    <tr>
                                        <td width="50%" align="left"  style="padding: 2px;margin:0;text-align:left;height: auto; padding-bottom: 80px;">
                                            <img src='<?php echo base_url() . "/uploads/cse_pdf_export/inrep_$pie_soc_chartName.png" ?>'>
                                        </td>
                                        <td width="50%" align="right" style="padding: 2px;margin:0;text-align:right;height: auto; padding-bottom: 80px;">
                                            <img src='<?php echo base_url() . "/uploads/cse_pdf_export/inrep_$pie_safe_chartName.png" ?>'>\
                                            
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
<table width="100%" cellspacing="0" cellpadding="0" border="0" autosize="1">
    <tbody>
        <tr>
            <td>
                <table width="100%" align="center" cellspacing="0" cellpadding="0" autosize="1">
                    <tbody>
                        <tr>
                            <td>
                                <table width="100%" align="center">
                                    <tbody>
                                        <?php
                                        if (!empty($comment_details)) {
                                            foreach ($comment_details as $result) {
                                                if (!empty($result['comment'])) {
                                                    ?>
                                                    <tr>
                                                        <td width="100%" align="left"  style="padding: 0 15px;margin:0;text-align:left;height: auto; padding-bottom: 5px;padding-top: 20px;">
                                                            <h1 style="color: #4e6031;padding: 15px;font-size: 1.2em; text-transform: uppercase;"><?= !empty($result['user_type']) ? $result['user_type'] : lang('NA'); ?>  <?= (!empty($result['created_date']) && $result['created_date'] != '0000-00-00') ? configDateTime($result['created_date']) : ''; ?>
                                                            </h1>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="100%" align="left" style="padding: 0 15px;margin:0;text-align:left;height: auto; padding-bottom: 100px;">
                                                            <h1 style="color: #777;padding: 15px;font-size: 0.8em; padding-bottom: auto;">
                                                                <?= !empty($result['comment']) ? $result['comment'] : lang('NA'); ?>
                                                            </h1>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
<table width="100%" cellspacing="0" cellpadding="0" border="0" autosize="1" style="margin-top:40px; margin-left: 15px;margin-right:15px;" >
     <tbody>
        <tr>
            <td>
                <table width="100%" cellspacing="0" cellpadding="0" border="0" autosize="1" style="margin-top:40px;border:1px solid #000;" >
    <tbody>
        <tr style=" background:#4f9b4f;padding: 20px;color: #000;border: 1px solid #000;">
            <td style="background:#4f9b4f;padding: 20px;color: #000;border-left: 1px solid #000; width: 50%;">
                0. No Known risk
            </td>
            <td style="background:#4f9b4f;padding: 20px;color: #000;border: 1px solid #000;width: 50%;">
                No history or evidence at present to indicate likelihood of risk from behaviour.
                No current indication of risk.
            </td>
        </tr>
        <tr style=" background:#5cb85c;padding: 20px;color: #000;border: 1px solid #000;">
            <td style="background:#5cb85c;padding: 20px;color: #000;border: 1px solid #000; width: 50%;">
                1. Low apparent risk
            </td>
            <td style="background:#5cb85c;padding: 20px;color: #000;border: 1px solid #000;width: 50%;">
                No current indication of risk but young person’s history
                indicates possible risk from identified behaviour.
            </td>
        </tr>
        <tr style=" background:#fa8128;padding: 20px;color: #000;border: 1px solid #000;">
            <td style="background:#fa8128;padding: 20px;color: #000;border: 1px solid #000; width: 50%;">
                2. Medium apparent risk
            </td>
            <td style="background:#fa8128;padding: 20px;color: #000;border: 1px solid #000;width: 50%;">
                Young person’s history and current behaviour indicates
                the presence of risk but action has already been identified to moderate risk.
            </td>
        </tr>
        <tr style=" background:#d9534f;padding: 20px;color: #000;border: 1px solid #000;">
            <td style="background:#d9534f;padding: 20px;color: #000;border: 1px solid #000; width: 50%;">
                3. High apparent risk
            </td>
            <td style="background:#d9534f;padding: 20px;color: #000;border: 1px solid #000;width: 50%;">
                The young person’s circumstances indicate that the behaviour may result in a risk of serious harm without
                intervention from one or more agency.
                The young person will commit the behaviour as soon as they are able and the risk of significant harm is considered imminent.
            </td>
        </tr>
        <tr>
        <td style="padding: 5px;color: #000; padding-top: 20px;">No known risk = 0</td>
        <td style="padding: 5px;color: #000; padding-top: 20px;">0-85 = Low</td>
        </tr>
         <tr>
             <td style="padding: 5px;color: #000;">Low risk = 1</td>
             <td style="padding: 5px;color: #000;">86-170 = Medium</td>
         </tr>
          <tr>
              <td style="padding: 5px;color: #000;">Med Risk = 2</td>
              <td style="padding: 5px;color: #000;">171-255 = High</td>
          </tr>
           <tr>
               <td style="padding: 5px;color: #000;">High Risk = 3</td>
               <td style="padding: 5px">&nbsp;</td>
           </tr>
            
         
        
    </tbody>   

</table>
</tr>
    </tbody>   

</table>



</body>
