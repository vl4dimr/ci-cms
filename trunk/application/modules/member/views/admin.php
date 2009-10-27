<!-- [Content] start -->
<div class="content wide">

<h1 id="page"><?=__("Member administration", $this->template['module'])?></h1>

<ul class="manage">
	<li><a href="<?=site_url('admin/member/settings')?>"><?=__("Settings", $this->template['module'])?></a></li>
	<li><a href="<?=site_url('admin/member/create')?>"><?=__("Add a new user", $this->template['module'])?></a></li>
	<li><a href="<?=site_url('admin')?>" class="last"><?=__("Cancel", $this->template['module'])?></a></li>
</ul>
		
<br class="clearfloat" />

<hr />

<?php if (isset($notice) || $notice = $this->session->flashdata('notification')):?>
<p class="notice"><?=$notice;?></p>
<?php endif;?>
<table>
<form action="<?php echo site_url($this->uri->uri_string()) ?>" method="post">
<tr>
<td>
</td>
<td>
<input type="text" class="input-text" name="filter" value="<?php echo $this->input->post('filter') ?>" />
</td>
<td>
<input type="submit" class="input-submit" name="submit" value="<?php echo __("Search", $this->template['module'] ) ?>" />
</td>
</tr>
</form>
</table>
<?php if($members) : ?>
<table class="page-list">
	<thead>
		<tr>
				<th width="3%" class="center">#</th>
				<th width="27%"><?=__("Username", $this->template['module'])?></th>
				<th width="40%"><?=__("Email", $this->template['module'])?></th>
				<th width="30%" colspan="3"><?=__("Action", $this->template['module'])?></th>
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
				<a href="<?=site_url('admin/'.$module.'/status/'. $member['username'].'/'.$member['status'])?>"><?=($member['status'] == 'active')?__("Deactivate", $this->template['module']):__("Activate", $this->template['module'])?></a></td>
				<td><a href="<?=site_url('admin/'.$module.'/edit/'.$member['username'])?>"><?=__("Edit", $this->template['module'])?></a></td>
				<td><a href="<?=site_url('admin/'.$module.'/delete/'.$member['username'])?>"><?=__("Delete", $this->template['module'])?></a></td>
		</tr>
<?php $i++; endforeach;?>
	</tbody>
</table>
<?php else: ?>

<?php echo __("No member found", $module) ?>
<?php endif ; ?>
<?=$pager?>
</div>
<!-- [Content] end -->
