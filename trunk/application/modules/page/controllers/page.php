<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
	
	class Page extends Controller {
		
		function Page()
		{
			parent::Controller();
			//$this->output->enable_profiler(true);
			$this->template['module'] = "page";
			$this->load->model('page_model', 'pages');
			$this->user->lang = $this->session->userdata('lang');
		}
		
		//all available blocks
		function blocks () {
			
		}

		function comment()
		{
			//settings
			$page = $this->pages->get_page($this->input->post('uri'));
			
			if (!$this->input->post('captcha'))
			{
				$this->session->set_flashdata('notification', __("You must submit the security code that appears in the image", $this->template['module']));
				redirect($this->input->post('uri'));
			}
			
			$expiration = time()-7200; // Two hour limit
			$this->db->where("captcha_time <", $expiration);
			$this->db->delete('captcha');

			// Then see if a captcha exists:
			$this->db->where('word', $this->input->post('captcha'));
			$this->db->where('ip_address', $this->input->ip_address());
			$this->db->where('captcha_time >', $expiration);
			$query = $this->db->get('captcha');
			$row = $query->row();
			

			if ($query->num_rows() == 0)
			{

				$this->session->set_flashdata('notification', __("You must submit the security code that appears in the image", $this->template['module']));
				redirect($this->input->post('uri'));
			}
					
			
			$fields = array('author', 'email', 'website', 'body');
			$data = array();
			$data['page_id'] = $page['id'];
			$data['ip'] = $this->input->ip_address();
			foreach ($fields as $field)
			{
				$data[$field] = $this->input->post($field);
			}
			
			if ($this->system->page_approve_comments && $this->system->page_approve_comments = 1)
			{
				$data['status'] = 1;
				if ($page['option']['notify'] == 1 && $page['email'])
				{
					$this->load->library('email');

					$this->email->from($page['email'], $this->system->site_name );
					$this->email->to($page['email']);

					$this->email->subject('[' . $this->system->site_name . '] '. __("Comment Notification", $this->template['module']));
					
					$smsg = __("
Hello,

A new comment has been sent to the page
%s


If you don't want to receive other notification, go to
%s

and disable notification.
", "page");
					$msg = sprintf($smsg, 
							site_url( $page['uri']),
							site_url('admin/page/create/' . $news['id'])
						);
						
					$this->email->message($msg);

					$this->email->send();
					
					//notify admin
				
				}

				if ($this->system->page_notify_admin && $this->system->page_notify_admin == 1)
				{
					$this->load->library('email');

					$this->email->from($page['email'], $this->system->site_name );
					

					$this->email->subject('[' . $this->system->site_name . '] '. __("Comment Notification", $this->template['module']));
					$msg = __("
Hello,

A new comment has been sent to the page
%s


If you don't want to receive other notification, go to
%s

and disable notification.
", "page");
					$msg = sprintf($msg,
							site_url($page['uri']),
							site_url('admin/page/settings#two')
						);
					$this->email->to($this->system->admin_email);
					$this->email->message($msg);
					$this->email->send();
				}
				
			}
			else
			{
				
				if ($page['email'] != '')
				{
					$this->load->library('email');

					$this->email->from($page['email'], $this->system->site_name );
					$this->email->to($page['email']);

					$this->email->subject('[' . $this->system->site_name . '] '. __("Comment to approve", $this->template['module']));
					
					$msg = __("
Hello,

A new comment has been sent to the page
%s
To approve it click the link below 
%s

If you don't want to receive other notification, go to
%s

and set to approve comments automatically.
", "page");
					$msg = sprintf($msg, 
							site_url($news['uri']),
							site_url('admin/page/comments/approve/' . $page['id']),
							site_url('admin/page/settings#two')
						);
						
					$this->email->message($msg);

					$this->email->send();

				}
				
				$this->session->set_flashdata('notification', __("Thank you for your comment. In this site, the comments need to be approved by the administrator. Once approved, you will see it listed here.", $this->template['module']));
			}
			
			$data = $this->plugin->apply_filters('comment_filter', $data);
			
			
			$this->db->insert('page_comments', $data);
			redirect( $this->input->post('uri'), 'refresh');
		}
		
		function index()
		{
			if ( $this->uri->segment(1) )
			{
				$num = 1;
				$built_uri = '';
				
				while ( $segment = $this->uri->segment($num))
				{
					$built_uri .= $segment.'/';
					$num++;
				}
				
				$new_length = strlen($built_uri) - 1;
				$built_uri = substr($built_uri, 0, $new_length);
			}
			else
			{
				$built_uri = $this->system->page_home;
			}
			
			if ( $page = $this->pages->get_page(array('uri' => $built_uri, 'lang' => $this->user->lang)) )
			{
				
				if ($page['active'] == 1)
				{
				
					$this->template['page'] = $page;
					$view = 'index';
					
					$this->template['breadcrumb'][] = 	array(
															'title'	=> (strlen($this->template['page']['title']) > 20 )? substr($this->template['page']['title'], 0, 20) . '...': $this->template['page']['title'],
															'uri'	=> $this->template['page']['uri']
														);
					
					$this->template['title'] = $this->template['page']['title'];
														
					$this->template['meta_keywords'] 	= $this->template['page']['meta_keywords'];
					$this->template['meta_description'] = $this->template['page']['meta_description'];
					//page hit
					if ($this->session->userdata('page'.$page['id']) != $page['id'])
					{
						$this->session->set_userdata('page'.$page['id'], $page['id']);
						$this->db->where('id', $page['id']);
						$this->db->set('hit', 'hit+1', FALSE);
						$this->db->update('pages');
						$this->cache->remove('pagelist'.$this->user->lang, 'page');
					}
				}
				else
				{
					$this->output->set_header("HTTP/1.0 403 Forbidden");
					$view = '403';
				}
			}
			else
			{
				// Make sure we send a 404 header
				
				$this->output->set_header("HTTP/1.0 404 Not Found");
				$view = '404';
			}
	
			$this->layout->load($this->template, $view);
		}
	}

?>
