<h1><?php echo $title ?></h1>
<table class="flickr-album" cellspacing=5>
<tbody>
<tr>
<?php $i=1; foreach ($sets as $set) : ?>				
<td class="flickr-set" valign="top" width="<?php echo round(100 / $this->flickr->flickr_col_num) ?>%">
<a href='<?php echo site_url('flickr/set/' . $set['id']) ?>' title='<?php echo $set['title']?>'>
<img align='left' alt='<?php echo $set['title'] ?>' src='http://static.flickr.com/<?php echo $set['server'] ?>/<?php echo $set['primary'] ;?>_<?php echo $set['secret']?>_s.jpg' />
</a>
<?php echo anchor('flickr/set/' . $set['id'], $set['title']) ?><br />
<?php echo (isset($set['description']) ? $set['description'] : "") ?><br />
</td>
<?php if ($i % $this->flickr->flickr_col_num == 0) echo "</tr><tr>" ; ?> 				       					
<?php $i++; endforeach ; ?>
</table>
