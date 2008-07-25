<!-- [Content] start -->
<div class="content wide">

<h1 id="page"><?=__("Comments")?></h1>

<ul class="manage">
	<li><a href="<?=site_url('admin/news/settings#two')?>" class="first"><?=__("Settings")?></a></li>
	<li><a href="<?=site_url('admin')?>" class="last"><?=__("Cancel")?></a></li>
</ul>
		
<br class="clearfloat" />

<hr />

<?php if (isset($notice) || $notice = $this->session->flashdata('notification')):?>
<p class="notice"><?=$notice;?></p>
<?php endif;?>

<table class="page-list">
	<thead>
		<tr>
				<th width="3%" class="center">#</th>
				<th width="24%"><?=__("Title")?></th>
				<th width="10%"><?=__("Author")?></th>
				<th width="10%"><?=__("Email")?></th>
				<th width="10%"><?=__("Ip")?></th>
				<th width="40%" colspan="4"><?=__("Action")?></th>
				<th width="3%" class="last center">ID</th>
		</tr>
	</thead>
	<tbody>
<?php if ($rows) : ?>
<?php $i = 1; foreach ($rows as $row): ?>
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
		<tr class="<?=$rowClass?>">
				<td class="center"><?=$i?></td>
				<td><?=(word_limiter($row['body'], 4))?></td>
				<td><?=$row['author']?></td>
				<td><?=$row['email']?></td>
				<td><?=$row['ip']?></td>
				<td><a href="<?=site_url('news/'. $row['uri'])?>" rel="external"><?=__("View")?></a></td>
				<td><a href="<?=site_url('admin/'.$module.'/create/'.$row['id'])?>"><?=__("Edit")?></a></td>
				<td><a href="<?=site_url('admin/'.$module.'/create/'.$row['id'])?>"><?=__("Edit")?></a></td>
				<td><a href="<?=site_url('admin/'.$module.'/delete/'.$row['id'])?>"><?=__("Delete")?></a></td>
				<td class="center"><?=$row['id']?></td>
		</tr>
<?php $i++; endforeach;?>
<?php endif; ?>
	</tbody>
</table>
<?=$pager?>
</div>
<!-- [Content] end -->
