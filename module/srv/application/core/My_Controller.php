<?php

/**
 * Created by IntelliJ IDEA.
 * User: yamamura
 * Date: 2016/12/22
 * Time: 10:40
 */
class My_Controller extends CI_Controller {
	protected $data = array();

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper(array('url', 'language'));
	}

	protected function load_pagination($url, $count)
	{
		$this->load->library('pagination');
		$this->pagination->initialize(array(
			'base_url' => $url,
			'total_rows' => $count
		));
	}

	protected function is_post_request()
	{
		return $this->is_http_request('POST');
	}

	protected function is_get_request()
	{
		return $this->is_http_request('GET');
	}

	private function is_http_request($method)
	{
		return $this->input->method(true) === $method;
	}
	
	protected function save_search_condition_session($search_id,$query_array) {
		if ($this->session->has_userdata($search_id)) {
			$this->session->unset_userdata($search_id);
		}
		$this->session->set_userdata($search_id, $query_array);
	}
	
	protected function load_search_condition_session($search_id) {
		if (!is_null($search_id) && $this->session->has_userdata($search_id)) {
			$search = $this->session->userdata($search_id);
			return $search;
		}
		return null;
	}
	
	/**
	 * PC・スマホを判定して、viewを切り替えます。
	 * @param unknown $view
	 * @return string
	 */
	protected function get_theme($view) {
		// スマホ判定
		if ($this->agent->is_mobile()) {
			return 'sp/' . $view;
		}
		return 'pc/' . $view;
	}
	
	/**
	 * PC・スマホを判定して、paginationを切り替えます。
	 */
	protected function get_ajax_pagination() {
		// スマホ判定
		if ($this->agent->is_mobile()) {
			return $this->ajax_pagination_sp;
		}
		return $this->ajax_pagination_pc;
	}
	
}