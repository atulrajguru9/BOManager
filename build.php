<?php
ob_start();
session_start();
require_once 'soapclient/SforceMetadataClient.php';
require_once 'conf.php';
require_once 'utils.php';

$sessionId = $_SESSION['access_token']; 
$client = new SforceMetadataClient($sessionId, $client_id, 'https://ap1.salesforce.com/services/Soap/m/36.0/00D900000000Wj4', 'soapclient/sforce.360.metadata.wsdl');


echo "<pre>";
//print_r($_POST);
require_once 'zip.php';


$sessionId = $_SESSION['access_token']; 
$client = new SforceMetadataClient($sessionId, $client_id, 'https://ap1.salesforce.com/services/Soap/m/36.0/00D900000000Wj4', 'soapclient/sforce.360.metadata.wsdl');

$date = new DateTime();
//echo $date->getTimestamp();

// Desired folder structure
$path = './temp/pack_'. $date->getTimestamp() ;

// To create the nested structure, the $recursive parameter 
// to mkdir() must be specified.

if (!mkdir($path, 0777, true)) {
    die('Failed to create PKG folders...');
}
if (!mkdir($path. '/objects', 0777, true)) {
    die('Failed to create objects folders...');
}
$str_obj_def = $_POST['XML_data']; 
$fp = fopen($path . '/objects/'.$_POST['obj_name'] .'.object' , 'w');
fwrite($fp, $str_obj_def);
fclose($fp);

if (!mkdir($path. '/profiles', 0777, true)) {
    die('Failed to create profiles folders...');
}

$admin_file = $path . '/profiles/Admin.profile';
build_profile_xml( $admin_file , $client);

$str_pkg = '<?xml version="1.0" encoding="UTF-8"?>
<Package xmlns="http://soap.sforce.com/2006/04/metadata">
	<types>
		<members>*</members>
		<name>CustomObject</name>
	</types>
	<types>
		<members>*</members>
		<name>Profile</name>
	</types>
	<version>34.0</version>
</Package>';

$fp = fopen($path . '/package.xml', 'w');
fwrite($fp, $str_pkg);
fclose($fp);


$files_to_zip = array(
	$path.'/objects/My_Obj1__b.object',
	$path.'/profiles/Admin.profile',
	$path.'/package.xml',
);
print_r($files_to_zip );
//if true, good; if false, zip creation failed
$result = create_zip($files_to_zip,$path.'.zip');
echo "<br> Zip >>> ". $result;
header("Location: deploy.php?pack=" . $path.'.zip' );
?>