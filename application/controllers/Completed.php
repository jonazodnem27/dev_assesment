    <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Completed extends CI_Controller
{
    public $year;
    public $schoolyear;
    public $semester;

    public function  __construct()
    {
        parent::__construct();
        $this->year = file_get_contents(base_url()."assets/database/year.txt");
        $this->schoolyear = file_get_contents(base_url()."assets/database/schoolyear.txt");
        $this->semester = file_get_contents(base_url()."assets/database/semester.txt");
    }


    public function index()
    {

        $username =  $this->session->userdata('completed');
        $conn = $this->sit_db->mssql_connect();
        $query = sqlsrv_query($conn,"SELECT * FROM Enlist". $this->year ." WHERE StudentNo = '$username'  AND Enlisted = 'Y'");
 
        $data = sqlsrv_fetch_array($query);
        $data['account'] = $this->sit_db->searchStud($username);
        $data['reports'] = $this->sit_db->fetchreports($username);
        $data['company'] = $this->sit_db->fetch_company();
        $data['title'] = 'Account';
        $res = $this->sit_db->searchActivity($username);
        // if($res != FALSE)
        // {
        //     $data['report'] = $res;
        // }
        $this->load->view('account',$data);
    }

}