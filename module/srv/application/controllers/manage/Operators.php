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
	private $arr_agreement = array();

	public function index1()
	{
		$per_page = $this->ajax_pagination_operators->per_page;
		$config = $this->config_ajax_paging('#operators_list', $this->operators_model, 'operators/ajax_pagination_operators_data', $per_page, null);
		$this->ajax_pagination_operators->initialize($config);

		$operators = $this->operators_model->get_rows([
				'limit' => $per_page
		]);
		$this->data['operators'] = $this->set_list_operators($operators);

		// load the view
		$this->render_list();
	}

	/**
	 * ページネーション処理に必要な情報を初期化する
	 *
	 * @param
	 *        	$target
	 * @param
	 *        	$model_name
	 * @param
	 *        	$url
	 * @param
	 *        	$per_page
	 */
	private function config_ajax_paging($target, $model_name, $url, $per_page, $input)
	{
		// total rows count
		if($input)
		{
			$params = ['param_search' => $input];
			$totalRec = count($model_name->get_rows($params));
		}
		else
		{
			$totalRec = count($model_name->get_rows());
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
		$search_name = $this->input->post('search_name');
		$service_provider = $this->input->post('service_provider');
		$param_search = [
				'search_name' => $search_name,
				'service_provider' => $service_provider
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
					'param_search' => $param_search
			];
		}
		else
		{
			$param = [
			'start' => $offset,
			'limit' => $per_page
			];
		}
		$this->ajax_pagination_operators->initialize($config);
		$operators = $this->operators_model->get_rows($param);
		$this->data['operators'] = $this->set_list_operators($operators);

		// load the view
		$agreements = $this->get_agreement();
		$this->data['condition_service_providers'] = array();
		foreach ($agreements as $agreement)
		{
			array_push($this->data['condition_service_providers'], $agreement["id"]);
		}
		$this->data['agreements'] = $agreements;

		$this->load->view('manage/operator/operators_ajax_pagination_data', $this->data);
	}

	/**
	 *
	 */
	public function render_list()
	{
		$agreements = $this->get_agreement();
		$this->data['condition_service_providers'] = array();
		foreach ($agreements as $agreement)
		{
			array_push($this->data['condition_service_providers'], $agreement["id"]);
		}
		$this->data['agreements'] = $agreements;

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
		$param = null;
		$search_name = $this->input->post('search_name');
		$service_provider = $this->input->post('service_provider');
		$search_param = [
				'search_name' => $search_name,
				'service_provider' => $service_provider
		];
		if ($this->input->post())
		{
			$param = [
					'limit' => $per_page,
					'param_search' => $search_param
			];
		}
		else
		{
			$param = [
					'limit' => $per_page
			];
		}

		$operators = $this->operators_model->get_rows($param);
		$this->data['operators'] = $this->set_list_operators($operators);
		$this->render_list();
	}

	/**
	 * リストを保存
	 */
	private function set_list_operators($operators)
	{
		$arr_operators = array();
		foreach ($operators as $operator)
		{
			$arr_service_providers = $this->operator_service_providers_model->get_data($operator['operator_id']);
			foreach ($arr_service_providers as $service_provider)
			{
				if ($service_provider['contract_status'] === self::$CONTRACT_STATUS_ONE)
				{
					$operator['service_provider_' . $service_provider['service_provider']] = self::$CONTRACT_STATUS_ONE;
				}
				else
				{
					$operator['service_provider_' . $service_provider['service_provider']] = self::$CONTRACT_STATUS_ZERO;
				}
			}
			array_push($arr_operators, $operator);
		}
		return $arr_operators;
	}

	/**
	 * 　データサービスプロバイダを取得する
	 */
	private function get_agreement()
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
		$this->data['agreements'] = $this->get_agreement();
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
				foreach ($this->arr_agreement as $data)
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
		$this->load->helper('form');
		$this->load->helper('url');
		$this->form_validation->set_rules('business_name', '事業者名', 'trim|required');
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
		$this->session->set_flashdata('arr_ip_address_crt', $arr_ip_address_crt);
	}

	/**
	 * Validate service provider create
	 */
	private function check_validate_service_provider_crt()
	{
		$this->arr_agreement = $this->get_agreement();
		foreach ($this->arr_agreement as $agreement)
		{
			$input_agreement_check = $this->input->post('chk_input_agreement_' . $agreement["id"]);
			$indentify_code_check = $this->input->post('indentify_code_' . $agreement["id"]);
			if ($input_agreement_check === self::$CONTRACT_STATUS_ONE)
			{
				$this->form_validation->set_rules('indentify_code_' . $agreement["id"], $agreement["name"], 'required|max_length[255]');
			}
		}
	}

	/**
	 * 　更新アクション
	 */
	public function update()
	{
		log_message('debug', "------------function update------------");
		$id = $this->uri->segment(4);
		log_message('debug', "--------------------------id : " . $id);
		$this->data['agreements'] = $this->get_agreement();
		$this->set_data_update($id);
		if ($this->input->post())
		{
			$this->session->set_flashdata('business_name', $this->input->post('business_name'));
			$this->session->set_flashdata('start_date', $this->input->post('start_date'));
			$this->session->set_flashdata('end_date', $this->input->post('end_date'));
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
		$arr_agreement = $this->get_agreement();
		$this->service_providers = $this->operator_service_providers_model->get_by_id($operator_id);
		foreach ($this->service_providers as $service_provider)
		{
			$input_agreement_check = $this->input->post('chk_input_agreement_' . $service_provider["service_provider"]);
			$this->session->set_flashdata('chk_input_agreement_' . $service_provider["service_provider"], $input_agreement_check);
			$this->session->set_flashdata('identifying_code_' . $service_provider["service_provider"], $this->input->post('identifying_code_' . $service_provider["service_provider"]));
			if ($input_agreement_check === self::$CONTRACT_STATUS_ONE)
			{
				foreach ($arr_agreement as $agreement)
				{
					if ($service_provider["service_provider"] == $agreement["id"])
					{
						$this->form_validation->set_rules('identifying_code_' . $service_provider["service_provider"], $agreement["name"], 'required|max_length[255]');
						$this->session->set_flashdata('error_message_update_1', "利用終了日は利用開始");
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
		$this->session->set_flashdata('arr_ip_address_id_delete', $this->arr_ip_address_id_delete);
		$this->session->set_flashdata('arr_ip_address', $arr_ip_address);
	}

	/**
	 * Validate IP Address
	 *
	 * @param string $ip
	 *        	IP address
	 * @param string $which
	 *        	IP protocol: 'ipv4' or 'ipv6'
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
}
