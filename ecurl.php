<?php 
/*  
  $email = "ritesh.rana@c-metric.com";
                $admin_email = "moulik2007dec@gmail.com";
                $subject = 'cron';
                $comment = 'cron';
                    
                //send email
                mail($admin_email, "$subject", $comment, "From:" . $email);
			//	die;
*/
// Get cURL resource
$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'http://103.254.245.142:81/cmcrm/CRMCron/fetchEmails',
    CURLOPT_USERAGENT => 'Email Cron'
));
// Send the request & save response to $resp
$resp = curl_exec($curl);
// Close request to clear up some resources
curl_close($curl);


?>