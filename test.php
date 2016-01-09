<?php

echo 'welcome <br/>';

//$from = "noreply@unileversolutions.com";
$to = 'rahul@unicodesystems.in';
//$to = 'emailtestingdev@gmail.com';
$subject = 'At ' . date('H:i:s',time());
$message = "<p>from quoteslash. at " .date('H:i:s',time()) . " this is testing</p>";

// Always set content-type when sending HTML email
$headers = "Bcc: mamta@unicodesystems.in\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
//$from_address = 'noreply@unileversolutions.com';
//$from_address = 'pitch.nyc@unilever.com';
$from_address = 'quoteslash@insuranceexpress.com';
//$from_address = 'noreply@quoteslash.com';
$from = !empty($from_address) ? $from_address : '';
$headers .= "From: " . $from;

echo mail($to, 'working with BCC again', $message, $headers, "-f" . $from);
