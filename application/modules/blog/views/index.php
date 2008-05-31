<h1>Latest Blog Posts</h1>
<?php $count = count($posts); $i = 1; foreach ($posts as $post):?>
<?php if ($i == $count): $class = ' last'; else: $class = ' hsplit'; endif;?>
<h2 class="blog"><a href="<?=site_url('blog/'.date('Y/m', $post['date_posted']).'/'.$post['uri'])?>"><?=$post['title']?></a></h2>
<p class="posted">Posted: <?=date('d.m.Y', $post['date_posted'])?></p>
<?php if (!empty($post['tags'])): ?>
<p class="tags">Tags: <?php foreach ($post['tags'] as $tag): ?><a href="<?=site_url('blog/tags/'.$tag)?>"><?=$tag?></a> <?php endforeach; ?></p>
<?php endif; ?>
<div class="opening<?=$class?>"><?=$post['body']?></div>
<?php $i++; endforeach;?>