<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
	
	class Layout {

		function Layout()
		{
			if ( !isset($this->obj) ) $this->obj =& get_instance();

			$this->theme = $this->obj->system->theme;
			$this->template = $this->obj->system->template;
		}
		
		function load($data, $view)
		{
			

			$breadcrumb = array();
			
			if (empty($data['breadcrumb'])) $data['breadcrumb'] = array();
			
			if ($data['module'] != 'page')
			{
				$breadcrumb[] = array(
									'title' => ucwords($data['module']),
									'uri'	=> $data['module']
								);
			}
							
			$data['breadcrumb'] = array_merge($breadcrumb, $data['breadcrumb']);
			
			$data['view'] = $view;
			
			
			if ( (isset($data['admin']) && $data['admin'] == true) || $data['module'] == 'admin')
			{
				$template_path = $this->theme. '/admin/index';
			}
			else
			{
				$template_path = $this->theme. '/site/index';
			}
			
			$output = $this->obj->load->view($template_path, $data, true);
			
			$this->render_partials();
			
			
			if ( !empty($this->partials) && empty($data['admin']) )
			{
				
				foreach ($this->partials as $area => $areas)
				{
					$areadata = "";
					if (is_array($areas)) 
					{
						foreach($areas as $val)
						{
							$areadata .= $val ."\n";
						}
					}
					$output = str_replace('{'.$area.'}', $areadata, $output);
				}
			}
			
			$here = substr($this->obj->uri->uri_string(), 1);
			
			$this->obj->session->set_userdata(array('last_uri' => $here));
			
			$this->obj->output->set_output($output);
		}

		function get_blocks()
		{
			$this->obj->db->select('area, method, module');
			$this->obj->db->where('theme', $this->theme);
			$this->obj->db->order_by('area', 'weight');
			
			$query = $this->obj->db->get('blocks');
			
			$built = array();
			
			if ($query->num_rows() > 0)
			{
				$result = $query->result_array();
				
				foreach	($result as $row)
				{
					$area = 'area.'.$row['area'];
					unset($row['area']);
					
					$built[][$area] = $row;
				}
			}

			$this->blocks = $built;
			
		}
		
		function is_in_area($harea, $module, $method) {
			if (!empty($this->blocks))
			{
				if (!empty($this->blocks))
				{
					foreach ($this->blocks as $areas)
					{
						foreach ($areas as $area => $partial) {
						
							if ($area == $harea && $partial['module'] == $module && $partial['method'] == $method)
							{
								return true;
							}
						}
					}
				}				
			}
			return false;
		}
		
		function render_partials()
		{
			$this->get_blocks();

			if (!empty($this->blocks) && empty($this->view_data['admin']))
			{
				foreach ($this->blocks as $areas)
				{
					foreach ($areas as $area => $partial) 
					{
						$this->render_partial($area, $partial);
					}
				}
			}
		}
		
		function render_partial($area, $partial)
		{
			$module = $partial['module'];
			$method = $partial['method'];
			
			if ( empty($this->loaded_partial_libraries[$module]) )
			{
				$class_name = ucfirst($module).'_Partials';
				
				$path = APPPATH.'modules/'.$module.'/'.strtolower($class_name).'.php';
				
				if ( !file_exists($path) )
				{
					$this->partials[$area] = '';
					return true;
				}
				
				include $path;
				
				$this->obj->$module = new $class_name;
				
				$this->loaded_partial_libraries[$module] = true;
			}
			
			$this->partials[$area][] = $this->obj->$module->$method();
			
		}
		
		function get_available_partials()
		{
			$handle = opendir(APPPATH.'modules');
	
			$this->available_partials = array();		
		
			if ($handle)
			{
				while ( false !== ($module = readdir($handle)) )
				{
					unset($available_partials);
					// make sure we don't map silly dirs like .svn, or . or ..

					if (substr($module, 0, 1) != ".")
					{
						if ( file_exists(APPPATH.'modules/'.$module.'/'.$module.'_partials.php') )
						{
							include(APPPATH.'modules/'.$module.'/'.$module.'_partials_available.php');
							
							$this->available_partials[$module] = $available_partials;
						}
					}
				}
			}
			
			return $this->available_partials;
		}
		
		function get_available_blocks()
		{
			$theme = $this->obj->system->theme;
			$template = $this->obj->system->template;
			
			include_once(APPPATH.'views/'.$theme.'/site/blocks_available.config.php');
			
			return $blocks[$template];
		}
		
		function get_themes()
		{
			$handle = opendir(APPPATH.'views');

			if ($handle)
			{
				while ( false !== ($theme = readdir($handle)) )
				{
					// make sure we don't map silly dirs like .svn, or . or ..

					if (substr($theme, 0, 1) != "." && $theme != 'index.html')
					{
						$themes[] = $theme;
					}
				}
			}
			
			return $themes;
		}
	
	}
?>