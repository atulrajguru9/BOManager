<?php
session_start();
require_once 'SalesforceAPI.php';
$instance_url = 'https://ap1.salesforce.com';
$salesforce = new SalesforceAPI($instance_url ,'35.0','3MVG9Y6d_Btp4xp6UyQN5Bmw5sKbzoA1Y_7M1UvdjZTot0QfJDRuINsjlntbkyHvi1JNGtABFsqa9ho7n26vL','7314617477980175651');

$salesforce->login('lsharma@cybage.com','Cybage@000','');
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

print_r( $_SESSION);
