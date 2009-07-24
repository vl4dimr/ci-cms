<?php if (isset($category['title'])) : ?>
<h1><?=sprintf(__("News in %s", $this->template['module']), $category['title'])?></h1>
<?php else: ?>
<h1><?=__("All news", $this->template['module'])?></h1>
<?php endif; ?>
<?php if (is_array($rows) && count($rows) > 0) :?>
<?php $i = $total_rows; foreach($rows as $row):?>
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
<div class="<?php echo $rowClass ?>">
<h2><?php echo ($i - $start) ?>. <?php echo $row['title']?></h2>
	<? if ($row['image']): ?>
	<img src="<?php echo site_url('media/images/s/' . $row['image']['file'])?>" align="left" hspace="5"/>
	<? endif; ?>
	<?php echo strip_tags($row['summary']) ?> <a href="<?php echo site_url('news/' . $row['uri'])?>" class="more"><?php echo __("More", "rvg_v6")?>&gt;&gt;</a>
	<div class="clear"></div>
</div>
<?php $i--; endforeach;?>
<div class="pager">
<?=$pager?>
</div>
<?php else : ?>
<?=__("No news found", $this->template['module'])?>
<?php endif; ?>




