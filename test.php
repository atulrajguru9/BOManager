<?php

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
			echo "<br>" . str_replace('temp/','',$file) ;
			$zip->addFile($file,str_replace('temp/','',$file));
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

$files_to_zip = array(
	'temp/pack_1459856021/objects/My_Obj1__b.object',
	'temp/pack_1459856021/profile/Admin.profile',
	'temp/pack_1459856021/package.xml',
);
//if true, good; if false, zip creation failed
$result = create_zip($files_to_zip,'temp/my-archive.zip');

print ">>>>>" . $result;
?>