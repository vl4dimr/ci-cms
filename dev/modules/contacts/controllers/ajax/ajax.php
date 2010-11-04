<?php  
/*
$Id$
*/


if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends Controller {
	var $settings = array();
	function Ajax()
	{
		parent::Controller();
		$this->template['module'] = "contacts";
		$this->config->load('config');
		
	}
	
	function form()
	{
		$this->load->view('ajax/form', $this->template);
	}
	
	function response()
	{
		if ($this->input->post('email') && $this->input->post('message') && $this->input->post('name') && $this->input->post('recaptcha_response_field')) {
			$this->load->helper('recaptcha');
			
			
			$privatekey = $this->config->item('privatekey');
			$resp = recaptcha_check_answer ($privatekey,
										$this->input->server('REMOTE_ADDR'),
										$this->input->post('recaptcha_challenge_field'),
										$this->input->post('recaptcha_response_field'));

			if (!$resp->is_valid) {
			// What happens when the CAPTCHA was entered incorrectly
				if($resp->error == 'incorrect-captcha-sol')
				{
					$jasonresp['errmessage'] = __('The entered text from the Captcha image is wrong.', 'contacts');
				}
				else
				{
					$jasonresp['errmessage'] = $resp->error ;
				}
			} else {
			// Your code here to handle a successful verification
				$this->load->library('email');

				$this->email->from($this->input->post('email'), $this->input->post('name') );
				$this->email->to($this->config->item('email'));
				$this->email->subject( __("Message from contact form", "contacts"));
				$this->email->message($this->input->post('message'));
				
				if($this->email->send())
				{
					$jasonresp['status'] = 'success';
					$jasonresp['errmessage'] = __('Message submitted succesfully!', 'contacts');
				}
				else
				{
					$jasonresp['errmessage'] = __('Email server error. Message could not be sent!', 'contacts');
				}
			}

		}
		else
		{
			$jasonresp['errmessage'] = 'Missing required fields!';

		}
		
		echo json_encode($jasonresp);
	}
}

?>