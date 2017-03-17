<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Program extends CI_Controller
{
    public $data = array(
                    'links' =>  array('program/','program/procedure','program/information'),
                    'contents' =>  array('Program Requirements','Program Procedure','Contact Information'),
                    'id'   => 'program',);

    public function index()
    {
        $data = $this->data;
        $data['title'] = 'SIT | Program Requirements';
        $data['main'] = 'program.txt';
      	$this->load->view('bookmark', $data);
	}

    public function procedure()
    {
        $data = $this->data;
        $data['title'] = 'SIT | Program Procedure';
        $data['main'] = 'procedure.txt';
        $this->load->view('bookmark', $data);
    }

    public function information()
    {
        $data = $this->data;
        $data['title'] = 'SIT | Contact Information';
        $data['main'] = 'information.txt';
        $this->load->view('bookmark', $data);
    }
}