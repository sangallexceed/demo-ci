<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Operators_model extends CI_Model
{
	private $table_name = 'operators';

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * idで取得する
	 *
	 * @param
	 *        	$id
	 */
	public function get_by_id($id)
	{
		$query = $this->db->get_where($this->table_name, array(
				'operator_id' => $id,
				'deleted_at' => null
		));
		return $query->row();
	}

	/**
	 * データを登録する
	 *
	 * @param
	 *        	$data
	 */
	public function insert_data($data)
	{
		$this->db->insert($this->table_name, $data);
	}

	/**
	 *
	 * @param
	 *        	$ip_address
	 */
	public function search($ip_address)
	{
		$now = date("Y/m/d");

		$this->db->select('*');
		$this->db->from($this->table_name . ' OP');
		$this->db->join($this->operator_ip_addresss_model->get_table_name() . ' IP', 'OP.operator_id = IP.operator_id', 'INNER');

		// 事業者IPアドレス.IPアドレス　＝　入力.リモートIPアドレス
		$this->db->where('IP.ip_address = ', $ip_address);
		// 事業者IPアドレス.削除日時　＝　NULL
		$this->db->where('IP.deleted_at', NULL);

		// 事業者.利用開始日　＜＝　入力.処理日　または　＝　NULL
		$this->db->group_start();
		$this->db->where('OP.start_date <=', $now);
		$this->db->or_where('OP.start_date', NULL);
		$this->db->group_end();

		// 事業者.利用終了日　＞＝　入力.処理日　または　＝　NULL
		$this->db->group_start();
		$this->db->where('OP.end_date >=', $now);
		$this->db->or_where('OP.end_date', NULL);
		$this->db->group_end();

		// 事業者.削除日時　IS NULL
		$this->db->where('OP.deleted_at', NULL);

		return $this->db->get()->result_array();
	}

	/**
	 *
	 * @param unknown $condition
	 *
	 */
	private function get_data_operators_condition($condition, $db)
	{
		if (!is_null($condition))
		{
			// 投稿日時
			$search_name = $condition['search_name'];
			$service_provider = $condition['service_provider'];
			$collumn = $condition['search_name'];
			if (isset($service_provider) && !empty($service_provider))
			{
				$db->join($this->operator_service_providers_model->get_table_name() . ' AS SP', 'SP.operator_id = OP.operator_id', 'INNER');
				$db->where('SP.service_provider = ', $service_provider);
				$db->where('SP.contract_status', '1');
				$db->where('SP.deleted_at', NULL);
			}
			if (isset($search_name) && !empty($search_name))
			{
				$operator_names = explode(' ', $search_name);
				$db->group_start();
				foreach ($operator_names as $operator_name)
				{
					$operator_name = trim($operator_name);
					$db->or_where('OP.operator_name = ', $operator_name);
				}
				$db->group_end();
			}
			if(array_key_exists("column", $condition) && array_key_exists("sort_type", $condition))
			{
				if(isset($condition['column']) && !empty($condition['column']))
				{
					$this->db->order_by("OP.".$condition['column'], $condition['sort_type']);
				}
			}
		}
	}

	/**
	 *
	 * @param
	 *        	$limit
	 * @param
	 *        	$id
	 */
	public function fetch_data($limit, $start)
	{
		$this->db->limit($limit, $start);
		$query = $this->db->get($this->table_name);

		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}

	public function result_array()
	{
		$this->trigger_events(array(
				'result',
				'result_array'
		));

		$result = $this->response->result_array();

		return $result;
	}

	/**
	 * idで取得する
	 *
	 * @param
	 *        	$id
	 */
	public function get_id($operater_name)
	{
		$query = $this->db->get_where($this->table_name, array(
				'operator_name' => $operater_name
		));
		return $query->row();
	}

	/**
	 * 掲示板の承認ステータスを更新する。
	 *
	 * @param
	 *        	$id
	 * @param
	 *        	$post
	 */
	public function update_data($id, $post)
	{
		$param_operator = [
				'updated_at' => date('Y-m-d H:i:s'),
				'operator_name' => $post['business_name']
		];
		$start_date = $post['start_date'];
		$end_date = $post['end_date'];
		if(!$start_date)
		{
			$start_date = null;
		}
		$param_operator = array_merge($param_operator, [
				"start_date" => $start_date
		]);
		if(!$end_date)
		{
			$end_date = null;
		}
		$param_operator = array_merge($param_operator, [
				"end_date" => $end_date
		]);
		$this->db->where('operator_id', $id);
		$this->db->update($this->table_name, $param_operator);
	}

	/**
	 * 承認の掲示板投稿一覧の取得
	 *
	 * @param $params
	 */
	function get_rows($params = [])
	{
		$today = date("Y-m-d");
		$arr_service_providers = array();
		$deleted_at_null = '';
		$arr_service_providers = $params['arr_service_providers'];
		$this->db->select('OP.operator_id, OP.operator_name, OP.start_date, OP.end_date');
		foreach ($params['arr_service_providers'] as $service_provider)
		{
			$this->db->select(',CASE WHEN SP'.$service_provider['id'] .'.service_provider = '.$service_provider['id'] .' THEN  1 ELSE  0 END as service_provider_'.$service_provider['id'] .'', false);
		}
 		$this->db->from($this->table_name . ' OP');
		foreach ($params['arr_service_providers'] as $service_provider)
		{
			$this->db->join($this->operator_service_providers_model->get_table_name() . ' AS SP'.$service_provider['id'] .'', 'OP.operator_id = SP'.$service_provider['id'] .'.operator_id
					AND SP'.$service_provider['id'] .'.deleted_at IS NULL
					AND SP'.$service_provider['id'] .'.service_provider = '.$service_provider['id'].'
					AND SP'.$service_provider['id'] .'.contract_status = 1', 'LEFT');
		}
		$this->db->group_start();
		$this->db->where('OP.start_date <= "'.$today.'"');
		$this->db->or_where('OP.start_date IS NULL');
		$this->db->group_end();
		$this->db->group_start();
		$this->db->where('OP.end_date >= "'.$today.'"');
		$this->db->or_where('OP.end_date IS NULL');
		$this->db->group_end();

		if(array_key_exists("param_search", $params))
		{
			$param_search = $params['param_search'];
			$search_name = $param_search['search_name'];
			$service_provider = $param_search['service_provider'];
			if (isset($service_provider) && !empty($service_provider))
			{
				$this->db->where('SP'.$service_provider.'.service_provider IS NOT NULL');
			}
			if (isset($search_name) && !empty($search_name))
			{
				$operator_names = explode(' ', $search_name);
				$this->db->group_start();
				foreach ($operator_names as $operator_name)
				{
					$operator_name = trim($operator_name);
					if($operator_name)
					{
						$this->db->or_like('OP.operator_name', $operator_name);
					}
				}
				$this->db->group_end();
			}
			if(array_key_exists("column", $param_search) && array_key_exists("sort_type", $param_search))
			{
				$collumn = $param_search['column'];
				if(array_key_exists("sort_service_provider", $param_search))
				{
					$sort_service_provider = $param_search['sort_service_provider'];
					if($sort_service_provider != 'no_service_provider')
					{
						if(isset($collumn) && !empty($collumn))
						{
							$this->db->order_by('SP'.$sort_service_provider.'.'.$collumn, $param_search['sort_type']);
						}
					}
					else
					{
						if(isset($collumn) && !empty($collumn))
						{
							$this->db->order_by("OP.".$collumn, $param_search['sort_type']);
						}
					}
				}
			}
		}
		$this->db->where('OP.deleted_at', NULL);
		if (array_key_exists("start", $params) && array_key_exists("limit", $params))
		{
			$this->db->limit($params['limit'], $params['start']);
		}
		else if (array_key_exists("limit", $params))
		{
			$this->db->limit($params['limit']);
		}
		$query = $this->db->get();
		log_message('debug', "----------SQL :" . $this->db->last_query());
		return $query->result_array();
	}

}
