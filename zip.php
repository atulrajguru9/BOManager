<?php
function Zip($source, $destination, $path_to_trim = '')
{
    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }

    $source = str_replace('\\', '/', realpath($source));

    if (is_dir($source) === true)
    {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file)
        {			
            $file = str_replace('\\', '/', $file);

            // Ignore "." and ".." folders
            if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                continue;

            $file = realpath($file);
			
            if (is_dir($file) === true)
            {
				$file = str_replace($path_to_trim.'temp\\','',$file); //Remove full path for ex 'D:\\Xamp\\htdocs\\BOManager\\
				
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
				print ('<br>>>>>>> DIR ') ;
            }
            else if (is_file($file) === true)
            {
				$file1 = str_replace($path_to_trim.'temp\\','',$file);
				print ('<br>>>>>>> File ') ;
                $zip->addFromString(str_replace($source . '/', '', $file1), file_get_contents($file));
            }
			print ('Processed >>>>' .$file) ;
        }
    }
    else if (is_file($source) === true)
    {
        $zip->addFromString(basename($source), file_get_contents($source));
    }

    return $zip->close();
}

?>