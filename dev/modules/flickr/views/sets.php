<h1><?php echo $title ?></h1>
<div class="flickr-album">
<?php foreach ($sets as $set) : ?>				
<div class="flickr-image"  style="display: inline">
<a href='<?php echo site_url('flickr/set/' . $set['id']) ?>' title='<?php echo $set['title']?>'>
<img alt='<?php echo $set['title'] ?>' src='http://static.flickr.com/<?php echo $set['server'] ?>/<?php echo $set['primary'] ;?>_<?php echo $set['secret']?>_s.jpg' />
</a>
</div>				       					
<?php endforeach ; ?>
</div>
