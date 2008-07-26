<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Admin extends Controller {
		var $settings = array();
		function Admin()
		{
			parent::Controller();
			
			$this->load->library('administration');

			$this->template['module']	= 'news';
			$this->template['admin']		= true;
			$this->settings = isset($this->system->news_settings) ? unserialize($this->system->news_settings) : array();
			
			$this->load->model('news_model', 'news');
			
			$this->page_id = ( $this->uri->segment(4) ) ? $this->uri->segment(4) : NULL;
			
		}
		
		function index($start = 0)
		{
			
			$per_page = 20;
			$this->user->check_level($this->template['module'], LEVEL_VIEW);
			
			if ( !$data = $this->cache->get('news'.$this->user->lang, 'news') )
			{
				if (!$data = $this->news->news_list()) $data = array();
				$this->cache->save('news'.$this->user->lang, $data, 'news', 0);
			}
			

			$this->template['rows'] = array_slice($data, $start, $per_page);
			
			
			$this->load->library('pagination');
			
			$config['uri_segment'] = 4;
			$config['first_link'] = __('First');
			$config['last_link'] = __('Last');
			$config['base_url'] = base_url() . 'admin/news/index';
			$config['total_rows'] = count($data);
			$config['per_page'] = $per_page; 

			$this->pagination->initialize($config); 

			$this->template['pager'] = $this->pagination->create_links();
			$this->layout->load($this->template, 'admin');
			
		}
		/**
		 * Dealing with page module settings
		 **/
		function settings()
		{
			$this->user->check_level($this->template['module'], LEVEL_DEL);
			if ($post = $this->input->post('submit') )
			{
				$fields = array('page_home');
				
				foreach ($fields as $field)
				{
					if ( $this->input->post($field) !== false)
					{
						$this->system->set($field, $this->input->post($field));
					}
				}
				$this->session->set_flashdata('notification', __("Settings updated"));	
				redirect('admin/page/settings');
			}
			else
			{
				$this->layout->load($this->template, 'settings');
			}
		}
		
		function save()
		{
			$this->user->check_level($this->template['module'], LEVEL_ADD);
		
			$fields = array('id', 'title', 'body', 'status', 'allow_comments', 'lang', 'notify');
			$data = array();
			
			foreach ($fields as $field)
			{
				$data[$field] = $this->input->post($field);
			}

			
			//var_dump($data);

				
			if($id = $this->input->post('id'))
			{
				$this->user->check_level($this->template['module'], LEVEL_EDIT);
			
				//update
				$this->db->where('id', $id);
				$this->db->update('news', $data);
			}
			else
			{
				$data['author'] = $this->user->username;
				$data['email'] = $this->user->email;
				$data['uri'] = $this->news->generate_uri($this->input->post('title'));
				$data['date'] = mktime();
				$this->db->insert('news', $data);
				$id = $this->db->insert_id();
				//insert
			}
			$this->cache->remove('news'.$this->user->lang, 'news');
			if ($image_ids = $this->input->post('image_ids'))
			{
				foreach($image_ids as $image_id)
				{
					$this->db->set('src_id', $id);
					$this->db->where('id', $image_id);
					$this->db->update('images');
				}	
			}	

			if ($_FILES['image']['name'] != '')
			{

				$config['upload_path'] = './media/images/o/';
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size']	= '500';
				$config['max_width']  = '1024';
				$config['max_height']  = '768';
				
				//var_dump($config['upload_path']);
				$this->load->library('upload', $config);
			
				if ( ! $this->upload->do_upload('image'))
				{
					$error = $this->upload->display_errors();
					
				}	
				else
				{
					$this->load->library('image_lib');
					$image_data = $this->upload->data();
					
					//var_dump($image_data);
					
					//resize to 150
					$config['source_image'] = $image_data['full_path'];
					$config['new_image'] = './media/images/s/';
					$config['width'] = 100;
					$config['height'] = 100;
					$config['maintain_ratio'] = true;
					$config['master_dim'] = 'width';
					$config['create_thumb'] = FALSE;
					$this->image_lib->initialize($config);
					if($this->image_lib->resize())
					{						
				
					
						$config['source_image'] = $image_data['full_path'];
						$config['new_image'] = './media/images/m/';
						$config['width'] = 300;
						$config['height'] = 200;
						$config['maintain_ratio'] = TRUE;
						$config['master_dim'] = 'width';
						$config['create_thumb'] = FALSE;
						$this->image_lib->initialize($config);

						$this->image_lib->resize();
						
						$data = array('src_id' => $id, 'module' => 'news', 'file' => $image_data['file_name']);
						$this->db->insert('images', $data);
				
					}
				
					
				}				
			}
			
			
			$this->session->set_flashdata('notification', __("News saved"));
			redirect('admin/news');
				
		}
		
		
		function create($id = null)
		{

			$this->user->check_level($this->template['module'], LEVEL_ADD);
					
			//default values
			$row = array(
			'allow_comments' => isset($this->settings['allow_comments'])? $this->settings['allow_comments'] : '1',
			'notify' => 1
			);
			
	

			if (!is_null($id))
			{
				$this->user->check_level($this->template['module'], LEVEL_EDIT);
			
				$query = $this->db->get_where('news', array('id' => $id));
				$row = $query->row_array();
			}
			$this->template['row'] = $row;
			$this->javascripts->add('ajaxfileupload.js');
			
			$this->db->where('src_id', 0);
			$this->db->or_where('src_id', $id);
			$this->db->where('module', 'news');			

			$query = $this->db->get('images');
			
			$this->template['images'] = $query->result_array();
			
			$this->layout->load($this->template, 'create');
		}
		
		
		function delete($id, $js = 0)
		{
			$this->user->check_level($this->template['module'], LEVEL_DEL);
			if ( $js > 0 )
			{
				$this->db->where('id', $this->input->post('id'));
				$query = $this->db->delete('news');
				
				$this->db->where('src_id', $this->input->post('id'));
				$this->db->set('src_id', 0);
				$query = $this->db->update('images');
				
				$this->session->set_flashdata('notification', 'News has been deleted.');
				$this->cache->remove('newslist'.$this->user->lang, 'news'); 
				redirect('admin/news');
			}
			else
			{
				$this->template['news'] = $this->news->get_news( array('id' => $this->page_id) );
				
				$this->layout->load($this->template, 'delete');
			}
		}
		
		
		function ajax_delete()
		{
			$this->db->where('id', $this->input->post('id'));
			$this->db->delete('images');
			echo __("The image was deleted");
		}
		
		function ajax_upload()
		{
			$image_data = array();
			$error = "";
			if(!empty($_FILES['image']['error']))
			{
				switch($_FILES['image']['error'])
				{

					case '1':
						$error = __('The uploaded file exceeds the upload_max_filesize directive in php.ini');
						break;
					case '2':
						$error = __('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form');
						break;
					case '3':
						$error = __('The uploaded file was only partially uploaded');
						break;
					case '4':
						$error = __('No file was uploaded.');
						break;

					case '6':
						$error = __('Missing a temporary folder');
						break;
					case '7':
						$error = __('Failed to write file to disk');
						break;
					case '8':
						$error = __('File upload stopped by extension');
						break;
					case '999':
					default:
						$error = __('No error code avaiable');
				}
			}
			elseif(empty($_FILES['image']['tmp_name']) || $_FILES['image']['tmp_name'] == 'none')
			{
				$error = __('No file was uploaded..');
			}
			else 
			{
			
			
				$config['upload_path'] = './media/images/o/';
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size']	= '500';
				$config['max_width']  = '1024';
				$config['max_height']  = '768';
				
				//var_dump($config['upload_path']);
				$this->load->library('upload', $config);	
				
				if ( ! $this->upload->do_upload('image'))
				{
					$error = $this->upload->display_errors('', '');
					
				}	
				else
				{
				
				

					$image_data = $this->upload->data();
					

					$config = array();
					//resize to 150
					$config['source_image'] = $image_data['full_path'];
					$config['new_image'] = './media/images/s/';
					$config['width'] = 100;
					$config['height'] = 100;
					$config['maintain_ratio'] = true;
					$config['master_dim'] = 'width';
					$config['create_thumb'] = FALSE;
					
					$this->load->library('image_lib');
					$this->image_lib->initialize($config);
					$id = '';
					
					if($this->image_lib->resize())
					{						
				
						$config = array();
						$config['source_image'] = $image_data['full_path'];
						$config['new_image'] = './media/images/m/';
						$config['width'] = 300;
						$config['height'] = 200;
						$config['maintain_ratio'] = TRUE;
						$config['master_dim'] = 'width';
						$config['create_thumb'] = FALSE;
						$this->image_lib->initialize($config);

						$this->image_lib->resize();
						$data = array('file' => $image_data['file_name'], 'module' => 'news');
						$this->db->insert('images', $data);
						$id = $this->db->insert_id();
						
					}
				}
	
			}
			echo "{";
			echo "error: '" . $error . "'";
			//echo "error: 'error is " . $image_data['file_name'] . "'";
			echo ",\n image: '" . (isset($image_data['file_name']) ? $image_data['file_name'] : '') . "'";
			echo ",\n imageid: " . (isset($id) ? $id : 5)  . "";
			echo "\n}";	
		}
		
	}

?>