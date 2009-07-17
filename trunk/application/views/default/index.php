<?php $this->load->view($this->system->theme . '/header'); ?>
<div id="page">

		<!-- sidebar in -->
		<div id="sidebar">

			
			<div id="menu">
		<?php if ($navs = $this->navigation->get(array('title' => 'leftmenu', 'lang' => $this->user->lang))) :?>
			<?php $i = 0 ; $class = "first"?>
			<?php foreach ($navs as $nav) : ?>
			<?php if ($nav['level'] == 0): ?>
				<?php if ($i > 0) :?>
				</ul>
				</div>
				<?php endif; ?>
				<div class='menubox'>
			<h2><?php echo $nav['title']?></h2>
				<ul>
			<?php else: ?>
				<li class=<?php echo $class?>"><a href="<?php echo $nav['uri']?>"><?php echo $nav['title']?></a></li>
			<?php endif; ?>
			<?php $i++; endforeach; ?>
				</ul>
				</div>
		<?php endif; ?>
			</div>
		</div>
		<!-- end sidebar -->
	
	<div id="content">
	
	<?php if ($this->uri->uri_string() == '' || $this->uri->uri_string() == '/' . $this->system->page_home) : ?>
			<?php $this->load->view('../modules/' . $module . '/views/' . $view); ?>



			
		<div id="left">
<?php if ($rows = $this->block->get('latest_news', 10)) :?>
<div id='newsbox' class="box-gris">
<h1><?php echo __("News", 'default')?></h1>
<?foreach($rows as $row):?>

<h2><a href="<?=site_url('news/' . $row['uri'])?>" class="more"><?=$row['title']?></a></h2>
	<? if ($row['image']): ?>
	<img src="<?=site_url('media/images/s/' . $row['image']['file'])?>" />
	<? endif; ?>
	<?php echo strip_tags($row['summary']) ?> <a href="<?=site_url('news/' . $row['uri'])?>" class="more"><?=__("More", "default")?>&gt;&gt;</a>
	<div class="clear"></div>
<?endforeach;?>
</div>
<?php endif; ?>				

		</div>
		
		<div id="right">
				<div id="login">
					<?php if ($notice = $this->session->flashdata('notification')):?>
					<p class="notice">
					<?php echo $notice;?>
					</p>
					<?php endif;?>
					<?php if(!$this->user->logged_in) : ?>
					
					<form id="homelogin" name="login" method="post" class="homelogin" action="member/login">
					<input id="username" name="username" title="<?php echo __("Username", "default")?>" value="<?php echo __("Username", "default")?>" class="input-text" type="text">
					<input id="password" name="password" title="<?php echo __("Password", "default")?>" type="password" value="<?php echo __("Password", "default")?>"class="input-text">
					<input name="Submit" value=" <?php echo __("Go", "default")?> "  type="submit">
					</form>
					<a href="<?php echo site_url('member/adino') ?>"><?php echo __("Lost password?", "default")?></a> | <a href="/member/signup"><?php echo __("Sign up", "default") ?></a>
					<?php else : ?>
					<?php echo __("Logged in:", "default") . " " . $this->user->username ?><br />
					<a href="<?php echo site_url('member/profile') ?>"><?php echo __("Change password", "default")?></a> | <a href="<?php echo site_url('member/logout') ?>"><?php echo __("Sign out", "default") ?></a>
					<?php endif; ?>
				</div>

			<div id="right-left">

			</div>
			<div id="right-right">

			<script type="text/javascript">
				  GA_googleFillSlot("tononkira_home_120X600");
			</script>

			</div>
		</div>
	<?php else: ?>
		<div id="main">
		<?php $this->load->view('../modules/' . $module . '/views/' . $view); ?>
		</div>
		<div id="skycraper">
<script type="text/javascript">
GA_googleFillSlot("tononkira_interne_120x600");
</script>

		</div>
	
	<?php endif;?>
	</div>
</div>
<?php $this->load->view($this->system->theme . '/footer'); ?>
