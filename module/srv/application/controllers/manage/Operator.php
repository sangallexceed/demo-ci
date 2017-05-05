<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Operator extends CI_Controller
{
	protected static $CONTRACT_STATUS_ZERO = '0';
	protected static $CONTRACT_STATUS_ONE = '1';
	private $count_error = 0;
	private $message_error = "";
	private $service_providers = array();
	private $arr_service_providers = array();

	public function __construct()
	{
		parent::__construct();

		// check user login
		$check_login_page = false;
		$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		if (strpos($actual_link, 'manage/operator') !== false || strpos($actual_link, 'manage/operator/search') !== false || strpos($actual_link, 'manage/operator/create') !== false || strpos($actual_link, 'manage/operator/update') !== false)
		{
			$check_login_page = true;
		}
		if ($check_login_page)
		{
			if (!$this->session->userdata('logged_in'))
			{
				redirect('login', 'refresh');
			}
		}
	}

	public function index()
	{
		try
		{
			$per_page = $this->ajax_pagination_operators->per_page;
			$config = $this->config_ajax_paging('#operators_list', $this->operators_model, 'operator/ajax_pagination_operators_data', $per_page, null);
			$this->ajax_pagination_operators->initialize($config);
			$arr_service_providers = $this->get_service_providers();
			$this->data['operators'] = $this->operators_model->get_rows([
					'limit' => $per_page,
					'arr_service_providers' => $arr_service_providers
			]);
			// load the view
			$this->render_list();
		}
		catch (Exception $e)
		{
			log_message('error', "=============================error : " .$e->getMessage());
		}
	}

	/**
	 * ページネーション処理に必要な情報を初期化する
	 *
	 * @param $target
	 * @param $model_name
	 * @param $url
	 * @param $per_page
	 */
	private function config_ajax_paging($target, $model_name, $url, $per_page, $input)
	{
		// total rows count
		$arr_service_providers = $this->get_service_providers();
		if ($input)
		{
			$params = [
					'param_search' => $input,
					'arr_service_providers' => $arr_service_providers
			];
			$totalRec = count($model_name->get_rows($params));
		}
		else
		{
			$params = [
					'arr_service_providers' => $arr_service_providers
			];
			$totalRec = count($model_name->get_rows($params));
		}
		// pagination configuration
		$config['target'] = $target;
		$config['base_url'] = base_url() . $url;
		$config['total_rows'] = $totalRec;
		$config['per_page'] = $per_page;

		return $config;
	}

	/**
	 * ページネーションがクリックされた時
	 */
	public function ajax_pagination_operators_data()
	{
		$page = $this->input->post('page');
		$column = $this->input->post('column');
		$sort_type = $this->input->post('sort');
		$search_name = $this->input->post('search_name');
		$service_provider = $this->input->post('service_provider');
		$sort_service_provider = $this->input->post('sort_service_provider');
		$arr_service_providers = $this->get_service_providers();
		$param_search = [
				'column' => $column,
				'sort_type' => $sort_type,
				'search_name' => $search_name,
				'service_provider' => $service_provider,
				'sort_service_provider' => $sort_service_provider
		];

		if (!$page)
		{
			$offset = 0;
		}
		else
		{
			$offset = $page;
		}

		$per_page = $this->ajax_pagination_operators->per_page;
		$config = $this->config_ajax_paging('#operators_list', $this->operators_model, 'operator/ajax_pagination_operators_data', $per_page, $param_search);
		$this->ajax_pagination_operators->initialize($config);

		if ($param_search)
		{
			$param = [
					'start' => $offset,
					'limit' => $per_page,
					'param_search' => $param_search,
					'arr_service_providers' => $arr_service_providers
			];
		}
		else
		{
			$param = [
					'start' => $offset,
					'limit' => $per_page,
					'arr_service_providers' => $arr_service_providers
			];
		}
		$this->ajax_pagination_operators->initialize($config);
		$this->data['operators'] = $this->operators_model->get_rows($param);

		// load the view
		$arr_service_providers = $this->get_service_providers();
		$this->data['condition_service_providers'] = $this->push_array($arr_service_providers);
		$this->data['arr_service_providers'] = $arr_service_providers;

		$this->load->view('manage/operator/operators_ajax_pagination_data', $this->data);
	}

	/**
	 * レンダリングリスト
	 */
	private function render_list()
	{
		$arr_service_providers = $this->get_service_providers();
		$this->data['condition_service_providers'] = $this->push_array($arr_service_providers);
		$this->data['arr_service_providers'] = $arr_service_providers;

		$this->load->view('manage/header');
		$this->load->view('manage/menu');
		$this->load->view('manage/operator/list', $this->data);
		$this->load->view('manage/footer');
	}

	/**
	 * 検索ボタンを押したとき
	 */
	public function search()
	{
		if (!is_null($this->input->post('btn_clear')))
		{
			redirect('operator');
		}
		$per_page = $this->ajax_pagination_operators->per_page;
		$config = $this->config_ajax_paging('#operators_list', $this->operators_model, 'operator/ajax_pagination_operators_data', $per_page, $this->input->post());
		$this->ajax_pagination_operators->initialize($config);
		$arr_service_providers = $this->get_service_providers();
		$params = null;
		$column = $this->input->post('column');
		$sort_type = $this->input->post('sort');
		$search_name = $this->input->post('search_name');
		$service_provider = $this->input->post('service_provider');

		$search_param = [
				'column' => $column,
				'sort_type' => $sort_type,
				'search_name' => $search_name,
				'service_provider' => $service_provider,
				'arr_service_providers' => $arr_service_providers
		];
		if ($this->input->post())
		{
			$params = [
					'limit' => $per_page,
					'param_search' => $search_param,
					'arr_service_providers' => $arr_service_providers
			];
		}
		else
		{
			$params = [
					'limit' => $per_page,
					'arr_service_providers' => $arr_service_providers
			];
		}
		$this->data['operators'] = $this->operators_model->get_rows($params);
		$this->render_list();
	}

	/**
	 * 　データサービスプロバイダを取得する
	 */
	private function get_service_providers()
	{
		$attr = config_item('service_provider');
		return $attr;
	}

	/**
	 * 　新規作成
	 */
	public function create()
	{
		$this->data['count'] = 1;
		$this->data['arr_service_providers'] = $this->get_service_providers();
		$this->data['error_date'] = "";
		if ($this->input->post())
		{
			if ($this->check_validate($operator_id = ''))
			{
				$this->insert();
			}
		}
		$this->load->view('manage/header');
		$this->load->view('manage/menu');
		$this->load->view("manage/operator/create", $this->data);
		$this->load->view('manage/footer');
	}

	/**
	 * 　インサート
	 */
	private function insert()
	{
		try
		{
			$count_ip_address = $this->input->post('count_ip_address');

			// insert table operator
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			if (!$start_date)
			{
				$start_date = null;
			}
			if (!$end_date)
			{
				$end_date = null;
			}
			$param_operator = [
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s'),
					'deleted_at' => null,
					'operator_name' => $this->input->post('business_name'),
					'start_date' => $start_date,
					'end_date' => $end_date
			];
			$insert_id = $this->operators_model->insert_data($param_operator);
			// insert operators success
			if ($insert_id)
			{
				for ($i = 0; $i <= $count_ip_address; $i++)
				{
					$ip_address = $this->input->post('ip_address_' . $i);
					if (isset($ip_address) && !empty($ip_address))
					{
						$param_operator_ip_addresss = [
								'created_at' => date('Y-m-d H:i:s'),
								'updated_at' => date('Y-m-d H:i:s'),
								'deleted_at' => null,
								'operator_id' => $insert_id,
								'ip_address' => $ip_address
						];
						// insert operator_ip_addresss
						$this->operator_ip_addresss_model->insert_data($param_operator_ip_addresss);
					}
				}
				//
				foreach ($this->arr_service_providers as $data)
				{
					$input_service_provider = $this->input->post('chk_input_service_provider_' . $data["id"]);
					$indentify_code = $this->input->post('identifying_code_' . $data["id"]);
					$param_operator_service_providers = [
							'created_at' => date('Y-m-d H:i:s'),
							'updated_at' => date('Y-m-d H:i:s'),
							'deleted_at' => null,
							'operator_id' => $insert_id,
							'service_provider' => $data["id"],
							'contract_status' => $input_service_provider,
							'identifying_code' => $indentify_code
					];
					$this->operator_service_providers_model->insert_data($param_operator_service_providers);
				}
				redirect('operator');
			}
		}
		catch (Exception $e)
		{
			log_message('error', $e->getMessage());
		}
	}

	/**
	 * チェックを有効にする
	 */
	private function check_validate($operator_id)
	{
		$this->form_validation->set_rules('business_name', '事業者名', 'trim|required|max_length[50]');
		$this->form_validation->set_rules('start_date', '利用開始日', 'callback_date_check');
		$this->form_validation->set_rules('end_date', '利用終了日', 'callback_date_check');

		$business_name = $this->input->post('business_name');
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');

		// check service provider
		if($operator_id)
		{
			$this->check_validate_service_provider_update($operator_id);
		}
		else
		{
			$this->check_validate_service_provider_crt();
		}
		// check access restriction
		$this->check_validate_access_restriction();

		if ($this->form_validation->run() === FALSE)
		{
			return false;
		}
		if (strlen($start_date) > 0 && strlen($end_date) > 0 && strtotime($start_date) > strtotime($end_date))
		{
			$this->data['error_date'] = "利用終了日は利用開始日以降に指定してください";
			return false;
		}
		if ($this->count_error > 0 && isset($this->message_error))
		{
			return false;
		}
		return true;
	}

	/**
	 * サービスプロバイダの作成を検証する
	 */
	private function check_validate_service_provider_crt()
	{
		$this->arr_service_providers = $this->get_service_providers();
		foreach ($this->arr_service_providers as $service_provider)
		{
			$input_service_provider_check = $this->input->post('chk_input_service_provider_' . $service_provider["id"]);
			$indentify_code_check = $this->input->post('indentify_code_' . $service_provider["id"]);
			if ($input_service_provider_check === self::$CONTRACT_STATUS_ONE)
			{
				$this->form_validation->set_rules('identifying_code_' . $service_provider["id"], $service_provider["name"], 'required|max_length[255]');
			}
		}
	}

	/**
	 * サービスプロバイダを検証する
	 *
	 * @param $operator_id
	 */
	private function check_validate_service_provider_update($operator_id)
	{
		// $this->session->sess_destroy();
		$arr_service_providers = $this->get_service_providers();
		$this->service_providers = $this->operator_service_providers_model->get_by_id($operator_id);
		foreach ($this->service_providers as $service_provider)
		{
			$input_service_provider_check = $this->input->post('chk_input_service_provider_' . $service_provider["service_provider"]);
			$this->session->set_flashdata('chk_input_service_provider_' . $service_provider["service_provider"], $input_service_provider_check);
			$this->session->set_flashdata('identifying_code_' . $service_provider["service_provider"], $this->input->post('identifying_code_' . $service_provider["service_provider"]));
			if ($input_service_provider_check === self::$CONTRACT_STATUS_ONE)
			{
				foreach ($arr_service_providers as $service_provider_item)
				{
					if ($service_provider["service_provider"] == $service_provider_item["id"])
					{
						$this->form_validation->set_rules('identifying_code_' . $service_provider["service_provider"], $service_provider_item["name"], 'required|max_length[255]');
					}
				}
			}
		}
	}

	/**
	 * 「アクセス制限の検証」をチェックする
	 */
	private function check_validate_access_restriction()
	{
		$count_ip_address = $this->input->post('count_ip_address');
		$this->count_error = 0;
		$arr_ip_address = array();
		for ($i = 1; $i <= $count_ip_address; $i++)
		{
			$this->message_error = '';
			$ip_address = $this->input->post('ip_address_' . $i);
			$ip_address_id = $this->input->post('operator_ip_address_' . $i);
			if (isset($ip_address) && !empty($ip_address))
			{
				$ip_addresss = array(
						'ip_address' => $ip_address,
						'ip_address_id' => $ip_address_id
				);
				if (!$this->valid_ip($ip_address, "ipv4"))
				{
					$this->message_error = '正しい形式で入力してください';
					$this->count_error = 1;
				}
				$ip_addresss['message_error_ip_address'] = $this->message_error;
				array_push($arr_ip_address, $ip_addresss);
			}
		}
		$this->data['arr_ip_address'] = $arr_ip_address;
	}

	/**
	 * 　更新アクション
	 */
	public function update()
	{
		$id = $this->uri->segment(4);
		$this->data['arr_service_providers'] = $this->get_service_providers();
		$this->set_data_update($id);
		$this->data['error_date'] = "";
		if ($this->input->post())
		{
			$this->data['business_name'] = $this->input->post('business_name');
			$this->data['start_date'] = $this->input->post('start_date');
			$this->data['end_date'] = $this->input->post('end_date');
			if ($this->check_validate($id))
			{
				$this->update_operator();
			}
		}
		$this->load->view('manage/header');
		$this->load->view('manage/menu');
		$this->load->view("manage/operator/update", $this->data);
		$this->load->view('manage/footer');
	}

	/**
	 * 承認画面にデータをセットする。
	 *
	 * @param unknown $id
	 */
	private function set_data_update($id)
	{
		try
		{
			$operator = $this->operators_model->get_by_id($id);
			$ip_addresss = $this->operator_ip_addresss_model->get_by_id($id);
			$service_providers = $this->operator_service_providers_model->get_by_id($id);
			//
			$this->data['operator'] = $operator;
			$this->data['ip_addresss'] = $ip_addresss;
			$this->data['service_providers'] = $service_providers;
			$this->data['count_ip_address'] = 1;
			$this->data['count_ip_address_id_delete'] = 1;
		}
		catch (Exception $e)
		{
			log_message('error', $e->getMessage());
		}
	}

	/**
	 * データを更新する
	 */
	private function update_operator()
	{
		$operator_id = $this->input->post('operator_id');

		$count_ip_address = $this->input->post('count_ip_address');
		// insert table operator
		$condition = $this->input->post();
		$this->operators_model->update_data($operator_id, $condition);

		$arr_ip_address = $this->data['ip_addresss'];
		try
		{
			// delete operator_ip_addresss
			foreach ($arr_ip_address as $ip_address_del)
			{
				$this->operator_ip_addresss_model->delete_row($ip_address_del['operator_ip_address_id']);
			}
			// insert operator_ip_addresss
			for ($i = 1; $i <= $count_ip_address; $i++)
			{
				$ip_address_id = $this->input->post('operator_ip_address_' . $i);
				$ip_address = $this->input->post('ip_address_' . $i);
				if (isset($ip_address) && !empty($ip_address))
				{
					$param_operator_ip_addresss = [
							'created_at' => date('Y-m-d H:i:s'),
							'updated_at' => date('Y-m-d H:i:s'),
							'deleted_at' => null,
							'operator_id' => $operator_id,
							'ip_address' => $ip_address
					];
					$this->operator_ip_addresss_model->insert_data($param_operator_ip_addresss);
				}
			}

			// delete and insert operator_service_providers
			foreach ($this->service_providers as $service_provider)
			{
				$operator_service_provider_id = $service_provider["operator_service_provider_id"];
				$service_provider_id = $service_provider["service_provider"];
				$input_service_provider_check = $this->input->post('chk_input_service_provider_' . $service_provider["service_provider"]);
				$indentify_code = $this->input->post('identifying_code_' . $service_provider["service_provider"]);
				// delete
				$this->operator_service_providers_model->delete_row($operator_service_provider_id, $operator_id);

				// insert
				$param_operator_service_providers = [
						'created_at' => date('Y-m-d H:i:s'),
						'updated_at' => date('Y-m-d H:i:s'),
						'deleted_at' => null,
						'operator_id' => $operator_id,
						'service_provider' => $service_provider_id,
						'contract_status' => $input_service_provider_check,
						'identifying_code' => $indentify_code
				];
				$this->operator_service_providers_model->insert_data($param_operator_service_providers);
			}
			redirect('operator');
		}
		catch (Exception $e)
		{
			log_message('error', $e->getMessage());
		}
	}

	/**
	 * Validate IP Address
	 *
	 * @param string $ip IP address
	 * @param string $which IP protocol: 'ipv4' or 'ipv6'
	 * @return bool
	 */
	public function valid_ip($ip, $which = '')
	{
		switch (strtolower($which))
		{
			case 'ipv4' :
				$which = FILTER_FLAG_IPV4;
				break;
			case 'ipv6' :
				$which = FILTER_FLAG_IPV6;
				break;
			default :
				$which = NULL;
				break;
		}

		return (bool)filter_var($ip, FILTER_VALIDATE_IP, $which);
	}

	/**
	 * date書式チェック
	 *
	 * @param unknown $date
	 * @return boolean
	 */
	public function date_check($date)
	{
		if (validate_date($date))
		{
			return true;
		}
		else
		{
			$this->form_validation->set_message('date_check', '{field}は無効値です。');
			return false;
		}
	}

	/**
	 * 配置データ
	 */
	public function arrangementData()
	{
		try
		{
			$per_page = $this->ajax_pagination_operators->per_page;
			$config = $this->config_ajax_paging('#operators_list', $this->operators_model, 'operator/ajax_pagination_operators_data', $per_page, $this->input->post());
			$this->ajax_pagination_operators->initialize($config);
			$param = null;
			$column = $this->input->post('column');
			$sort_type = $this->input->post('sort');
			$search_name = $this->input->post('search_name');
			$service_provider = $this->input->post('service_provider');
			$sort_service_provider = $this->input->post('sort_service_provider');
			$arr_service_providers = $this->get_service_providers();
			$search_param = [
					'column' => $column,
					'sort_type' => $sort_type,
					'sort_service_provider' => $sort_service_provider,
					'search_name' => $search_name,
					'service_provider' => $service_provider
			];
			if ($this->input->post())
			{
				$param = [
						'limit' => $per_page,
						'param_search' => $search_param,
						'arr_service_providers' => $arr_service_providers
				];
			}
			else
			{
				$param = [
						'limit' => $per_page,
						'arr_service_providers' => $arr_service_providers
				];
			}

			$this->data['operators'] = $this->operators_model->get_rows($param);
			// load the view
			$arr_service_providers = $this->get_service_providers();
			$this->data['condition_service_providers'] = $this->push_array($arr_service_providers);
			$this->data['arr_service_providers'] = $arr_service_providers;

			$this->load->view('manage/operator/operators_ajax_pagination_data', $this->data);
		}catch (Exception $e)
		{
			log_message('error', $e->getMessage());
		}
	}

	/**
	 * プッシュアレイ
	 *
	 * @param $arr_service_providers
	 * @return array
	 */
	private function push_array($arr_service_providers)
	{
		$condition_service_providers = array();
		foreach ($arr_service_providers as $service_provider_item)
		{
			array_push($condition_service_providers, $service_provider_item["id"]);
		}
		return $condition_service_providers;
	}
}
