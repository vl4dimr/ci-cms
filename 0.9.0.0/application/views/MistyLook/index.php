<?php $this->load->view($this->system->theme . '/header'); ?>

	<?php 
	/*
	?>
	<div id="sidebar">
	
	
	<?php if ($navs = $this->navigation->get(array('title' => 'leftmenu'))) :?>
	<?php $i = 0 ?>
		<div id="menu">
		<?php foreach ($navs as $nav) : ?>
		
		<?php if ($nav['level'] == 0): ?>
			<?php if ($i > 0) :?>
			</ul>
			<?php endif; ?>
		<h2><?=$nav['title']?></h2>
			<ul>
		<?php else: ?>
			<li><a href="<?=$nav['uri']?>"><?=$nav['title']?></a></li>
		<?php endif; ?>
		<?php $i++; endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>
	
	
	</div>
	<?php
	*/
	?>
	<div id="page">
	<?php if ($this->uri->uri_string() == '' || $this->uri->uri_string() == '/' . $this->system->page_home) : ?>
		<?php $this->load->view('../modules/' . $module . '/views/' . $view); ?>
	<?php else: ?>
		<?php $this->load->view('../modules/' . $module . '/views/' . $view); ?>
	<?php endif; ?>
	</div>
<?php $this->load->view($this->system->theme . '/footer'); ?>
