<!-- [Content] start -->


<h1><?=$title?></h1>

<?php if ($rows && count($rows) > 0) : ?>
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
<img src="<?=site_url('media/images/downloads/dir.gif')?>" >
<a href="<?=site_url('downloads/index/' . $row['id'])?>"><?=strip_tags($row['title'])?></a><br />
				<?=$row['summary']?>

<?php endforeach;?>
<?php endif; ?>

<?php unset($row); if ($files && count($files) > 0) : ?>
<?php foreach ($files as $row): ?>
<?php 
if($page_break_pos = strpos($row['desc'], "<!-- page break -->"))
{
	$row['summary'] = substr($row['desc'], 0, $page_break_pos);
}
else
{
	$row['summary'] = $row['desc'];
}

if ($row['file_link'])
{
	$row['link'] = $row['file_link'];
	$row['ext'] = $ext = substr(strrchr($row['file_link'], "."), 1);
	
}
else
{
	$row['ext'] = $ext = substr(strrchr($row['file'], "."), 1);
	$row['link'] = site_url('downloads/document/get/' . $row['file']);
}
?>		
<img src="<?=site_url('media/images/downloads/' . $row['ext'] . '.gif')?>" >
<a href="<?=$row['link']?>"><?=$row['title']?></a><br />
				<?=$row['summary']?>

<?php endforeach;?>
<?php endif; ?>


<?=$pager?>

<!-- [Content] end -->
