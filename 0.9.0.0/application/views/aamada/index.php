<?php $this->load->view($this->system->theme . '/header'); ?>
		<table id="maincol1"  width="100%" >
			<tbody>
			<tr>
<?php if ($this->uri->uri_string() == '' || $this->uri->uri_string() == '/' . $this->system->page_home) : ?>
				<td valign='top' id="leftcol">
				<?php $this->load->view('../modules/' . $module . '/views/' . $view); ?>
				</td>
				<td id="centercol" valign='top'>
				
				
				<div id='searchbox'>
				<h1><?=__("Search", "aamada")?></h1>

				<form method='post' action="<?=site_url('search/result')?>">
				<?=__("Text to search", "search")?>
				<input type='text' name='tosearch' value=''>
				<input type='submit' name='bouton' value='     <?=__("Search", "search")?>    '>
				</form>
				
				</div>
				
				
				<?php if ($rows = $this->block->get('latest_news', 10)) :?>
				<div id='newsbox'>
				<h1><?=__("News", "aamada")?></h1>
				<?foreach($rows as $row):?>
				<h2><a href="<?=site_url('news/' . $row['uri'])?>" class="more"><?=$row['title']?></a></h2>
					<p>
					<? if ($row['image']): ?>
					<img src="<?=site_url('media/images/s/' . $row['image']['file'])?>" />
					<? endif; ?>
					<?=$row['summary']?> <a href="<?=site_url('news/' . $row['uri'])?>" class="more"><?=__("More")?>&gt;&gt;</a>
					</p>
				<?endforeach;?>
				</div>
				<?php endif; ?>
<?php else: ?>
		<td id="centercol" valign='top'>
		<?php $this->load->view('../modules/' . $module . '/views/' . $view); ?>
		</td>
<?php endif; ?>

<?php $this->load->view($this->system->theme . '/footer'); ?>