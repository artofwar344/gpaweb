<?php

//
// phpCAS simple client
//

// import phpCAS lib
include_once('CAS.php');
$cas = new phpCAS();
$cas->setDebug();

// initialize phpCAS
$cas->client(CAS_VERSION_2_0,'cas.gtcfla.edu.cn',443,'lyuapServer');

// no SSL validation for the CAS server
$cas->setNoCasServerValidation();

// force CAS authentication
$cas->forceAuthentication();

// at this step, the user has been authenticated by the CAS server
// and the user's login name can be read with phpCAS::getUser().

// logout if desired
if (isset($_REQUEST['logout'])) 
{
	$cas->logout();
}

// for this test, simply print that the authentication was successfull
?>
<html>
  <head>
    <title>phpCAS simple client</title>
  </head>
  <body>
    <h1>Successfull Authentication!</h1>
    <p>the user's login is <b><?php echo $cas->getUser(); ?></b>.</p>
    <p>phpCAS version is <b><?php echo $cas->getVersion(); ?></b>.</p>
    <p><a href="?logout=">Logout</a></p>
  </body>
</html>
