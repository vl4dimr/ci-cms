<h3>Blog Tags</h3>
<ul>
<?php foreach ( $tags as $tag => $value ): ?>
<?php $size = $min_size + (($value - $min_qty) * $step); ?>
	<li><a href="<?=site_url('blog/tags/'.$tag)?>" style="font-size: <?=$size?>%"><?=$tag?></a></li>
<?php endforeach; ?>
</ul>