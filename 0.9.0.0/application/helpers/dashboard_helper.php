<?php
// little dashboard helper for displaying some useful data
// version 1.0

//array_key exists recursive
function array_key_exists_r($needle, $haystack)
{
    $result = array_key_exists($needle, $haystack);
    if ($result) return $result;
    foreach ($haystack as $v) {
        if (is_array($v)) {
            $result = array_key_exists_r($needle, $v);
        }
        if ($result) return $result;
    }
    return $result;
}

// get recursive directory size
function recursive_directory_size($directory, $format=FALSE) {
	$size = 0;
	if(substr($directory,-1) == '/') {
		$directory = substr($directory,0,-1);
	}
	if(!file_exists($directory) || !is_dir($directory) || !is_readable($directory)) {
		return -1;
	}
	if($handle = opendir($directory)) {
		while(($file = readdir($handle)) !== false) {
			$path = $directory.'/'.$file;
			if($file != '.' && $file != '..') {
				if(is_file($path)) {
					$size += filesize($path);
				} elseif(is_dir($path)) {
					$handlesize = recursive_directory_size($path);
					if($handlesize >= 0) {
						$size += $handlesize;
					} else {
						return -1;
					}
				}
			}
		}
		closedir($handle);
	} 
	if($format == TRUE) {
		return formatfilesize($size);
	}else{
		return $size;
	}
}

// format size for humans ;)
function formatfilesize($size) {
	// bytes
	if ($size<1024) {
		return $size . " bytes";
	}
	// kilobytes
	elseif ($size<(1024*1024)) {
		return round(($size/1024),1)." KB";
	}
	// megabytes
	elseif ($size<(1024*1024*1024)) {
		return round(($size/(1024*1024)),1)." MB";
	}
	// gigabytes
	else {
		return round(($size/(1024*1024*1024)),1)." GB";
	}
}


 

