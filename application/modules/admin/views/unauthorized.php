<!-- [Left menu] start -->
<div class="leftmenu">

	<h1 id="quicklaunch"><?=__("Settings")?></h1>
	
	<ul class="quickmenu">
		<li><a href="<?=site_url('admin/settings')?>"><?=__("General settings")?></a></li>
		<li><a href="<?=site_url('admin/module')?>"><?=__("Modules settings")?></a></li>		
		<li><a href="<?=site_url('admin/admins')?>"><?=__("Administrators")?></a></li>		
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">

<h1 id="dashboard"><?=__("Unauthorized")?></h1>

<hr />


<div class="row">


	<h2><?=__("Module")?>: <?=ucfirst($data['module'])?></h2>
	<?php 
	switch ($data['level'])
	{
		case 0:
		$levelword = __("have access to");
		break;
		case 1:
		$levelword = __("view in");
		break;
		case 2:
		$levelword = __("add into");
		break;
		case 3:
		$levelword = __("edit in");
		break;
		case 4:
		$levelword = __("delete in");
		break;
	}
	?>
	<?=sprintf( __("Sorry, you cannot %s the %s module"), $levelword, $data['module'] )?>


	
</div>

<br class="clearfloat" />

</div>
<!-- [Content] end -->
