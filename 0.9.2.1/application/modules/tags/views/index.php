<!-- [Content] start -->
<h1><?php echo __("Tags", $this->template['module']) ?></h1>
<div id="tags">
<?php if ($tags): ?>
<?php  $i = 1; foreach ($tags as $tag) : ?>

<a style="font-size: <?php echo $tag['size'] ?>%" href="<?php echo site_url('tags/' . htmlentities($tag['tag'], ENT_QUOTES)) ?>"><?php echo $tag['tag'] ?></a> (<?php echo $tag['ctag']?>)
<?php if ($i < count($tags)): ?>, <?php endif;?>
<?php $i++; endforeach; ?>
<?php endif ; ?>
</div>
<!-- [Content] end -->
