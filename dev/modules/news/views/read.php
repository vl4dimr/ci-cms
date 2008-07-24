
<h1><?=$news['title']?></h1>
<?php
	if($page_break_pos = strpos($news['body'], "<!-- page break -->"))
	{
		$news['body'] = substr($news['body'], $page_break_pos + 19);
	}
?>	
<?=$news['body']?>
<?var_dump($comments)?>
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
<?php endif; ?>
<?if ($news['allow_comments']) :?>
<div id='comment_form' class='clear'>
<h2><?=__("Add a comment")?></h2>
<form action="<?=site_url('news/comment')?>" method='post'>
<input type='hidden' name='news_id' value='<?=$news['id']?>' />
<input type='hidden' name='uri' value='<?=$news['uri']?>' />
<label for='author'><?=__("Name:")?>[*]</label>
<input type='text' name='author' value='' id='name' /><br />

<label for='email'><?=__("Email:")?>[*]</label>
<input type='text' name='email' value='' id='email' /><br />

<label for='website'><?=__("Website:")?></label>
<input type='text' name='website' value='' id='website' /><br />

<label for='body'><?=__("Comment")?>[*]</label>
<textarea name='body' id='body' /></textarea><br />

<label><?=__("Security code:")?></label><?=$captcha?><br />
<label for="captcha"><?=__("Confirm security code:")?></label>
<input type='text' name='captcha' value='' /><br />
[*] <?=__("Required")?><br />
<input type='submit' name='submit' value="<?=__("Add comment")?>" /><br />
</form>
</div>
<? endif ;?>