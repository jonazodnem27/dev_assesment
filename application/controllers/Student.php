<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends CI_Controller
{
    public $data = array(
                'links' =>  array('student/','student/faq'),
                'contents' =>  array('Guideliness for Student-Trainee','Frequently Asked Questions (FAQ)'),
                'id'   => 'student',);

    public function index()
    {
        $data = $this->data;
        $data['title'] = 'SIT | Guideliness for Student-Trainee';
        $data['main'] = 'student.txt';
      	$this->load->view('bookmark', $data);
	}

	public function faq()
    {
        $data = $this->data;
        $data['title'] = 'SIT | FAQ';
        $data['main'] = 'faq.txt';
        $this->load->view('bookmark', $data);
	}
}?>