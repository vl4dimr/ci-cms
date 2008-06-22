<!-- [Content] start -->
<div class="content wide">

<h1 id="navigation"><?=__("Navigation administration")?></h1>

<ul class="manage">
	<li><a href="<?=site_url('admin/navigation/create')?>" class="first"><?=__("Create new")?></a></li>
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
				<th width="27%"><?=__("Menu")?></th>
				<th width="40%"><?=__("Target")?></th>
				<th width="30%" colspan="3"><?=__("Action")?></th>
		</tr>
	</thead>
	<tbody>
<?// var_dump($navigation) ?>	
<?php $i = 1; foreach ($navigation as $nav): ?>
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
		<tr class="<?=$rowClass?>">
				<td class="center"><?=$i?></td>
				<td><?=($nav['level'] > 0) ? "|".str_repeat("__", $nav['level']): ""?> <?=$nav['title']?></td>
				<td><?=$nav['uri']?></td>
				<td>
				<a href="<?=site_url('admin/navigation/move/up/'. $nav['id'])?>"><img src="<?=site_url('application/views/' . $this->system->theme . '/admin/images/moveup.gif')?>" width="16" height="16" title="<?=__("Move up")?>"/></a>
				<a href="<?=site_url('admin/navigation/move/down/'. $nav['id'])?>"><img src="<?=site_url('application/views/' . $this->system->theme . '/admin/images/movedown.gif')?>" width="16" height="16" title="<?=__("Move down")?>"/></a></td>
				<td><a href="<?=site_url('admin/navigation/edit/'.$nav['id'])?>"><?=__("Edit")?></a></td>
				<td><a href="<?=site_url('admin/navigation/delete/'.$nav['id'])?>"><?=__("Delete")?></a></td>
		</tr>
<?php $i++; endforeach;?>
	</tbody>
</table>
<?=//$pager?>
</div>
<!-- [Content] end -->
