<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About extends CI_Controller
{
    public $data = array('links' =>  array('about/','about/structure','about/mission','about/chart'),
                         'contents' =>  array('Historical Background','Stucture of Supervised Industrial Training Program',
                         'IRJP\'s Mission, Vision, Rationale, Goals & Objective','IRJP\'s Organizational Chart'),
                         'id' => 'about',);

	public function index()
    {
        $data = $this->data;
        $data['title'] = 'SIT | About';
        $data['main'] = 'about.txt';
    	$this->load->view('bookmark', $data);
	}

    public function structure()
    {
        $data = $this->data;
        $data['title'] = 'SIT | Structure of SIT';
        $data['main'] = 'structure.txt';
        $this->load->view('bookmark', $data);
    }

    public function mission()
    {
        $data = $this->data;
        $data['title'] = 'SIT | Mission and Vision';
        $data['main'] = 'mission.txt';
        $this->load->view('bookmark', $data);
    }

    public function chart()
    {
        $data = $this->data;
        $data['title'] = 'SIT | Organizational Chart';
        $data['main'] = 'chart.txt';
        $this->load->view('bookmark', $data);
    }

}