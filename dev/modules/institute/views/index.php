<h1><?php echo $title ?></h1>
<a href="#sign"><?php echo __("Sign our guestbook", $module) ?></a>
<?php if($rows): ?>
<?php $i = 0; foreach($rows as $row): ?>
<div class="gbbox">
<div class="title">
<?php echo date("d/m/Y", $row['g_date']) ?> - <?php echo strip_tags($row['g_name']) ?> 
</div>
<?php if($this->user->level['guestbook'] >= LEVEL_EDIT): ?>
<div class="admin-box">
<?php echo anchor('admin/guestbook/edit/' . $row['id'], __("Edit", $module)) ?>
<?php if($this->user->level['guestbook'] >= LEVEL_DEL): ?>
 | <?php echo anchor('admin/guestbook/delete/' . $row['id'], __("Delete", $module)) ?>
<?php endif; ?>
</div>
<?php endif; ?>
<div class="message">
<?php echo nl2br(strip_tags($row['g_msg'])) ?>
</div>
</div>
<?php $i++; endforeach; ?>
<?php endif; ?>
<div class="pager">
<?php echo $pager ?>
</div>

<a name="sign"> </a>
<?php $this->load->module_view('guestbook', 'sign'); ?>
