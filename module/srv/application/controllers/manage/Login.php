<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class login extends CI_Controller {
	protected $data = array('username' => null);
	public function index()
	{
		$this->load->helper('url');
		$this->load->view('manage/login');
	}

	public function validation()
	{
		$this->form_validation->set_rules('username', 'username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'username', 'trim|required|xss_clean');
		if($this->input->post('username') == ''){
			$this->session->set_flashdata('error_message', 'username not null');
			$this->data['username'] = $this->form_validation->set_value('username');
			redirect('login');
		}else if($this->input->post('password') == ''){
			$this->session->set_flashdata('error_message', 'password not null');
			$this->data['username'] = $this->form_validation->set_value('username');
			redirect('login');
		}else{
			if ($this->form_validation->run())
			{

			}
			else
			{
				if($this->input->post('username') == 'duannk' && $this->input->post('password') == '123'){
					log_message('debug', '=========: ' . 'log--4');
					$this->render_form();
				}else{
					$this->session->set_flashdata('error_message', 'Invalid username or password.');
					$this->data['username'] = $this->form_validation->set_value('username');
					redirect('login');
				}
			}
		}
	}
	private function render_form(){
		redirect('operators');
	}
}
