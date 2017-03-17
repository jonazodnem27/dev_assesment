<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faculty extends CI_Controller
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
        $username =  $this->session->userdata('sit');
        if($this->session->userdata('director') != NULL)
        {
          redirect('errors');
        }
        $conn = $this->sit_db->mssql_connect();
        $query = sqlsrv_query($conn,
        "SELECT * FROM Users INNER JOIN Teachers 
         ON Users.UserID= Teachers.TeacherID 
         WHERE Users.UserID = '$username' 
         AND Users.Administrator = 'N'");
        
        $data = sqlsrv_fetch_array($query);
        $query2 = sqlsrv_query($conn,
        "SELECT * FROM FacultyTimeLoad INNER JOIN Schedule
         ON FacultyTimeLoad.scheduleid = Schedule.SectionID 
         WHERE FacultyTimeLoad.fid = '$username' AND 
         Schedule.SectionCode LIKE '%-SIT' AND 
         Schedule.SchoolYear = '". $this->schoolyear ."' AND 
         Schedule.Semester = '". $this->semester ."'
         ORDER BY Schedule.SectionID ASC");
        
        $data['subjects'] = array();
        $array = array();
        while($row = sqlsrv_fetch_array($query2))
        {
            if(!in_array($row['SectionCode'],$array))
            {    
                array_push($data['subjects'], $row);
                array_push($array, $row['SectionCode']);
            }
        }
        $data2=sqlsrv_fetch_array($query2);

        $data['title'] = 'Faculty | Home';
        $this->load->view('faculty', $data);
    }

    public function report()
    {
        $username =  $this->session->userdata('sit');
        $subject =  $this->session->userdata('subject');
        $conn = $this->sit_db->mssql_connect();
        $query = sqlsrv_query($conn,
        "SELECT * FROM Users INNER JOIN Teachers
         ON Users.UserID= Teachers.TeacherID 
         WHERE Users.UserID = '$username'
         AND Users.Administrator = 'N'");
        
        $result = sqlsrv_fetch_array($query);

        $data['company'] = $this->sit_db->fetch_company();
        $data['UserName'] = $result['UserName'];
        $data['UserInitial'] = $result['UserInitial'];
        $data['UserID'] = $result['UserID'];
        $data['title'] = 'Faculty | Reports';
        $data['subject'] = $subject;
        $data['registered'] = $this->sit_db->searchByFac($username);
        $data['company'] = $this->sit_db->display_field();
        $data['hours'] = $this->sit_db->checkTIme();
        $data['narrative'] = $this->sit_db->viewNarratives();
        $data['supervisor'] = $this->sit_db->searchSupervisorbyFac($username);
        
        $this->load->view('reports', $data);
    }

    public function message(){
         $username =  $this->session->userdata('sit');
         $conn = $this->sit_db->mssql_connect();
        $query = sqlsrv_query($conn,
        "SELECT * FROM Users INNER JOIN Teachers
         ON Users.UserID= Teachers.TeacherID 
         WHERE Users.UserID = '$username'
         AND Users.Administrator = 'N'");
        
        $data = sqlsrv_fetch_array($query);
        $data['title'] = 'Messages';
        $data['user'] = $username;
        $data['list2'] = 'Director';
        $data['list1'] = 'My Students';
        $this->load->view('message', $data);
    }

    public function company()
    {

        $username =  $this->session->userdata('sit');
        $subject =  $this->session->userdata('subject');

        $this->form_validation->set_rules('company', 'Supervisor Company', array('required'));
        $this->form_validation->set_rules('name', 'Supervisor Name', array('required'));
        $this->form_validation->set_rules('position', 'Supervisor Position', array('required'));
        $this->form_validation->set_rules('contact', 'Supervisor Contact Number', array('required'));
        $this->form_validation->set_rules('email', 'Supervisor Email Address', array('required'));

        $conn = $this->sit_db->mssql_connect();
        $query = sqlsrv_query($conn,
        "SELECT * FROM Users INNER JOIN Teachers
        ON Users.UserID= Teachers.TeacherID
        WHERE Users.UserID = '$username' AND Users.Administrator = 'N'");
        
        $data = sqlsrv_fetch_array($query);
        //// for director
            // $supervisor = $this->sit_db->display_field();
            // $data['field'] = $supervisor;
            // $data['supervisor'] = $this->sit_db->searchSupervisor($supervisor[0]->id);
            // $data['visited'] = $this->sit_db->visitedCompany();

        $data['supervisor'] = $this->sit_db->searchSupervisorbyFac($username);
        $data['visited'] = $this->sit_db->fetch_company();
        $data['title'] = 'Faculty | Compamy Preference';

        if($this->form_validation->run() == FALSE)
        {
            $this->load->view('field',$data);
        }
        else
        {
            // $datas = array(
            //    'companyName' => $this->input->post('name'),
            //    'companyAddress' => $this->input->post('address'),
            //    'companyCity' => $this->input->post('email'),
            // );

            $datas = array(
                'sup_name' => $this->input->post('name'),
                'sup_position' => $this->input->post('position'),
                'sup_contact' => $this->input->post('contact'),
                'sup_email' => $this->input->post('email'),
                'comp_id' => $this->input->post('company'),
                'College'=> $data['Department'],
                'fac_id' => $username,);

            // $this->sit_db->insert_field($datas);
            $this->sit_db->addSupervisor($datas);
            $this->session->set_userdata('message', 'Added new company details');
            redirect('faculty/company');
        } 
    }

    public function visit($city)
    {
        $username =  $this->session->userdata('sit');
        $subject =  $this->session->userdata('subject');
        $conn = $this->sit_db->mssql_connect();
        $query = sqlsrv_query($conn,
        "SELECT * FROM Users INNER JOIN Teachers
         ON Users.UserID= Teachers.TeacherID
         WHERE UserID = '$username' 
         AND Administrator = 'N'");
        
        $data = sqlsrv_fetch_array($query);
        $data['companies'] = $this->sit_db->fieldByCity($city);
        $data['students'] = $this->sit_db->searchAllStud();
        $data['visited'] = $this->sit_db->listCompany();
        $data['title'] = 'Faculty | Visited Company';
        $data['city'] = $city;

        $this->load->view('visited',$data);
           
    }

    public function deploy()
    {
        $username =  $this->session->userdata('sit');
        $subject =  $this->session->userdata('subject');
        $conn = $this->sit_db->mssql_connect();
        $query = sqlsrv_query($conn,
        "SELECT * FROM Users INNER JOIN Teachers 
         ON Users.UserID= Teachers.TeacherID
         WHERE Users.UserID = '$username'
         AND Users.Administrator = 'N'");

        $data = sqlsrv_fetch_array($query);
        $data['companies'] = $this->sit_db->display_field();
        $data['students'] = $this->sit_db->searchByFac($username);
        $data['visited'] = $this->sit_db->listCompany();
        $data['title'] = 'Faculty | Deployed Students';

        $this->load->view('deploy',$data);
              
    }

    public function student($id)
    {
        $data = $this->sit_db->searchStud($id);
        if($data == FALSE)
        {
            redirect('errors');
        }
        else
        {
            $data['data'] = $data;
            $data['title'] = 'Faculty | Student Information';
            $data['reports'] = $this->sit_db->fetchreports($id);
            $data['company'] = $this->sit_db->fetch_company();
            $this->load->view('student',$data);
        
        }
    }

    public function view($id,$week)
    {
        $res = $this->sit_db->searchWeek($id,$week);
        if($res == FALSE)
        {
            redirect('error');
        }
        else
        {
            $data['title'] = 'Student Report';
            $data['user'] = $this->sit_db->searchStud($id);
            $data['supervisor'] = $this->sit_db->searchSupervisorbyID($data['user'][0]->Supervisor);
            $data['week'] = $res;
            $this->load->view('week',$data);
        }
    }

    public function details($id)
    {
        if($id == NULL)
        {
            redirect('errors');
        }

        $newID = $id;
        $username =  $this->session->userdata('sit');
        $subject =  $this->session->userdata('subject');
        $conn = $this->sit_db->mssql_connect();
        $query = sqlsrv_query($conn,"
        SELECT * FROM Users INNER JOIN Teachers
        ON Users.UserID= Teachers.TeacherID
        WHERE Users.UserID = '$username' 
        AND Users.Administrator = 'N'");
        
        $result = sqlsrv_fetch_array($query);
        $Name = $result['UserName'];

        //$query2 = sqlsrv_query($conn,
        //  "SELECT * FROM Enlist2016 INNER JOIN GSSubmitted2016
        //   ON GSSubmitted2016.subject = Enlist2016.SectionCode 
        //   WHERE GSSubmitted2016.teacherid = '$username' AND 
        //   GSSubmitted2016.subject LIKE '%-SIT' AND 
        //   Enlist2016.SectionCode = '$id' 
        //   ORDER BY Enlist2016.StudentName ASC");

        $query2 = sqlsrv_query($conn,
        "SELECT DISTINCT Enlist". $this->year .".EID, Enlist". $this->year .".StudentNo, Enlist". $this->year .".Semester,
         Enlist". $this->year .".SchoolYear, Enlist". $this->year .".SubjectCode, Enlist". $this->year .".StudentYear,
         Enlist". $this->year .".SubjectDescription, Enlist". $this->year .".StudentName, Enlist". $this->year .".sDay,
         Enlist". $this->year .".sTime, Enlist". $this->year .".course, FacultyTimeLoad.scheduleid, FacultyTimeLoad.fid,
         Users.UserName, Schedule.SectionID,Schedule.college
         FROM Users,Schedule,Enlist". $this->year .",FacultyTimeLoad
         WHERE Enlist". $this->year .".sectioncode=Schedule.SectionCode AND
         Schedule.SectionID=FacultyTimeLoad.scheduleid AND
         FacultyTimeLoad.fid=Users.Userid AND
         FacultyTimeLoad.fid='$username' AND
         Schedule.SectionCode like '%SIT' AND
         Enlist". $this->year .".SectionCode = '$id'
         ORDER BY Enlist". $this->year .".StudentName ASC");

        $data['lists'] = array();
        while($row = sqlsrv_fetch_array($query2))
        {
            $id = $row['StudentNo'];
            $res = $this->sit_db->searchStud($id);
            if($res == FALSE)
            {
                array_push($data['lists'], $row);
            }
        }

        $data['company'] = $this->sit_db->display_field();
        $data['supervisor'] = $this->sit_db->searchSupervisorbyFac($username);
        $data['UserName'] = $result['UserName'];
        $data['UserInitial'] = $result['UserInitial'];
        $data['UserID'] = $result['UserID'];
        $data['title'] = 'Faculty | Training Field';
        $data['subject'] = $subject;
        $data['registered'] = $this->sit_db->search_all($username,$newID);
        $this->load->view('details', $data);

    }

    public function sendDB()
    {
        $id = $this->input->post('id');
        $value = $this->input->post('value');
        $this->sit_db->insertUser($id,$value);
    }

    public function visited()
    {
        $id = $this->input->post('id');
        $fac =  $this->session->userdata('sit');
        $year = $this->year;
        $semester = $this->semester;
        $this->sit_db->visited($id,$fac,$year,$semester);
        $t = time();
        echo date("m/d/y",$t);
    }

    public function updateSupervisor(){
        $id = $this->input->post('id');
        $value = $this->input->post('value');

        $co = explode("|", $value);
            $data = array(
              'sup_name' => $co[5],
              'sup_position' => $co[4],
              'sup_contact' => $co[3],
              'sup_email' => $co[2],
              'comp_id' => $co[0],
              );
         $this->sit_db->updateSuper($id,$data);       

    }

    public function updatedeployed(){
        $id = $this->input->post('id');
        $value = $this->input->post('value');
        $result = $this->sit_db->searchSupervisorbyID($value);
        $comp = $result[0]->comp_id;
            $data = array(
              'companyID' => $comp,
              'Supervisor' => $value,
              );
         $this->sit_db->updateStudDeployed($id,$data); 
    }
}?>