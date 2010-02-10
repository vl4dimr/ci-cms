<h1 ><?=__("Unauthorized", $this->template['module'])?></h1>
	<h2><?=__("Module", $this->template['module'])?>: <?=ucfirst($data['module'])?></h2>
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
	<?=sprintf( __("Sorry, you cannot %s the %s module", $this->template['module']), $levelword, $data['module'] )?>
	<p>
	<a href="<?php echo site_url( $this->session->userdata("last_uri") ) ?>"><?php _e("Go back", $this->template['module']) ?></a>
	</p>

