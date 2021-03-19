<?php 
class m_auth extends CI_model{
	public function getLogin($user){
		$cek = $this->db->get_where("user_login",['username' => "$user"]);
		return $cek->row();
	}
	public function getDetailLogin($id){
		$this->db->select("*");
		$this->db->join("karyawan","user_login.id_karyawan=karyawan.id_karyawan");
		
	$cek = $this->db->get_where('user_login',['user_login.id_user_login'=>$id]);
		return $cek->row();

	}
}