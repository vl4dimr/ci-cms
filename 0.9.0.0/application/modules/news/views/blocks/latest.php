<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<h3><?=__("Latest news", $this->template['module'])?></h3>
<ul>
<?php foreach ($rows as $row): ?>
	<li><a href="<?=site_url('news/'. $row['uri'])?>"><?=(($row['title']) > 20 )? substr($row['title'], 0, 20) . '...': $row['title']?></a></li>
<?php endforeach; ?>
</ul>