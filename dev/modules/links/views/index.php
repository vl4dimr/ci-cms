<!-- [Content] start -->
<h1><?php echo $cat['title'] ?></h1>
<div class="adminbox">
<a href="<?php echo site_url('links/link/create/' . $cat['id']) ?>"><?php _e("Add link in this category", "links")?></a>
</div>
<?php if (isset($cat['description'])) : 
if($page_break_pos = strpos($cat['description'], "<!-- page break -->"))
{
	$cat['summary'] = substr($cat['description'], 0, $page_break_pos);
}
else
{
	$cat['summary'] = $cat['description'];
}
?>
<?php echo $cat['summary'] ?>
<?php endif ?>

<?php if ($rows && count($rows) > 0 ) : ?>
<h3><?=__("Categories in", 'links')?> <?=$cat['title']?></h3>

<?php $i = 1; foreach ($rows as $row): ?>
	
<a href="<?=site_url('links/index/' . $row['id']) ?>"><?=(strlen($row['title']) > 20? substr($row['title'], 0,20) . '...': $row['title'])?></a>, 
<?php $i++; endforeach;?>
<?php endif; ?>


<?php if ($links && count($links) > 0 ) : ?>

<h3><?=__("Links in", 'links')?> <?=$cat['title']?></h3>
<?php $i = 1; foreach ($links as $row): ?>
<div class="links-box">
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
<?php 

$row['description'] = strip_tags($row['description']);
$row['summary'] = (strlen($row['description']) > 250) ? substr($row['description'], 0, 250) . "... ": $row['description'];

?>		
		<div class="image">
		<img src="<?php echo ($row['icon'] == '') ? site_url("links/thumbnail/empty.gif") : site_url("links/thumbnail/" . $row['icon']) ; ?>" />
		</div>
		<div class="title <?php echo $rowClass?>"><a href="<?=site_url('links/link/show/'. $row['id'])?>"><?=(strlen($row['title']) > 20? substr($row['title'], 0,20) . '...': $row['title'])?></a></div>
		<div class="text <?php echo $rowClass?>"><?php echo $row['summary'] ?></div>
		<div class="url"><a target="_blank" href='<?=site_url('links/goto/'. $row['id'])?>'><?=$row['url']?></a></div>
		<div class="hit"><?php _e("Hit:", "links") ?> <?=$row['hit']?></div>
</div>		
<?php $i++; endforeach;?>
<?php endif; ?>

<?=$pager?>

<!-- [Content] end -->
