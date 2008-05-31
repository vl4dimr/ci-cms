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
<div id="latest_comments">
	<h2>Latest Comments<?php if ($comments_pending > 0):?> <a href="<?=site_url('admin/blog/comments/moderate')?>">(<?=$comments_pending?> in Moderation)</a><?php endif;?> &raquo;</h2>
	<?php if (!empty($comments)): ?>
	<ul>
	<?php foreach ($comments as $comment): ?>
		<li>
			<a href="<?php echo (!empty($comment['website'])) ? $comment['website'] : 'mailto:'.$comment['email']?>"><?php echo $comment['author']?></a> on <a href="<?php echo site_url('blog/'.date('Y/m', $comment['post_date_posted']).'/'.$comment['post_uri'])?>"><?php echo $comment['post_title']?></a> (<a href="<?php echo site_url('admin/blog/comments/edit/'.$comment['id'])?>">edit</a>)
		</li>
	<?php endforeach; ?>
	</ul>
	<?php else:?>
		None
	<?php endif; ?>
</div>

<div id="latest_trackbacks">
	<h2>Latest Trackbacks <?php if ($trackbacks_pending > 0):?> <a href="<?=site_url('admin/blog/trackbacks/moderate')?>">(<?=$trackbacks_pending?> in Moderation)</a><?php endif;?>&raquo;</h2>
	<ul>
		<?php if (!empty($trackbacks)): ?>
		<ul>
		<?php foreach ($trackbacks as $trackback): ?>
			<li>
				<a href="<?php echo $trackback['urk']?>"><?php echo $trackback['title']?></a> on <a href="<?php echo site_url('blog/'.date('Y/m', $trackback['post_date_posted']).'/'.$trackback['post_uri'])?>"><?php echo $trackback['post_title']?></a> (<a href="<?php echo site_url('admin/blog/trackbacks/edit/'.$trackback['id'])?>">edit</a>)
			</li>
		<?php endforeach; ?>
		</ul>
		<?php else:?>
			None
		<?php endif; ?>
	</ul>
</div>

<h2>Posts</h2>
<table class="page-list">
	<thead>
		<tr>
				<th width="3%" class="center">#</th>
				<th width="27%">Title</th>
				<th width="27%">URI</th>
				<th width="10%">Post status</th>
				<th width="10%">View post</th>
				<th width="10%">Edit post</th>
				<th width="10%">Delete post</th>
				<th width="3%" class="last center">ID</th>
		</tr>
	</thead>
	<tbody>
<?php $i = 1; foreach ($posts as $post): ?>
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
		<tr class="<?=$rowClass?>">
				<td class="center"><?=$i?></td>
				<td><?=$post['title']?></td>
				<td><?=$post['uri']?></td>
				<td><?=ucfirst($post['status'])?></td>
				<td><a href="<?=site_url('blog/'.date('Y/m', $post['date_posted']).'/'.$post['uri'])?>" rel="external">View</a></td>
				<td><a href="<?=site_url('admin/'.$module.'/edit/'.$post['id'])?>">Edit</a></td>
				<td><a href="<?=site_url('admin/'.$module.'/delete/'.$post['id'])?>">Delete</a></td>
				<td class="center"><?=$post['id']?></td>
		</tr>
<?php $i++; endforeach;?>
	</tbody>
</table>

</div>
