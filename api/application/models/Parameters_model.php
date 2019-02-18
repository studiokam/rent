<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Parameters_model extends CI_Model {


	public function get( $id = false)
	{

		if ($id == false) {
			$this->db->order_by('name');
			$q = $this->db->get('parameters');
			$q = $q->result();
		}
		else 
		{
			$this->db->where('id', $id);
			$q = $this->db->get('parameters');
			$q = $q->row();
		}
		
		return $q;
	}


	public function create($parameter)
	{
		
		// sprawdzenie czy jest juz taki parametr
		$this->db->where('name', $parameter['name']);
		$q = $this->db->get('parameters');
		$q = $q->row();

		if (count($q)) {
			echo 'paramExists';
		} else {
			$this->db->insert('parameters', $parameter);
		}

	}


	public function delete($parameter)
	{
		$this->db->where('id', $parameter['id']);
		$this->db->delete('parameters');
	}


}
