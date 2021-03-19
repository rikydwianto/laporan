<?php 
class model extends CI_model{
	public function getAllCabang()
	{
		$this->db->select("*");
		$this->db->from("cabang");
		$this->db->join('wilayah','cabang.id_wilayah=wilayah.id_wilayah');
		$data  = $this->db->get()->result_array();
		return ($data);

	}
	public function getCabang($idcabang=null)
	{
		if($idcabang==null)
		{
			$data = $this->db->get("cabang")->result_array();

		}
		else {
			$data =  $this->db->get_where('cabang',['id_cabang'=>$idcabang])->row();
		}
		return $data;

	}
	public function getJabatan($idjabatan=null)
	{
		if($idjabatan==null)
		{
			$data = $this->db->get("jabatan")->result_array();

		}
		else {
			$data =  $this->db->get_where('jabatan',['id_jabatan'=>$id_jabatan])->result_array()[0];
		}
		return $data;

	}
	public function getKaryawan($idkaryawan=null)
	{
		
		
		if($idkaryawan==null){
			$this->db->select("*");
		$this->db->order_by("cabang.nama_cabang",'asc');
		$this->db->order_by("karyawan.nama_karyawan",'asc');
		$this->db->from("karyawan");
		$this->db->join("jabatan",'karyawan.id_jabatan=jabatan.id_jabatan');
		$this->db->join("cabang",'karyawan.id_cabang=cabang.id_cabang');
			$data =  $this->db->get()->result_array();
		}
		else {
			$this->db->select("*");
			$this->db->order_by("cabang.nama_cabang",'asc');
			$this->db->order_by("karyawan.nama_karyawan",'asc');
			$this->db->join("jabatan",'karyawan.id_jabatan=jabatan.id_jabatan');
			$this->db->join("cabang",'karyawan.id_cabang=cabang.id_cabang');
			$data =  $this->db->get_where('karyawan',['id_karyawan'=>$idkaryawan])->result_array()[0];
		}
		return $data;


	}
}