<!-- [Content] start -->
<div class="content wide">

<h1 id="page"><?php echo __("Photo Gallery Administration")?></h1>

<ul class="manage">
	<li><a href="<?php echo site_url('admin/gallery/settings')?>"><?php echo __("Settings")?></a></li>
	<li><a href="<?php echo site_url('admin/gallery/create')?>"><?php echo __("Add a new image")?></a></li>
	<li><a href="<?php echo site_url('admin')?>" class="last"><?php echo __("Cancel")?></a></li>
</ul>
		
<br class="clearfloat" />

<hr />

<?php if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php echo $notice;?></p>
<?php endif;?>

<table class="page-list">
	<thead>
		<tr>
				<th width="5%" class="center">#</th>
				<th width="15% class="center"><?php echo __("Image Name")?></th>
                <th width="15%"><?php echo __("Name")?></th>
				<th width="15%"><?php echo __("Category")?></th>
                <th width="20%"><?php echo __("Caption")?></th>
                <th width="5%"><?php echo __("Move")?></th>
				<th width="25%" colspan="3"><?php echo __("Action")?></th>
		</tr>
	</thead>
	<tbody>
<?php $i = 1; foreach ($images as $image): ?>
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
		<tr class="<?php echo $rowClass?>">
				<td class="center"><?php echo $i?></td>
                <td calss="center"><img height="75px" width="100px" src="<?php echo $image['image_link']?>"/></td>
				<td><?php echo $image['name']?></td>
				<td><?php echo $image['category']?></td>
                <td><?php echo $image['caption']?></td>
				<td>
				<?php if ($image['ordering']): ?>
				<a href="<?php echo site_url('admin/gallery/move/up/'. $image['id']);?>"><img src="<?php echo base_url().'application/views/admin/images/moveup.gif';?>" width="16" height="16" title="<?php echo __("Move up", $this->template['module'])?>"/></a>
				<a href="<?php echo site_url('admin/gallery/move/down/'. $image['id']);?>"><img src="<?php echo base_url().'application/views/admin/images/movedown.gif';?>" width="16" height="16" title="<?php echo __("Move down", $this->template['module'])?>"/></a>
				<?php endif; ?>
                </td>
				<td><a href="<?php echo site_url('admin/'.$module.'/edit/'.$image['id'])?>"><?php echo __("Edit")?></a></td>
				<td><a href="<?php echo site_url('admin/'.$module.'/delete/'.$image['id'])?>"><?php echo __("Delete")?></a></td>
                <td>
                <?php if($image['default']): ?>
                <a href="<?php echo site_url('admin/'.$module.'/makedefault/'.$image['id'].'/0'); ?>"><?php echo __("Clear")?></a>
                <?php else: ?>
                <a href="<?php echo site_url('admin/'.$module.'/makedefault/'.$image['id'].'/1'); ?>"><?php echo __("Default")?></a>
                <?php endif; ?>
                </td>
		</tr>
<?php $i++; endforeach;?>
	</tbody>
</table>
<?php echo $pager?>
</div>
<!-- [Content] end -->
