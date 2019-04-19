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
                              <td align="left">
                                <img src="<?php echo base_url() . "/uploads/assets/front/images/logo.png" ?>" border="0" alt="" style="width: 150px;" />
                              </td>
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
                              <td align="center" >WEEKLY REPORT TO SOCIAL WORKER</td>
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
    
        <table width="100%" cellspacing="0" cellpadding="10" border="0" style="margin: 0;">
            <tbody>
                <tr>
                    <td>
                        
                    </td>
                </tr>
            </tbody>
        </table>
        
        <!-- this is for the young person name-->
         <table width="100%" cellspacing="0" cellpadding="7" border="1" style="margin-bottom: 20px;">
            <tbody>
                <tr>
                    <td width="20%">
                        CHILD'S NAME
                    </td>

                    <td width="40%" style="background-color: #4e6031; color:#fff;">
                      <b>
                        <?= !empty($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?>
                        <?= !empty($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?>
                      </b>
                    </td>
                    
                    <td width="20%">
                        D.O.B.
                    </td>
                    
                    <td width="20%" style="background-color: #4e6031; color:#fff;">
                      <b>
                            <?= !empty($YP_details[0]['date_of_birth']) ? configDateTime($YP_details[0]['date_of_birth']) : '' ?>
                      </b>
                    </td>
                </tr>
                <tr>
                    <td>
                        PERIOD OF REPORT
                    </td>
                    
                    <td colspan="3" style="background-color: #4e6031; color:#fff;">
                      <b>
                        <?= !empty($edit_data[0]['start_date']) ? configDateTime($edit_data[0]['start_date']) : '' ?> - 
                        <?= !empty($edit_data[0]['end_date']) ? configDateTime($edit_data[0]['end_date']) : '' ?>
                      </b>
                    </td>
                </tr>
            </tbody>
        </table>
    </header>