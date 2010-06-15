<div class="content wide">

<h1 id="page"><?php echo $title?></h1>

<ul class="manage">
	<li><a href="<?php echo site_url('admin/groups/members/add/' . $rows['g_id'])?>" class="first"><?php echo __("Add member", $module)?></a></li>
	<li><a href="<?php echo site_url('admin/groups/index/' . $start)?>" class="last"><?php echo __("Cancel", $module)?></a></li>
</ul>
		
<br class="clearfloat" />

<hr />
<div class="group-info">
<b><?php echo __("Group name:", $module) ?></b> <?php echo strip_tags($rows['g_name']) ?><br />
<?php if($rows['g_desc']) : ?>
<b><?php echo __("Group description:", $module) ?></b> <br/> <?php echo strip_tags($rows['g_desc']) ?>
<?php endif; ?>
</div>
<?php if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php echo $notice;?></p>
<?php endif;?>

<table class="page-list" >
	<thead>
		<tr>
				<th width="3%" class="center">#</th>
				<th width="20%"><?php echo __("Joined", $module)?></th>
				<th width="37%"><?php echo __("Username", $module)?></th>
				<th width="20%"><?php echo __("From", $module)?></th>
				<th width="20%"><?php echo __("To", $module)?></th>
				<th width="20%" colspan="2"><?php echo __("Action", $module)?></th>
		</tr>
	</thead>
<tbody>
<?php if($rows['members']) : ?>
<?php $i = 1; foreach ($rows['members'] as $row): ?>
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
<tr class="<?php echo $rowClass ?>">
	<td valign="top" width="20" align="right"><?php echo ($i + $start) ?>.</td>
	<td valign="top" >
	<?php echo date("d/m/Y", $row['g_date']) ?>
	</td>
	<td valign="top" >
	<?php echo anchor('admin/groups/user/' . $row['g_user'],  $row['g_user']) ?>
	</td>
	<td valign="top" >
	<?php echo ($row['g_from'] == 0) ? __("Unlimited", $module) : date("d/m/Y", $row['g_from']) ?>
	</td>
	<td valign="top" >
	<?php echo ($row['g_to'] == 0) ? __("Unlimited", $module) : date("d/m/Y", $row['g_to']) ?>
	</td>
	<?php if($this->user->level['admin'] >= LEVEL_EDIT) : ?>
		<td valign="top">
		<?php echo  anchor('admin/groups/members/edit/' .$row['g_id'] . '/'. $row['g_user'], __("Edit", $module)) ?>
		</td>		
		<?php if($this->user->level['admin'] >= LEVEL_DEL) : ?>
		<td valign="top">
		<?php echo  anchor('admin/groups/members/delete/' .$row['g_id'] . '/' . $row['g_user'], __("Delete", $module)) ?>
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
