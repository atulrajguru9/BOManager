<?php
ob_start();
session_start();
require_once 'SalesforceAPI.php';
require_once 'conf.php';

print_r( array($instance_url, $version, $client_id, $client_secret));
$salesforce = new SalesforceAPI($instance_url, $version, $client_id, $client_secret);

$salesforce->login($_POST['user'],  $_POST['pass'] , $_POST['token']);
$objects = $salesforce->getAllObjects();
/*$api_versions = $salesforce->getOrgLimits();
$limits = $salesforce->getOrgLimits();
$resource = $salesforce->getAvailableResources();


$date = new DateTime();

$good_metadata = $salesforce->getObjectMetadata('Account');
$good_metadata_all = $salesforce->getObjectMetadata('Account', true);
$good_metadata_since = $salesforce->getObjectMetadata('Account', true, $date);
$bad_metadata = $salesforce->getObjectMetadata('SomeOtherObject');

$create_account = $salesforce->create( 'Account', ['name' => 'New Account'] );
$update_project = $salesforce->update( 'Account', $create_account->id, ['name' => 'Changed'] );
$project = $salesforce->get( 'Account', $create_account->id );
$project_with_fields = $salesforce->get( 'Account', $create_account->id, ['Name', 'OwnerId'] );
$delete_project = $salesforce->delete( 'Account', $create_account->id );

$response = $salesforce->searchSOQL('SELECT name from Position__c',true);
*/
echo "<pre>----";

$_SESSION['access_token'] 	= $salesforce->getToken();
$_SESSION['instance_url'] 	= $instance_url;	

//print_r( $_SESSION);
//print_r( $salesforce);
header("Location: list.php");
