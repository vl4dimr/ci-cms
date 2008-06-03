<!-- [Content] start -->
<div class="content wide">

<h1 id="page"><?=__("Language administration")?></h1>

<ul class="manage">
	<li><a href="<?=site_url('admin/language/settings')?>"><?=__("Settings")?></a></li>
	<li><a href="<?=site_url('admin/language/add')?>"><?=__("Add new language")?></a></li>
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
				<th width="5%" class="center">#</th>
				<th width="15%"><?=__("Code")?></th>
				<th width="20%"><?=__("Name")?></th>
				<th width="10%"><?=__("Ordering")?></th>
				<th width="15%"><?=__("Default")?></th>
				<th width="10%"><?=__("Active")?></th>
				<th width="25%" colspan=3><?=__("Action")?></th>
		</tr>
	</thead>
	<tbody>
<?php $i = 1; foreach ($langs as $lang): ?>
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
		<tr class="<?=$rowClass?>">
				<td class="center"><?=$i?></td>
				<td><?=$lang['code']?></td>
				<td><?=$lang['name']?></td>
				<td><?=$lang['ordering']?></td>
				<td><?php if ($lang['default']==1): echo __("Yes"); else: echo "<a href='" . site_url('admin/language/setdefault/'. $lang['id']) . "'>" . __("Make default") . "</a>" ;endif;?></td>
				<td><?php if ($lang['active']==1): echo __("Yes"); else: echo __("No"); endif;?></td>
				<td><?php if ($lang['active']==1): echo "<a href='" . site_url('admin/language/active/0/'. $lang['id']) . "'>" . __("Deactivate") . "</a>"; else: echo "<a href='" . site_url('admin/language/active/1/'. $lang['id']) . "'>" . __("Activate") . "</a>" ;endif;?></td>
				<td><a href='<?=site_url('admin/language/delete/'. $lang['id'])?>'><?=__("Delete")?></a></td>
				<td><a href='<?=site_url('admin/language/edit/'. $lang['id'])?>'><?=__("Edit")?></a></td>
		</tr>
<?php $i++; endforeach;?>
	</tbody>
</table>

</div>
<!-- [Content] end -->
