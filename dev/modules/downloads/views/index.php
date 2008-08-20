<!-- [Content] start -->


<h1><?=$title?></h1>

<?php if ($rows) : ?>
<?php foreach ($rows as $row): ?>
<?php 
if($page_break_pos = strpos($row['desc'], "<!-- page break -->"))
{
	$row['summary'] = substr($row['desc'], 0, $page_break_pos);
}
else
{
	$row['summary'] = $row['desc'];
}
?>		
<img src="<?=site_url('downloads/images/dir.gif')?>" >
<a href="<?=site_url('downloads/index/' . $row['id'])?>"><?=(strlen($row['title']) > 20? substr($row['title'], 0,20) . '...': $row['title'])?></a><br />
				<?=$row['summary']?>

<?php endforeach;?>
<?php endif; ?>

<?=$pager?>

<!-- [Content] end -->
