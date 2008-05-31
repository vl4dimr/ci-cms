<?php

	class Admin extends Controller {
		
		function Admin()
		{
			parent::Controller();
			$this->load->library('administration');
			
			$this->template['module'] 	= 'blog';
			$this->template['admin']	= true;
			
			$this->load->model('blog_model');

		}
		
		function index()
		{
			
			$this->template['comments'] = $this->blog_model->latest_comments(NULL, 5);
			$this->template['trackbacks'] = $this->blog_model->latest_trackbacks(NULL, 5);
			
			$this->template['comments_pending'] = $this->blog_model->no_comments_moderation();
			$this->template['trackbacks_pending'] = $this->blog_model->no_trackbacks_moderation();
			
			
			$this->template['posts'] = $this->blog_model->all_posts();
			
			$this->layout->load($this->template, 'admin');
		}
		
		function create()
		{
			if (!$this->input->post('submit'))
			{
				$this->layout->load($this->template, 'create');
			}
			else
			{
				// First let's process the 'mandatory' fields.
				
				$post = array(
							'title' 	=> NULL,
							'uri'		=> NULL,
							'status' 	=> NULL,
							'body'		=> NULL
						);
				
				$posted = array();
				$errors = array();
						
				foreach ( $post as $key => $data )
				{
					if ( $this->input->post($key) )
					{
						$posted[$key] = $this->input->post($key);
					}
					
					if ( empty($posted[$key]) )
					{
						$errors[$key] = true;
					}
				}
				
				if ( empty($errors) )
				{
					$optional = array(
									'ext_body'				=> NULL,
									'allow_comments'		=> NULL,
									'allow_pings'			=> NULL,
									'meta_keywords'			=> NULL,
									'meta_description'		=> NULL
								);
								
					foreach ( $optional as $key => $data )
					{
						if ( $this->input->post($key) )
						{
							$posted[$key] = $this->input->post($key);
						}
					}
					
					$post_id = $this->blog_model->save_post($posted);
					
					if ($tags = $this->input->post('tags'))
					{
						if (!empty($tags))
						{
							$this->blog_model->save_tags($post_id, $tags);
						}
					}
				}
				
				$this->session->set_flashdata('notification', 'Post has been created, continue editing here');	
				
				redirect('admin/blog/edit/'.$post_id);
				exit;
			}
		}
		
		function edit()
		{
			$id = $this->uri->segment(4);
			
			if ($this->input->post('submit'))
			{
				// First let's process the 'mandatory' fields.
				
				$post = array(
							'title' 	=> NULL,
							'uri'		=> NULL,
							'status' 	=> NULL,
							'body'		=> NULL
						);
				
				$posted = array();
				$errors = array();
						
				foreach ( $post as $key => $data )
				{
					if ( $this->input->post($key) )
					{
						$posted[$key] = $this->input->post($key);
					}
					
					if ( empty($posted[$key]) )
					{
						$errors[$key] = true;
					}
				}
				
				if ( empty($errors) )
				{
					$optional = array(
									'ext_body'				=> NULL,
									'allow_comments'		=> NULL,
									'allow_pings'			=> NULL,
									'meta_keywords'			=> NULL,
									'meta_description'		=> NULL
								);
								
					foreach ( $optional as $key => $data )
					{
						if ( $this->input->post($key) )
						{
							$posted[$key] = $this->input->post($key);
						}
					}
					
					$posted['id'] = $id;
					
					$post_id = $this->blog_model->save_post($posted);
					
					if ($tags = $this->input->post('tags'))
					{
						if (!empty($tags))
						{
							$this->blog_model->save_tags($id, $tags);
						}
					}
				}
				
				$this->session->set_flashdata('notification', 'Post has been saved, continue editing here');	
			}
			
			$this->template['post'] = $this->blog_model->single_post_by_id($id);
			$this->template['post']['tags_string'] = $this->blog_model->get_tags($id, 'string');
			
			$this->layout->load($this->template, 'edit');
		}
		
		function delete()
		{
			if ($post_id = $this->uri->segment(4))
			{
				$this->blog_model->delete_post($post_id);
			}
			
			redirect($this->session->userdata('last_uri'));
		}
		
		function tags()
		{
			die('tags admin!');
		}
		
		function settings()
		{
			die('settings');
		}
		
		function comments()
		{
			$process = $this->uri->segment(4);
			$id = intval($this->uri->segment(5));
			
			if ( !empty($process) )
			{
				switch ( $process ) 
				{
					case 'moderate':
						$this->comments_moderate();
					break;
					
					case 'edit':
						$this->comment_edit($id);
					break;

					case 'approve':
						$this->comment_approve($id);
					break;
					
					case 'unapprove':
						$this->comment_unapprove($id);
					break;

					case 'spam':
						$this->comment_spam($id);
					break;

					case 'delete':
						$this->comment_delete($id);
					break;
				}
			}
			else
			{
				$this->template['comments'] = $this->blog_model->comments();
				$this->template['comments_pending'] = $this->blog_model->no_comments_moderation();
				
				$this->layout->load($this->template, 'comments/index');
			}
		}
		
		function comments_moderate()
		{
			$this->template['comments'] = $this->blog_model->comments_pending();
			
			$this->layout->load($this->template, 'comments/moderate');
		}
		
		function comment_edit($id)
		{
			echo 'edit '.$id;
		}
		
		function comment_approve($id)
		{
			if ( $id )
			{
				$this->blog_model->approve_comment($id);
				redirect($this->session->userdata('last_uri'));
			}
		}
		
		function comment_unapprove($id)
		{
			if ( $id )
			{
				$this->blog_model->unapprove_comment($id);
				redirect($this->session->userdata('last_uri'));
			}
		}
		
		function comment_spam($id)
		{
			if ( $id )
			{
				$this->blog_model->spam_comment($id);
				redirect($this->session->userdata('last_uri'));
			}
		}
		
		function comment_delete($id)
		{
			if ( $id )
			{
				$this->blog_model->delete_comment($id);
				redirect($this->session->userdata('last_uri'));
			}
		}
		
		
		
		
		function trackbacks()
		{
			$process = $this->uri->segment(4);
			$id = intval($this->uri->segment(5));
			
			if ( !empty($process) )
			{
				switch ( $process ) 
				{
					case 'moderate':
						$this->trackbacks_moderate();
					break;

					case 'approve':
						$this->trackback_approve($id);
					break;

					case 'spam':
						$this->trackback_spam($id);
					break;

					case 'delete':
						$this->trackback_delete($id);
					break;
				}
				
				exit;
			}
		}
		
		function trackbacks_moderate()
		{
			echo 'moderating trackbacks';
		}
		
		function trackback_approve($id)
		{
			echo 'approve '.$id;
		}
		
		function trackback_spam($id)
		{
			echo 'spam '.$id;
		}
		
		function trackback_delete($id)
		{
			echo 'delete '.$id;
		}
	}

?>