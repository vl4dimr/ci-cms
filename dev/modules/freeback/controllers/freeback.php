<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * $Id
 **/

class Freeback extends Controller
{

    function Freeback()
    {
        parent::Controller();
        $this->template['module']	= 'freeback';
		$this->template['title']	= 'Freeback form';
		$this->load->model('freeback_model');
	}

    function index()
    {
		$data = $this->freeback_model->getMailto(array('status'=>1));
		$this->template['mailto']	= $data;
		$pool = '0123456789';
		$str = '';
		for ($i = 0; $i < 6; $i++)
		{
			$str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
		}
		$word = $str;
		$this->load->plugin('captcha');
		$vals = array(
			'img_path'	 => './media/captcha/',
			'img_url'	 => site_url('media/captcha'). '/',
			'font_path'	 => APPPATH . 'modules/freeback/fonts/Fatboy_Slim.ttf',
			'img_width'	 => 150,
			'img_height' => 30,
			'expiration' => 1800,
			'word' => $word
		);

		$cap = create_captcha($vals);

		$data = array(
			'captcha_id'	=> '',
			'captcha_time'	=> $cap['time'],
			'ip_address'	=> $this->input->ip_address(),
			'word'			=> $cap['word']
		);
		$this->db->insert('captcha', $data);
		$this->template['captcha'] = $cap['image'];

		$this->layout->load($this->template, 'freeback_view');
    }
    function send (){
		if (!$this->input->post('captcha'))
		{
			$this->session->set_flashdata('notification', '<p style="color:#900">'.__('You must submit the security code that appears in the image'.'</p>', $this->template['module']));
			redirect('freeback');
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
			$this->session->set_flashdata('notification', '<p style="color:#900">'.__('You must submit the security code that appears in the image'.'</p>', $this->template['module']));
			redirect('freeback');
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username',__('Username', $this->template['module']),"trim|required|xss_clean");
		$this->form_validation->set_rules('email',__('Email', $this->template['module']),"trim|required|valid_email");
		$this->form_validation->set_rules('message',__('Message', $this->template['module']),"trim|required|min_length[4]|max_length[252]|xss_clean");
		$this->form_validation->set_error_delimiters('<p style="color:#900">', '</p>');
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('notification', __($this->form_validation->error_string(), $this->template['module']));
			redirect('freeback');
		}
		else
		{
			$this->load->library('email');
			$this->email->from($this->system->admin_email);
			$mailto = $this->freeback_model->getMailto(array('id'=>$this->input->post('responder')));
			if(sizeof($mailto)>0){				$this->email->to($mailto[0]['email'], $this->system->site_name);
				$this->db->where(array('id'=>$mailto[0]['id']));
				$this->db->set('hit', 'hit+1', FALSE);
				$this->db->update('freeback');			} else {				$this->email->to($this->system->admin_email, $this->system->site_name);
				$mailto[0]['title'] = 'admin';
			}
			$this->email->subject(sprintf(__("Message from %s", $this->template['module']), $this->system->site_name));
			$this->email->message(sprintf(__("Hello ".$mailto[0]['title'].",\n\nA new messsage from your site. These are the submitted information.\n\nUsername: %s\nEmail: %s\nIP: %s\n Message: %s\nThank you.", $this->template['module']), $this->input->post('username'), $this->input->post('email'), $this->input->ip_address(),$this->input->post('message')));
			if ( ! $this->email->send()) {
				$this->session->set_flashdata('notification', '<p style="color:#900">'.__('Cannot send Email'.'</p>', $this->template['module']));
			} else {
				$this->session->set_flashdata('notification', __('Message sended', $this->template['module']));
			}
			redirect('freeback');
		}
	}
}
