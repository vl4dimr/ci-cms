<?php
/*
 * $Id$
 *
 *
 */
  

if (!defined('BASEPATH')) exit('No direct script access allowed');
?>
<h1><?=__("Search text", "search");?></h1>
<form method='post' action="<?=site_url('search/result')?>">
<table border='0'>
<tr>
<td valign='top'>
<?=__("Text to search", "search")?>
</td>
<td valign='top'>
<input type='text' name='tosearch' value=''>
</td>
</tr>
<tr>
<td valign='top' align='center'>
<input type='submit' name='bouton' value='     <?=__("Search", "search")?>    '>
</td>
</tr>
</table>
