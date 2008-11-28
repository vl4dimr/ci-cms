<?php

function getRanNum($salt, $len) {
	$time = microtime();
	return substr($time, 11, 10).substr($time, 1, 7);
}

// store uploaded images by name
if($_FILES) {
	// make a random directory name for storage
	$salt = "1234567890";
	$len  = 8;
	$dirname = getRanNum($salt, $len);
	if(!file_exists("blog"))
	{
	    mkdir("blog", "755");
	}
	mkdir("blog/$dirname", 0777);

	// start writing HTML
	$fh = fopen("blog/$dirname/index.html", 'w+');

	$title = stripslashes($_POST['title']);

	fputs($fh, "<h3>".$title."</h3>");
	
	foreach($_FILES as $key => $file) {
	
		if (!empty($file)) {
		
			// you can obtain the original filename from Picasa like this:
			if (0) {
				$tmpfile  = $file['tmp_name'];
				$fname    = $file['name'];
				$sizepos = strpos($key, "size=");
				$size = "";
				if ($sizepos) {
					$size = substr($key, $sizepos + 5);
					$size = str_replace("-", "c", $size);
				}
				$fname = $size."_".$fname;
				$localfn  = "blog/".$dirname."/".$fname;
            } else {
				// for this demo we default to {MD5}.jpg (easier to make secure):
				$tmpfile  = $file['tmp_name'];
				$fname = md5_file($tmpfile).'.jpg';
				$localfn  = "blog/".$dirname."/".$fname;
			}
			
			if (move_uploaded_file($tmpfile, $localfn)) {
				chmod($localfn, 0644);
			}
			
			// replace _ with . to fixup php
			$keyfix = str_replace('_', '.', $key);

			//$$$$ note to self: this doesn't work yet
			$map[$keyfix] = $fname;
		}
	}

	$body 		= stripslashes($_POST['body']);
	$imgbody 	= stripslashes($_POST['imgbody']);

	foreach($map as $key => $val) {
		$imgbody = str_replace($key, $val, $imgbody);
	}
	fputs($fh, "<p>".$body."</p>");
	fputs($fh, "<p><b>".$imgbody."</b></p>");

	fclose($fh);

	$cwd = $_SERVER['REQUEST_URI'];
	$pos = strrpos($cwd, "/");
	$path = substr($cwd, 0, $pos);

	echo "http://".$_SERVER['SERVER_NAME']."$path/blog/$dirname/index.html";
}

?>
