<!-- [Content] start -->
<div class="content wide">

<h1 id="navigation">Navigation</h1>

<hr />

<?php if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?=$notice;?></p>
<?php endif;?>

<p>Welcome to the navigation admin</p>

<ul id="navigation_list">
	<?php foreach ($navigation as $nav):?>
		<li id="item_<?=$nav['id']?>"><div class="handle"></div><?=$nav['title']?></li>
	<?php endforeach;?>
</ul>
<script type="text/javascript">
	function updateOrder(){
	  var options = {
	    method : 'post',
	    parameters : Sortable.serialize('navigation_list')
	  };
		new Ajax.Request('<?=site_url('admin/nav_ajax_reorder')?>', options);
	}
	Sortable.create('navigation_list', {constraint:'vertical', handle:'handle', onUpdate : updateOrder});
</script>

</div>
<!-- [Content] end -->
