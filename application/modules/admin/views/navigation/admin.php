<!-- [Content] start -->
<div class="content wide">

<h1 id="navigation"><?php echo __("Navigation administration", 'admin')?></h1>

<ul class="manage">
	<li><a href="<?php echo site_url('admin/navigation/create')?>" class="first"><?php echo __("Create new", 'admin')?></a></li>
	<li><a href="<?php echo site_url('admin')?>" class="last"><?php echo __("Cancel", 'admin')?></a></li>
</ul>
		
<br class="clearfloat" />

<hr />

<?php if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php echo $notice;?></p>
<?php endif;?>

<table class="page-list">
	<thead>
		<tr>
				<th width="3%" class="center">#</th>
				<th width="27%"><?php echo __("Menu", 'admin')?></th>
				<th width="40%"><?php echo __("Target", 'admin')?></th>
				<th width="30%" colspan="3"><?php echo __("Action", 'admin')?></th>
		</tr>
	</thead>
	<tbody>
<?// var_dump($navigation) ?>
<?php if (isset($navigation)) : ?> 	
<?php $i = 1; foreach ($navigation as $nav): ?>
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
		<tr class="<?php echo $rowClass?>">
				<td class="center"><?php echo $i?></td>
				<td><?php echo ($nav['level'] > 0) ? "|".str_repeat("__", $nav['level']): ""?> <?php echo $nav['title']?></td>
				<td><?php echo $nav['uri']?></td>
				<td>
				<a href="<?php echo site_url('admin/navigation/move/up/'. $nav['id'])?>"><img src="<?php echo site_url('application/views/admin/images/moveup.gif')?>" width="16" height="16" title="<?php echo __("Move up", 'admin')?>" alt="<?php echo __("Move up", 'admin')?>"/></a>
				<a href="<?php echo site_url('admin/navigation/move/down/'. $nav['id'])?>"><img src="<?php echo site_url('application/views/admin/images/movedown.gif')?>" width="16" height="16" title="<?php echo __("Move down", 'admin')?>" alt="<?php echo __("Move down", 'admin')?>"/></a></td>
				<td><a href="<?php echo site_url('admin/navigation/edit/'.$nav['id'])?>"><?php echo __("Edit", 'admin')?></a></td>
				<td><a href="<?php echo site_url('admin/navigation/delete/'.$nav['id'])?>"><?php echo __("Delete", 'admin')?></a></td>
		</tr>
<?php $i++; endforeach;?>
<?php endif; ?>
	</tbody>
</table>

</div>
<!-- [Content] end -->
