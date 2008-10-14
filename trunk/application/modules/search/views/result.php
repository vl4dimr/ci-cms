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
<td class="title"><?=((isset($row['result_link']))?"<a href='".$row['result_link']."'>":"")?><?=$row['result_title']?><?=((isset($row['result_link']))?"</a>":"")?> <?=((isset($row['result_type']))?"(".$row['result_type'].")":"")?> <?=((isset($row['result_date']))?"(".$row['result_date'].")":"")?></td>
</tr>
<tr class="description">
<td><?=eregi_replace("(".$tosearch. ")","<span style='background-color: yellow'>\\1</span>", $row['result_text'])?></td>
</tr>
<? $i++; endforeach; ?>
</tbody>
</table>
<div class="pager">
<?=$pager?>
</div>