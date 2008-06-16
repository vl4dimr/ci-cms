<!-- [Content] start -->
<div class="content wide">

<h1 id="page"><?=__("Member administration")?></h1>

<ul class="manage">
	<li><a href="<?=site_url('admin/member/settings')?>"><?=__("Settings")?></a></li>
	<li><a href="<?=site_url('admin/member/add')?>"><?=__("Add a new user")?></a></li>
	<li><a href="<?=site_url('admin')?>" class="last"><?=__("Cancel")?></a></li>
</ul>
		
<br class="clearfloat" />

<hr />

<?php if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?=$notice;?></p>
<?php endif;?>

<table class="page-list">
	<thead>
		<tr>
				<th width="3%" class="center">#</th>
				<th width="27%"><?=__("Username")?></th>
				<th width="40%"><?=__("Email")?></th>
				<th width="30%" colspan="3"><?=__("Action")?></th>
		</tr>
	</thead>
	<tbody>
<?php $i = 1; foreach ($members as $member): ?>
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
		<tr class="<?=$rowClass?>">
				<td class="center"><?=$i?></td>
				<td><?=$member['username']?></td>
				<td><?=$member['email']?></td>
				<td>
				<a href="<?=site_url('admin/'.$module.'/status/'. $member['username'].'/'.$member['status'])?>"><?=($member['status'] == 'active')?__("Deactivate"):__("Activate")?></a></td>
				<td><a href="<?=site_url('admin/'.$module.'/edit/'.$member['username'])?>"><?=__("Edit")?></a></td>
				<td><a href="<?=site_url('admin/'.$module.'/delete/'.$member['username'])?>"><?=__("Delete")?></a></td>
		</tr>
<?php $i++; endforeach;?>
	</tbody>
</table>
<?=$pager?>
</div>
<!-- [Content] end -->
