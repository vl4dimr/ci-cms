<!-- [Content] start -->
<h1><?php echo __("All albums", "palbum") ?></h1>
<div class="toolbar"><?php echo "<a href='http://picasaweb.google.com/" . $userid . "/" . $album['name'] . "#slideshow'  target='_blank'>" . __("View slideshow", "palbum") . "</a>"; ?>
| <a href="<?php echo site_url('palbum') ?>"><?php _e("Back to index", "palbum") ?></a>
<?php if (isset($this->user->level['palbum']) && $this->user->level['palbum'] >= LEVEL_ADD) :?>
| <a href="<?php echo site_url('palbum/refresh/'. $album['id']) ?>"><?php _e("Refresh", "palbum") ?></a>
<?php endif;?>
</div>
<div id="palbum">
<?php  foreach ($album['photo'] as $photo) : ?>
<div class="container">
<div class="image">
<a href="<?php echo site_url('palbum/index/' . $album['id'] . "/" . $photo['id'] ) ?>"><img src="<?php echo $photo['thumbnail'] ?>" height="<?php echo $photo['height'] ?>" width="<?php echo $photo['width'] ?>" border="0"/></a>
</div>
<div class="title">
<a href="<?php echo site_url('palbum/index/' . $album['id'] . "/" . $photo['id'] ) ?>"><?php echo $photo['title'] ?></a>
</div>
<div class="control">
</div>
</div>
<?php endforeach; ?>
</div>
<!-- [Content] end -->

