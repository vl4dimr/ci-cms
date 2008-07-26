<script type="text/javascript">
$(document).ready(function(){
	$(".delete").click(function(){
		if (confirm("<?=addslashes(__("Confirm delete?"))?>"))
		{
		window.location = this+'/1';
		return false;
		} else {
		return false;
		}
	});
	/*handleDeleteImage();*/
});
</script>
<!-- [Content] start -->
<div class="content wide">

<h1 id="page"><?=__("Comments")?></h1>

<ul class="manage">
	<li><a href="<?=site_url('admin/news/settings#two')?>" class="first"><?=__("Settings")?></a></li>
	<li><a href="<?=site_url('admin/news')?>" class="last"><?=__("Cancel")?></a></li>
</ul>
		
<br class="clearfloat" />

<hr />

<?php if (isset($notice) || $notice = $this->session->flashdata('notification')):?>
<p class="notice"><?=$notice;?></p>
<?php endif;?>
<form action="<?=site_url('admin/news/comments')?>" name="filter" method='post'>
<label for="status"><?=__("Show:")?></label>
<select name="status" style="input-select">
<option><?=__("All")?>
<option value="1" <?=($status == '1')?"selected": ""?>><?=__("Approved")?></option>
<option value="0" <?=($status == '0')?"selected": ""?>><?=__("Suspended")?></option>
<input type="submit" name="submit" value="Ok"? />
</select>
</form>
<table class="page-list">
	<thead>
		<tr>
				<th width="3%" class="center">#</th>
				<th width="34%"><?=__("Title")?></th>
				<th width="10%"><?=__("Author")?></th>
				<th width="20%"><?=__("Email")?></th>
				<th width="10%"><?=__("Ip")?></th>
				<th width="20%" colspan="2"><?=__("Action")?></th>
				<th width="3%" class="last center">ID</th>
		</tr>
	</thead>
	<tbody>
<?php if ($rows) : ?>
<?php $i = 1; foreach ($rows as $row): ?>
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
		<tr class="<?=$rowClass?>" <?=($row['status']==0)?"style='color: #AAAAAA'":""?>>
				<td class="center"><?=$i?></td>
				<td><?=(word_limiter($row['body'], 4))?></td>
				<td><?=$row['author']?></td>
				<td><?=$row['email']?></td>
				<td><?=$row['ip']?></td>
				<?php if ($row['status'] == 0):?>
				<td><a href="<?=site_url('admin/news/comments/approve/'.$row['id'])?>"><?=__("Approve")?></a></td>
				<?php else: ?>
				<td><a href="<?=site_url('admin/news/comments/suspend/'.$row['id'])?>"><?=__("Suspend")?></a></td>			
				<?php endif; ?>
				<td><a class='delete' href="<?=site_url('admin/news/comments/delete/'.$row['id'])?>"><?=__("Delete")?></a></td>
				<td class="center"><?=$row['id']?></td>
		</tr>
<?php $i++; endforeach;?>
<?php endif; ?>
	</tbody>
</table>
<?=$pager?>
</div>
<!-- [Content] end -->
