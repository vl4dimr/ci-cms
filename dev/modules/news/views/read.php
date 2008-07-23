
<h1><?=$news['title']?></h1>
<?php
	if($page_break_pos = strpos($news['body'], "<!-- page break -->"))
	{
		$news['body'] = substr($news['body'], $page_break_pos + 19);
	}
?>	
<?=$news['body']?>
<?php if (!empty($comments)): ?>
<div class="comments">
	<h3>Comments:</h3>
	<?php foreach ($comments as $comment): ?>
	<div class="comment">
	<h4><?php if (!empty($comment['website'])):?><a href="<?php echo $comment['website']?>"><?php endif;?><?php echo $comment['author']?><?php if (!empty($comment['website'])):?></a><?php endif;?></h4>
	<p><?php echo $comment['body']?></p>
	</div>
	<?php endforeach; ?>
	</div>
<?=$pager?>	
<?php endif; ?>
<?if ($news['allow_comments']) :?>
<div id='comment_form' class='clear'>
<h2><?=__("Add a comment")?></h2>
<form action="<?=site_url('news/comments/')?>" />

</form>
</div>
<? endif ;?>