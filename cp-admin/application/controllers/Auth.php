<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model("auth/m_auth");
		cek_login();
	}

	public function index(){
		$data = [
			"judul" => "Halaman Login"		];
		$this->form_validation->set_rules('nik', 'Username', 'required|trim');
		$this->form_validation->set_rules('password', 'Password', 'required');
		if($this->form_validation->run() != true )
		{
			$this->load->view("tamplate/login",$data);
		}
		else
		{
			$nik = htmlspecialchars($this->input->post('nik',true));
			$password = htmlspecialchars($this->input->post('password',true));
			$isi = $this->m_auth->getLogin($nik);
			if($isi){
				if($isi->password == md5($password)){
					$this->session->set_flashdata('pesan',"Login Berhasil");
					$this->session->set_userdata(['idkaryawan' => $isi->id_karyawan]);
					$this->session->set_userdata(['user_login' => $isi->id_user_login]);
					redirect(base_url('home'));
				}
				else
				{
					$this->session->set_flashdata('pesan',"$nik PASSWORD SALAH!");
				}
			}
			else
			{
					$this->session->set_flashdata('pesan',"$nik TIDAK DITEMUKAN!");
			}
			redirect(base_url('auth'));
		}
		
	}


	public function login(){
		
		
	}


	public function logout(){
		$this->session->sess_destroy();
		redirect(base_url('auth'));
	}
}