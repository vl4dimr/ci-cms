<h3>Latest Blog Entries</h3>
<ul>
<?php foreach ($posts as $post): ?>
	<li><a href="<?=site_url('blog/'.date('Y/m', $post['date_posted']).'/'.$post['uri'])?>"><?=$post['title']?></a></li>
<?php endforeach; ?>
</ul>
