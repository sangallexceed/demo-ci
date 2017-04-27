<?php
use phpDocumentor\Reflection\Types\This;

defined('BASEPATH') or exit('No direct script access allowed');

class Operators extends My_Controller
{

	function __construct()
	{
		parent::__construct();
	}
	protected static $search_id = 'search_agreement';
	protected static $CONTRACT_STATUS_ZERO = '0';
	protected static $CONTRACT_STATUS_ONE = '1';
	private $count_error = 0;
	private $message_error = "";
	private $arr_ip_address_id_delete = array();
	private $service_providers = array();
	private $arr_service_providers = array();

	public function index()
	{
		$per_page = $this->ajax_pagination_operators->per_page;
		$config = $this->config_ajax_paging('#operators_list', $this->operators_model, 'operators/ajax_pagination_operators_data', $per_page, null);
		$this->ajax_pagination_operators->initialize($config);
		$arr_service_providers = $this->get_service_providers();
		$this->data['operators'] = $this->operators_model->get_rows([
				'limit' => $per_page,
				'arr_service_providers' => $arr_service_providers
		]);
		// load the view
		$this->render_list();
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
		if($input)
		{
			$params = [
					'param_search' => $input,
					'arr_service_providers' => $arr_service_providers
			];
			$totalRec = count($model_name->get_rows($params));
		}
		else
		{
			$params = ['arr_service_providers' => $arr_service_providers];
			$totalRec = count($model_name->get_rows($params));
		}
		//$totalRec = count($model_name->get_rows());
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
		$arr_service_providers = $this->get_service_providers();
		$param_search = [
				'column' => $column,
				'sort_type' => $sort_type,
				'search_name' => $search_name,
				'service_provider' => $service_provider,
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
		$config = $this->config_ajax_paging('#operators_list', $this->operators_model, 'operators/ajax_pagination_operators_data', $per_page, $param_search);
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
		$this->data['condition_service_providers'] = array();
		foreach ($arr_service_providers as $service_provider_item)
		{
			array_push($this->data['condition_service_providers'], $service_provider_item["id"]);
		}
		$this->data['arr_service_providers'] = $arr_service_providers;

		$this->load->view('manage/operator/operators_ajax_pagination_data', $this->data);
	}

	/**
	 *
	 */
	public function render_list()
	{
		$arr_service_providers = $this->get_service_providers();
		$this->data['condition_service_providers'] = array();
		foreach ($arr_service_providers as $service_provider_item)
		{
			array_push($this->data['condition_service_providers'], $service_provider_item["id"]);
		}
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
		$per_page = $this->ajax_pagination_operators->per_page;
		$config = $this->config_ajax_paging('#operators_list', $this->operators_model, 'operators/ajax_pagination_operators_data', $per_page, $this->input->post());
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
		$this->data['count_ip_address_crt'] = 1;
		$this->data['arr_service_providers'] = $this->get_service_providers();
		if ($this->input->post())
		{
			if ($this->check_validate_create())
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
		$count_ip_address_crt = $this->input->post('count_ip_address_crt');

		// insert table operator
		$param_operator = [
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
				'deleted_at' => null,
				'operator_name' => $this->input->post('business_name'),
				'start_date' => $this->input->post('start_date'),
				'end_date' => $this->input->post('end_date')
		];
		$this->operators_model->insert_data($param_operator);
		// insert operators success
		if ($this->db->affected_rows() > 0)
		{
			// get operator_id
			$model = $this->operators_model->get_id($this->input->post('business_name'));

			if (is_null($model))
			{
				$this->session->set_flashdata('error_message', "id null");
				redirect('operators/create');
			}
			else
			{
				for ($i = 0; $i <= $count_ip_address_crt; $i++)
				{
					$ip_address = $this->input->post('ip_address_' . $i);
					if (isset($ip_address) && !empty($ip_address))
					{
						$param_operator_ip_addresss = [
								'created_at' => date('Y-m-d H:i:s'),
								'updated_at' => date('Y-m-d H:i:s'),
								'deleted_at' => null,
								'operator_id' => $model->operator_id,
								'ip_address' => $ip_address
						];
						// insert operator_ip_addresss
						$this->operator_ip_addresss_model->insert_data($param_operator_ip_addresss);
					}
				}
				//
				foreach ($this->arr_service_providers as $data)
				{
					$input_agreement = $this->input->post('chk_input_agreement_' . $data["id"]);
					$indentify_code = $this->input->post('indentify_code_' . $data["id"]);
					$param_operator_service_providers = [
							'created_at' => date('Y-m-d H:i:s'),
							'updated_at' => date('Y-m-d H:i:s'),
							'deleted_at' => null,
							'operator_id' => $model->operator_id,
							'service_provider' => $data["id"],
							'contract_status' => $input_agreement,
							'identifying_code' => $indentify_code
					];
					$this->operator_service_providers_model->insert_data($param_operator_service_providers);
				}
				redirect('operators');
			}
		}
	}

	/**
	 * Validate create
	 */
	private function check_validate_create()
	{
		$this->form_validation->set_rules('business_name', '事業者名', 'trim|required');
		if ($this->form_validation->run() === FALSE)
		{
			$this->session->set_flashdata('error_business_name_crt', validation_errors());
		}
		$this->form_validation->set_rules('start_date', '利用開始日', 'callback_date_check');
		$this->form_validation->set_rules('end_date', '利用終了日', 'callback_date_check');

		$business_name = $this->input->post('business_name');
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');

		// check service provider
		$this->check_validate_service_provider_crt();
		// check access restriction
		$this->check_validate_access_restriction_crt();

		if ($this->form_validation->run() === FALSE)
		{
			log_message('debug', "=========================error:" . validation_errors());
			$this->session->set_flashdata('error_message_create', validation_errors());
			return false;
		}
		if (strlen($start_date) > 0 && strlen($end_date) > 0 && strtotime($start_date) > strtotime($end_date))
		{
			$this->session->set_flashdata('error_message_create', "利用終了日は利用開始日以降に指定してください");
			return false;
		}
		if ($this->count_error > 0 && isset($this->message_error))
		{
			$this->session->set_flashdata('error_message_create', $this->message_error);
			return false;
		}
		return true;
	}

	/**
	 * Validate Access restriction
	 */
	private function check_validate_access_restriction_crt()
	{
		$arr_ip_address_crt = array();
		$this->count_error = 0;
		$this->message_error = '';
		$count_ip_address_crt = $this->input->post('count_ip_address_crt');
		for ($i = 0; $i <= $count_ip_address_crt; $i++)
		{
			$ip_address = $this->input->post('ip_address_' . $i);
			if (isset($ip_address) && !empty($ip_address))
			{
				$ip_addresss = array(
						'ip_address' => $ip_address
				);
				array_push($arr_ip_address_crt, $ip_addresss);
				// check ipv4 for ip_address
				if (!$this->valid_ip($ip_address, "ipv4"))
				{
					$this->message_error = '正しい形式で入力してください';
					$this->count_error = 1;
				}
				else
				{
					// get ip_address
					$model_ip_addresss = $this->operator_ip_addresss_model->get_ip_address($ip_address);
					// check exist ip_address
					if ($model_ip_addresss != 0)
					{
						$this->message_error = 'Ip address existing';
						$this->count_error = 1;
					}
				}
			}
		}
		//$this->session->set_flashdata('arr_ip_address_crt', $arr_ip_address_crt);
		$this->data['arr_ip_address_crt'] = $arr_ip_address_crt;
	}

	/**
	 * Validate service provider create
	 */
	private function check_validate_service_provider_crt()
	{
		$this->arr_service_providers = $this->get_service_providers();
		$this->session->sess_destroy();
		foreach ($this->arr_service_providers as $service_provider)
		{
			$input_agreement_check = $this->input->post('chk_input_agreement_' . $service_provider["id"]);
			$indentify_code_check = $this->input->post('indentify_code_' . $service_provider["id"]);
			if ($input_agreement_check === self::$CONTRACT_STATUS_ONE)
			{
				$this->form_validation->set_rules('indentify_code_' . $service_provider["id"], $service_provider["name"], 'required|max_length[255]');
				if ($this->form_validation->run() === FALSE)
				{
					$this->session->set_flashdata('error_service_provider_'. $service_provider["id"],validation_errors());
				}
			}
		}
	}

	/**
	 * 　更新アクション
	 */
	public function update()
	{
		$id = $this->uri->segment(4);
		$this->data['arr_service_providers'] = $this->get_service_providers();
		$this->set_data_update($id);
		if ($this->input->post())
		{
			$this->data['business_name'] = $this->input->post('business_name');
			$this->data['start_date'] = $this->input->post('start_date');
			$this->data['end_date'] = $this->input->post('end_date');
			if ($this->check_validate_update($id))
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
		$operator = $this->operators_model->get_by_id($id);
		$ip_addresss = $this->operator_ip_addresss_model->get_by_id($id);
		$service_providers = $this->operator_service_providers_model->get_by_id($id);
		if (is_null($operator))
		{
			show_404();
		}
		//
		$this->data['operator'] = $operator;
		$this->data['ip_addresss'] = $ip_addresss;
		$this->data['service_providers'] = $service_providers;
		$this->data['count_ip_address'] = 1;
		$this->data['count_ip_address_id_delete'] = 1;
	}

	private function update_operator()
	{
		$operator_id = $this->input->post('operator_id');

		$count_ip_address = $this->input->post('count_ip_address');
		// insert table operator
		$condition = $this->input->post();
		$this->operators_model->update_data($operator_id, $condition);

		for ($i = 1; $i <= $count_ip_address; $i++)
		{
			$ip_address_id = $this->input->post('operator_ip_address_' . $i);
			$ip_address = $this->input->post('ip_address_' . $i);
			log_message('debug', "-----------ip_address: " . $ip_address);
			if ((isset($ip_address_id) && !empty($ip_address_id)) && (isset($ip_address) && !empty($ip_address)))
			{
				$this->operator_ip_addresss_model->update_data($ip_address_id, $operator_id, $ip_address);
			}
			elseif (!isset($ip_address_id) || empty($ip_address_id))
			{
				if (isset($ip_address) && !empty($ip_address))
				{
					$param_operator_ip_addresss = [
							'created_at' => date('Y-m-d H:i:s'),
							'updated_at' => date('Y-m-d H:i:s'),
							'deleted_at' => null,
							'operator_id' => $operator_id,
							'ip_address' => $ip_address
					];
					// insert operator_ip_addresss
					$this->operator_ip_addresss_model->insert_data($param_operator_ip_addresss);
				}
			}
		}

		// delete operator_ip_addresss
		log_message('debug', "-----------arr_ip_address_id_delete: " . print_r($this->arr_ip_address_id_delete, true));
		foreach ($this->arr_ip_address_id_delete as $ip_address_id_delete)
		{
			if (isset($ip_address_id_delete) && !empty($ip_address_id_delete))
			{
				$this->operator_ip_addresss_model->delete_row($operator_id, $ip_address_id_delete);
			}
		}
		// update operator_service_providers
		foreach ($this->service_providers as $service_provider)
		{
			$service_provider_id = $service_provider["service_provider"];
			$input_agreement = $this->input->post('chk_input_agreement_' . $service_provider["service_provider"]);
			$indentify_code = $this->input->post('identifying_code_' . $service_provider["service_provider"]);
			$this->operator_service_providers_model->update_data($operator_id, $service_provider_id, $input_agreement, $indentify_code);
		}
		redirect('operators');
	}

	/**
	 * Validate update
	 */
	private function check_validate_update($operator_id)
	{
		$this->form_validation->set_rules('business_name', '事業者名', 'required');
		if ($this->form_validation->run() === FALSE)
		{
			$this->session->set_flashdata('error_business_name_upd', validation_errors());
		}
		$this->form_validation->set_rules('start_date', '利用開始日', 'callback_date_check');
		$this->form_validation->set_rules('end_date', '利用終了日', 'callback_date_check');

		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');

		// check service provider
		$this->check_validate_service_provider($operator_id);
		// check access restriction
		$this->check_validate_access_restriction();

		if ($this->form_validation->run() === FALSE)
		{
			log_message('debug', "-----------value form: " . set_value("business_name"));
			$this->session->set_flashdata('error_message_update', validation_errors());
			return false;
		}
		if (strlen($start_date) > 0 && strlen($end_date) > 0 && strtotime($start_date) > strtotime($end_date))
		{
			$this->session->set_flashdata('error_message_update', "利用終了日は利用開始日以降に指定してください");
			return false;
		}
		if ($this->count_error > 0 && isset($this->message_error))
		{
			$this->session->set_flashdata('error_message_update', $this->message_error);
			return false;
		}
		return true;
	}

	/**
	 * Validate service provider
	 */
	private function check_validate_service_provider($operator_id)
	{
		$this->session->sess_destroy();
		$arr_service_providers = $this->get_service_providers();
		$this->service_providers = $this->operator_service_providers_model->get_by_id($operator_id);
		foreach ($this->service_providers as $service_provider)
		{
			$input_agreement_check = $this->input->post('chk_input_agreement_' . $service_provider["service_provider"]);
			$this->session->set_flashdata('chk_input_agreement_' . $service_provider["service_provider"], $input_agreement_check);
			$this->session->set_flashdata('identifying_code_' . $service_provider["service_provider"], $this->input->post('identifying_code_' . $service_provider["service_provider"]));
			if ($input_agreement_check === self::$CONTRACT_STATUS_ONE)
			{
				foreach ($arr_service_providers as $service_provider_item)
				{
					if ($service_provider["service_provider"] == $service_provider_item["id"])
					{
						$this->form_validation->set_rules('identifying_code_' . $service_provider["service_provider"], $service_provider_item["name"], 'required|max_length[255]');
						if ($this->form_validation->run() === FALSE)
						{
							$this->session->set_flashdata('error_service_provider_upd_'. $service_provider["service_provider"],validation_errors());
						}
					}
				}
			}
		}
	}

	/**
	 * Validate Access restriction
	 */
	private function check_validate_access_restriction()
	{
		$count_ip_address = $this->input->post('count_ip_address');
		$this->count_error = 0;
		$this->message_error = '';
		$arr_ip_address = array();
		$this->arr_ip_address_id_delete = array();
		$count_arr_ip_address_id_delete = $this->input->post('count_arr_ip_address_id_delete');
		for ($i = 1; $i <= $count_arr_ip_address_id_delete; $i++)
		{
			$ip_address_id_delete = $this->input->post('ip_address_id_delete_' . $i);
			if (isset($ip_address_id_delete) && !empty($ip_address_id_delete))
			{
				array_push($this->arr_ip_address_id_delete, $ip_address_id_delete);
			}
		}
		for ($i = 1; $i <= $count_ip_address; $i++)
		{
			$ip_address = $this->input->post('ip_address_' . $i);
			$ip_address_id = $this->input->post('operator_ip_address_' . $i);
			$ip_address_id_delete = $this->input->post('delete_operator_ip_address_' . $i);
			log_message('debug', "-----------arr_ip_address_id_delete: " . $ip_address_id_delete);
			if (isset($ip_address_id_delete) && !empty($ip_address_id_delete))
			{
				array_push($this->arr_ip_address_id_delete, $ip_address_id_delete);
			}
			if (isset($ip_address) && !empty($ip_address))
			{
				$ip_addresss = array(
						'ip_address' => $ip_address,
						'ip_address_id' => $ip_address_id
				);
				array_push($arr_ip_address, $ip_addresss);
				if (!$this->valid_ip($ip_address, "ipv4"))
				{
					$this->message_error = '正しい形式で入力してください';
					$this->count_error = 1;
				}
			}
		}
		$this->data['arr_ip_address_id_delete'] = $this->arr_ip_address_id_delete;
		$this->data['arr_ip_address'] = $arr_ip_address;
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
	 * date書式チェック
	 *
	 * @param unknown $date
	 * @return boolean
	 */
	public function arrangementData()
	{
		log_message('debug', "------------------------function arrangementData--------------");
		log_message('debug', "------------------------: " . $this->input->post('column'));
 		$per_page = $this->ajax_pagination_operators->per_page;
 		$config = $this->config_ajax_paging('#operators_list', $this->operators_model, 'operators/ajax_pagination_operators_data', $per_page, $this->input->post());
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

 		$this->data['operators']= $this->operators_model->get_rows($param);
		// load the view
		$arr_service_providers = $this->get_service_providers();
		$this->data['condition_service_providers'] = array();
		foreach ($arr_service_providers as $service_provider_item)
		{
			array_push($this->data['condition_service_providers'], $service_provider_item["id"]);
		}
		$this->data['arr_service_providers'] = $arr_service_providers;

		$this->load->view('manage/operator/operators_ajax_pagination_data', $this->data);
	}
}
