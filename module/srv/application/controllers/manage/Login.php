<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	protected $data = array('username' => null);


	public function __construct()
	{
		parent::__construct();
		$this->config->load('rsync');
	}

	public function index()
	{
		if($this->input->post())
		{
			$this->verifylogin();
		}
		else
		{
			$this->render_login();
		}

	}

	/**
	 * レンダリングログイン
	 */
	private function render_login()
	{
		$this->load->view('manage/login');
	}

	/**
	 * ユーザーのログインを確認する
	 */
	public function verifylogin()
	{
		try
		{
			$arr_auth = config_item('manage');

			$this->form_validation->set_rules('username', 'username', 'trim|required');
			$this->form_validation->set_rules('password', 'password', 'trim|required');
			if ($this->form_validation->run() === false)
			{
				$this->session->set_flashdata('error_message', validation_errors());
				$this->render_login();
				return;
			}
			else
			{
				$ip_address = get_ip_address();
				$user_name = $this->input->post('username');
				$pass_word = $this->input->post('password');
				$message = "";
				foreach ($arr_auth as  $arr_ip_address)
				{
					if(!in_array ($ip_address, $arr_ip_address['ip_address']))
					{
						$this->session->set_flashdata('error_message', $ip_address);
						$this->render_login();
						return;
					}
				}
				$accounts = $arr_auth['acl']['accounts'];
				foreach ($accounts as $key => $value)
				{
					if($user_name === $key && hash ( "sha256", $pass_word ) === $value)
					{
						$this->session->set_userdata('logged_in', $user_name);
						redirect('operator');
						return;
					}
					else
					{
						$message = "ログインできませんでした。";
					}
				}
				if($message != "")
				{
					$this->session->set_flashdata('error_message', $message);
					$this->render_login();
					return;
				}
			}
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
		}
	}

	/**
	 * ログアウト
	 */
	public function logout()
	{
		$this->session->unset_userdata('logged_in');
		$this->session->sess_destroy();
		redirect('login');
	}
}
