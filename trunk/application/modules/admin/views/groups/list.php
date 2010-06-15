<div class="content wide">

<h1 id="page"><?php echo $title?></h1>

<ul class="manage">
	<li><a href="<?php echo site_url('admin/groups/create/' . $start)?>" class="first"><?php echo __("Add new", $module)?></a></li>
	<li><a href="<?php echo site_url('admin')?>" class="last"><?php echo __("Cancel", $module)?></a></li>
</ul>
		
<br class="clearfloat" />

<hr />


<?php if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php echo $notice;?></p>
<?php endif;?>

<table class="page-list" >
	<thead>
		<tr>
				<th width="3%" class="center">#</th>
				<th width="37%"><?php echo __("Name", $module)?></th>
				<th width="20%"><?php echo __("Members", $module)?></th>
				<th width="20%"><?php echo __("Owner", $module)?></th>
				<th width="20%" colspan="2"><?php echo __("Action", $module)?></th>
		</tr>
	</thead>
<tbody>
<?php if($rows) :?>
<?php $i = 1; foreach ($rows as $row): ?>
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
<tr class="<?php echo $rowClass ?>">
	<td valign="top" width="20" align="right"><?php echo ($i + $start) ?>.</td>
	
	<td valign="top" >
	<?php echo $row['g_name'] ?>
	</td>
	<td valign="top">
	<?php echo anchor('admin/groups/members/list/' .$row['g_id'] . '/' . $start, __("View", $module) . " (". $row['cnt'] . ")") ?>
	</td>
	<td valign="top">
	<?php echo $row['g_owner'] ?>
	</td>

	<?php if($this->user->level['admin'] >= LEVEL_EDIT) : ?>
		<td valign="top">
		<?php echo  anchor('admin/groups/edit/' . $start . '/' . $row['g_id'], __("Edit", $module)) ?>
		</td>		
		<?php if($this->user->level['admin'] >= LEVEL_DEL) : ?>
		<td valign="top">
		<?php echo  anchor('admin/groups/delete/' . $start . '/' . $row['g_id'], __("Delete", $module)) ?>
		</td>		
		<?php endif; ?>
	<?php endif; ?>
</tr>
<?php $i++; endforeach;?>
<?php endif; ?>
</tbody>
</table>

<?php echo $pager?>

</div>
