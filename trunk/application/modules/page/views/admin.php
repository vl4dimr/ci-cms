<!-- [Content] start -->
<div class="content wide">

<h1 id="page"><?php echo __("Page Admininistration", $module) ?></h1>

<ul class="manage">
	<li><a href="<?=site_url('admin/page/settings')?>"><?=__("Settings", $this->template['module'])?></a></li>
	<li><a href="<?=site_url('admin/page/create')?>"><?php _e("Create new Page", $this->template['module'])?></a></li>
	<li><a href="<?=site_url('admin')?>" class="last"><?php _e("Cancel", $this->template['module'])?></a></li>
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
				<th width="27%"><?=__("Title", $this->template['module'])?></th>
				<th width="27%"><?=__("SEF address", $this->template['module'])?></th>
				<th width="5%"><?=__("Status", $this->template['module'])?></th>
				<th width="5%"><?=__("Ordering", $this->template['module'])?></th>
				<th width="27%" colspan="3"><?=__("Action", $this->template['module'])?></th>
				<th width="6%" class="last center"><?=__("Hits", $this->template['module'])?></th>
		</tr>
	</thead>
	<tbody>
<?php if ($pages) : ?>
<?php $i = 1; foreach ($pages as $page): ?>
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
		<tr class="<?=$rowClass?>">
				<td class="center"><?=$i?></td>
				<td><?=($page['level'] > 0) ? "|".str_repeat("__", $page['level']): ""?> <?=(strlen($page['title']) > 20? substr($page['title'], 0,20) . '...': $page['title'])?></td>
				<td><?=$page['uri']?></td>
				<td><?php if ($page['active']==1): echo 'Published'; else: echo 'Draft'; endif;?></td>
				<td>
				<a href="<?=site_url('admin/page/move/up/'. $page['id'])?>"><img src="<?=site_url('application/views/admin/images/moveup.gif')?>" width="16" height="16" title="<?=__("Move up", $this->template['module'])?>" alt="<?=__("Move up", $this->template['module'])?>"/></a>
				<a href="<?=site_url('admin/page/move/down/'. $page['id'])?>"><img src="<?=site_url('application/views/admin/images/movedown.gif')?>" width="16" height="16" title="<?=__("Move down", $this->template['module'])?>" alt="<?=__("Move down", $this->template['module'])?>"/></a></td>
				<td><a href="<?=site_url($page['uri'])?>" rel="external">View</a></td>
				<td><a href="<?=site_url('admin/'.$module.'/edit/'.$page['id'])?>">Edit</a></td>
				<td><a href="<?=site_url('admin/'.$module.'/delete/'.$page['id'])?>">Delete</a></td>
				<td class="center"><?=$page['hit']?></td>
		</tr>
<?php $i++; endforeach;?>
<?php endif; ?>
	</tbody>
</table>
<?=$pager?>
</div>
<!-- [Content] end -->
