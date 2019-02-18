<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products_model extends CI_Model {


	public function get( $id = false)
	{

		if ($id == false) {
			
			$params = $this->db->get('parameters');
			$params = $params->result_array();

			$check_rent_date = $this->db->get('products');
			$check_rent_date = $check_rent_date->result_array();

			// sprawdzenie czy przedmiot nie jest "po terminie"
			foreach ($check_rent_date as $key => $value) {

				$item_end_date = strtotime($value['endRentDate']);
				$now = time();

				if ($value['endRentDate'] != '0000-00-00 00:00:00') {
					
					if ($item_end_date < $now) {
						//zmiana statusu na "powinien wrocic na magazyn"
						$status_data = array(
							'status' => 2,
							'endRentDate' => '0000-00-00 00:00:00'
						);
						$this->db->where( 'id' , $value['id'] );
						$this->db->update( 'products' , $status_data );
					}
				}
			}

			$query = $this->db->get('products');
			$query = $query->result_array();

			$main_array = [];
			$params_array = [];
			foreach ($query as $key => $value) {
				$param_array = json_decode($value['parameters'], true);
				foreach ($param_array as $key2 => $value2) {
					foreach ($value2 as $key3 => $value3) {
						foreach ($params as $row) {
							if ($value3 == $row['id']) {
								$params_array[] = $row['name'];
							}
						}
					}
				}
				$main_array[] = array(
					'id' => $value['id'],
					'name' => $value['name'],
					'status' => $value['status'],
					'thumb' => $value['thumb'],
					'endRentDate' => $value['endRentDate'],
					'param_array' => $params_array
				);
				$params_array = [];
			}
			$q = $main_array;
		}
		else 
		{
			$this->db->where('id', $id);
			$q = $this->db->get('products');
			$q = $q->row();

			// TODO: sprawdzenie czy przedmiot nie jest "po terminie"

		}
		
		return $q;
	}

	public function gethistory( $id = false)
	{

		if ($id == false) {
			$q = $this->db->get('rent');
			$q = $q->result();
		}
		else 
		{
			$this->db->order_by('id', 'DESC');
			$this->db->where('productId', $id);
			$q = $this->db->get('rent');
			$q = $q->result();
		}
		
		return $q;
	}

	public function create($product)
	{
		$this->db->insert('products', $product);
	}

	public function rent($data)
	{
		// dodanie do historii dzierżaw 
		$this->db->insert('rent', $data);

		// update statusu i daty
		$end_date = date('Y-m-d'.' 15:00:00', strtotime($data['rentDate']. ' + '.$data['days'].' days'));
		$status_data = array(
			'status' => 1,
			'endRentDate' => $end_date
		);
		$this->db->where( 'id' , $data['productId'] );
		$this->db->update( 'products' , $status_data );
	}


	public function update( $product )
	{

		$product_data = array(
			'name' => $product['name'],
			'parameters' => $product['parameters']
		);

		$this->db->where( 'id' , $product['id'] );
		$this->db->update( 'products' , $product_data );
	}

	// potwierdzenie powrotu przedmiotu na magazyn
	public function status_upp( $status )
	{
		$this->db->where( 'id' , $status['id'] );
		$this->db->update( 'products' , $status );
	}

	// wypowiedzenie przed czasem
	public function item_termination( $status )
	{
		$this->db->where( 'id' , $status['id'] );
		$this->db->update( 'products' , $status );
	}

	// usunięcie przedmiotu
	public function delete($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('products');
	}

	// ustawienie miniaturki
	public function setThumb( $productId , $product )
	{
		$this->db->where( 'id' , $productId );
		$this->db->update( 'products' , $product );
	}

	// usunięcie miniaturki
	public function deleteThumb( $id , $image )
	{
		
		$this->db->where('id', $id);
		$q = $this->db->get('products');
		$q = $q->result_array();

		if ($q[0]['thumb'] == $image) {

			//usuniecie oznaczenia miniaturki
			$img_data = array(
				'thumb' => ''
			);

			$this->db->where( 'id' , $id );
			$this->db->update( 'products' , $img_data );
		}

	}


}
