<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Images extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$post = file_get_contents('php://input');
		$_POST = json_decode($post, true);

		$this->load->model('Products_model');
	}

	public function upload($id)
	{

		if ( !empty( $_FILES ) ) 
		{
		    $tempPath = $_FILES[ 'file' ][ 'tmp_name' ];

		    $basePath = FCPATH . '..' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;

		    $basePath = $basePath . $id . DIRECTORY_SEPARATOR;
		    mkdir($basePath, 0755);

		    $uploadPath = $basePath . $_FILES[ 'file' ][ 'name' ];

		    move_uploaded_file( $tempPath, $uploadPath );

		    $answer = array( 'answer' => 'File transfer completed' );

		    $json = json_encode( $answer );

		    echo $json;

		} 
		else 
		{
		    echo 'No files';
		}

	}

	public function get($id)
	{
		$basePath = FCPATH . '..' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;

		$basePath = $basePath . $id . DIRECTORY_SEPARATOR;

		if (!is_dir($basePath)) {
			return;
		}
	
		$files = scandir($basePath);
		$files = array_diff($files, array('.', '..'));
		$newFiles = array();
			
		foreach ($files as $file) {
			$newFiles[] .=$file; 
		}

		echo json_encode($newFiles);

	}

	public function delete()
	{
	
		$id = $this->input->post('id');
		$image = $this->input->post('image');

		$this->Products_model->deleteThumb( $id , $image );

		$imagePath = FCPATH . '..' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;
		$imagePath = $imagePath . $id . DIRECTORY_SEPARATOR;
		$imagePath = $imagePath . $image;

		unlink($imagePath);

	}

	public function setThumb()
	{

		$input = $this->input->post( 'product' );
		$productId = $input['id'];

		$imageName = $this->input->post( 'image' );
		$product['thumb'] = $imageName;

		$this->Products_model->setThumb( $productId , $product );
	}

}
