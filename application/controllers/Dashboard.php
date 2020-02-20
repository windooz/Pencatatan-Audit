<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		date_default_timezone_set('Asia/Jakarta');

		$this->load->model('m_data');

		// cek session yang login, 
		// jika session status tidak sama dengan session telah_login, berarti pengguna belum login
		// maka halaman akan di alihkan kembali ke halaman login.
		if($this->session->userdata('status')!="telah_login"){
			redirect(base_url().'login?alert=belum_login');
		}
	}

	public function index()
	{
		// hitung jumlah artikel
		$data['jumlah_checklist'] = $this->m_data->get_data('artikel')->num_rows();
		// hitung jumlah perusahaan
		$data['jumlah_perusahaan'] = $this->m_data->get_data('perusahaan')->num_rows();
		// hitung jumlah pengguna
		$data['jumlah_auditor'] = $this->m_data->get_data('pengguna')->num_rows();
		// hitung jumlah halaman
		$data['jumlah_audit'] = $this->m_data->get_data('halaman')->num_rows();
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_index',$data);
		$this->load->view('dashboard/v_footer');
	}

	public function keluar()
	{
		$this->session->sess_destroy();
		redirect('login?alert=logout');
	}

	public function ganti_password()
	{
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_ganti_password');
		$this->load->view('dashboard/v_footer');
	}

	public function ganti_password_aksi()
	{

		// form validasi
		$this->form_validation->set_rules('password_lama','Password Lama','required');
		$this->form_validation->set_rules('password_baru','Password Baru','required|min_length[8]');
		$this->form_validation->set_rules('konfirmasi_password','Konfirmasi Password Baru','required|matches[password_baru]');

		// cek validasi
		if($this->form_validation->run() != false){

			// menangkap data dari form
			$password_lama = $this->input->post('password_lama');
			$password_baru = $this->input->post('password_baru');
			$konfirmasi_password = $this->input->post('konfirmasi_password');

			// cek kesesuaian password lama dengan id pengguna yang sedang login dan password lama
			$where = array(
				'pengguna_id' => $this->session->userdata('id'),
				'pengguna_password' => md5($password_lama)
			);
			$cek = $this->m_data->cek_login('pengguna', $where)->num_rows();

			// cek kesesuaikan password lama
			if($cek > 0){

				// update data password pengguna
				$w = array(
					'pengguna_id' => $this->session->userdata('id')
				);
				$data = array(
					'pengguna_password' => md5($password_baru)
				);
				$this->m_data->update_data($where, $data, 'pengguna');

				// alihkan halaman kembali ke halaman ganti password
				redirect('dashboard/ganti_password?alert=sukses');
			}else{
				// alihkan halaman kembali ke halaman ganti password
				redirect('dashboard/ganti_password?alert=gagal');
			}

		}else{
			$this->load->view('dashboard/v_header');
			$this->load->view('dashboard/v_ganti_password');
			$this->load->view('dashboard/v_footer');
		}

	}

	// CRUD perusahaan
	public function perusahaan()
	{
		$data['perusahaan'] = $this->m_data->get_data('perusahaan')->result();
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_perusahaan',$data);
		$this->load->view('dashboard/v_footer');
	}

	public function perusahaan_tambah()
	{
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_perusahaan_tambah');
		$this->load->view('dashboard/v_footer');
	}

	public function perusahaan_aksi()
	{
		$this->form_validation->set_rules('perusahaan','Perusahaan','required');

		if($this->form_validation->run() != false){

			$perusahaan = $this->input->post('perusahaan');

			$data = array(
				'perusahaan_nama' => $perusahaan,
				'perusahaan_slug' => strtolower(url_title($perusahaan))
			);

			$this->m_data->insert_data($data,'perusahaan');

			redirect(base_url().'dashboard/perusahaan');
			
		}else{
			$this->load->view('dashboard/v_header');
			$this->load->view('dashboard/v_perusahaan_tambah');
			$this->load->view('dashboard/v_footer');
		}
	}

	public function perusahaan_edit($id)
	{
		$where = array(
			'perusahaan_id' => $id
		);
		$data['perusahaan'] = $this->m_data->edit_data($where,'perusahaan')->result();
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_perusahaan_edit',$data);
		$this->load->view('dashboard/v_footer');
	}

	public function perusahaan_update()
	{
		$this->form_validation->set_rules('perusahaan','Perusahaan','required');

		if($this->form_validation->run() != false){

			$id = $this->input->post('id');
			$perusahaan = $this->input->post('perusahaan');

			$where = array(
				'perusahaan_id' => $id
			);

			$data = array(
				'perusahaan_nama' => $perusahaan,
				'perusahaan_slug' => strtolower(url_title($perusahaan))
			);

			$this->m_data->update_data($where, $data,'perusahaan');

			redirect(base_url().'dashboard/perusahaan');
			
		}else{

			$id = $this->input->post('id');
			$where = array(
				'perusahaan_id' => $id
			);
			$data['perusahaan'] = $this->m_data->edit_data($where,'perusahaan')->result();
			$this->load->view('dashboard/v_header');
			$this->load->view('dashboard/v_perusahaan_edit',$data);
			$this->load->view('dashboard/v_footer');
		}
	}


	public function perusahaan_hapus($id)
	{
		$where = array(
			'perusahaan_id' => $id
		);

		$this->m_data->delete_data($where,'perusahaan');

		redirect(base_url().'dashboard/perusahaan');
	}
	// END CRUD perusahaan

	// CRUD ARTIKEL
	public function audit()
	{
		$data['audit'] = $this->db->query("SELECT * FROM audit,checklist,jawaban,perusahaan,pengguna WHERE artikel_perusahaan=perusahaan_id and artikel_author=pengguna_id order by audit_id desc")->result();	
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_daftar_audit',$data);
		$this->load->view('dashboard/v_footer');
	}

	public function soal()
	{
		$data['soal'] = $this->m_data->get_data('soal')->result();
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_soal',$data);
		$this->load->view('dashboard/v_footer');
	}

	public function soal_tambah()
	{
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_soal_tambah');
		$this->load->view('dashboard/v_footer');
	}

	public function soal_aksi()
	{
		// Wajib isi judul,konten dan perusahaan
		$this->form_validation->set_rules('soal','Soal','required');
		$this->form_validation->set_rules('note','Note');

		if($this->form_validation->run() != false){

			$soal = $this->input->post('soal');
			$note = $this->input->post('note');
			$data = array(
				'soal' => $soal,
				'note' => $note
			);

			$this->m_data->insert_data($data,'soal');

			redirect(base_url().'dashboard/soal');
			
		}else{
			$this->load->view('dashboard/v_header');
			$this->load->view('dashboard/v_soal_tambah');
			$this->load->view('dashboard/v_footer');
		}
	}

	public function soal_edit($id)
	{
		$where = array(
			'soal_id' => $id
		);
		$data['soal'] = $this->m_data->edit_data($where,'soal')->result();
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_soal_edit',$data);
		$this->load->view('dashboard/v_footer');
	}

	public function soal_update()
	{
		$this->form_validation->set_rules('soal','Soal','required');

		if($this->form_validation->run() != false){

			$id = $this->input->post('id');
			$soal = $this->input->post('soal');
			$note = $this->input->post('note');

			$where = array(
				'soal_id' => $id
			);

			$data = array(
				'soal' => $soal,
				'note' => $note
			);

			$this->m_data->update_data($where, $data,'soal');

			redirect(base_url().'dashboard/soal');
			
		}else{

			$id = $this->input->post('id');
			$where = array(
				'soal_id' => $id
			);
			$data['soal'] = $this->m_data->edit_data($where,'soal')->result();
			$this->load->view('dashboard/v_header');
			$this->load->view('dashboard/v_soal_edit',$data);
			$this->load->view('dashboard/v_footer');
		}
	}


	public function soal_hapus($id)
	{
		$where = array(
			'soal_id' => $id
		);

		$this->m_data->delete_data($where,'soal');

		redirect(base_url().'dashboard/soal');
	}


	public function artikel()
	{
		$data['artikel'] = $this->db->query("SELECT * FROM artikel,perusahaan,pengguna WHERE artikel_perusahaan=perusahaan_id and artikel_author=pengguna_id order by artikel_id desc")->result();	
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_artikel',$data);
		$this->load->view('dashboard/v_footer');
	}

	public function artikel_tambah()
	{
		$data['perusahaan'] = $this->m_data->get_data('perusahaan')->result();
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_artikel_tambah',$data);
		$this->load->view('dashboard/v_footer');
	}

	public function artikel_aksi()
	{
		// Wajib isi judul,konten dan perusahaan
		$this->form_validation->set_rules('judul','Judul','required|is_unique[artikel.artikel_judul]');
		$this->form_validation->set_rules('konten','Konten','required');
		$this->form_validation->set_rules('perusahaan','Perusahaan','required');

		// Membuat gambar wajib di isi
		if (empty($_FILES['sampul']['name'])){
			$this->form_validation->set_rules('sampul', 'Gambar Sampul', 'required');
		}

		if($this->form_validation->run() != false){

			$config['upload_path']   = './gambar/artikel/';
			$config['allowed_types'] = 'gif|jpg|png';

			$this->load->library('upload', $config);

			if ($this->upload->do_upload('sampul')) {

				// mengambil data tentang gambar
				$gambar = $this->upload->data();

				$tanggal = date('Y-m-d H:i:s');
				$judul = $this->input->post('judul');
				$slug = strtolower(url_title($judul));
				$konten = $this->input->post('konten');
				$sampul = $gambar['file_name'];
				$author = $this->session->userdata('id');
				$perusahaan = $this->input->post('perusahaan');
				$status = $this->input->post('status');

				$data = array(
					'artikel_tanggal' => $tanggal,
					'artikel_judul' => $judul,
					'artikel_slug' => $slug,
					'artikel_konten' => $konten,
					'artikel_sampul' => $sampul,
					'artikel_author' => $author,
					'artikel_perusahaan' => $perusahaan,
					'artikel_status' => $status,
				);

				$this->m_data->insert_data($data,'artikel');

				redirect(base_url().'dashboard/artikel');	
				
			} else {

				$this->form_validation->set_message('sampul', $data['gambar_error'] = $this->upload->display_errors());

				$data['perusahaan'] = $this->m_data->get_data('perusahaan')->result();
				$this->load->view('dashboard/v_header');
				$this->load->view('dashboard/v_artikel_tambah',$data);
				$this->load->view('dashboard/v_footer');
			}

		}else{
			$data['perusahaan'] = $this->m_data->get_data('perusahaan')->result();
			$this->load->view('dashboard/v_header');
			$this->load->view('dashboard/v_artikel_tambah',$data);
			$this->load->view('dashboard/v_footer');
		}
	}


	public function artikel_edit($id)
	{
		$where = array(
			'artikel_id' => $id
		);
		$data['artikel'] = $this->m_data->edit_data($where,'artikel')->result();
		$data['perusahaan'] = $this->m_data->get_data('perusahaan')->result();
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_artikel_edit',$data);
		$this->load->view('dashboard/v_footer');
	}


	public function artikel_update()
	{
		// Wajib isi judul,konten dan perusahaan
		$this->form_validation->set_rules('judul','Judul','required');
		$this->form_validation->set_rules('konten','Konten','required');
		$this->form_validation->set_rules('perusahaan','Perusahaan','required');
		
		if($this->form_validation->run() != false){

			$id = $this->input->post('id');

			$judul = $this->input->post('judul');
			$slug = strtolower(url_title($judul));
			$konten = $this->input->post('konten');
			$perusahaan = $this->input->post('perusahaan');
			$status = $this->input->post('status');

			$where = array(
				'artikel_id' => $id
			);

			$data = array(
				'artikel_judul' => $judul,
				'artikel_slug' => $slug,
				'artikel_konten' => $konten,
				'artikel_perusahaan' => $perusahaan,
				'artikel_status' => $status,
			);

			$this->m_data->update_data($where,$data,'artikel');


			if (!empty($_FILES['sampul']['name'])){
				$config['upload_path']   = './gambar/artikel/';
				$config['allowed_types'] = 'gif|jpg|png';

				$this->load->library('upload', $config);

				if ($this->upload->do_upload('sampul')) {

					// mengambil data tentang gambar
					$gambar = $this->upload->data();

					$data = array(
						'artikel_sampul' => $gambar['file_name'],
					);

					$this->m_data->update_data($where,$data,'artikel');

					redirect(base_url().'dashboard/artikel');	

				} else {
					$this->form_validation->set_message('sampul', $data['gambar_error'] = $this->upload->display_errors());
					
					$where = array(
						'artikel_id' => $id
					);
					$data['artikel'] = $this->m_data->edit_data($where,'artikel')->result();
					$data['perusahaan'] = $this->m_data->get_data('perusahaan')->result();
					$this->load->view('dashboard/v_header');
					$this->load->view('dashboard/v_artikel_edit',$data);
					$this->load->view('dashboard/v_footer');
				}
			}else{
				redirect(base_url().'dashboard/artikel');	
			}

		}else{
			$id = $this->input->post('id');
			$where = array(
				'artikel_id' => $id
			);
			$data['artikel'] = $this->m_data->edit_data($where,'artikel')->result();
			$data['perusahaan'] = $this->m_data->get_data('perusahaan')->result();
			$this->load->view('dashboard/v_header');
			$this->load->view('dashboard/v_artikel_edit',$data);
			$this->load->view('dashboard/v_footer');
		}
	}

	public function artikel_hapus($id)
	{
		$where = array(
			'artikel_id' => $id
		);

		$this->m_data->delete_data($where,'artikel');

		redirect(base_url().'dashboard/artikel');
	}
	// end crud artikel


	// CRUD PAGES
	public function pages()
	{
		$data['halaman'] = $this->m_data->get_data('halaman')->result();	
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_pages',$data);
		$this->load->view('dashboard/v_footer');
	}

	public function pages_tambah()
	{
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_pages_tambah');
		$this->load->view('dashboard/v_footer');
	}

	public function pages_aksi()
	{
		// Wajib isi judul,konten
		$this->form_validation->set_rules('judul','Judul','required|is_unique[halaman.halaman_judul]');
		$this->form_validation->set_rules('konten','Konten','required');

		if($this->form_validation->run() != false){

			$judul = $this->input->post('judul');
			$slug = strtolower(url_title($judul));
			$konten = $this->input->post('konten');

			$data = array(
				'halaman_judul' => $judul,
				'halaman_slug' => $slug,
				'halaman_konten' => $konten
			);

			$this->m_data->insert_data($data,'halaman');

			// alihkan kembali ke method pages
			redirect(base_url().'dashboard/pages');	

		}else{
			$this->load->view('dashboard/v_header');
			$this->load->view('dashboard/v_pages_tambah');
			$this->load->view('dashboard/v_footer');
		}
	}

	public function pages_edit($id)
	{
		$where = array(
			'halaman_id' => $id
		);
		$data['halaman'] = $this->m_data->edit_data($where,'halaman')->result();
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_pages_edit',$data);
		$this->load->view('dashboard/v_footer');
	}


	public function pages_update()
	{
		// Wajib isi judul,konten 
		$this->form_validation->set_rules('judul','Judul','required');
		$this->form_validation->set_rules('konten','Konten','required');
		
		if($this->form_validation->run() != false){

			$id = $this->input->post('id');

			$judul = $this->input->post('judul');
			$slug = strtolower(url_title($judul));
			$konten = $this->input->post('konten');
			
			$where = array(
				'halaman_id' => $id
			);

			$data = array(
				'halaman_judul' => $judul,
				'halaman_slug' => $slug,
				'halaman_konten' => $konten
			);

			$this->m_data->update_data($where,$data,'halaman');

			redirect(base_url().'dashboard/pages');
		}else{
			$id = $this->input->post('id');
			$where = array(
				'halaman_id' => $id
			);
			$data['halaman'] = $this->m_data->edit_data($where,'halaman')->result();
			$this->load->view('dashboard/v_header');
			$this->load->view('dashboard/v_pages_edit',$data);
			$this->load->view('dashboard/v_footer');
		}
	}

	public function pages_hapus($id)
	{
		$where = array(
			'halaman_id' => $id
		);
		
		$this->m_data->delete_data($where,'halaman');

		redirect(base_url().'dashboard/pages');
	}
	// end crud pages


	public function profil()
	{
		// id pengguna yang sedang login
		$id_pengguna = $this->session->userdata('id');

		$where = array(
			'pengguna_id' => $id_pengguna
		);

		$data['profil'] = $this->m_data->edit_data($where,'pengguna')->result();

		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_profil',$data);
		$this->load->view('dashboard/v_footer');
	}

	public function profil_update()
	{
		
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_profil_edit');
		$this->load->view('dashboard/v_footer');


		// Wajib isi nama dan email
		$this->form_validation->set_rules('nama','Nama','required');
		$this->form_validation->set_rules('email','Email','required');
		
		if($this->form_validation->run() != false){

			$id = $this->session->userdata('id');

			$nama = $this->input->post('nama');
			$email = $this->input->post('email');
			
			$where = array(
				'pengguna_id' => $id
			);

			$data = array(
				'pengguna_nama' => $nama,
				'pengguna_email' => $email
			);

			$this->m_data->update_data($where,$data,'pengguna');

			redirect(base_url().'dashboard/profil/?alert=sukses');
		}else{
			// id pengguna yang sedang login
			$id_pengguna = $this->session->userdata('id');

			$where = array(
				'pengguna_id' => $id_pengguna
			);

			$data['profil'] = $this->m_data->edit_data($where,'pengguna')->result();

			$this->load->view('dashboard/v_header');
			$this->load->view('dashboard/v_profil',$data);
			$this->load->view('dashboard/v_footer');
		}
	}


	public function pengaturan()
	{
		$data['pengaturan'] = $this->m_data->get_data('pengaturan')->result();

		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_pengaturan',$data);
		$this->load->view('dashboard/v_footer');
	}


	public function pengaturan_update()
	{
		// Wajib isi nama dan deskripsi website
		$this->form_validation->set_rules('nama','Nama Website','required');
		$this->form_validation->set_rules('deskripsi','Deskripsi Website','required');
		
		if($this->form_validation->run() != false){

			$nama = $this->input->post('nama');
			$deskripsi = $this->input->post('deskripsi');
			$link_facebook = $this->input->post('link_facebook');
			$link_twitter = $this->input->post('link_twitter');
			$link_instagram = $this->input->post('link_instagram');
			$link_github = $this->input->post('link_github');

			$where = array(

			);

			$data = array(
				'nama' => $nama,
				'deskripsi' => $deskripsi,
				'link_facebook' => $link_facebook,
				'link_twitter' => $link_twitter,
				'link_instagram' => $link_instagram,
				'link_github' => $link_github
			);

			// update pengaturan
			$this->m_data->update_data($where,$data,'pengaturan');

			// Periksa apakah ada gambar logo yang diupload
			if (!empty($_FILES['logo']['name'])){
				
				$config['upload_path']   = './gambar/website/';
				$config['allowed_types'] = 'jpg|png';

				$this->load->library('upload', $config);

				if ($this->upload->do_upload('logo')) {
					// mengambil data tentang gambar logo yang diupload
					$gambar = $this->upload->data();

					$logo = $gambar['file_name'];
					
					$this->db->query("UPDATE pengaturan SET logo='$logo'");
				}
			}

			redirect(base_url().'dashboard/pengaturan/?alert=sukses');

		}else{
			$data['pengaturan'] = $this->m_data->get_data('pengaturan')->result();

			$this->load->view('dashboard/v_header');
			$this->load->view('dashboard/v_pengaturan',$data);
			$this->load->view('dashboard/v_footer');
		}
	}

	// CRUD PENGGUNA
	public function pengguna()
	{
		$data['pengguna'] = $this->m_data->get_data('pengguna')->result();	
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_pengguna',$data);
		$this->load->view('dashboard/v_footer');
	}

	public function pengguna_tambah()
	{
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_pengguna_tambah');
		$this->load->view('dashboard/v_footer');
	}

	public function pengguna_aksi()
	{
		// Wajib isi
		$this->form_validation->set_rules('nama','Nama Pengguna','required');
		$this->form_validation->set_rules('email','Email Pengguna','required');
		$this->form_validation->set_rules('username','Username Pengguna','required');
		$this->form_validation->set_rules('password','Password Pengguna','required|min_length[8]');
		$this->form_validation->set_rules('level','Level Pengguna','required');
		$this->form_validation->set_rules('status','Status Pengguna','required');

		if($this->form_validation->run() != false){

			$nama = $this->input->post('nama');
			$email = $this->input->post('email');
			$username = $this->input->post('username');
			$password = md5($this->input->post('password'));
			$level = $this->input->post('level');
			$status = $this->input->post('status');

			$data = array(
				'pengguna_nama' => $nama,
				'pengguna_email' => $email,
				'pengguna_username' => $username,
				'pengguna_password' => $password,
				'pengguna_level' => $level,
				'pengguna_status' => $status
			);


			$this->m_data->insert_data($data,'pengguna');

			redirect(base_url().'dashboard/pengguna');	

		}else{
			$this->load->view('dashboard/v_header');
			$this->load->view('dashboard/v_pengguna_tambah');
			$this->load->view('dashboard/v_footer');
		}
	}

	public function pengguna_edit($id)
	{
		$where = array(
			'pengguna_id' => $id
		);
		$data['pengguna'] = $this->m_data->edit_data($where,'pengguna')->result();
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_pengguna_edit',$data);
		$this->load->view('dashboard/v_footer');
	}


	public function pengguna_update()
	{
		// Wajib isi
		$this->form_validation->set_rules('nama','Nama Pengguna','required');
		$this->form_validation->set_rules('email','Email Pengguna','required');
		$this->form_validation->set_rules('username','Username Pengguna','required');
		$this->form_validation->set_rules('level','Level Pengguna','required');
		$this->form_validation->set_rules('status','Status Pengguna','required');

		if($this->form_validation->run() != false){

			$id = $this->input->post('id');

			$nama = $this->input->post('nama');
			$email = $this->input->post('email');
			$username = $this->input->post('username');
			$password = md5($this->input->post('password'));
			$level = $this->input->post('level');
			$status = $this->input->post('status');

			if($this->input->post('password') == ""){
				$data = array(
					'pengguna_nama' => $nama,
					'pengguna_email' => $email,
					'pengguna_username' => $username,
					'pengguna_level' => $level,
					'pengguna_status' => $status
				);
			}else{
				$data = array(
					'pengguna_nama' => $nama,
					'pengguna_email' => $email,
					'pengguna_username' => $username,
					'pengguna_password' => $password,
					'pengguna_level' => $level,
					'pengguna_status' => $status
				);
			}
			
			$where = array(
				'pengguna_id' => $id
			);

			$this->m_data->update_data($where,$data,'pengguna');

			redirect(base_url().'dashboard/pengguna');
		}else{
			$id = $this->input->post('id');
			$where = array(
				'pengguna_id' => $id
			);
			$data['pengguna'] = $this->m_data->edit_data($where,'pengguna')->result();
			$this->load->view('dashboard/v_header');
			$this->load->view('dashboard/v_pengguna_edit',$data);
			$this->load->view('dashboard/v_footer');
		}
	}

	public function pengguna_hapus($id)
	{
		$where = array(
			'pengguna_id' => $id
		);
		$data['pengguna_hapus'] = $this->m_data->edit_data($where,'pengguna')->row();
		$data['pengguna_lain'] = $this->db->query("SELECT * FROM pengguna WHERE pengguna_id != $id")->result();
		$this->load->view('dashboard/v_header');
		$this->load->view('dashboard/v_pengguna_hapus',$data);
		$this->load->view('dashboard/v_footer');
	}

	public function pengguna_hapus_aksi()
	{
		$pengguna_hapus = $this->input->post('pengguna_hapus');
		$pengguna_tujuan = $this->input->post('pengguna_tujuan');

		// hapus pengguna
		$where = array(
			'pengguna_id' => $pengguna_hapus
		);

		$this->m_data->delete_data($where,'pengguna');

		// pindahkan semua artikel pengguna yang dihapus ke pengguna yang dipilih
		$w = array(
			'artikel_author' => $pengguna_hapus
		);

		$d = array(
			'artikel_author' => $pengguna_tujuan
		);

		$this->m_data->update_data($w,$d,'artikel');

		redirect(base_url().'dashboard/pengguna');
	}
	// end crud pengguna
	
}
