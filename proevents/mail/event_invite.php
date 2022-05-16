<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));

$submittedData = t(
    "
Event Name: $eName

Event Description: $eDescription

Event Link: $eLink
"
);


$body = t(
    "
$uName has invited you to an event from " . substr(BASE_URL, 7) . ".

===================================
%s
===================================

See you There!
" . substr(BASE_URL, 7) . "

",
    $submittedData
);