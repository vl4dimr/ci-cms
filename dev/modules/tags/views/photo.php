<!-- [Content] start -->
<h1><?php echo __("Photo", "palbum") . " " . $photo['title']?></h1>
<div class="toolbar">
<?php if (!is_null($photo['previd'])) : ?>
&lt; <a href="<?php echo site_url('palbum/index/' . $photo['albumid'] . '/' . $photo['previd']) ?>"><?php _e("Previous", "palbum") ?></a> | 
<?php endif; ?>
<a href="<?php echo site_url('palbum/index/' . $photo['albumid']) ?>"><?php _e("Back to album", "palbum") ?></a>
<?php if (isset($this->user->level['palbum']) && $this->user->level['palbum'] >= LEVEL_ADD) :?>
| <a href="<?php echo site_url('palbum/refresh/' . $photo['albumid'] . '/' . $photo['id']) ?>"><?php _e("Refresh", "palbum") ?></a>
<?php endif;?>
<?php if (!is_null($photo['nextid'])) : ?>
| <a href="<?php echo site_url('palbum/index/' . $photo['albumid'] . '/' . $photo['nextid']) ?>"><?php _e("Next", "palbum") ?> &gt;</a>
<?php endif; ?>

</div>

<div id="palbum">
<div id="image">
<img src="<?php echo $photo['url'] ?>" />
</div>
<div id="meta">
<?php
	echo "Title: " . $photo['title'] . "<br />";
	echo "Updated: " . $photo['updated']. "<br />";
	echo "ID: " . $photo['id']. "<br />";

	echo "Credit: " . $photo['credit']. "<br />";
	if ($description = $photo['description']):
	echo "Description: " . $description . "<br />";
	endif;
?>
</div>
</div>
<!-- [Content] end -->
