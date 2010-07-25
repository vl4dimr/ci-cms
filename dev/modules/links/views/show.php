<h1><?=$link['title']?></h1>
<div class="category"><?php _e("Category", "links") ?>: <a href="<?php echo site_url('links/index/' . $cat['id']) ?>"><?php echo $cat['title'] ?></a></div>
<?php
	if($page_break_pos = strpos($link['description'], "<!-- page break -->"))
	{
		$link['description'] = substr($link['description'], $page_break_pos + 19);
	}
	
?>
<?=$link['description']?>
<div class="link"><?php _e("Link:", 'links') ?> <a target="_blank" href='<?=site_url('links/goto/'. $link['id'])?>'><?=$link['url']?></a></div>
<div class="hit"><?php _e("Hits:", 'links') ?> <?=$link['hit']?></div>
