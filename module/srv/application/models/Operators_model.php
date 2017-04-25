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
	private function search_operators_condition($condition, $db)
	{
		if (!is_null($condition))
		{
			// 投稿日時
			$search_name = $condition['search_name'];
			$service_provider = $condition['service_provider'];
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
		}
	}

	/**
	 *
	 * @param
	 *
	 */
	public function record_count($condition)
	{
		$this->db->select('*');
		$this->db->from($this->table_name . ' OP');
		if (!is_null($condition))
		{
			$this->search_operators_condition($condition, $this->db);
		}
		return $this->db->count_all_results();
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

		if (isset($post['start_date']))
		{
			$param_operator = array_merge($param_operator, [
					"start_date" => $post['start_date']
			]);
		}
		if (isset($post['end_date']))
		{
			$param_operator = array_merge($param_operator, [
					"end_date" => $post['end_date']
			]);
		}
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
		$this->db->select('*');
		$this->db->from($this->table_name . ' OP');
		if(array_key_exists("param_search", $params))
		{
			$param_search = $params['param_search'];
			$this->search_operators_condition($param_search, $this->db);
		}
		if (array_key_exists("start", $params) && array_key_exists("limit", $params))
		{
			$this->db->limit($params['limit'], $params['start']);
		}
		else if (array_key_exists("limit", $params))
		{
			$this->db->limit($params['limit']);
		}
		$query = $this->db->get();
		return $query->result_array();
	}
}
