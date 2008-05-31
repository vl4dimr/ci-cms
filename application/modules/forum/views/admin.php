<!-- [Content] start -->
<div class="content wide">

<h1 id="page">Page Admininistration</h1>

<ul class="manage">
	<li><a href="<?=site_url('admin/page/create')?>">Create new Page</a></li>
	<li><a href="<?=site_url('admin')?>" class="last">Cancel</a></li>
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
				<th width="27%">Title</th>
				<th width="27%">SEF adress</th>
				<th width="10%">Page status</th>
				<th width="10%">View page</th>
				<th width="10%">Edit page</th>
				<th width="10%">Delete page</th>
				<th width="3%" class="last center">ID</th>
		</tr>
	</thead>
	<tbody>
<?php $i = 1; foreach ($pages as $page): ?>
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
		<tr class="<?=$rowClass?>">
				<td class="center"><?=$i?></td>
				<td><?=$page['menu_title']?></td>
				<td><?=$page['uri']?></td>
				<td><?php if ($page['active']==1): echo 'Published'; else: echo 'Draft'; endif;?></td>
				<td><a href="<?=site_url($page['uri'])?>" rel="external">View</a></td>
				<td><a href="<?=site_url('admin/'.$module.'/edit/'.$page['id'])?>">Edit</a></td>
				<td><a href="<?=site_url('admin/'.$module.'/delete/'.$page['id'])?>">Delete</a></td>
				<td class="center"><?=$page['id']?></td>
		</tr>
<?php $i++; endforeach;?>
	</tbody>
</table>

</div>
<!-- [Content] end -->
