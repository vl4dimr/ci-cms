<?php
	
	class Blog extends Controller {
		
		var $num_to_display = 10;
		
		function Blog()
		{
			parent::Controller();
			$this->template['module'] = "blog";
			
			$this->load->model('blog_model');
		}
		
		function index()
		{
			$this->template['title'] = 'Blog';
			
			$this->template['posts'] = $this->blog_model->latest_posts($this->num_to_display);
			$this->layout->load($this->template, 'index');
		}
		
		function read()
		{
			$year = $this->uri->segment(2);
			$month = $this->uri->segment(3);
			$uri = $this->uri->segment(4);
			
			if ( $post = $this->blog_model->single_post($year, $month, $uri) )
			{
				if ( $this->uri->segment(5) == 'trackback' )
				{
					$this->_trackback($post['id']);
				}
				
				$this->template['breadcrumb'][] = 	array(
														'title'	=> $post['title'],
														'uri'	=> 'blog/'.$year.'/'.$month.'/'.$uri
													);
													
				$this->template['title'] = $post['title'];
				$this->template['meta_keywords'] = $post['meta_keywords'];
				$this->template['meta_description'] = $post['meta_description'];
				
				$this->template['post'] = $post;
				$this->template['comments'] = $this->blog_model->posts_comments($post['id']);
				$this->template['trackbacks'] = $this->blog_model->posts_trackbacks($post['id']);
				
				$this->layout->load($this->template, 'read');
			}
			else
			{
				$this->layout->load($this->template, '404');
			}
		}
		
		function _trackback($post_id)
		{
			$this->load->library('trackback');
			
			if ( ! $this->trackback->receive())
			{
			    $this->trackback->send_error("The Trackback did not contain valid data");
			}

			$data = array(
		                'post_id'		=> $post['id'],
		                'url'			=> $this->trackback->data('url'),
		                'title'			=> $this->trackback->data('title'),
		                'excerpt'		=> $this->trackback->data('excerpt'),
		                'blog_name'		=> $this->trackback->data('blog_name'),
		                'date_posted'	=> time(),
		                'ip_address'	=> $this->input->ip_address()
                	);
                	
			$this->blog_model->save_trackback($data);
			$this->trackback->send_success();
			
			exit;
		}
		
		function tags()
		{
			die('doing something with the tags!');
		}
	}

?>