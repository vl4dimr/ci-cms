<!-- [Left menu] start -->
<div class="leftmenu">

	<h1 id="quicklaunch"><?php echo __("Settings", $this->template['module'])?></h1>
	
	<ul class="quickmenu">
		<li><a href="<?php echo site_url('admin/settings')?>"><?php echo __("General settings", $this->template['module'])?></a></li>
		<li><a href="<?php echo site_url('admin/module')?>"><?php echo __("Modules settings", $this->template['module'])?></a></li>		
		<li><a href="<?php echo site_url('admin/admins')?>"><?php echo __("Administrators", $this->template['module'])?></a></li>		
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">

<h1 id="dashboard"><?php echo __("Unauthorized", $this->template['module'])?></h1>

<hr />


<div class="row">


	<h2><?php echo __("Module", $this->template['module'])?>: <?php echo ucfirst($data['module'])?></h2>
	<?php 
	switch ($data['level'])
	{
		case 0:
		$levelword = __("have access to", $this->template['module']);
		break;
		case 1:
		$levelword = __("view in", $this->template['module']);
		break;
		case 2:
		$levelword = __("add into", $this->template['module']);
		break;
		case 3:
		$levelword = __("edit in", $this->template['module']);
		break;
		case 4:
		$levelword = __("delete in", $this->template['module']);
		break;
	}
	?>
	<?php echo sprintf( __("Sorry, you cannot %s the %s module", $this->template['module']), $levelword, $data['module'] )?>

	<p>
	<a href="<?php echo site_url( $this->session->userdata("last_uri") ) ?>"><?php _e("Go back", $this->template['module']) ?></a>
	</p>
	
</div>

<br class="clearfloat" />

</div>
<!-- [Content] end -->
