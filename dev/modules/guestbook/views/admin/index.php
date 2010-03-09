<!-- [Content] start -->
<div class="content wide">

<h1 id="page"><?php echo $title ?></h1>

<ul class="manage">
	<li><a href="<?php echo site_url('admin/guestbook/settings')?>"><?php echo __("Settings", $module)?></a></li>
	<li><a href="<?php echo site_url('admin')?>" class="last"><?php echo __("Cancel", $module)?></a></li>
</ul>
		
<br class="clearfloat" />

<hr />

<?php if (isset($notice) || $notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php echo $notice;?></p>
<?php endif;?>

<table class="page-list">
	<thead>
		<tr>
				<th width="3%" class="center">ID</th>
				<th width="10%"><?php echo __("Date", $module)?></th>
				<th width="17%"><?php echo __("Name", $module)?></th>
				<th width="30%"><?php echo __("Message", $module)?></th>
				<th width="10%"><?php echo __("IP", $module)?></th>
				<th width="30%" colspan="2"><?php echo __("Action", $module)?></th>
		</tr>
	</thead>
	<tbody>

<?php if ($rows) : ?>
<?php $i = 1; foreach ($rows as $row): ?>
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
		<tr class="<?php echo $rowClass?>">
				<td class="center"><?php echo $row['id']?></td>
				<td><?php echo date("d/m/Y", $row['g_date'])?></td>
				<td><?php echo strip_tags($row['g_name'])?></td>
				<td><?php echo substr(strip_tags($row['g_msg']), 0, 60) ?>...</td>
				<td><?php echo $row['g_ip'] ?></td>
				<td><a href="<?php echo site_url('admin/'.$module.'/edit/'.$row['id'])?>"><?php echo __("Edit", $module)?></a></td>
				<td><a href="<?php echo site_url('admin/'.$module.'/delete/'.$row['id'])?>"><?php echo __("Delete", $module)?></a></td>
		</tr>
<?php $i++; endforeach;?>
<?php endif; ?>
	</tbody>
</table>
<?php echo $pager?>
</div>
<!-- [Content] end -->



