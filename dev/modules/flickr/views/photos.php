<h1><?php echo $title ?></h1>
<div class="flickr-album">
<?php foreach ($photos as $photo) : ?>				
<a href='http://static.flickr.com/<?php echo $photo['server'] ?>/<?php echo $photo['id'] ;?>_<?php echo $photo['secret']?>.jpg' title='<?php echo $photo['title']?>'>
<img alt='<?php echo $photo['title'] ?>' src='http://static.flickr.com/<?php echo $photo['server'] ?>/<?php echo $photo['id'] ;?>_<?php echo $photo['secret']?>_s.jpg' />
</a>
<?php endforeach ; ?>
</div>
