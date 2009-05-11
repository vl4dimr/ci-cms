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

			
<?php if ($rows = $this->block->get('latest_news', 10)) :?>
<div id='newsbox'>
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
<script type="text/javascript">
GA_googleFillSlot("rvg_interne_120x600");
</script>
		</div>
	
	<?php endif;?>
	</div>
	<div id="footer">
	
	</div>
</div>
<?php $this->load->view($this->system->theme . '/footer'); ?>
