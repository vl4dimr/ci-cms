<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Admin extends Controller {
		var $settings = array();
		var $fields = array();
		var $template = array();
		function Admin()
		{
			parent::Controller();
			
			$this->load->library('administration');

			$this->template['module']	= 'news';
			$this->template['admin']		= true;
			$this->settings = isset($this->system->news_settings) ? unserialize($this->system->news_settings) : array();
			
			$this->load->model('news_model', 'news');
			
	
		}
		
		function index($start = 0)
		{
			
			$per_page = 20;
			$this->user->check_level($this->template['module'], LEVEL_VIEW);
			
			$params['start'] = $start;
			$params['limit'] = $per_page;
			$params['where'] = array('news.lang' => $this->user->lang);
			$params['order_by'] = "id DESC";
			
			$data = $this->news->get_list($params);
			

			$this->template['rows'] = $data;
			
			
			$this->load->library('pagination');
			
			$config['uri_segment'] = 4;
			$config['first_link'] = __('First');
			$config['last_link'] = __('Last');
			$config['base_url'] = site_url('admin/news/index');
			$config['total_rows'] = $this->news->get_total();
			$config['per_page'] = $per_page; 

			$this->pagination->initialize($config); 

			$this->template['pager'] = $this->pagination->create_links();
			$this->layout->load($this->template, 'admin');
			
		}
		/**
		 * Dealing with page module settings
		 **/
		function settings($action = null)
		{

			$this->fields = array(
				'allow_comments' => 1,
				'approve_comments' => 1,
				'notify_admin' => 0
				);
			switch ($action)
			{
				
				case "save" :
			
					$setting = is_array($this->input->post('settings')) ? serialize($this->input->post('settings')) : '';
					$this->settings->set('news_settings', $setting);
					$this->session->set_flashdata('notification', __("Settings saved", $this->template['module']));
					redirect('admin/news/settings');
				break;
				default:
					//fields
					$this->user->check_level($this->template['module'], LEVEL_EDIT);		
					$settings = isset($this->system->news_settings) ? unserialize($this->system->news_settings) : array();
					foreach ($this->fields as $key => $value)
					{
						$this->_settings[$key] = isset($settings[$key])? $settings[$key] : $value;
					}
					
					$this->template['settings'] = $this->_settings;
						
					$this->layout->load($this->template, 'settings');
				
				break;
			}
	
		}
		
		function save()
		{
			
			$this->load->library('form_validation');

			$this->form_validation->set_rules('title', __("Title", $this->template['module']), 'required');

			$this->form_validation->set_message('required', __("The %s field can not be empty", $this->template['module']));
			
			if ($this->form_validation->run() == FALSE)
			{
				$this->create($this->input->post('id'));
				return;
			}		

			$id = $this->news->save();
			$this->plugin->do_action('news_save', $id);
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
			
			
			$this->session->set_flashdata('notification', __("News saved", $this->template['module']));
			redirect('admin/news');
				
		}
		
		function admin_header()
		{
			$output = "<script language=\"javascript\" src=\"" . base_url() . "application/views/admin/javascript/datePicker/date.js\"></script>\n";
			$output .= "<script language=\"javascript\" src=\"" . base_url() . "application/views/admin/javascript/datePicker/datePicker.js\"></script>\n";

			$output .= "<link href=\"" . base_url() . "application/views/admin/javascript/datePicker/styles.css\" rel=\"stylesheet\" type=\"text/css\" />";
			
			echo $output;

		}
		
		
		function move($direction, $id)
		{
			$this->user->check_level($this->template['module'], LEVEL_EDIT);

			if (!isset($direction) || !isset($id))
			{
				redirect('admin/news');
			}
			
			$this->news->move($direction, $id);
			
			redirect('admin/news');					
			
		}
		
		function create($id = null)
		{
		
			$this->user->check_level($this->template['module'], LEVEL_ADD);
			$this->load->library('form_validation');
			$this->form_validation->set_rules('title', __("Title", $this->template['module']), 'required');

			$this->form_validation->set_message('required', __("The %s field can not be empty", $this->template['module']));
					
			//default values
			$row = array(
			'allow_comments' => isset($this->settings['allow_comments'])? $this->settings['allow_comments'] : '1',
			'notify' => 1,
			'cat' => 0
			);
			
			$this->plugin->add_action('header', array(&$this, 'admin_header'));


			if (!is_null($id))
			{
				$this->user->check_level($this->template['module'], LEVEL_EDIT);
			
				$query = $this->db->get_where('news', array('id' => $id));
				$row = $query->row_array();
				$row['tags'] =  $this->news->get_tags(array('where' => array('news_id' => $id),'limit' => 50, 'order_by' => 'cnt DESC' ));
			}
			$this->template['row'] = $row;
			$this->template['categories'] = $this->news->get_catlist();
			$this->template['tags'] = $this->news->get_tags(array('limit' => 50, 'order_by' => 'cnt DESC' ));
			$this->javascripts->add('ajaxfileupload.js');
			
			$this->db->where('src_id', 0);
			$this->db->or_where('src_id', $id);
			$this->db->where('module', 'news');			

			$query = $this->db->get('images');
			
			$this->template['images'] = $query->result_array();

			

			$this->layout->load($this->template, 'create');
			return;
		}
		
		
		function delete($id, $js = 0)
		{
			$this->user->check_level($this->template['module'], LEVEL_DEL);
			if ( $js > 0 )
			{
				$this->news->delete($id);
				
				$this->session->set_flashdata('notification', 'News has been deleted.');
				$this->cache->remove('news'.$this->user->lang, 'news'); 
				$this->plugin->do_action('news_delete', $id);
				redirect('admin/news');
			}
			else
			{
				$this->template['id'] = $id;
				
				$this->layout->load($this->template, 'delete');
			}
		}
		
		
		function ajax_delete()
		{
			$this->db->where('id', $this->input->post('id'));
			$this->db->delete('images');
			echo __("The image was deleted", $this->template['module']);
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

