<?php

include "Util.php";

class Admin extends CI_Controller {

	public function login() {
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$expiry = $this->input->post('expiry');
		$admins = $this->db->query("SELECT * FROM `admins` WHERE `email`='" . $email . "' AND `password`='" . $password . "'")->result_array();
		if (sizeof($admins) > 0) {
			$admin = $admins[0];
			echo json_encode(array(
				'response_code' => 1,
				'user_id' => intval($admin['id'])
			));
		} else {
			echo json_encode(array(
				'response_code' => -2
			));
		}
	}

	public function get_users() {
		$start = intval($this->input->post('start'));
		$length = intval($this->input->post('length'));
		$users = $this->db->query("SELECT * FROM `users` ORDER BY `email` ASC LIMIT " . $start . "," . $length)->result_array();
		for ($i=0; $i<sizeof($users); $i++) {
		}
		echo json_encode($users);
	}

	public function get_users_by_email() {
		$email = $this->input->post('email');
		$users = $this->db->query("SELECT * FROM `users` WHERE `email`='" . $email . "'")->result_array();
		for ($i=0; $i<sizeof($users); $i++) {
		}
		echo json_encode($users);
	}
	
	public function add_user() {
		$email = $this->input->post('email');
		if ($this->db->query("SELECT * FROM `users` WHERE `email`='" . $email . "'")->num_rows() > 0) {
			echo json_encode(array(
				'response_code' => -1
			));
			return;
		}
		$this->db->insert('users', array(
			'email' => $email
		));
		echo json_encode(array(
			'response_code' => 1
		));
	}
	
	public function update_user() {
		$id = intval($this->input->post('id'));
		$email = $this->input->post('email');
		if ($this->db->query("SELECT * FROM `users` WHERE `email`='" . $email . "'")->num_rows() > 0) {
			echo json_encode(array(
				'response_code' => -1
			));
			return;
		}
		$this->db->where('id', $id);
		$this->db->update('users', array(
			'email' => $email
		));
		echo json_encode(array(
			'response_code' => 1
		));
	}
	
	public function delete_user() {
		$id = intval($this->input->post('id'));
		$this->db->where('id', $id);
		$this->db->delete('users');
	}

	public function get_admins() {
		$start = intval($this->input->post('start'));
		$length = intval($this->input->post('length'));
		$admins = $this->db->query("SELECT * FROM `admins` ORDER BY `email` ASC LIMIT " . $start . "," . $length)->result_array();
		for ($i=0; $i<sizeof($admins); $i++) {
		}
		echo json_encode($admins);
	}
	
	public function add_admin() {
		$name = $this->input->post('name');
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		if ($this->db->query("SELECT * FROM `admins` WHERE `email`='" . $email . "'")->num_rows() > 0) {
			echo json_encode(array(
				'response_code' => -1
			));
			return;
		}
		$this->db->insert('admins', array(
			'name' => $name,
			'email' => $email,
			'password' => $password
		));
		echo json_encode(array(
			'response_code' => 1
		));
	}
	
	public function update_admin() {
		$id = intval($this->input->post('id'));
		$changed = intval($this->input->post('changed'));
		$name = $this->input->post('name');
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		if ($changed == 1) {
			if ($this->db->query("SELECT * FROM `admins` WHERE `email`='" . $email . "'")->num_rows() > 0) {
				echo json_encode(array(
					'response_code' => -1
				));
				return;
			}
		}
		$this->db->where('id', $id);
		$this->db->update('admins', array(
			'name' => $name,
			'email' => $email,
			'password' => $password
		));
		echo json_encode(array(
			'response_code' => 1
		));
	}
	
	public function delete_admin() {
		$id = intval($this->input->post('id'));
		$this->db->where('id', $id);
		$this->db->delete('admins');
	}
	
	public function update_kemampuan_perorangan() {
		$type = $this->input->post('type');
		$config['upload_path']          = './userdata/';
		$config['allowed_types']        = '*';
		$config['max_size']             = 2147483647;
		$config['file_name']            = Util::generateUUIDv4();
		$this->load->library('upload', $config);
		if ($this->upload->do_upload('file')) {
			$this->db->where("type", $type);
			$this->db->update("kemampuan_perorangan", array(
				"path" => $this->upload->data()['file_name']
			));
		} else {
			echo json_encode($this->upload->display_errors());
		}
		$kemampuan = $this->db->query("SELECT * FROM `kemampuan_perorangan` WHERE `type`='".$type."'")->row_array();
		echo json_encode($kemampuan);
	}
	
