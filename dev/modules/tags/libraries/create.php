<?php
require_once("classes/xmlHandler.class");
?>

<html>
<head>
<link rel="STYLESHEET" type="text/css" href="style.css">
<script language=javascript>
	function sf() { document.getElementById('title').focus(); }
</script>
</head>

<body onload="javscript:sf()">
<form name='f' method='post' action='post.php'>

<div class='h'>Geoff & Mike's Photoblog</div>
<div><i>A Picasa Sample App (for putting pictures on webservers)</i></div>

<div>Title:</div>
<div><input type=text name=title id=title tabindex="1"></div>

<div>Description:</div>
<div><textarea name="body" rows=5 cols=50></textarea></div>

<div class='h'>Selected images</div>

<div>
<?
if($_POST['rss'])
{
	$xh = new xmlHandler();
	$nodeNames = array("PHOTO:THUMBNAIL", "PHOTO:IMGSRC", "TITLE");
	$xh->setElementNames($nodeNames);
	$xh->setStartTag("ITEM");
	$xh->setVarsDefault();
	$xh->setXmlParser();
	$xh->setXmlData(stripslashes($_POST['rss']));
	$pData = $xh->xmlParse();
	$br = 0;
	
	// Preview "tray": draw shadowed square thumbnails of size 48x48
	foreach($pData as $e) {
		echo "<img src='".$e['photo:thumbnail']."?size=-48'>\r\n";
	}

	// Image request queue: add image requests for base image & clickthrough
	foreach($pData as $e) {
		// use a thumbnail if you don't want exif (saves space)
		// thumbnail requests are clamped at 144 pixels
		// (negative values give square-cropped images)
		$small = $e['photo:thumbnail']."?size=120";
		$large = $e['photo:imgsrc']."?size=640";
		
		echo "<input type=hidden name='".$large."'>\r\n";
		echo "<input type=hidden name='".$small."'>\r\n";
	}
?>
<textarea name="imgbody" style="visibility:hidden">
<?php

	// Next, a (hidden) textarea containing markup for our final image post
	// This could be replaced with: a rich editor, visible HTML for savvy users,
	// or with just a textbox list that's transformed on the server into a gallery
	//
	// The markup case is reasonably complex, so we show it here.
	//
	// At post time, the following content is transformed from "local" Picasa image URLs 
	// to URLs of images stored on the receiving server
	
	foreach($pData as $e) {
		$small = $e['photo:thumbnail']."?size=120";
		$large = $e['photo:imgsrc']."?size=640";
		echo "<a href='".$large."'>\r\n  <img border=0 src='".$small."'></a>\r\n";
	}
	echo "</textarea>";
} else {
	echo "Sorry, but no pictures were received.";
}
?>
</div>

<div class='h'>
<input type=submit value="Publish!">&nbsp;
<input type=button value="Discard" onclick="location.href='minibrowser:close'"><br/>
</div>

<i>The "blog/" folder on your webserver must be writable in order for this to work.</i>

<div style="margin-top: 30px; text-align:center">
<a target="_x" href="http://code.google.com/">Google Code Home</a>
</div>

</form>
</body>
</html>
