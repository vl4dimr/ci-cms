<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
	
	class Module extends Controller {
		
		function Module()
		{

			parent::Controller();
			
			$this->load->library('administration');
			
			$this->template['module'] = "admin";
		}
		
		function index()
		{
				
			$this->db->order_by('ordering');
			$query = $this->db->get('modules');
			
			
			if($rows = $query->result_array())
			{
				$modules = array();
				foreach ( $rows as $module ) 
				{
					$modules[ $module['name'] ] = $module;
				}
			}
			/*
			Check if all physical modules are installed
			*/
			unset( $module );
			$handle = opendir(APPPATH.'modules');

			if ($handle)
			{
				while ( false !== ($module = readdir($handle)) )
				{
					// make sure we don't map silly dirs like .svn, or . or ..

					if ( (substr($module, 0, 1) != ".") && ($module != 'admin') )
					{
						if ( !isset($modules[$module]) )
						{
							$modules[$module] = array(
								'name' => $module,
								'status' => -1,
								'description' => null,
								'version' => null
							);
						}
					}
				}
			}
			
			$this->template['modules'] = $modules;
			$this->layout->load($this->template, 'module/index');

		}
		
		function install($module = null)
		{
			if (is_null($module)) 
			{
				$this->session->set_flashdata('notification', __("Please select a module"));
				redirect('admin/module');
			}
			
			if ($this->_is_installed($module))
			{
				$this->session->set_flashdata('notification', __("The module you are trying to install is already installed"));
				redirect('admin/module');
			}
			
			//now install it
			if (is_readable(APPPATH.'modules/'.$module.'/setup.xml'))
			{
				$this->load->helper('xml');
				$xmldata = join('', file(APPPATH.'modules/'.$module.'/setup.xml'));
				$xmlarray = xmlize($xmldata);
				//var_dump($xmlarray);
				echo "name = " . $xmlarray['module']['@']['name'];
				echo "\nversion = " . $xmlarray['module']['@']['version'];
				echo "\ndescription = " . $xmlarray['module']['#']['description'][0]['#'];
				echo "\nqueries = " . var_dump($xmlarray['module']['#']['install'][0]['#']['query']);
				
				/*
				$sql = "INSERT INTO #__modules (name, rank, active, version, config) VALUES ('" . $xmlarray[module][name] . "', '" . $xmlarray[module][rank] . "', '" . $xmlarray[module][active] . "', '" . $xmlarray[module][version] . "', '" . base64_encode(serialize($xmlarray[module][config])) . "')";
				echo($sql);
				$qry = $DB->query($sql);

				//jerena raha misy sql injection
				if(count($xmlarray[module][query]) > 0) {
				foreach($xmlarray[module][query] as $qry) {
				$qry =str_replace("%prefix%", $CFG->prefix, $qry);
				$DB->query($qry);
				}
				}
				*/
			
			}
			else
			{
				$this->session->set_flashdata('notification', __("Setup file not readable."));
				redirect('admin/module');
			}
		}
		
		function _is_installed($module)
		{
			$query = $this->db->get_where('modules', array('name' => $module), 1);
			if ($query->num_rows() > 0)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		

	}

?>