	public function update_menarmed() {
		$id = intval($this->input->post('menarmed_category_id'));
		$config['upload_path']          = './userdata/';
		$config['allowed_types']        = '*';
		$config['max_size']             = 2147483647;
		$config['file_name']            = Util::generateUUIDv4();
		$this->load->library('upload', $config);
		if ($this->upload->do_upload('file')) {
			$this->db->where("menarmed_category_id", $id);
			$this->db->update("menarmed", array(
				"path" => $this->upload->data()['file_name']
			));
		} else {
			echo json_encode($this->upload->display_errors());
		}
		$menarmed = $this->db->query("SELECT * FROM `menarmed` WHERE `menarmed_category_id`=".$id)->row_array();
		echo json_encode($menarmed);
	}
	
	public function get_menarmed_categories() {
		$categories = $this->db->get("menarmed_categories")->result_array();
		echo json_encode($categories);
	}
	
	public function get_menarmed_by_category_id() {
		$id = intval($this->input->post('id'));
		$menarmed = $this->db->query("SELECT * FROM `menarmed` WHERE `menarmed_category_id`=".$id)->row_array();
		echo json_encode($menarmed);
	}
	
	public function add_menarmed_category() {
		$name = $this->input->post('name');
		$config['upload_path']          = './userdata/';
		$config['allowed_types']        = '*';
		$config['max_size']             = 2147483647;
		$config['file_name']            = Util::generateUUIDv4();
		$this->load->library('upload', $config);
		if ($this->upload->do_upload('file')) {
			$this->db->insert("menarmed_categories", array(
				"name" => $name
			));
			$id = $this->db->insert_id();
			$this->db->insert("menarmed", array(
				"menarmed_category_id" => $id,
				"path" => $this->upload->data()['file_name']
			));
		} else {
			echo json_encode($this->upload->display_errors());
		}
		$this->db->insert("menarmed_categories", array(
			"name" => $name
		));
	}
	
	public function delete_menarmed_category() {
		$id = intval($this->input->post('id'));
		$menarmed = $this->db->query("SELECT * FROM `menarmed` WHERE `menarmed_category_id`=".$id)->row_array();
		if ($menarmed != NULL) {
			unlink("userdata/".$menarmed['path']);
		}
		$this->db->query("DELETE FROM `menarmed` WHERE `menarmed_category_id`=".$id);
		$this->db->query("DELETE FROM `menarmed_categories` WHERE `id`=".$id);
	}
	
	public function add_pelanggaran() {
		$menarmedID = intval($this->input->post('menarmed_id'));
		$nama = $this->input->post('nama');
		$jabatan = $this->input->post('jabatan');
		$satuan = $this->input->post('satuan');
		$jenisPelanggaran = $this->input->post('jenis_pelanggaran');
		$uraianPelanggaran = $this->input->post('uraian_pelanggaran');
		$motifPelanggaran = $this->input->post('motif_pelanggaran');
		$prosesHukum = $this->input->post('proses_hukum');
		$keterangan = $this->input->post('keterangan');
		$this->db->insert("pelanggaran", array(
			"menarmed_id" => $menarmedID,
			"nama" => $nama,
			"jabatan" => $jabatan,
			"satuan" => $satuan,
			"jenis_pelanggaran" => $jenisPelanggaran,
			"uraian_pelanggaran" => $uraianPelanggaran,
			"motif_pelanggaran" => $motifPelanggaran,
			"proses_hukum" => $prosesHukum,
			"keterangan" => $keterangan
		));
	}
	
	public function update_pelanggaran() {
		$id = intval($this->input->post('id'));
		$nama = $this->input->post('nama');
		$jabatan = $this->input->post('jabatan');
		$satuan = $this->input->post('satuan');
		$jenisPelanggaran = $this->input->post('jenis_pelanggaran');
		$uraianPelanggaran = $this->input->post('uraian_pelanggaran');
		$motifPelanggaran = $this->input->post('motif_pelanggaran');
		$prosesHukum = $this->input->post('proses_hukum');
		$keterangan = $this->input->post('keterangan');
		$this->db->where("id", $id);
		$this->db->update("pelanggaran", array(
			"nama" => $nama,
			"jabatan" => $jabatan,
			"satuan" => $satuan,
			"jenis_pelanggaran" => $jenisPelanggaran,
			"uraian_pelanggaran" => $uraianPelanggaran,
			"motif_pelanggaran" => $motifPelanggaran,
			"proses_hukum" => $prosesHukum,
			"keterangan" => $keterangan
		));
	}
	
	public function delete_pelanggaran() {
		$id = intval($this->input->post('id'));
		$this->db->query("DELETE FROM `pelanggaran` WHERE `id`=".$id);
	}
}
