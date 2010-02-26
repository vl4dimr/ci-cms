<login><?php foreach($data as $key => $val): ?>
<<?php echo $key ?>><?php echo htmlentities($val) ?></<?php echo $key ?>>
<?php endforeach;?></login>