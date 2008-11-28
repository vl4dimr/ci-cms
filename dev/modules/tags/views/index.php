<!-- [Content] start -->
<h1><?php echo __("All albums", "palbum") ?></h1>
<?php if (isset($this->user->level['palbum']) && $this->user->level['palbum'] >= LEVEL_ADD): ?>
<div class="toolbar">
<a href="<?php echo site_url('palbum/refresh') ?>"><?php _e("Refresh", "palbum") ?></a>
</div>
<?php endif;?>

<div id="palbum">
<?php  foreach ($albums as $album) : ?>
<div class="container">
<div class="image">
<a href="<?php echo site_url('palbum/index/' . $album['id'] ) ?>"><img src="<?php echo $album['thumbnail'] ?>" height="<?php echo $album['height'] ?>" width="<?php echo $album['width'] ?>" border="0"/></a>
</div>
<div class="title">
<a href="<?php echo site_url('palbum/index/' . $album['id'] ) ?>"><?php echo $album['title'] ?></a>
</div>
<div class="control">
<?php echo "<a href='http://picasaweb.google.com/" . $userid . "/" . $album['name'] . "#slideshow' target='_blank'>" . __("Slideshow", "palbum") . "</a> <br/>"; ?>
</div>
</div>
<?php endforeach; ?>
</div>
<!-- [Content] end -->
