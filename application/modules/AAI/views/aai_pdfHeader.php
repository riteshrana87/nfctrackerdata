<header style="margin:500px;">
       
        <table width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #4e6031; margin: 0;">
            <tbody>
                <tr>
                    <td width="20%">
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

                    <td width="60%">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto; font-size: 16px; color: #FFF;" >
                            <tbody>
                                <tr>
                                    <td style="">
                                        <table width="100%" cellpadding="20" border="0">
                                            <tbody >
                                                <tr>
                                                    <td align="center" >ACCIDENT AND INCIDENT RECORD</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td width="20%">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto;">
                          <tbody>
                            <tr>
                              <td style="">
                                <table width="100%" cellpadding="0" border="0">
                                  <tbody >
                                    <tr>
                                      <td align="right">
                                        <img src="<?php echo base_url() . "/uploads/assets/front/images/NFC LOGO.jpg" ?>" border="0" alt="" style="width: 50px;" />
                                      </td>
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
                                                    <td align="left" style="font-size: 16px;">&nbsp;&nbsp;YOUNG PERSON:</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>

                    <td width="50%">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto; font-size: 16px; color: #FFF;background-color: #4e6031;" >
                            <tbody>
                                <tr>
                                    <td width="50%">
                                        <table width="100%" cellpadding="5" border="0">
                                            <tbody >
                                                <tr>
                                                    <td align="left" style="text-transform: uppercase;"><b>
                                                        <?php echo isset($yp_details['yp_fname']) ? $yp_details['yp_fname'] : '' ?> <?php echo isset($yp_details['yp_lname']) ? $yp_details['yp_lname'] : '' ?>
                                                    </b></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
					
					<td align="left" width="25%" >
                                &nbsp;&nbsp;DOB:
								<?= (!empty($yp_details['date_of_birth']) && $yp_details['date_of_birth'] != '0000-00-00') ? configDateTime($yp_details['date_of_birth']) : '' ?>
                                                  
					</td>
					
                </tr>
            </tbody>
        </table>
    </header>