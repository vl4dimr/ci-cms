<h1><?php echo $title?></h1>
<p>
<?php if($rows) : ?>
<?php $i = 0; foreach($rows as $row) : ?>
<?php if($i > 0): ?>, <?php endif; ?>
<?php echo anchor('news/tag/' . $row['uri'], $row['tag']) ?>
<?php $i++; endforeach; ?>
<?php endif; ?>
</p>