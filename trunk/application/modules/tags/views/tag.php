<!-- [Content] start -->
<h1><?php echo __("Tag:", $this->template['module']) ?> <?php echo $tag ?></h1>
<div id="tags">
<?php if ($rows) : ?>
<?php

echo sprintf(__("All contents with tag %s", $this->template['module']), $tag); 
?>
<?php  foreach ($rows as $row) : ?>
<div id="title"><a href="<?php echo site_url($row['url']) ?>"><?php echo $row['title'] ?></a></div>
<div id="summary"><?php echo $row['summary']?></div>
<?php endforeach; ?>
<?php else : ?>
<?php echo sprintf(__("No element found with the tag %s", $this->template['module']), $tag) ?>
<?php endif; ?>
</div>
<!-- [Content] end -->
