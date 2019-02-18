<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Parameters extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$post = file_get_contents('php://input');
		$_POST = json_decode($post, true);

		$this->load->model('Parameters_model');
	}

	public function get( $id = false )
	{
		$output = $this->Parameters_model->get($id);
		echo json_encode($output);
	}


	public function create()
	{
		$parameter = $this->input->post('parameter');
		$this->Parameters_model->create($parameter);
	}


	public function delete()
	{
		$parameter = $this->input->post('parameter');
		$this->Parameters_model->delete($parameter);
	}

	

}
