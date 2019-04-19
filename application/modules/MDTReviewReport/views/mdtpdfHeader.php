<header style="margin:500px;">
       
        <table width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #4e6031; margin: 0;">
            <tbody>
                <tr>
                    <td>
                        <table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto;">
                            <tbody>
                                <tr>
                                    <td style="">
                                        <table width="100%" cellpadding="0" border="0">
                                            <tbody >
                                                <tr>
                                                    <td align="left"><img src="<?php echo base_url() . "/uploads/assets/front/images/logo.png" ?>" border="0" alt="" style="width: 150px;" /></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>

                    <td>
                        <table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto; font-size: 16px; color: #FFF;" >
                            <tbody>
                                <tr>
                                    <td style="">
                                        <table width="100%" cellpadding="20" border="0">
                                            <tbody >
                                                <tr>
                                                    <td align="right" > MDT REVIEW REPORT</td>
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
        <table width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #ffffff; margin: 0;">
            <tbody>
                <tr>
                    <td>
                        <table width="100%" cellspacing="0" cellpadding="10" border="0" style="margin: 0 auto;">
                            <tbody>
                                <tr>
                                    <td style="">
                                        <table width="100%" cellpadding="0" border="0">
                                            <tbody>
                                                <tr>
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
        <!-- this is for the young person name-->
         <table width="100%" cellspacing="0" cellpadding="0" border="1" style=" margin: 0;">
            <tbody>
                <tr>
                    <td width="25%">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto;">
                            <tbody>
                                <tr>
                                    <td style="">
                                        <table width="100%" cellpadding="0" border="0">
                                            <tbody >
                                                <tr>
                                                    <td align="left" style="font-size: 16px;">&nbsp;&nbsp;Young Person:</td>
                                                    <td align="left" style="font-size: 16px;"> <?php echo isset($yp_details['yp_fname']) ? $yp_details['yp_fname'] : '' ?> <?php echo isset($yp_details['yp_lname']) ? $yp_details['yp_lname'] : '' ?></td>
                                                </tr>
                                                <tr>
                                                    <td align="left" style="font-size: 16px;">&nbsp;&nbsp;Social Worker Name:</td>
                                                    <td align="left" style="font-size: 16px;"> <?= !empty($edit_data[0]['social_worker']) ? $edit_data[0]['social_worker'] : $YP_details[0]['senior_social_worker_firstname'].' '. $YP_details[0]['senior_social_worker_surname'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td align="left" style="font-size: 16px;">&nbsp;&nbsp;Placing Authority:</td>
                                                    <td align="left" style="font-size: 16px;"><?= !empty($edit_data[0]['placing_authority']) ? $edit_data[0]['placing_authority'] : $YP_details[0]['authority'] ?> </td>
                                                </tr>
                                                 <tr>
                                                    <td align="left" style="font-size: 16px;">&nbsp;&nbsp;Case Manager:</td>
                                                    <td align="left" style="font-size: 16px;"><?= !empty($edit_data[0]['case_manager_name']) ? $edit_data[0]['case_manager_name'] : '' ?> </td>
                                                </tr>
                                                 <tr>
                                                    <td align="left" style="font-size: 16px;">&nbsp;&nbsp;For the period of:</td>
                                                    <td align="left" style="font-size: 16px;"> <?php echo configDateTime($edit_data[0]['report_start_date']); ?> - <?php echo configDateTime($edit_data[0]['report_end_date']); ?></td>
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
    </header>