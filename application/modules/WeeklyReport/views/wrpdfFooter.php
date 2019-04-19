<footer style="border-top: 1px solid #000; background-color: #ffffff; margin-top: 15px;">
        <table width="100%" cellspacing="10" cellpadding="0" border="0">
            <tbody>
                <tr>
                    <td>
                        <table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto;">
                            <tbody>
                                <tr>
                                    <td style="">
                                        <table width="100%" cellpadding="0" border="0">
                                            <tbody>
                                                <tr>
                                                    <td align="left" width="33.33%" style="text-transform: uppercase; font-size: 13px; color: #1766a6;">Reported By : <b><?= !empty($edit_data) ? configDateTime($edit_data) : '' ?></b></td>
                                                    <td align="center" width="33.33%" style="text-transform: uppercase; font-size: 13px; color: #1766a6;"></td>
                                                    <td align="right" width="33.33%" style="text-transform: uppercase; font-size: 13px; color: #1766a6;">Date <b><?php echo date("d/m/Y"); ?></b></td>
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
    </footer>