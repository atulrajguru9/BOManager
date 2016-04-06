<?php
ob_start();
session_start();
require_once 'soapclient/SforceMetadataClient.php';
require_once 'conf.php';

$sessionId = $_SESSION['access_token']; 
$client = new SforceMetadataClient($sessionId, $client_id, 'https://ap1.salesforce.com/services/Soap/m/36.0/00D900000000Wj4', 'soapclient/sforce.360.metadata.wsdl');
echo "<pre>";
if(isset($_GET['pack'])){
	$deployOptions = new DeployOptions();

    $deployOptions->allowMissingFiles = false;
    $deployOptions->autoUpdatePackage = false;
    $deployOptions->checkOnly = false;
    $deployOptions->ignoreWarnings = false;
    $deployOptions->performRetrieve = false;
    $deployOptions->purgeOnDelete = false; 
    $deployOptions->rollbackOnError = false;
    $deployOptions->singlePackage = false;
    $deployOptions->testLevel = "NoTestRun";    
    $deployOptions->runTests = array();

	
	$response = $client->deploy($_GET['pack'], $deployOptions);	
	print_r ($response);	
	echo '<a href="deploy.php?jid='. $response->id . '"> Job is queued. Click here to check status</a>';
} else if(isset($_GET['jid'])){
	$response = $client->checkDeployStatus($_GET['jid'], true, $debugInfo);
	print_r ($response);	
	if($response->success == 1){
		echo '<a href="list.php"> Deployment Succeeded. Click here to view details</a>';
	}else{
		echo '>>>>>>>>' . $response->success;
	}
} else{		
	print_r($response);
	echo '<a href="deploy.php?jid='. $response->id . '"> Click here to Recheck status</a>';
}


?>