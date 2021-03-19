<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model("auth/m_auth");
		$this->load->model("model");
		cek_login();
	}
	public function index(){
		$id = $this->session->userdata('user_login');
		$idkaryawan = $this->session->userdata('idkaryawan');
		$db = $this->m_auth->getDetailLogin($id);

		$cabang = $this->model->getCabang($db->id_cabang);
		$data = [
			'judul'=>'Halaman Admin - utama',
			'tahun' => date("Y")
		];
		$detail = ['detail' => $db,
		'cabang' => $cabang->nama_cabang
		];
		// $this->load->view("tamplate/blank",$data);
		$this->load->view("tamplate/header",$data);
		$this->load->view("tamplate/sidebar",$data);
		$this->load->view("tamplate/topbar",$data);
		$this->load->view("halaman_awal",$detail);
		$this->load->view("tamplate/footer",$data);
	}
	public function karyawan($idkaryawan=null){
		$data = [
			'judul'=>'Halaman Admin - Data Karyawan',
			'tahun' => date("Y")
		];
		$karyawan = $this->model->getKaryawan($idkaryawan);
		$cabang = $this->model->getCabang();
		$jabatan = $this->model->getJabatan();
		$detail = [
			'karyawan' => $karyawan,
			'cabang' => $cabang,
			'jabatan' => $jabatan

		];
		$this->load->view("tamplate/header",$data);
		$this->load->view("tamplate/sidebar",$data);
		$this->load->view("tamplate/topbar",$data);
		if($idkaryawan==null)
		{
			$this->load->view("karyawan/tampil",$detail);
		}
		else
		{
			$this->load->view("karyawan/detail",$detail);
		}
		$this->load->view("tamplate/footer",$data);

	}
	public function editkaryawan(){
		$id = $this->input->post('id');
		$nama = $this->input->post('nama');
		$nik = $this->input->post('nik');
		$jabatan = $this->input->post('jabatan');
		$cabang = $this->input->post('cabang');
		$status = $this->input->post('status');
		$this->form_validation->set_rules('nik','NIK','trim|required');
		$this->form_validation->set_rules('nama','NAMA','trim|required');
		if($this->form_validation->run()==true)
		{
			$data = [
				'nama_karyawan'=>$nama,
				'nik_karyawan'=>$nik,
				'id_jabatan'=>$jabatan,
				'id_cabang'=>$cabang,
				'status_karyawan'=>$status,
			];
			$this->db->set($data);
			$this->db->where('id_karyawan',$id);
			$cek = $this->db->update('karyawan');
			if($cek){
				$this->session->flashdata('pesan','Berhasil Dirubah');
				redirect("home/karyawan/".$id);
			}
		}
		else
		{
			redirect('home/karyawan/'.$id);
		}

	}
public function cabang($id=null){
		$id = $this->session->userdata('user_login');
		$idkaryawan = $this->session->userdata('idkaryawan');
		$db = $this->m_auth->getDetailLogin($id);

		$cabang = $this->model->getAllCabang();
		$data = [
			'judul'=>'Halaman Admin - Cabang',
			'tahun' => date("Y")
		];
		$detail = ['detail' => $db,
		'cabang' => $cabang
		];
		// $this->load->view("tamplate/blank",$data);
		$this->load->view("tamplate/header",$data);
		$this->load->view("tamplate/sidebar",$data);
		$this->load->view("tamplate/topbar",$data);
		$this->load->view("cabang/tampil",$detail);
		$this->load->view("tamplate/footer",$data);
	}

}