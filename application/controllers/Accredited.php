    <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accredited extends CI_Controller
{
    public function index()
    {
       	$data['companies'] = $this->sit_db->fetch_company();
       	$data['id'] = 'accredited';
       	$data['title'] = 'Accredited Companies';
       	$data['links'] = array('accredited/');
       	$data['contents'] = array('Accredited Companies');
       	$this->load->view('list',$data);
    }
}