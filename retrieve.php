<?php
ob_start();
session_start();
require_once 'soapclient/SforceMetadataClient.php';
require_once 'conf.php';

$sessionId = $_SESSION['access_token']; 
$client = new SforceMetadataClient($sessionId, $client_id, 'https://ap1.salesforce.com/services/Soap/m/36.0/00D900000000Wj4', 'soapclient/sforce.360.metadata.wsdl');

if(isset($_GET['jid'])){
	$outputHeaders = array();
	$response = $client->checkRetrieveStatus($_GET['jid'], true, $outputHeaders);	
	if($response->status == 'Succeeded'){
		$filename = 'temp/down_'.time() .'.zip';
		file_put_contents($filename, $response->zipFile);
		print "<br>Got the zip";
		
		$zip = new ZipArchive;
		if ($zip->open($filename) === TRUE) {
			$path = 'temp/down_'.time();
			$zip->extractTo($path);
			$zip->close();
			echo 'unzip ok';
			header("Location: new.php?path=" . $path );
		} else {
			echo 'failed';
		}
	} else{		
		print_r($response);
		print "<br>Request is still processing. Pleae refresh this page again.";
	}
} else{
	
	$rr = new RetrieveRequest ();
	$rr->apiVersion = '36.0';
	$rr->singlePackage = '';

	$po = new Package();
	$po->version = '34.0';

	$ptm = new PackageTypeMembers ();
	$ptm->name = 'Profile';
	$ptm->members = array('*');

	$po->types = array($ptm);
	$rr->unpackaged = $po;

	$response = $client->retrieve($rr);

	//print_r($response);
	header("Location: retrieve.php?jid=" . $response->id );
}


?>