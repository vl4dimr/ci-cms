<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Captcha
{
	
	var $options = array(
						'dir' => 'cache',				//Default to BASEPATH.'cache'
						'cache_postfix' => '.cache',	//Prefix to all cache filenames
						'expiry_postfix' => '.exp',		//Expiry file prefix
						'group_postfix' => '.group', 	//Group directory prefix
						'default_ttl' => 3600  			//Default time to live = 3600 seconds (One hour).
					);
	
	/**
	 * 	Constructor
	 * 
	 * 	@param	Options to override defaults
	 */
	function Captcha($options = NULL)
	{
		
		if ($options != NULL) $this->options = array_merge($this->options, $options);
		
	}
	
	function image()
	{
		$obj =& get_instance();
		
		$pool = '0123456789';

		$str = '';
		for ($i = 0; $i < 6; $i++)
		{
			$str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
		}
		
		$word = $str;


		$obj->load->plugin('captcha');
		$vals = array(
			'img_path'	 => './media/captcha/',
			'img_url'	 => base_url() .'media/captcha/',
			'font_path'	 => APPPATH . 'modules/news/fonts/Fatboy_Slim.ttf',
			'img_width'	 => 150,
			'img_height' => 30,
			'expiration' => 1800,
			'word' => $word
		);

		$cap = create_captcha($vals);
		
		$data = array(
			'captcha_id'	=> '',
			'captcha_time'	=> $cap['time'],
			'ip_address'	=> $obj->input->ip_address(),
			'word'			=> $cap['word']
		);

		$obj->db->insert('captcha', $data);
		
		return $cap['image'];
		
	}
	
	function verify()
	{
		$obj =& get_instance();
		if (!$obj->input->post('captcha'))
		{
			return 404;
		}
		
		$expiration = time()-7200; // Two hour limit
		$obj->db->where("captcha_time <", $expiration);
		$obj->db->delete('captcha');

		// Then see if a captcha exists:
		$obj->db->where('word', $obj->input->post('captcha'));
		$obj->db->where('ip_address', $obj->input->ip_address());
		$obj->db->where('captcha_time >', $expiration);
		$query = $obj->db->get('captcha');
		$row = $query->row();
		

		if ($query->num_rows() == 0)
		{
			return 403;
		}
		return 200;
	}
}