    <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class View extends CI_Controller
{
    public function pdf($pdf)
    {
    	$data['pdf'] = $pdf;
    	$this->load->view('pdf',$data);
    }
}
