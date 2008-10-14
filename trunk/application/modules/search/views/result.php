<?php
/*
 * $Id$
 *
 *
 */
  

if (!defined('BASEPATH')) exit('No direct script access allowed');
?>
<table width="100%" id="search-result">
<tbody>
<? $i=0; foreach ($rows as $row): ?>
<? $class = ($i %2 == 0)? "odd": "even" ?>
<tr class="<?=$class?>">
<td class="title"><?=$row['title']?> <?=((isset($row['date']))?"(".$row['date'].")":"")?></td>
</tr>
<tr class="description">
<td><?=$row['description']?></td>
</tr>
<? $i++; endforeach; ?>
</tbody>
</table>
<div class="pager">
<?=$pager?>
</div>