<!-- [Content] start -->
<div class="content wide">

<h1 id="navigation">Navigation</h1>

<hr />


<p class="notice"><?=isset($notice)?$notice:'';?></p>


<p>Welcome to the navigation admin</p>

<ul id="navigation_list" style="list-style-position: inside; cursor: hand; cursor: pointer;">
	<?php foreach ($navigation as $nav):?>
		<li id="item_<?=$nav['id']?>" class='sortitem' style='padding: 5px; border: 1px solid #bbb; margin: 3px; width: 100px; background: #fff;'><?=$nav['title']?></li>
	<?php endforeach;?>
</ul>
<script type="text/javascript">
$("#navigation_list").sortable(
	{
		opacity: 0.7,
        revert: true,
        scroll: true ,
        update: function()
		{
			serial = $("#navigation_list").sortable("serialize");
			$.ajax({
				url: "<?=site_url('admin/nav_ajax_reorder')?>",
				type: "POST",
				data: serial,
				// complete: function(){},
				success: function(feedback){ $("p.notice").show().html(feedback).fadeOut(3000); }
				//success: function(feedback){ alert(feedback); }
				// error: function(){}
			});
			
		}
		
	});	
</script>

</div>
<!-- [Content] end -->
