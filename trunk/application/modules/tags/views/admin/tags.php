<!-- [Content] start -->
<div class="content wide">

<h1 id="page"><?php echo __("Tags Administration", $this->template['module'])?></h1>

<ul class="manage">
	<li><a href="<?php echo site_url('admin/tags/settings')?>"><?php echo __("Settings", $this->template['module'])?></a></li>
	<li><a href="<?php echo site_url('admin')?>" class="last"><?php echo __("Cancel", $this->template['module'])?></a></li>
</ul>
		
<br class="clearfloat" />

<hr />

<?php if (isset($notice) || $notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php echo $notice;?></p>
<?php endif;?>

<table class="page-list">
	<thead>
		<tr>
				<th width="3%" class="center">#</th>
				<th width="30%"><?php echo __("Tag", $this->template['module'])?></th>
				<th width="40%"><?php echo __("Link", $this->template['module'])?></th>
				<th width="20%"><?php echo __("Action", $this->template['module'])?></th>
				<th width="7%" class="last center"><?php echo __("Count", $this->template['module'])?></th>
		</tr>
	</thead>
	<tbody>
<?php if ($rows && count($rows) > 0 ) : ?>
<?php $i = 1; foreach ($rows as $row): ?>
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>

		<tr class="<?php echo $rowClass?>">
				<td class="center"><?php echo $i?></td>
				<td><?php echo ($row['tag'])?></td>
				<td><a href='<?php echo site_url('tags/' . $row['tag']) ?>'><?php echo site_url('tag/' . $row['tag']) ?></a></td>
				<td><a href="<?php echo site_url('admin/tags/delete/'.$row['tag'])?>"><?php _e("Delete", $this->template['module']) ?></a></td>
				<td class="center"><?php echo $row['ctag']?></td>
		</tr>
<?php $i++; endforeach;?>
<?php endif; ?>
	</tbody>
</table>
<?php echo $pager?>



</div>
<!-- [Content] end -->
