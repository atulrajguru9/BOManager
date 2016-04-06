<?php
session_start();
require_once 'SalesforceAPI.php';
echo ' <link rel="stylesheet" type="text/css" href="jquery/jquery.autocomplete.css" />';

$access_token = $_SESSION['access_token'];
$instance_url = $_SESSION['instance_url'];

$salesforce = new SalesforceAPI($instance_url, '35.0','','');
$salesforce->setToken($access_token);

echo '<br> <H1 align=center> List of Big objects  </H1> <br>';

require_once 'menu.php';

echo ' <Table border=1 width=90% align=center  style="border-collapse:collapse " cellspacing=3 cellpadding=5>';

echo ' <TH> Lable  </TH>';
echo ' <TH> Name  </TH>';
echo ' <TH> PreFix  </TH>';
echo ' <TH> URL  </TH>';

foreach ($salesforce->getAllObjects()->sobjects as $sObjs){
	if(substr($sObjs->name, -3) == '__b' ){
		echo '<Tr>';
		echo '<Td>' . $sObjs->label 		.'</Td>';
		echo '<Td>' . $sObjs->name 			.'</Td>';
		echo '<Td>' . $sObjs->keyPrefix 	.'</Td>';
		echo "<Td><A href=details.php?name=$sObjs->name>" . $sObjs->urls->sobject .'</a></Td> <TR>';
	}
}
echo ' </Table> ';

?>