<!-- [Content] start -->
<div class="content wide">

<h1 id="blog">Blog Admininistration</h1>
 
<ul class="manage">
	<li><a href="<?=site_url('admin/blog/create')?>">Write Post</a></li>
	<li><a href="<?=site_url('admin/blog/comments')?>">Comments</a></li>
	<li><a href="<?=site_url('admin/blog/tags')?>">Tags</a></li>
	<li><a href="<?=site_url('admin/blog/settings')?>">Blog Settings</a></li>
	<li><a href="<?=site_url('admin')?>" class="last">Cancel</a></li>
</ul>

		<br class="clearfloat" />

		<hr />
		<?php if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?=$notice;?></p>
		<?php endif;?>
		
	<h2>Comments<?php if ($comments_pending > 0):?> <a href="<?=site_url('admin/blog/comments/moderate')?>">(<?=$comments_pending?> in Moderation)</a><?php endif;?></h2>
	
	<?php if (!empty($comments)):?>
		<ol>
			<?php foreach ($comments as $comment):?>
				<li>	
				<div class="comment">
					<p><strong><?=$comment['author']?></strong> | <a href="mailto:<?=$comment['email']?>"><?=$comment['email']?></a><?php if (!empty($comment['website'])):?> | <a href="<?=$comment['website']?>"><?=$comment['website']?></a><?php endif;?></p>
					<p class="comment">
						<?=$comment['body']?>
					</p>
					<p class="comment_actions">
						<?=date('M j, g:ia')?> - [ <a href="<?=site_url('admin/blog/comments/unapprove/'.$comment['id'])?>">Unapprove</a> | <a href="<?=site_url('admin/blog/comments/delete/'.$comment['id'])?>">Delete</a> | <a href="<?=site_url('admin/blog/comments/spam/'.$comment['id'])?>">Spam</a> ]
					</p>
				</div>
				</li>
			<?php endforeach;?>
		</ol>
	<?php else:?>
		<p>There are no comments</p>
	<?php endif;?>
		
</div>