<h3><?=__("Language list", $this->template['module'])?></h3>
<? if (is_array($langs)) : ?>
<ul>
	<? foreach ($langs as $lang) :?>
	<li><a href="<?=site_url($lang['code'])?>"><?=$lang['name']?></a></li>
	<? endforeach; ?>
</ul>
<? endif; ?>