<h1><?php echo $title ?></h1>
<ul>
<?php if(!$student) : ?>
<li><?php echo anchor('institute/profile/create', __("Create your profile", $module)) ?></li>
<?php else : ?>
<li><?php echo anchor('institute/profile/edit', __("Modify your profile", $module)) ?></li>
<li><?php echo anchor('institute/myclass/register', __("Register for a class", $module)) ?></li>
<?php endif; ?>
</ul>

<h1><?php echo __("Your classes", $module) ?></h1>

<?php if(!$classes || empty($classes)): ?>
<p><?php echo __("You haven't registered for any class yet.", $module) ?></p>
<p><?php echo anchor('institute/myclass/register', __("Please click here to register for a class.", $module) ?></p>
<?php else: ?>
<ul>
<?php foreach($classes as $class) : ?>
<li><strong><?php echo anchor('institute/myclass/enter/' . $class['c_id'], $class['c_name']) ?>: </strong>
	<?php echo anchor('institute/myclass/enter/' . $class['c_id'], __("Enter class", $module)) ?> | 
	<?php echo anchor('institute/myclass/edit/' . $class['c_id'], __("Edit class", $module)) ?> | 
	<?php echo anchor('institute/myclass/delete/' . $class['c_id'], __("Remove class", $module)) ?> 
</li>
<?php endforeach; ?>
</ul>
<?php endif; ?> 