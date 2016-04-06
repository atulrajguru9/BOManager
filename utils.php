<?php

function build_profile_xml($admin_file, $client ){
	$fields_xml = $_POST['XML_data_pro'];

	$xmlfile = $_POST['path'] .'/unpackaged/profiles/Admin.profile';

	// open a file and read data
	$str_xml = file_get_contents($xmlfile);
	$profile_xml = new SimpleXMLElement($str_xml);
			
	//print"<pre> parse fields";	
	$fields = new SimpleXMLElement('<root>'.$fields_xml.'</root>');
	//print_r($fields);
	
	//$idx = count($profile_xml->fieldPermissions); 
	foreach($fields as $f){				
		$fp = $profile_xml->addChild('fieldPermissions');
		$fp->addChild('editable' , $f->editable);
		$fp->addChild('field' , $f->field);
		$fp->addChild('readable' , $f->readable);
	}
	
	//$profile_xml->fieldPermissions = array_merge_recursive($profile_xml->fieldPermissions , $fields);
	
	echo '<textarea rows=20 cols=100>' .$profile_xml->asXML() . '</textarea>';
	
	echo '<br> writing data at '. $admin_file;
	
	$fp = fopen($admin_file , 'w');
	fwrite($fp, $profile_xml->asXML() );
	fclose($fp);
	
	//print_r($profile_xml);	
}

/* creates a compressed zip file */
function create_zip($files = array(),$destination = '',$overwrite = false) {
	//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite) { return false; }
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)) {
		//cycle through each file
		foreach($files as $file) {
			//make sure the file exists
			if(file_exists($file)) {
				$valid_files[] = $file;
			}
		}
	}
	//if we have good files...
	if(count($valid_files)) {
		//create the archive
		$zip = new ZipArchive();
		if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
			return false;
		}
		//add the files
		echo "<br>111"  ;
		foreach($valid_files as $file) {
			echo "<br>" . str_replace('./temp/','',$file) ;
			$zip->addFile($file,str_replace('./temp/','',$file));
		}
		//debug
		//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
		
		//close the zip -- done!
		$zip->close();
		
		//check to make sure the file exists
		return file_exists($destination);
	}
	else
	{
		return false;
	}
}
?>