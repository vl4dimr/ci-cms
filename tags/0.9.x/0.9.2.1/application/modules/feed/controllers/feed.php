<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * $Id
 **/
 
class Feed extends Controller 
{

    function Feed()
    {
        parent::Controller();
		
        $this->load->helper('xml');
    }
    
    function index()
    {

        $data['encoding'] = 'utf-8';
        $data['feed_name'] = $this->system->site_name;
        $data['feed_url'] = base_url();
        $data['page_description'] = $this->system->meta_description;
        $data['page_language'] = $this->user->lang;
        $data['creator_email'] = (isset($this->system->admin_email))? $this->system->admin_email : "";
		$this->load->helper('xml');

		//get page first if allowed
		
		$contents = array();
		
		if (isset($this->system->page_publish_feed) && $this->system->page_publish_feed == 1)
		{
			$this->db->where('lang', $this->user->lang);
			$query = $this->db->get('pages');
			if ($query->num_rows() > 0 )
			{
				$rows = $query->result_array();
				foreach ($rows as $key => $row)
				{
					$contents[$key]['title'] = $row['title'];
					$contents[$key]['url'] = site_url($row['uri']);
					$contents[$key]['body'] = $row['body'];
					$contents[$key]['date'] = (isset($row['date'])) ? $row['date'] : '';
				}
			}
		}
        //now get other contents
		
		$contents = $this->plugin->apply_filters('feed_content', $contents);
		
		usort($contents, array($this, '_cmpi'));
		$data['contents'] = $contents;
		header("Content-Type: application/rss+xml");
        
		$this->load->view('rss', $data);
    }
	
	function _cmpi($a, $b)
	{
		$a1 = $a['date'];
		$b1 = $b['date'];
		
		if ($a1 == $b1) {
            return 0;
        }
        return ($a1 < $b1) ? +1 : -1;
	}

}

