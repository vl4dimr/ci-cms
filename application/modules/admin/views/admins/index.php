<?php  if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- [Left menu] start -->
<!-- [Content] start -->
<div class="content wide">

<h1 id="page"><?php echo __("Administrators", $this->template['module'])?></h1>

<ul class="manage">
	<li><a href="<?php echo site_url('admin/admins/create')?>" class="first"><?php echo __("Add new", $this->template['module'])?></a></li>
	<li><a href="<?php echo site_url('admin')?>" class="last"><?php echo __("Cancel", $this->template['module'])?></a></li>
</ul>
		
<br class="clearfloat" />

<hr />


<?php if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php echo $notice;?></p>
<?php endif;?>

<p><?php echo __("Here you can see who is managing what.", $this->template['module'])?></p>

<?php if(is_array($admins)) : ?>
<table class="page-list">
	<thead>
		<tr>
				<th width="3%" class="center">#</th>
				<th width="20%"><?php echo __("Username", $this->template['module'])?></th>
				<th width="37%"><?php echo __("Level", $this->template['module'])?></th>
				<th width="40%" colspan="2"><?php echo __("Action", $this->template['module'])?></th>
		</tr>
	</thead>
	<tbody>
<?php $i = 1; $currentmodule = '' ?>

<?php foreach ($admins as $admin): ?>
<?php if ($admin['module'] != $currentmodule) : ?>
<?php $i = 1; $currentmodule = $admin['module'] ;?>
<tr>
	<td colspan="5"><strong><?php echo __("Module name", $this->template['module'])?>: <?php echo ucfirst($admin['module'])?></strong></td>
</tr>
<?php endif;?>
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
		<tr class="<?php echo $rowClass?>">
				<td class="center"><?php echo $i?></td>
				<td><?php echo $admin['username']?></td>
				<td><?php echo $admin['level']?></td>
				
				<td>
				
				<a href="<?php echo site_url('admin/admins/edit/'. $admin['id'])?>"><?php echo __("Edit", $this->template['module'])?></a>
				</td>
				<td>
				<a href="<?php echo site_url('admin/admins/delete/'. $admin['id'])?>"><?php echo __("Delete", $this->template['module'])?></a>
				</td>
		</tr>
<?php $i++; endforeach;?>
	</tbody>
</table>
<?//=$pager?>
<?php endif; ?>
</div>
<!-- [Content] end -->

