<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Operator_ip_addresss_model extends CI_Model
{
	private $table_name = 'operator_ip_addresss';

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * ip_addressで取得する
	 *
	 * @param
	 *        	$ip_address
	 */
	public function get_ip_address($ip_address)
	{
		$query = $this->db->get_where($this->table_name, array(
				'ip_address' => $ip_address,
				'deleted_at' => null
		));
		return $query->num_rows();
	}

	/**
	 * ipで取得する
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
	 * IPアドレスを削除する
	 *
	 * @param $id
	 */
	public function delete_row($id)
	{
		$this->db->where('operator_ip_address_id', $id);
		$this->db->delete($this->table_name);
	}
}
