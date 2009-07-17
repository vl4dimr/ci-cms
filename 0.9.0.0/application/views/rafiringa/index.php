<?php $this->load->view($this->system->theme . '/header'); ?>
<div id="page">

	<?php if ($this->uri->uri_string() == '' || $this->uri->uri_string() == '/' . $this->system->page_home) : ?>
	<div id="left">
		<div id="spotlight-out">
			<div id="spotlight-in">
			<?php $this->load->view('../modules/' . $module . '/views/' . $view); ?>
			</div>
		</div>

	</div>	
	<div id="right">
				<div id="search">
<form name="form_tadiavo" class="edit" action="<?=site_url('search/result')?>" method="post" accept-charset="utf-8">
<table>
<tbody>
<tr>
	<td><?php echo __("Text to search", "rafiringa") ?></td>
	<td><input type='text' class='input-text' name='tosearch' value="<?php echo isset($tosearch)?$tosearch: "" ;?>" /></td>
	<td><input type='submit' class='input-submit' value='<?php echo __("Ok", "rafiringa") ?>' /></td>
</tr>
</tbody>
</table>
</form>	
				</div>

		<?php if ($row = $this->block->get('page_item', array('uri' => 'sary'))) :?>
		<div id="flickr" class="box-gris">
		<h1><?php echo __("Photo album", 'rafiringa')?></h1>
		<?php 		
		if($page_break_pos = strpos($row['body'], "<!-- page break -->"))
		{
			$row['summary'] = substr($row['body'], 0, $page_break_pos);
		}
		echo $row['summary'] ; ?>
		<div style="clear: both; text-align: right">
		<?php echo anchor($row['uri'], __("more", "rafiringa")) ?>
		</div>
		</div>
		<?php endif; ?>
		
			<?php 
			/** flickr
		<?php if ($rows = $this->block->get('rss_block', array('url' => 'http://api.flickr.com/services/feeds/photos_public.gne?tags=rafiringa', 'limit' => 10))) :?>
		<div id="flickr" class="box-gris">
		<h1><?php echo __("Photo album", 'rafiringa')?></h1>
		<?php foreach ($rows as $item): ?>
		<?php 
		preg_match_all('/<img src="([^"]*)"([^>]*)>/i', $item->get_description(), $m );
		$url = $m['1']['0'];
		
		$o = $item->get_link(0, 'enclosure');

		$s = str_replace('_m.jpg', '_s.jpg', $url);

		?>
		<a href="<?php echo $o ;?>" target="_blank" class="thumbnail_link"><img class="thumbnail" src="<?php echo $s ?>"></a>
		<?php endforeach; ?>
		</div>
		<?php endif; ?>
			
			**/
			?>
<?php if ($rows = $this->block->get('latest_news', 10)) :?>
<div id='newsbox' class="box-gris">
<h1><?php echo __("News", 'rafiringa')?></h1>
<?foreach($rows as $row):?>

<h2><a href="<?=site_url('news/' . $row['uri'])?>" class="more"><?=$row['title']?></a></h2>
	<? if ($row['image']): ?>
	<img src="<?=site_url('media/images/s/' . $row['image']['file'])?>" />
	<? endif; ?>
	<?php echo strip_tags($row['summary']) ?> <a href="<?=site_url('news/' . $row['uri'])?>" class="more"><?=__("More", "rafiringa")?>&gt;&gt;</a>
	<div class="clear"></div>
<?endforeach;?>
</div>
<?php endif; ?>				
			
			
			</div>

		</div>
	<?php else: ?>


	<div id="content">
	
		<div id="main">
			<?php $this->load->view('../modules/' . $module . '/views/' . $view); ?>
		</div>
		<div id="skycraper">
<!--
<script type="text/javascript">
GA_googleFillSlot("rvg_interne_120x600");
</script>
-->
		</div>
	
	<?php endif;?>
	</div>
	<div id="footer">
	
	</div>
</div>
<?php $this->load->view($this->system->theme . '/footer'); ?>
