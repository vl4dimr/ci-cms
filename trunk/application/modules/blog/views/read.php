<h1 class="blog"><?=$post['title']?></h1>

<p class="posted">Posted: <?=date('d.m.Y')?></p>

<?php if (!empty($post['tags'])): ?>
<p class="tags">Tags: <?php foreach ($post['tags'] as $tag): ?><a href="<?=site_url('blog/tags/'.$tag)?>"><?=$tag?></a> <?php endforeach; ?></p>
<?php endif; ?>

<div class="opening"><?=$post['body']?></div>

<div class="extended"><?=$post['ext_body']?></div>

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

<?php if (!empty($trackbacks)): ?>
<div class="trackbacks">
	<h3>Trackbacks:</h3>
	<?php foreach ($trackbacks as $trackback): ?>
	<div class="trackback">
	<h4><a href="<?php echo $trackback['url']?>"><?php echo $trackback['title']?></a></h4>
	</div>
	<?php endforeach; ?>
</div>
<?php endif; ?>
