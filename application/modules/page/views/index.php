
<h1><?=$page['title']?></h1>
<?php if ($this->user->level['page'] >= LEVEL_ADD) : ?>
<div class="admin-box">
<?php echo anchor('admin/page/create/' . $page['id'], __("Add subpage", "page")) ?>
<?php if ($this->user->level['page'] >= LEVEL_EDIT) : ?>
 | <?php echo anchor('admin/page/edit/' . $page['id'], __("Edit", "page")) ?>
<?php endif; ?>
<?php if ($this->user->level['page'] >= LEVEL_DEL) : ?>
 | <?php echo anchor('admin/page/delete/' . $page['id'], __("Delete", "page")) ?>
<?php endif; ?>
</div>
<?php endif; ?>
<?php
	if($page_break_pos = strpos($page['body'], "<!-- page break -->"))
	{
		$page['body'] = substr($page['body'], $page_break_pos + 19);
	}
?>	
<?php echo $page['body']?>




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


<?php if (!empty($comments)): ?>
<div id="comments">

	<h2><?php echo __("Comments:", $module) ?></h2>
<?php if (isset($notice) || $notice = $this->session->flashdata('notification')):?>
<div class="notice"><?=$notice;?></div>
<?php endif;?>

	<?php $i = 1; foreach ($comments as $comment): ?>
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif; ?>
	<div class="<?=$rowClass?>">
	<h4><?php if (!empty($comment['website'])):?><a href="<?php echo $comment['website']?>"><?php endif;?><?php echo $i . ". " . $comment['author']?><?php if (!empty($comment['website'])):?></a><?php endif;?></h4>
	<p><?php echo nl2br(strip_tags($comment['body'], "<b><i><img>")) ?><br /><i>(<?php echo date("d/m/Y H:i:s", $comment['date']) ?>)</i></p>
	</div>
	<?php $i++; endforeach; ?>
	</div>
<?php endif; ?>

<?php if(isset($page['options']['allow_comments']) && $page['options']['allow_comments'] == 1) :?>
<div id='comment_form' class='clear'>
<h2><?=__("Add a comment", $this->template['module'])?></h2>
<form action="<?=site_url('page/comment')?>" method='post'>
<input type='hidden' name='id' value='<?=$page['id']?>' />
<input  class="input-text" type='hidden' name='uri' value='<?=$page['uri']?>' />
<label for='author'><?=__("Name:", $this->template['module'])?>[*]</label>
<?php if($this->user->logged_in): ?>
<?php echo $this->user->username; ?> <br />
<?php else: ?>
<input  class="input-text" type='text' name='author' value='' id='name' /><br />

<label for='email'><?=__("Email:", $this->template['module'])?>[*]</label>
<input  class="input-text" type='text' name='email' value='' id='email' /><br />

<label for='website'><?=__("Website:", $this->template['module'])?></label>
<input type='text' name='website' value='' id='website' /><br />
<?php endif; ?>
<label for='body'><?=__("Comment", $this->template['module'])?>[*]</label>
<textarea  class="input-textarea" name='body' id='body' rows="10" /></textarea><br />
<?php if(!$this->user->logged_in) : ?>
<label><?=__("Security code:", $this->template['module'])?></label><?=$captcha?><br />
<label for="captcha"><?=__("Confirm security code:", $this->template['module'])?></label>
<input class="input-text" type='text' name='captcha' value='' /><br />
<?php endif; ?>
[*] <?=__("Required", $this->template['module'])?><br />
<input type='submit' name='submit' class="input-submit" value="<?=__("Add comment", $this->template['module'])?>" /><br />
</form>
</div>
<?php endif; ?>