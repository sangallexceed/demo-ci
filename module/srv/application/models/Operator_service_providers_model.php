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
	 * @param $id
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
	 * @param $data
	 */
	public function insert_data($data)
	{
		$this->db->insert($this->table_name, $data);
	}

	/**
	 * 削除する
	 *
	 * @param $id
	 */
	public function delete_row($id, $operator_id)
	{
		$this->db->where('operator_service_provider_id', $id);
		$this->db->where('operator_id', $operator_id);
		$this->db->delete($this->table_name);
	}

}
