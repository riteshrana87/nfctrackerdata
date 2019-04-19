 
<table width="100%" style="padding-left: 15px; padding-right: 15px"  cellpadding="0" border="0" >
    <tbody>
        <tr style="margin-top: 10px">
            <td style="color: #777; font-size: 17px; width: 10%">
                Name :
            </td>
            <td  style="color: #4e6031 ; font-size: 17px;width: 20%">
                <?php echo isset($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?> <?php echo isset($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?> 
            </td>
            <td style="color: #777; font-size: 17px; width: 10%">
                Gender :
            </td>
            <td  style="color: #4e6031 ; font-size: 17px;width: 15%">
                <?php if (!empty($YP_details[0]['gender'])) {
                    if ($YP_details[0]['gender'] == 'M') { ?> Male<?php } else { ?>Female <?php }
                } else { ?> N/A <?php } ?> 
            </td>
            <td style="color: #777; font-size: 17px; width: 10%">
                year :
            </td>
            <td  style="color: #4e6031 ; font-size: 17px;width: 20%" colspan="2">
<?php echo $year; ?>
            </td>
        </tr>
    </tbody>
</table>
<table  width="100%" cellspacing="0" cellpadding="0"  border="0" style="border-spacing:0; padding-left: 15px; padding-right: 15px; padding-top: 100px">
    <tbody>
        <tr>
            <td>
                <table width="100%" cellspacing="0" cellpadding="0"  border="0" style="padding: 0; margin: 0;border-spacing:0;">
                    <tbody>
                        <tr>
                            <td>
                                <table width="100%" align="center" cellspacing="0" cellpadding="0" style="border-bottom:0px solid ; margin-top: 20px;" >
                                    <tbody>
                                        <tr>
                                            <td  style="padding: 2px;margin:0;text-align:left;height: auto; padding-bottom: 100px;"> <img src='<?php echo base_url() . "/uploads/pdf_export/inrep_$chartName.png" ?>'></td>
                                            <td  style="padding: 2px;margin:0;text-align:right;height: auto; padding-bottom: 100px;"><img src='<?php echo base_url() . "/uploads/pdf_export/inrep_$emo_chartName.png" ?>'></td>
                                        </tr>
                                        <tr>
                                            <td  style="padding: 2px;margin:0;text-align:left;height: auto; padding-bottom: 100px;"> <img src='<?php echo base_url() . "/uploads/pdf_export/inrep_$con_chartName.png" ?>'></td>
                                            <td  style="padding: 2px;margin:0;text-align:right;height: auto; padding-bottom: 100px;"><img src='<?php echo base_url() . "/uploads/pdf_export/inrep_$hyp_chartName.png" ?>'></td>
                                        </tr>
                                        <tr>
                                            <td  style="padding: 2px;margin:0;text-align:left;height: auto;"> <img src='<?php echo base_url() . "/uploads/pdf_export/inrep_$peer_chartName.png" ?>'></td>
                                            <td  style="padding: 2px;margin:0;text-align:right;height: auto;"><img src='<?php echo base_url() . "/uploads/pdf_export/inrep_$pro_chartName.png" ?>'></td>
                                        </tr>
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
<table  width="100%" cellspacing="0" cellpadding="0"  border="0" style="border-spacing:0; padding-left: 15px; padding-right: 15px; margin-top: 40px; padding-bottom:20px;">
    <tbody>
        <tr>
            <td>
                <table width="100%" cellspacing="0" cellpadding="0"  border="0" style="padding: 0; margin: 0;border-spacing:0;">
                    <tbody>
                        <tr>
                            <td>
                                <table width="100%" align="center" cellspacing="0" cellpadding="0" style="border-bottom:0px solid ; margin-top: 20px;" >
                                    <tbody>
                                        <tr>
                                            <td  style="padding: 2px;margin:0;text-align:left;height: auto; padding-bottom: 100px;"> <img src='<?php echo base_url() . "/uploads/pdf_export/inrep_$pie_tot_chartName.png" ?>'></td>
                                            <td  style="padding: 2px;margin:0;text-align:right;height: auto; padding-bottom: 100px;"><img src='<?php echo base_url() . "/uploads/pdf_export/inrep_$pie_emo_chartName.png" ?>'></td>
                                        </tr>
                                        <tr>
                                            <td  style="padding: 2px;margin:0;text-align:left;height: auto; padding-bottom: 100px;"> <img src='<?php echo base_url() . "/uploads/pdf_export/inrep_$pie_con_chartName.png" ?>'></td>
                                            <td  style="padding: 2px;margin:0;text-align:right;height: auto; padding-bottom: 100px;"><img src='<?php echo base_url() . "/uploads/pdf_export/inrep_$pie_hyp_chartName.png" ?>'></td>
                                        </tr>
                                        <tr>
                                            <td  style="padding: 2px;margin:0;text-align:left;height: auto;"> <img src='<?php echo base_url() . "/uploads/pdf_export/inrep_$pie_peer_chartName.png" ?>'></td>
                                            <td  style="padding: 2px;margin:0;text-align:right;height: auto;"><img src='<?php echo base_url() . "/uploads/pdf_export/inrep_$pie_pro_chartName.png" ?>'></td>
                                        </tr>
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
<table  width="100%" cellspacing="0" cellpadding="0"  border="0" style="border-spacing:0; padding-left: 15px; padding-right: 15px; margin-top: 40px; padding-bottom:20px;">
    <tbody>
        <tr>
            <td>
                <table width="100%" cellspacing="0" cellpadding="0"  border="0" style="padding: 0; margin: 0;border-spacing:0;">
                    <tbody>
                        <tr>
                            <td>
                                <table width="100%" align="center" cellspacing="0" cellpadding="0" style="border-bottom:0px solid ; margin-top: 20px;" >
                                    <tbody>
                                        <tr>
                                            <td  style="padding: 2px;margin:0;text-align:left;height: auto; padding-bottom: 100px;"> <img src='<?php echo base_url() . "/uploads/pdf_export/inrep_$line_chartName.png" ?>'></td>
                                        </tr>
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