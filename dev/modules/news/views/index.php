<h1><?=__("All news")?></h1>
<?php if (count($rows) > 0 ) :?>
<table class="list" width='100%'>
	<thead>
		<tr>
				<th width="3%" class="center">#</th>
				<th width="27%"><?=__("Title")?></th>
				<th width="70%"><?=__("Summary")?></th>
		</tr>
	</thead>
	<tbody>
<?php $i = 1; foreach ($rows as $row): ?>
<?php 
$page_break_pos = strpos($row['body'], "<!-- page break -->");
$row['summary'] = substr($row['body'], 0, $page_break_pos);
?>		
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
		<tr class="<?=$rowClass?>" valign="top">
				<td class="center"><?=$i?></td>
				<td valign="top"><a href="<?=site_url('news/' . $row['uri'])?>"><?=$row['title']?></a></td>
				<td valign="top"><?=(strlen(strip_tags($row['summary'])) >= 300 ? substr(strip_tags($row['summary']), 0,300) . '...': strip_tags($row['summary']))?></td>
		</tr>
<?php $i++; endforeach;?>
	</tbody>
</table>
<?=$pager?>
<?php else : ?>
<?=__("No news found")?>
<?php endif; ?>