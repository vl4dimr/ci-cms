<?php

$this->add_filter('search_result', 'news_search_result');
$this->add_filter('feed_content', 'news_feed_content');
$this->add_action('news_save_comment', 'news_news_save_comment');

function news_news_save_comment()
{
	
	$obj =& get_instance();
	
	if($obj->user->logged_in)
	{
		return;
	}
	if (!$obj->input->post('captcha'))
	{
		$obj->session->set_flashdata('notification', __("You must submit the security code that appears in the image", $obj->template['module']));
		redirect('news/' . $obj->input->post('uri'));
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

		$obj->session->set_flashdata('notification', __("You must submit the security code that appears in the image", $obj->template['module']));
		redirect('news/' . $obj->input->post('uri'));
	}

}

$this->add_filter('news_comment_form', 'news_news_comment_form');

function news_news_comment_form($msg)
{
	$obj =& get_instance();
	if(!$obj->user->logged_in)
	{
	
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
	
	$msg .= "
	<label>" . __("Security code:", 'news') . "</label>" . $cap['image'] . "<br />
	<label for=\"captcha\">" . __("Confirm security code:", 'news') . "</label>
	<input class=\"input-text\" type='text' name='captcha' value='' /><br />";
	}
	return $msg;

}

function news_feed_content($data)
{
	$obj =& get_instance();
	
	$obj->load->model('news_model');
	
	$rows = $obj->news_model->get_list(array('limit' => 10, 'where' => array('news.lang <>' => '0')));
	
	$contents = array();
	foreach ($rows as $key => $row)
	{
		$contents[$key]['title'] = $row['title'];
		$contents[$key]['url'] = site_url( $row['lang'] . '/news/' . $row['uri']);
		$contents[$key]['body'] = $row['body'];
		if (isset($row['image']['file']))
		{
			$contents[$key]['img'] = site_url('media/images/s/' . $row['image']['file']);
		}

		$contents[$key]['date'] = (isset($row['date'])) ? $row['date'] : '';
		$contents[$key]['author'] = $row['author'];

	}
	return array_merge($data, $contents);
}
	
function news_search_result($rows)
{

	$obj =& get_instance();

	if ($tosearch = $obj->input->post('tosearch'))
	{
		$where[] = " (body LIKE '%". ereg_replace(" ", "%' AND body LIKE '%", $obj->db->escape_str($tosearch)). "%') ";
		$where[] = " (n.title LIKE '%". ereg_replace(" ", "%' AND n.title LIKE '%", $obj->db->escape_str($tosearch)). "%') ";
		

		$sql = "SELECT n.id result_order, c.title AS result_type, n.title AS result_title, CONCAT('" . site_url('news') . "/', n.uri) AS result_link, CONCAT('... ', SUBSTRING(body, LOCATE('" . $obj->db->escape_str($tosearch) . "', body) - 50 , 200), '... ') AS result_text FROM " . $obj->db->dbprefix('news') . " n " .
		" LEFT JOIN " . $obj->db->dbprefix('news_cat') . " c ON n.cat=c.id " .
		(count($where) > 0 ? " WHERE " . join(" OR ", $where) : "") .
		" ORDER BY result_order";
		

		$query = $obj->db->query($sql);
		
		if ($query->num_rows() > 0 )
		{
			$list = $query->result_array();
			$rows = array_merge_recursive($rows, $list);
			return $rows;
		}
		else
		{
			return $rows;
		}
		
	}
}

?>