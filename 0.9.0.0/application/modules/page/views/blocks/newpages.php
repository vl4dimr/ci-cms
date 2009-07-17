<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<h3><?=__("Latest Pages", $this->template['module'])?></h3>
<ul>
<?php foreach ($pages as $page): ?>
	<li><a href="<?=site_url($page['uri'])?>"><?=(($page['title']) > 20 )? substr($page['title'], 0, 20) . '...': $page['title']?></a></li>
<?php endforeach; ?>
</ul>