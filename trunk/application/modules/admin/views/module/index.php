<?php  if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- [Left menu] start -->
<!-- [Content] start -->
<div class="content wide">

<h1 id="page"><?=__("Modules administration", $this->template['module'])?></h1>

<ul class="manage">
	<li><a href="<?=site_url('admin')?>" class="last"><?=__("Cancel", $this->template['module'])?></a></li>
</ul>
		
<br class="clearfloat" />

<hr />


<?php if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?=$notice;?></p>
<?php endif;?>

<p><?=__("These are the modules uploaded in your site. Newly uploaded modules need to be installed. You can also deactivate, activate or desinstall all installed modules.", $this->template['module'])?>
<table class="page-list">
	<thead>
		<tr>
				<th width="3%" class="center">#</th>
				<th width="20%"><?=__("Name", $this->template['module'])?></th>
				<th width="37%"><?=__("Description", $this->template['module'])?></th>
				<th width="10%"><?=__("Version", $this->template['module'])?></th>				
				<th width="30%" colspan="3"><?=__("Action", $this->template['module'])?></th>
		</tr>
	</thead>
	<tbody>
<?php $i = 1; foreach ($modules as $module): ?>
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
		<tr class="<?=$rowClass?>">
				<td class="center"><?=$i?></td>
				<td><?=$module['name']?></td>
				<td><?=$module['description']?></td>
				<td><?=$module['version']?></td>
				<td>
				<?php if ($module['status'] == 1 && $module['ordering'] >= 100): ?>
				<a href="<?=site_url('admin/module/move/up/'. $module['name'])?>"><img src="<?=site_url('application/views/admin/images/moveup.gif')?>" width="16" height="16" title="<?=__("Move up", $this->template['module'])?>"/></a>
				<a href="<?=site_url('admin/module/move/down/'. $module['name'])?>"><img src="<?=site_url('application/views/admin/images/movedown.gif')?>" width="16" height="16" title="<?=__("Move down", $this->template['module'])?>"/></a>
				</td>
				<?php endif; ?>
				<td>
				<?php if ($module['status'] == 1 && $module['ordering'] >= 100): ?>
				<a href="<?=site_url('admin/module/deactivate/'. $module['name'])?>"><?=__("Deactivate", $this->template['module'])?></a>
				<?php elseif ($module['status'] == 0) : ?>
				<a href="<?=site_url('admin/module/activate/'. $module['name'])?>"><?=__("Activate", $this->template['module'])?></a>
				<?php endif; ?>
				</td>
				<td>
				<?php if ($module['status'] == 0  && $module['ordering'] >= 100): ?>
				<a href="<?=site_url('admin/module/uninstall/'. $module['name'])?>"><?=__("Uninstall", $this->template['module'])?></a>
				<?php elseif ($module['status'] == -1): ?>
				<a href="<?=site_url('admin/module/install/'. $module['name'])?>"><?=__("Install", $this->template['module'])?></a>
				<?php else: ?>
				<a href="<?=site_url('admin/module/update/'. $module['name'])?>"><?=__("Update", $this->template['module'])?></a>
				<?php endif; ?>
				</td>
		</tr>
<?php $i++; endforeach;?>
	</tbody>
</table>
<?//=$pager?>
</div>
<!-- [Content] end -->

