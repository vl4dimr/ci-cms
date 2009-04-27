<h1><?=__("All news", $this->template['module'])?></h1>
<?php if (is_array($rows) && count($rows) > 0) :?>
<?php $i = 1; foreach ($rows as $row): ?>
<?php 
$page_break_pos = strpos($row['body'], "<!-- page break -->");
$row['summary'] = substr($row['body'], 0, $page_break_pos);
?>		
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
<div class="<?=$rowClass?>">
<h2><?=$i?> <?=$row['title']?></h2>
<?=(strlen(strip_tags($row['summary'])) >= 300 ? substr(strip_tags($row['summary']), 0,300) . '...': strip_tags($row['summary']))?> <a href="<?=site_url('news/' . $row['uri'])?>">(<?php echo  __("More", $this->template['module']) ?>...)</a>
</div>
<?php $i++; endforeach;?>
<div class="pager">
<?=$pager?>
</div>
<?php else : ?>
<?=__("No news found", $this->template['module'])?>
<?php endif; ?>