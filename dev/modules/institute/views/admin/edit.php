
<div class="leftmenu">

	<h1 id="pageinfo"><?php echo __("Information", $this->template['module'])?></h1>
	
	<ul id="tabs" class="quickmenu">
		<li><a href="#one"><?php echo __("Details", $this->template['module'])?></a></li>
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">
<form class="edit" id="topic_create" method="post" action="<?php echo site_url('admin/guestbook/save') ?>">
<input type="hidden" name="id" value="<?php echo $row['id'] ?>" />
<h1 id="edit"><?php echo $title ?></h1>
		<ul>
			<li><input type="submit" name="submit" value="<?php echo __("Save", $this->template['module'])?>" class="input-submit" /></li>
			<li><a href="<?php echo site_url('admin/guestbook')?>" class="input-submit last"><?php echo __("Cancel", $this->template['module'])?></a></li>
		</ul>

<br class="clearfloat" />
<hr />

<label for="g_name"><?php echo __("Name:", $module) ?></label>
<input type="text" name="g_name" id="g_name" class="input-text" value="<?php echo $row['g_name'] ?>"/><br/>

<label for="g_email"><?php echo __("Email:", $module) ?></label>
<input type="text" name="g_email" id="g_email" class="input-text" value="<?php echo $row['g_email'] ?>"/><br/>

<label for="g_msg"><?php echo __("Message:", $module) ?></label>
<textarea name="g_msg" id="g_msg" class="input-textarea"><?php echo $row['g_msg'] ?></textarea><br/>

</form>
</div>