
<h1><?=$page['title']?></h1>
<?php
	if($page_break_pos = strpos($page['body'], "<!-- page break -->"))
	{
		$page['body'] = substr($page['body'], $page_break_pos + 19);
	}
?>	
<?=$page['body']?>
