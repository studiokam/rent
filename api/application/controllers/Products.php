<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$post = file_get_contents('php://input');
		$_POST = json_decode($post, true);

		$this->load->model('Products_model');
	}

	// pobranie przedmiotu/ów
	public function get( $id = false )
	{
		$output = $this->Products_model->get($id);
		echo json_encode($output);
	}

	// pobranie historii dzierżaw
	public function gethistory( $id = false )
	{
		$output = $this->Products_model->gethistory($id);
		echo json_encode($output);
	}

	// dodanie nowego przedmiotu
	public function create()
	{
		$product = $this->input->post('product');
		$this->Products_model->create($product);
	}

	// aktualizacja przedmiotu
	public function update()
	{
		$product = $this->input->post( 'product' );
		$this->Products_model->update( $product );
	}

	// potwierdzenie powrotu przedmiotu na magazyn
	public function status_upp()
	{
		$product = $this->input->post( 'status' );
		$this->Products_model->status_upp( $product );
	}

	// wypowiedzenie przed czasem
	public function item_termination()
	{
		$product = $this->input->post( 'status' );
		$this->Products_model->item_termination( $product );
	}

	// wydanie przedmiotu - dzierżawa
	public function rent()
	{
		$data = $this->input->post( 'rents' );

		// sprawdzenie czy podana data + dni nie kończa się przed "teraz"
		$time_check = date('Y-m-d'.' 15:00:00', strtotime($data['rentDate']. ' + '.$data['days'].' days'));
		
		if (time() > strtotime($time_check)) {
			echo "timeStartPlusDaysError";
		} 
		else 
		{
			$this->Products_model->rent( $data );
		}

	}

	// usunięcie przedmiotu
	public function delete($id)
	{
		$parameter = $this->input->get($id);
		$this->Products_model->delete($id);
	}

	

}
