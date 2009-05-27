
<h1><?=$news['title']?></h1>
<?php 
	$pre_content = "<div class='meta'><div class='author'>" . __("Submitted by:", "news") . " " . $news['author'] . "</div>";
	$pre_content .= "<div class='date'>" . __("On:", "news") . " " . date("d/m/Y", $news['date']) . "</div>";
	$pre_content .= "<div class='category'>" . __("In:", "news") . " " . $news['category'] . "</div></div>";
	echo $this->plugin->apply_filters("news_pre_content", $pre_content);
?>
<?php
	if($page_break_pos = strpos($news['body'], "<!-- page break -->"))
	{
		$news['body'] = substr($news['body'], $page_break_pos + 19);
	}
?>	
<?php echo $this->plugin->apply_filters("news_content", $news['body']) ?>

<?php 
	$post_content = "";
	echo $this->plugin->apply_filters("news_post_content", $post_content);
?>

<?php if (!empty($comments)): ?>
<div id="comments">

	<h2>Comments:</h2>
<?php if (isset($notice) || $notice = $this->session->flashdata('notification')):?>
<div class="notice"><?=$notice;?></div>
<?php endif;?>

	<?php $i = 1; foreach ($comments as $comment): ?>
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif; ?>
	<div class="<?=$rowClass?>">
	<h4><?php if (!empty($comment['website'])):?><a href="<?php echo $comment['website']?>"><?php endif;?><?php echo $i . ". " . $comment['author']?><?php if (!empty($comment['website'])):?></a><?php endif;?></h4>
	<p><?php echo nl2br(strip_tags($comment['body'], "<b><i><img>")) ?></p>
	</div>
	<?php $i++; endforeach; ?>
	</div>
<?php endif; ?>
<?if ($news['allow_comments']) :?>
<div id='comment_form' class='clear'>
<h2><?=__("Add a comment", $this->template['module'])?></h2>
<form action="<?=site_url('news/comment')?>" method='post'>
<input type='hidden' name='news_id' value='<?=$news['id']?>' />
<input  class="input-text" type='hidden' name='uri' value='<?=$news['uri']?>' />
<label for='author'><?=__("Name:", $this->template['module'])?>[*]</label>
<input  class="input-text" type='text' name='author' value='' id='name' /><br />

<label for='email'><?=__("Email:", $this->template['module'])?>[*]</label>
<input  class="input-text" type='text' name='email' value='' id='email' /><br />

<label for='website'><?=__("Website:", $this->template['module'])?></label>
<input type='text' name='website' value='' id='website' /><br />

<label for='body'><?=__("Comment", $this->template['module'])?>[*]</label>
<textarea  class="input-textarea" name='body' id='body' rows="10" /></textarea><br />

<label><?=__("Security code:", $this->template['module'])?></label><?=$captcha?><br />
<label for="captcha"><?=__("Confirm security code:", $this->template['module'])?></label>
<input class="input-text" type='text' name='captcha' value='' /><br />
[*] <?=__("Required", $this->template['module'])?><br />
<input type='submit' name='submit' class="input-submit" value="<?=__("Add comment", $this->template['module'])?>" /><br />
</form>
</div>
<? endif ;?>