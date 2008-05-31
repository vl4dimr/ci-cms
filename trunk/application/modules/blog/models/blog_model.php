<?php

	class Blog_Model extends Model {
		
		function Blog_Model()
		{
			parent::Model();
			
			$this->posts_table 			= 'blog_posts';
			$this->posts_tags_table 	= 'blog_posts_tags';
			$this->tags_table 			= 'blog_tags';
			$this->comments_table 		= 'blog_comments';
			$this->trackbacks_table 	= 'blog_trackbacks';
		}
		
		function latest_posts($num)
		{
			$this->db->where('status', 'published');
			$this->db->orderby('date_posted', 'desc');
			
			$query = $this->db->get($this->posts_table, $num);
			
			$posts = array();
			$built = array();
			
			if ( $query->num_rows() > 0 )
			{
				$posts = $query->result_array();
				
				foreach ($posts as $post)
				{
					if (!empty($post['id']))
					{
						$post['tags'] = $this->get_tags($post['id']);
					}
					
					$built[] = $post;
				}
			}
			
			return $built;
		}
		
		function latest_headlines($num)
		{
			$this->db->select('title, uri, date_posted');
			return $this->latest_posts($num);
		}
		
		function single_post($year, $month, $uri)
		{
			$next_month = $month + 1;
			
			$from =  mktime(0, 0, 0, $month, 0, $year);
			$to =  mktime(0, 0, 0, $next_month, 0, $year);
			
			$this->db->where('date_posted >', $from);
			$this->db->where('date_posted <', $to);
			$this->db->where('uri', $uri);
			
			$query = $this->db->get($this->posts_table, 1);
			
			if ( $query->num_rows() == 1 )
			{
				$post = $query->row_array();
				
				$post['tags'] = $this->get_tags($post['id']);
				
				return $post;
			}
			else
			{
				return false;
			}
		}
		
		function single_post_by_id($id)
		{
			$this->db->where('id', $id);
			
			$query = $this->db->get($this->posts_table, 1);
			
			if ( $query->num_rows() == 1 )
			{
				$post = $query->row_array();
				
				$post['tags'] = $this->get_tags($post['id']);
				
				return $post;;
			}
			else
			{
				return false;
			}
		}
		
		function save_post($post)
		{
			// $post is an array...
			
			if ( !empty($post['id']) )
			{
				$this->db->where('id', $post['id']);
				
				unset($post['id']);
				
				return $this->db->update($this->posts_table, $post);
			}
			else
			{
				$post['date_posted'] = time();
				
				$query = $this->db->insert($this->posts_table, $post);
					
				return $this->db->insert_id();
			}
		}
		
		function save_tags($id, $tags)
		{
			$tags = str_replace(', ', ',', $tags);
			$tags = str_replace('"', '', $tags);
			$tags = str_replace("'", '', $tags);
			
			$tags = explode(',', $tags);
			
			$tag_array = array();
			
			foreach ( $tags as $tag )
			{
				$tag_array[] = preg_replace("/[^a-z0-9s]/", "", strtolower($tag));
			}
			
			$this->db->where('post_id', $id);
			$query = $this->db->delete($this->posts_tags_table);
			
			foreach	($tag_array as $tag)
			{
				unset($data);
				
				$this->db->where('tag', $tag);
				$query = $this->db->get($this->tags_table, 1);
				
				if ($query->num_rows == 1)
				{
					$row = $query->row_array();
					$tag_id = $row['id'];
				}
				else
				{
					$data['tag'] = $tag;
					
					$query = $this->db->insert($this->tags_table, $data);
					$tag_id = $this->db->insert_id();
				}
				
				$tag_data = array(
								'post_id' 	=> $id,
								'tag_id'		=> $tag_id
							);
			
				$query = $this->db->insert($this->posts_tags_table, $tag_data);
			}
		}
		
		function delete_post($post_id)
		{
			$this->db->where('id', $post_id);
			$this->db->delete($this->posts_table);
			
			$this->db->where('post_id', $post_id);
			$this->db->delete($this->comments_table);
			
			$this->db->where('post_id', $post_id);
			$this->db->delete($this->posts_tags_table);
			
			$this->db->where('post_id', $post_id);
			$this->db->delete($this->trackbacks_table);
			
			$this->session->set_flashdata('notification', 'Post #'.$post_id.' Deleted');
		}
		
		function get_tags($post_id, $return = 'array')
		{
			$built = array();
			$string = '';
			$this->db->select($this->tags_table .'.tag');
			
			$this->db->join($this->tags_table, $this->posts_tags_table . '.tag_id = ' . $this->tags_table . '.id', 'inner');
			$this->db->where($this->posts_tags_table .'.post_id', $post_id);

			$this->db->orderby($this->posts_tags_table .'.id');
			
			$query = $this->db->get($this->posts_tags_table);
			
			if ($query->num_rows() > 0)
			{
				$result = $query->result();
				
				foreach ($result as $tag)
				{
					$built[] = $tag->tag;
				}
				
				if ($return == 'string')
				{
					foreach ($built as $tag)
					{
						$string .= $tag.', ';
					}
						
					$string = substr($string, 0, -2);
				}
			}
			
			switch ($return)
			{		
				case 'array':
					return $built;
				break;
				
				case 'string':
					return $string;
				break;
			}
		}
			
		function all_posts()
		{
			$this->db->orderby('date_posted', 'desc');
			
			$query = $this->db->get($this->posts_table);
			
			$posts = array();
			
			if ( $query->num_rows() )
			{
				$posts = $query->result_array();
			}
			
			return $posts;
		}
		
		function tag_cloud($num)
		{
	/*
			$this->db->select($this->tags_table.'.tag, count(pt.id) as qty');
			$this->db->join($this->posts_tags_table.' pt', $this->posts_tags_table.'.tag_id = '.$this->tags_table.'.id', 'inner');
			$this->db->groupby($this->tags_table.'.id');
			
			$query = $this->db->get($this->tags_table);*/
			$query = $this->db->query("SELECT t.tag, count(pt.id) as qty FROM ".$this->db->dbprefix($this->tags_table)." t INNER JOIN 
			".$this->db->dbprefix($this->posts_tags_table)." pt ON pt.tag_id=t.id GROUP BY t.id");
			$built = array();
			
			if ($query->num_rows > 0)
			{
				$result = $query->result_array();
				
				foreach ($result as $row)
				{
					$built[$row['tag']] = $row['qty'];
				}
				
				return $built;
			}
			else
			{
				return array();
			}
		}
	
		function latest_comments($post_id, $num = 10)
		{
			if (!empty($post_id))
			{
				$this->db->where($this->comments_table.'.post_id', $post_id);
			}
			
			$this->db->where($this->comments_table.'.status', 'approved');
			
			$this->db->select($this->comments_table.'.id, '.$this->comments_table.'.author, '.$this->comments_table.'.email, '.$this->comments_table.'.website');
			$this->db->select($this->posts_table.'.title as post_title');
			$this->db->select($this->posts_table.'.date_posted as post_date_posted');
			$this->db->select($this->posts_table.'.uri as post_uri');
			
			$this->db->join($this->posts_table, $this->posts_table.'.id = '.$this->comments_table.'.post_id', 'inner');
			
			$this->db->orderby($this->comments_table.'.date_posted', 'desc');
			
			$query = $this->db->get($this->comments_table, $num);
			
			$comments = array();
			
			if ( $query->num_rows() > 0 )
			{
				$comments = $query->result_array();
			}
			
			return $comments;
		}
	
		function no_comments_moderation()
		{
			$this->db->select('COUNT(id) as no_comments');
			$this->db->where('status', 'pending');
			
			$query = $this->db->get($this->comments_table);
			
			$row = $query->row_array();
			
			return $row['no_comments'];
		}
		
		function comments_pending()
		{
			$this->db->where('status', 'pending');
			
			$query = $this->db->get($this->comments_table);
			
			if ( $query->num_rows() > 0 )
			{
				return $query->result_array();
			}
			
			return array();
		}
		
		function comments()
		{
			$this->db->where('status', 'approved');
			
			$query = $this->db->get($this->comments_table);
			
			if ( $query->num_rows() > 0 )
			{
				return $query->result_array();
			}
			
			return array();
		}
	
		function posts_comments($post_id)
		{
			$this->db->where('post_id', $post_id);
			$this->db->where('status', 'approved');
			
			$this->db->orderby('date_posted');
			
			$query = $this->db->get($this->comments_table);
			
			$comments = array();
			
			if ( $query->num_rows() > 0 )
			{
				$comments = $query->result_array();
			}
			
			return $comments;
		}
		
		function approve_comment($comment_id)
		{
			$data = array('status' => 'approved');
			
			$this->db->where('id', $comment_id);
			
			$query = $this->db->update($this->comments_table, $data);
			
			$this->session->set_flashdata('notification', 'Comment #'.$comment_id.' Approved');
		}
		
		function unapprove_comment($comment_id)
		{
			$data = array('status' => 'pending');
			
			$this->db->where('id', $comment_id);
			
			$query = $this->db->update($this->comments_table, $data);
			
			$this->session->set_flashdata('notification', 'Comment #'.$comment_id.' Unapproved');
		}
		
		function delete_comment($comment_id)
		{
			$this->db->where('id', $comment_id);
			$query = $this->db->delete($this->comments_table);

			$this->session->set_flashdata('notification', 'Comment #'.$comment_id.' Deleted');
		}
		
		function spam_comment($comment_id)
		{
			$this->db->where('id', $comment_id);
			$query = $this->db->delete($this->comments_table);

			$this->session->set_flashdata('notification', 'Comment #'.$comment_id.' is Spam - Deleted');
		}
		
		function latest_trackbacks($post_id, $num = 10)
		{
			if (!empty($post_id))
			{
				$this->db->where($this->trackbacks_table.'.post_id', $post_id);
			}
			
			$this->db->where($this->trackbacks_table.'.status', 'approved');
			
			$this->db->select($this->trackbacks_table.'.id, '.$this->trackbacks_table.'.title, '.$this->trackbacks_table.'.url');
			$this->db->select($this->posts_table.'.title as post_title');
			$this->db->select($this->posts_table.'.date_posted as post_date_posted');
			$this->db->select($this->posts_table.'.uri as post_uri');
			
			$this->db->join($this->posts_table, $this->posts_table.'.id = '.$this->trackbacks_table.'.post_id', 'inner');
			
			$this->db->orderby($this->trackbacks_table.'.date_posted', 'desc');
			
			$query = $this->db->get($this->trackbacks_table, $num);
			
			$comments = array();
			
			if ( $query->num_rows() > 0 )
			{
				$comments = $query->result_array();
			}
			
			return $comments;
		}
		
		function no_trackbacks_moderation()
		{
			$this->db->select('COUNT(id) as no_comments');
			$this->db->where('status', 'pending');
			
			$query = $this->db->get($this->trackbacks_table);
			
			$row = $query->row_array();
			
			return $row['no_comments'];
		}
		
		function posts_trackbacks($post_id)
		{
			$this->db->where('post_id', $post_id);
			$query = $this->db->get($this->trackbacks_table);

			$trackbacks = array();
			
			if ( $query->num_rows() > 0 )
			{
				$trackbacks = $query->result_array();
			}
			
			return $trackbacks;
		}
		
		function save_trackback($trackback)
		{
			$this->db->insert($this->trackbacks_table, $trackback);
		}
	}


?>