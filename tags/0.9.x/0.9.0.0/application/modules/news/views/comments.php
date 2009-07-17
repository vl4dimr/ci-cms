<script type="text/javascript">
$(document).ready(function(){
	$(".delete").click(function(){
		if (confirm("<?=addslashes(__("Confirm delete?", $this->template['module']))?>", $this->template['module']))
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

<h1 id="page"><?=__("Comments", $this->template['module'])?></h1>

<ul class="manage">
	<li><a href="<?=site_url('admin/news/settings#two')?>" class="first"><?=__("Settings", $this->template['module'])?></a></li>
	<li><a href="<?=site_url('admin/news')?>" class="last"><?=__("Cancel", $this->template['module'])?></a></li>
</ul>
		
<br class="clearfloat" />

<hr />

<?php if (isset($notice) || $notice = $this->session->flashdata('notification')):?>
<p class="notice"><?=$notice;?></p>
<?php endif;?>
<form action="<?=site_url('admin/news/comments')?>" name="filter" method='post'>
<label for="status"><?=__("Show:", $this->template['module'])?></label>
<select name="status" style="input-select">
<option><?=__("All", $this->template['module'])?>
<option value="1" <?=($status == '1')?"selected": ""?>><?=__("Approved", $this->template['module'])?></option>
<option value="0" <?=($status == '0')?"selected": ""?>><?=__("Suspended", $this->template['module'])?></option>
<input type="submit" name="submit" value="Ok"? />
</select>
</form>
<table class="page-list">
	<thead>
		<tr>
				<th width="3%" class="center">#</th>
				<th width="34%"><?=__("Title", $this->template['module'])?></th>
				<th width="10%"><?=__("Author", $this->template['module'])?></th>
				<th width="20%"><?=__("Email", $this->template['module'])?></th>
				<th width="10%"><?=__("Ip", $this->template['module'])?></th>
				<th width="20%" colspan="2"><?=__("Action", $this->template['module'])?></th>
				<th width="3%" class="last center">ID</th>
		</tr>
	</thead>
	<tbody>
<?php if ($rows) : ?>
<?php $i = 1; foreach ($rows as $row): ?>
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
		<tr class="<?=$rowClass?>" <?=($row['status']==0)?"style='color: #AAAAAA'":""?>>
				<td class="center"><?=$i?></td>
				<td><a href="<?php echo site_url()?>" target="_blank"><?=(word_limiter($row['body'], 4))?></a></td>
				<td><?=$row['author']?></td>
				<td><?=$row['email']?></td>
				<td><?=$row['ip']?></td>
				<?php if ($row['status'] == 0):?>
				<td><a href="<?=site_url('admin/news/comments/approve/'.$row['id'])?>"><?=__("Approve", $this->template['module'])?></a></td>
				<?php else: ?>
				<td><a href="<?=site_url('admin/news/comments/suspend/'.$row['id'])?>"><?=__("Suspend", $this->template['module'])?></a></td>			
				<?php endif; ?>
				<td><a class='delete' href="<?=site_url('admin/news/comments/delete/'.$row['id'])?>"><?=__("Delete", $this->template['module'])?></a></td>
				<td class="center"><?=$row['id']?></td>
		</tr>
<?php $i++; endforeach;?>
<?php endif; ?>
	</tbody>
</table>
<?=$pager?>
</div>
<!-- [Content] end -->
