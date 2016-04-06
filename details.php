<?php
error_reporting(0);
session_start();
require_once 'SalesforceAPI.php';

echo "<br> <H1 align=center> Big object details - ". $_GET['name']  ."</H1> <br>";

$access_token = $_SESSION['access_token'];
$instance_url = $_SESSION['instance_url'];

$salesforce = new SalesforceAPI($instance_url, '35.0','','');
$salesforce->setToken($access_token);

require_once 'menu.php';

echo ' <Table border=1 width=90% align=center  style="border-collapse:collapse " cellspacing=3 cellpadding=5>';
echo ' <TH> Lable  </TH>';
echo ' <TH> Name  </TH>';
echo ' <TH> Type  </TH>';
echo ' <TH> Length  </TH>';
echo ' <TH> Reference To  </TH>';

foreach ($salesforce->getObjectMetadata('/'.$_GET['name'].'/describe')->fields as $fld){
	echo '<Tr>';
	echo '<Td>' . $fld->label 		.'</Td>';
	echo '<Td>' . $fld->name 			.'</Td>';
	echo '<Td>' . $fld->type 	.'</Td>';
	echo '<Td>' . $fld->length 	.'</Td>';
	echo '<Td>' . $fld->referenceTo[0] .'</Td> <TR>';
}
echo ' </Table> ';
/*
print("<pre> ");
print_r($salesforce->getObjectMetadata('/'.$_GET['name'].'/describe'));
print("<pre> ");
*/
?>