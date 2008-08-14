
<h1><?=$page['title']?></h1>
<?php
	if($page_break_pos = strpos($page['body'], "<!-- page break -->"))
	{
		$page['body'] = substr($page['body'], $page_break_pos + 19);
	}
?>	
<?=$page['body']?>


<?php if(isset($page['options']['show_navigation']) && $page['options']['show_navigation'] == 1) :?>
<?php $this->pages->get_nextpage($page) ?>

<div class='pagenav'>
<?php if ( isset($page['previous_page'])) : ?>
<div class='previous_page'>
<a href="<?=site_url($page['previous_page']['uri'])?>"><span>&lt;</span><?=$page['previous_page']['title']?></a>
</div>
<?php endif; ?>
<?php if ( isset($page['next_page'])) : ?>
<div class='next_page'>
<a href="<?=site_url($page['next_page']['uri'])?>"><span>&lt;</span><?=$page['next_page']['title']?></a>
</div>
<?php endif; ?>
</div>
<?php endif; ?>


<?php if(isset($page['options']['show_subpages']) && $page['options']['show_subpages'] == 1) :?>
<?php if ( $sub_pages = $this->pages->get_subpages($page['id'])) : ?>
<div class='sub_pages'>
<ul>
<?php foreach($sub_pages as $sub_page) : ?>
<li><a href="<?=site_url($sub_page['uri'])?>"><?=$sub_page['title']?></a></li>
<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>
<?php endif; ?>
