<?php
ob_start();
set_time_limit (0);
session_start();

	// TODO Test.salesforce.com
	// use Server flow
	
	require_once ('Soap\soapclient\SforcePartnerClient.php');

	$mySforceConnection = new SforcePartnerClient();
	$wsdl = 'Soap\soapclient\partner.wsdl.xml';
	$mySoapClient = $mySforceConnection->createConnection($wsdl);	

	$mylogin = $mySforceConnection->login($_POST['user'], $_POST['pass'] . $_POST['token']);
	
	$_SESSION['metadataServerUrl'] = $mylogin->metadataServerUrl;
	$_SESSION['location'] = $mylogin->serverUrl;
	$_SESSION['sessionId'] = $mylogin->sessionId;	
	$_SESSION['wsdl'] = $wsdl;
	
	/*
	print("<pre>");
	print_r($mySforceConnection->describeGlobal());
	*/
	header("Location: list.php");
?>