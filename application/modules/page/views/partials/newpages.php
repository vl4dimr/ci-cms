<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<h3><?=__("Latest Pages")?></h3>
<ul>
<?php foreach ($pages as $page): ?>
	<li><a href="<?=site_url($page['uri'])?>"><?=$page['menu_title']?></a></li>
<?php endforeach; ?>
</ul>