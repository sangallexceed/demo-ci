<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login_model extends CI_Model
{
	private $table_name = 'users';
	protected static $search_all = 'ALL';
	
	public function login($data)
	{
		$this -> db -> select('id, username, password');
		$this -> db -> from('users');
		$this -> db -> where('username', $data['username']);
		$this -> db -> where('password', MD5($data['password']));
		$this -> db -> limit(1);
	
		$query = $this->db->get();
	
		if ($query -> num_rows() == 1)
		{
			return $query->result();
		}
		else
		{
			return false;
		}
	}
}
