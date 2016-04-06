<?php
// SOAP_CLIENT_BASEDIR - folder that contains the PHP Toolkit and your WSDL
// $USERNAME - variable that contains your Salesforce.com username (must be in the form of an email)
// $PASSWORD - variable that contains your Salesforce.com password

define("SOAP_CLIENT_BASEDIR", "soap/soapclient");
require_once (SOAP_CLIENT_BASEDIR.'/SforcePartnerClient.php');
require_once (SOAP_CLIENT_BASEDIR.'/SforceHeaderOptions.php');

try {
	$mySforceConnection = new SforcePartnerClient();
	$mySoapClient = $mySforceConnection->createConnection(SOAP_CLIENT_BASEDIR.'/partner.wsdl.xml');
	$mylogin = $mySforceConnection->login('atul.rajguru@sfdc.com', 'Sales@12355');

	echo "***** Get User Info*****\n";
	$response = $mySforceConnection->getUserInfo();

	print("<pre>");
	print_r($mySforceConnection->describeGlobal());

	print_r($response);
} catch (Exception $e) {
	print_r($e);
}
?>