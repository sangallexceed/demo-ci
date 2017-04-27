<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Operator_service_providers_model extends CI_Model
{
	private $table_name = 'operator_service_providers';

	public function __construct()
	{
		parent::__construct();
	}

	public function get_table_name()
	{
		return $this->table_name;
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
		return $query->result_array();
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
	 * 掲示板の承認ステータスを更新する。
	 *
	 * @param
	 *        	$id
	 * @param
	 *        	$post
	 */
	public function update_data($id, $service_provider_id, $input_agreement, $indentify_code)
	{
		$data = [
				'updated_at' => date('Y-m-d H:i:s'),
				'contract_status' => $input_agreement,
				'identifying_code' => $indentify_code
		];
		$this->db->where('operator_id', $id);
		$this->db->where('service_provider', $service_provider_id);
		$this->db->update($this->table_name, $data);
	}

}
