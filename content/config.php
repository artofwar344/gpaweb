<?php
$phpcas_path = '.';

// Full Hostname of your CAS Server
$cas_host = 'login.sust.edu.cn';

// Context of the CAS Server
$cas_context = '/cas';

// Port of your CAS server. Normally for a https server it's 443
$cas_port = 80;

// Set the session-name to be unique to the current script so that the client script
// doesn't share its session with a proxied script.
// This is just useful when running the example code, but not normally.
session_name(
    'session_for:'
    . preg_replace('/[^a-z0-9-]/i', '_', basename($_SERVER['SCRIPT_NAME']))
);
// Set an UTF-8 encoding header for internation characters (User attributes)
header('Content-Type: text/html; charset=utf-8');
?>
