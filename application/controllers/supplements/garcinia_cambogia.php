<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Garcinia_Cambogia extends CI_Controller {

	public function index()
	{
		$this->load->view('garcinia/landing');
	}


	public function video()
	{
		$this->load->view('garcinia/video');
	}

	public function order()
	{
		$this->load->view('garcinia/ordertemp');
	}
	
}