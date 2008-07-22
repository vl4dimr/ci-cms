
<h1><?=$news['title']?></h1>
<?php
	if($page_break_pos = strpos($news['body'], "<!-- page break -->"))
	{
		$news['body'] = substr($news['body'], $page_break_pos + 19);
	}
?>	
<?=$news['body']?>
