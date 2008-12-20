<!-- [Content] start -->
<div class="content wide">

<h1 id="page"><?=__("News Administration", $this->template['module'])?></h1>

<ul class="manage">
	<li><a href="<?=site_url('admin/news/settings')?>"><?=__("Settings", $this->template['module'])?></a></li>
	<li><a href="<?=site_url('admin/news/comments')?>"><?=__("Manage comments", $this->template['module'])?></a></li>
	<li><a href="<?=site_url('admin/news/category')?>"><?=__("Manage categories", $this->template['module'])?></a></li>
	<li><a href="<?=site_url('admin/news/create')?>"><?=__("Create news", $this->template['module'])?></a></li>
	<li><a href="<?=site_url('admin')?>" class="last"><?=__("Cancel", $this->template['module'])?></a></li>
</ul>
		
<br class="clearfloat" />

<hr />

<?php if (isset($notice) || $notice = $this->session->flashdata('notification')):?>
<p class="notice"><?=$notice;?></p>
<?php endif;?>

<table class="page-list">
	<thead>
		<tr>
				<th width="3%" class="center">ID</th>
				<th width="40%"><?=__("Title", $this->template['module'])?></th>
				<th width="10%"><?=__("Ordering", $this->template['module'])?></th>
				<th width="10%"><?=__("Status", $this->template['module'])?></th>
				<th width="30%" colspan="3"><?=__("Action", $this->template['module'])?></th>
				<th width="7%" class="last center"><?=__("Hits", $this->template['module'])?></th>
		</tr>
	</thead>
	<tbody>
<?php if ($rows) : ?>
<?php $i = 1; foreach ($rows as $row): ?>
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
		<tr class="<?=$rowClass?>">
				<td class="center"><?=$row['id']?></td>
				<td><?=$row['title']?></td>
				<td><a href="<?=site_url('admin/news/move/up/'. $row['id'])?>"><img src="<?=site_url('application/views/admin/images/moveup.gif')?>" width="16" height="16" title="<?=__("Move up", $this->template['module'])?>" alt="<?=__("Move up", $this->template['module'])?>"/></a>
				<a href="<?=site_url('admin/news/move/down/'. $row['id'])?>"><img src="<?=site_url('application/views/admin/images/movedown.gif')?>" width="16" height="16" title="<?=__("Move down", $this->template['module'])?>" alt="<?=__("Move down", $this->template['module'])?>"/></a></td>
				<td><?php if ($row['status']==1): echo __('Published'); else: echo __('Draft'); endif;?></td>
				<td><a href="<?=site_url('news/'. $row['uri'])?>" rel="external"><?=__("View", $this->template['module'])?></a></td>
				<td><a href="<?=site_url('admin/'.$module.'/create/'.$row['id'])?>"><?=__("Edit", $this->template['module'])?></a></td>
				<td><a href="<?=site_url('admin/'.$module.'/delete/'.$row['id'])?>"><?=__("Delete", $this->template['module'])?></a></td>
				<td class="center"><?=$row['hit']?></td>
		</tr>
<?php $i++; endforeach;?>
<?php endif; ?>
	</tbody>
</table>
<?=$pager?>
</div>
<!-- [Content] end -->
