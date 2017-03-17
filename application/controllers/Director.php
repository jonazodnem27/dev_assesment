    <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Director extends CI_Controller
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
        $subject =  $this->session->userdata('subject');
        $director =  $this->session->userdata('director');
        if($director == NULL)
        {
            redirect('errors');
        }
        else
        {
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
             WHERE Schedule.SectionCode LIKE '%-SIT' AND 
             Schedule.SchoolYear = '". $this->schoolyear ."' AND 
             Schedule.Semester = '". $this->semester ."' 
             ORDER BY Schedule.SectionID ASC");

            $query3 = sqlsrv_query($conn,
            "SELECT * FROM Users INNER JOIN Teachers
            ON Users.UserID= Teachers.TeacherID ");
                             
            $query4 = sqlsrv_query($conn,
            "SELECT DISTINCT Enlist". $this->year .".StudentName, Enlist". $this->year .".SectionCode,
            FacultyTimeLoad.fid 
            FROM  Schedule, Enlist". $this->year .",FacultyTimeLoad
            WHERE Enlist". $this->year .".Sectioncode=Schedule.SectionCode AND
            Schedule.SectionID=FacultyTimeLoad.scheduleid AND
            Schedule.SectionCode like '%-SIT' AND 
            Schedule.Semester='". $this->semester ."' AND 
            Schedule.SchoolYear='". $this->schoolyear ."'
            ORDER BY FacultyTimeLoad.fid, Enlist". $this->year .".StudentName");

            $data['students'] = array();
            $data['subjects'] = array();
            $data['users'] = array();
            $array = array();
               
            while($row1 = sqlsrv_fetch_array($query3))
            {
                array_push($data['users'], $row1);
            }

            while($row2 = sqlsrv_fetch_array($query4))
            {
                array_push($data['students'], $row2);
            }

            while($row = sqlsrv_fetch_array($query2))
            {
                if(!in_array($row['SectionCode'],$array))
                {    
                    array_push($data['subjects'], $row);
                    array_push($array, $row['SectionCode']);
                }
            }



            $data['title'] = 'Director | Home';
            $this->load->view('director',$data);
        }
    }

    public function message(){

            $username =  $this->session->userdata('sit');
        $subject =  $this->session->userdata('subject');
        $director =  $this->session->userdata('director');
         $subjects = array();
        $users = array();

        if($director == NULL)
        {
            redirect('errors');
        }

         $username =  $this->session->userdata('sit');
         $conn = $this->sit_db->mssql_connect();
        $query = sqlsrv_query($conn,
        "SELECT * FROM Users INNER JOIN Teachers
         ON Users.UserID= Teachers.TeacherID 
         WHERE Users.UserID = '$username'
         AND Users.Administrator = 'N'");
        
        $data = sqlsrv_fetch_array($query);

        // $query2 = sqlsrv_query($conn,
        //     "SELECT * FROM FacultyTimeLoad INNER JOIN Schedule 
        //      ON FacultyTimeLoad.scheduleid = Schedule.SectionID
        //      WHERE Schedule.SectionCode LIKE '%-SIT' AND 
        //      Schedule.SchoolYear = '". $this->schoolyear ."' AND 
        //      Schedule.Semester = '". $this->semester ."' 
        //      ORDER BY Schedule.SectionID ASC");

        //     $query3 = sqlsrv_query($conn,
        //     "SELECT UserID,UserName FROM Users INNER JOIN Teachers
        //     ON Users.UserID=Teachers.TeacherID ");

        //     while($row1 = sqlsrv_fetch_array($query3)){
        //         array_push($users, $row1);
        //     }

        //     while($row = sqlsrv_fetch_array($query2)){
        //         if(!in_array($row['fid'],$subjects)){    
        //             array_push($subjects, $row['fid']);
        //         }
        //     }

        //     // ECHO '<PRE>';
        //     // var_dump($subjects);
        //     // var_dump($users);
        //     // ECHO '</PRE>';

        //     foreach ($subjects as $key => $value){ 
                
        //     foreach ($users as $key => $val){
                
        //     if($value == $val['UserID']){
        //          echo $val['UserName'];
        //     } } }





        $data['title'] = 'Messages';
        $data['user'] = $username;
        $data['list1'] = 'My Faculties';
        $data['director'] = $director;
        $this->load->view('message', $data);
    }

    public function enrolled($year,$semester=null,$college=null,$faculty=null){

        $username =  $this->session->userdata('sit');
        $subject =  $this->session->userdata('subject');
        $director =  $this->session->userdata('director');
        if($director == NULL)
        {
            redirect('errors');
        }
        else
        {

            $this->form_validation->set_rules('semester', 'Semester', array('required'));
            $this->form_validation->set_rules('college', 'College', array('required'));
            $this->form_validation->set_rules('faculty', 'Faculty', array('required'));

        if($this->form_validation->run() == TRUE){
               $year = $this->input->post('year');
               $sem = $this->input->post('semester');
               $college = $this->input->post('college');
               $faculty = $this->input->post('faculty');

               redirect('director/enrolled/'.$year.'/'.$sem.'/'.$college.'/'.$faculty);

        }else{

        $conn = $this->sit_db->mssql_connect();
        $query = sqlsrv_query($conn,
        "SELECT * FROM Users INNER JOIN teachers 
         ON Users.UserID= Teachers.TeacherID
         WHERE Users.UserID = '$username' AND Users.Administrator = 'N'");
        $data = sqlsrv_fetch_array($query);

            $query2 = sqlsrv_query($conn,
                "SELECT * FROM FacultyTimeLoad INNER JOIN Schedule 
                 ON FacultyTimeLoad.scheduleid = Schedule.SectionID
                 WHERE Schedule.SectionCode LIKE '%-SIT' AND 
                 Schedule.SchoolYear = '". $year ."' AND 
                 Schedule.Semester = '". $semester ."' 
                 ORDER BY Schedule.SectionID ASC");

            $query3 = sqlsrv_query($conn,
                        "SELECT * FROM Users INNER JOIN Teachers
                        ON Users.UserID= Teachers.TeacherID ");

            $data['subjects'] = array();
            $data['faculty'] = array();
            $array = array();

            while($row1 = sqlsrv_fetch_array($query3))
                        {
                            array_push($data['faculty'], $row1);
                        }
            while($row = sqlsrv_fetch_array($query2))
                        {
                            if(!in_array($row['SectionCode'],$array))
                            {    
                                array_push($data['subjects'], $row);
                                array_push($array, $row['SectionCode']);
                            }
                        }

        $data['users'] = $this->sit_db->enrolled($year,$semester,$college,$faculty);
        $data['year'] = $this->sit_db->years();
        $data['companies'] = $this->sit_db->display_field();
        $data['supervisor'] = $this->sit_db->getSupervisors();
        $data['yearly'] = $year;
        $data['semester'] = $semester;
        $data['college'] = $college;
        $data['facul'] = $faculty;
        $data['title'] = 'Director | Home';

        $this->load->view('enrolled',$data);
        }
    }
    }

    public function getFaculty(){

        $semester = $this->input->post('sem');
        $year = $this->input->post('year');
        $col = $this->input->post('col');
        $sem = str_replace('_', ' ', $semester);

        $conn = $this->sit_db->mssql_connect();
        $query3 = sqlsrv_query($conn,
         "SELECT * FROM Users INNER JOIN Teachers
           ON Users.UserID= Teachers.TeacherID 
           ORDER BY Users.UserName ASC");


        $data['faculty'] = array();
        while($row1 = sqlsrv_fetch_array($query3))
         {
             array_push($data['faculty'], $row1);
          }


          $array = array();

        $query2 = sqlsrv_query($conn,
                "SELECT * FROM FacultyTimeLoad INNER JOIN Schedule 
                 ON FacultyTimeLoad.scheduleid = Schedule.SectionID
                 WHERE Schedule.SectionCode LIKE '%-SIT' AND 
                 Schedule.SchoolYear = '". $year ."' AND
                 Schedule.Semester = '". $sem ."' 
                 ORDER BY Schedule.SectionID ASC");

         $data['subjects'] = array();
         while($row = sqlsrv_fetch_array($query2))
                        {
                            if(!in_array($row['SectionCode'],$array))
                            {    
                                array_push($data['subjects'], $row);
                                array_push($array, $row['SectionCode']);
                            }
                        }

    $array2 = array(); 
    $subjects = $data['subjects'];
    $faculty = $data['faculty'];
    // echo "SELECT * FROM FacultyTimeLoad INNER JOIN Schedule 
    //              ON FacultyTimeLoad.scheduleid = Schedule.SectionID
    //              WHERE Schedule.SectionCode LIKE '%-SIT' AND 
    //              Schedule.SchoolYear = '". $year ."' AND Schedule.College = '".$col."' AND
    //              Schedule.Semester = '". $sem ."' 
    //              ORDER BY Schedule.SectionID ASC";

    echo '<option value="" selected disabled>Please Choose a Faculty-in-Charge</option>';
      foreach ($subjects as $key => $value)
       { 
        //echo '<option value="'.$value['fid'].'">'.$value['fid'].'</option>';
        if(in_array($value['fid'],$array2)){  
          continue;
        }

          foreach ($faculty as $key => $val){
              if($value['fid'] == $val['UserID']){
               echo '<option value="'.$val['UserID'].'">'.$val['UserName'].'</option>';
               array_push($array2, $value['fid']);
              }
            }
    }

    }

    public function all($year, $sem=null,$college=null){

        $username =  $this->session->userdata('sit');
        $subject =  $this->session->userdata('subject');
        $director =  $this->session->userdata('director');
        if($director == NULL){
            redirect('errors');
        }
        else
        {

            $this->form_validation->set_rules('semester', 'Semester', array('required'));
            $this->form_validation->set_rules('college', 'College', array('required'));

        if($this->form_validation->run() == TRUE){
               $year = $this->input->post('year');
               $sem = $this->input->post('semester');
               $college = $this->input->post('college');

               redirect('director/all/'.$year.'/'.$sem.'/'.$college);

        }else{

        $conn = $this->sit_db->mssql_connect();
        $query = sqlsrv_query($conn,
        "SELECT * FROM Users INNER JOIN teachers 
         ON Users.UserID= Teachers.TeacherID
         WHERE Users.UserID = '$username' AND Users.Administrator = 'N'");
        $data = sqlsrv_fetch_array($query);

        $schoolYear = explode('-', $year);
        $yearOnly = $schoolYear[0];
        if($sem == NULL && $college == NULL){

        $query2 = sqlsrv_query($conn,
                "SELECT * FROM Enlist".$yearOnly." WHERE SubjectCode = 'SIT' AND Enlisted = 'Y' ORDER BY StudentName ASC");

        }else{
        $semester = str_replace('_', ' ', $sem);
        $query2 = sqlsrv_query($conn,
                "SELECT * FROM Enlist".$yearOnly." INNER JOIN Courses ON Courses.CourseCode = Enlist".$yearOnly.".Course WHERE SubjectCode = 'SIT' AND Enlist".$yearOnly.".Enlisted = 'Y' AND Enlist".$yearOnly.".Semester = '".$semester."' AND Courses.SubjectDeptCode = '".$college."' ORDER BY StudentName ASC");

        }  



        $data['students'] = array();
        $array = array();
        while($row = sqlsrv_fetch_array($query2))
                        {
                            if(!in_array($row['StudentNo'],$array))
                            {    
                                array_push($data['students'], $row);
                                array_push($array, $row['StudentNo']);
                            }
                        }



        $data['yearly'] = $year;
        $data['semester'] = $sem;
        $data['college'] = $college;
        $data['title'] = 'Director | Home';

        $this->load->view('all',$data);
        }
    }
    }

    public function handled($year,$semester = null){
        $username =  $this->session->userdata('sit');
        $subject =  $this->session->userdata('subject');
        $director =  $this->session->userdata('director');
        if($director == NULL)
        {
            redirect('errors');
        }
        else
        {

            $this->form_validation->set_rules('semester', 'Semester', array('required'));

        if($this->form_validation->run() == TRUE){
               $year = $this->input->post('year');
               $sem = $this->input->post('semester');

               redirect('director/handled/'.$year.'/'.$sem);

        }else{

            $schoolYear = explode('-', $year);
            $yearOnly = $schoolYear[0];

        $conn = $this->sit_db->mssql_connect();
        $query = sqlsrv_query($conn,
        "SELECT * FROM Users INNER JOIN teachers 
         ON Users.UserID= Teachers.TeacherID
         WHERE Users.UserID = '$username' AND Users.Administrator = 'N'");
        $data = sqlsrv_fetch_array($query);
        $query3 = sqlsrv_query($conn,
            "SELECT * FROM Users INNER JOIN Teachers
            ON Users.UserID= Teachers.TeacherID ");


            if($semester == NULL){

            $query2 = sqlsrv_query($conn,
            "SELECT * FROM FacultyTimeLoad INNER JOIN Schedule 
             ON FacultyTimeLoad.scheduleid = Schedule.SectionID
             WHERE Schedule.SectionCode LIKE '%-SIT' AND 
             Schedule.SchoolYear = '". $year ."' 
             ORDER BY Schedule.SectionID ASC");
                             
            $query4 = sqlsrv_query($conn,
            "SELECT DISTINCT Enlist". $yearOnly .".StudentName, Enlist". $yearOnly .".SectionCode,
            FacultyTimeLoad.fid 
            FROM  Schedule, Enlist". $yearOnly .",FacultyTimeLoad
            WHERE Enlist". $yearOnly .".Sectioncode=Schedule.SectionCode AND
            Schedule.SectionID=FacultyTimeLoad.scheduleid AND
            Schedule.SectionCode like '%-SIT' AND 
            Schedule.SchoolYear='". $year ."'
            ORDER BY FacultyTimeLoad.fid, Enlist". $yearOnly .".StudentName");

            }else{

            $semester2 = str_replace('_', ' ', $semester);

            $query2 = sqlsrv_query($conn,
            "SELECT * FROM FacultyTimeLoad INNER JOIN Schedule 
             ON FacultyTimeLoad.scheduleid = Schedule.SectionID
             WHERE Schedule.SectionCode LIKE '%-SIT' AND 
             Schedule.SchoolYear = '". $year ."' AND 
             Schedule.Semester = '". $semester2 ."' 
             ORDER BY Schedule.SectionID ASC");
                             
            $query4 = sqlsrv_query($conn,
            "SELECT DISTINCT Enlist". $yearOnly .".StudentName, Enlist". $yearOnly .".SectionCode,
            FacultyTimeLoad.fid 
            FROM  Schedule, Enlist". $yearOnly .",FacultyTimeLoad
            WHERE Enlist". $yearOnly .".Sectioncode=Schedule.SectionCode AND
            Schedule.SectionID=FacultyTimeLoad.scheduleid AND
            Schedule.SectionCode like '%-SIT' AND 
            Schedule.Semester='". $semester2 ."' AND 
            Schedule.SchoolYear='". $year ."'
            ORDER BY FacultyTimeLoad.fid, Enlist". $yearOnly .".StudentName");

            }


            $data['students'] = array();
            $data['subjects'] = array();
            $data['users'] = array();
            $array = array();
               
            while($row1 = sqlsrv_fetch_array($query3))
            {
                array_push($data['users'], $row1);
            }

            while($row2 = sqlsrv_fetch_array($query4))
            {
                array_push($data['students'], $row2);
            }

            while($row = sqlsrv_fetch_array($query2))
            {
                if(!in_array($row['SectionCode'],$array))
                {    
                    array_push($data['subjects'], $row);
                    array_push($array, $row['SectionCode']);
                }
            }

        $data['year'] = $this->sit_db->years();
        $data['yearly'] = $year;
        $data['semester'] = $semester;
        $data['title'] = 'Director | Home';

        $this->load->view('handled',$data);

        }
    }
}

    public function companydeployed($year, $semester=null){
        $username =  $this->session->userdata('sit');
        $subject =  $this->session->userdata('subject');
        $director =  $this->session->userdata('director');
        if($director == NULL)
        {
            redirect('errors');
        }
        else
        {

            $this->form_validation->set_rules('semester', 'Semester', array('required'));

        if($this->form_validation->run() == TRUE){
               $year = $this->input->post('year');
               $sem = $this->input->post('semester');

               redirect('director/companydeployed/'.$year.'/'.$sem);

        }else{

        $conn = $this->sit_db->mssql_connect();
        $query = sqlsrv_query($conn,
        "SELECT * FROM Users INNER JOIN teachers 
         ON Users.UserID= Teachers.TeacherID
         WHERE Users.UserID = '$username' AND Users.Administrator = 'N'");
        $data = sqlsrv_fetch_array($query);

        $data['year'] = $this->sit_db->years();
        $data['companies'] = $this->sit_db->fetch_company();
        $data['users'] = $this->sit_db->companyDeployed($year,$semester);

        $data['yearly'] = $year;
        $data['semester'] = $semester;
        $data['title'] = 'Director | Home';

        $this->load->view('company',$data); 
    }
}
}

    public function companies(){
        $username =  $this->session->userdata('sit');
        $subject =  $this->session->userdata('subject');
        $director =  $this->session->userdata('director');
        if($director == NULL)
        {
            redirect('errors');
        }
        else
        {
                $conn = $this->sit_db->mssql_connect();
                $query = sqlsrv_query($conn,
                "SELECT * FROM Users INNER JOIN teachers 
                 ON Users.UserID= Teachers.TeacherID
                 WHERE Users.UserID = '$username' AND Users.Administrator = 'N'");
                $data = sqlsrv_fetch_array($query);
                $data['companies'] = $this->sit_db->fetch_company();
                $data['title'] = 'Director | Home';
                $this->load->view('companies',$data);
        }
    }

    public function onsitevisit($year,$sem=null){
        $username =  $this->session->userdata('sit');
        $subject =  $this->session->userdata('subject');
        $director =  $this->session->userdata('director');
        if($director == NULL)
        {
            redirect('errors');
        }
        else
        {

            $this->form_validation->set_rules('semester', 'Semester', array('required'));

        if($this->form_validation->run() == TRUE){
               $year = $this->input->post('year');
               $sem = $this->input->post('semester');

               redirect('director/onsitevisit/'.$year.'/'.$sem);

        }else{

        $conn = $this->sit_db->mssql_connect();
        $query = sqlsrv_query($conn,
        "SELECT * FROM Users INNER JOIN teachers 
         ON Users.UserID= Teachers.TeacherID
         WHERE Users.UserID = '$username' AND Users.Administrator = 'N'");
        $data = sqlsrv_fetch_array($query);

        $schoolYear = explode('-', $year);
        $yearOnly = $schoolYear[0];
        $semester = str_replace('_', ' ', $sem);


        $query3 = sqlsrv_query($conn,
         "SELECT * FROM Users INNER JOIN Teachers
         ON Users.UserID= Teachers.TeacherID ");
        $data['faculty'] = array();
            $array = array();
         while($row1 = sqlsrv_fetch_array($query3))
        {
             array_push($data['faculty'], $row1);
         }
        $data['year'] = $this->sit_db->years();
        $data['companies'] = $this->sit_db->fetch_company();
        $data['visited'] = $this->sit_db->visitedCompany($yearOnly,$semester);

        $data['yearly'] = $year;
        $data['semester'] = $sem;
        $data['title'] = 'Director | Home';

        $this->load->view('onsite',$data); 
    }
}

    }

    public function faculty($id)
    {
        $username =  $this->session->userdata('sit');
        $subject =  $this->session->userdata('subject');
        $director =  $this->session->userdata('director');

        $conn = $this->sit_db->mssql_connect();
        $query = sqlsrv_query($conn,
        "SELECT * FROM Users INNER JOIN Teachers 
         ON Users.UserID= Teachers.TeacherID 
         WHERE users.UserID = '$username' 
         AND users.Administrator = 'N'");

        $data = sqlsrv_fetch_array($query);
        $username =  $this->session->userdata('sit');
        $subject =  $this->session->userdata('subject');
        $director =  $this->session->userdata('director');

        $data['id'] = $id;
        $data['title'] = 'Home | Director';

        if($director == NULL)
        {
            redirect('errors');
        }
        else
        {
            $query2 = sqlsrv_query($conn,
            "SELECT * FROM FacultyTimeLoad INNER JOIN Schedule 
             ON FacultyTimeLoad.scheduleid = Schedule.SectionID 
             WHERE FacultyTimeLoad.fid = '$id' AND 
             Schedule.SectionCode LIKE '%-SIT' AND 
             Schedule.SchoolYear = '". $this->schoolyear ."' AND 
             Schedule.Semester = '". $this->semester ."' 
             ORDER BY Schedule.SectionID ASC");

            $query3 = sqlsrv_query($conn,
            "SELECT * FROM Users INNER JOIN Teachers
             ON Users.UserID= Teachers.TeacherID
             WHERE Users.UserID = '$id' 
             AND Users.Administrator = 'N'");
             
            $query4 = sqlsrv_query($conn,
            "SELECT * FROM Enlist". $this->year ."
             WHERE SectionCode LIKE '%-SIT' 
             ORDER BY StudentName");
             
            $data['students'] = array();
            $data['faculty'] = sqlsrv_fetch_array($query3);
            while($row6 = sqlsrv_fetch_array($query4))
            {
                array_push($data['students'], $row6);
            }
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
            $hrs = $this->sit_db->checkTIme();
            
            $arrayHrs = array();
            foreach ($hrs as $key => $value) {
                 if(isset($arrayHrs[$hrs[$key]->user_id])){
                  $arrayHrs[$hrs[$key]->user_id]['hours'] += $hrs[$key]->totalHours;
                }else{
                     array_push($arrayHrs, $arrayHrs[$hrs[$key]->user_id] = array('hours' => $hrs[$key]->totalHours, 'userid'=>$hrs[$key]->user_id));   
                    unset($arrayHrs[$key]);
               }
            }

            $data['hrs'] = $arrayHrs;
            $data['req'] = $this->sit_db->req_hrs();
            $data['registered'] = $this->sit_db->searchByFac($id);
            $data['companies'] = $this->sit_db->fetch_company();
            $data['visited'] = $this->sit_db->listCompany();

            $this->load->view('dir-supervisor',$data);
        }
    }

    public function accredited()
    {
        $username =  $this->session->userdata('sit');
        $subject =  $this->session->userdata('subject');
        $director =  $this->session->userdata('director');

        $this->form_validation->set_rules('name', 'Company Name', array('required'));
        $this->form_validation->set_rules('address', 'Company Address', array('required'));
        $this->form_validation->set_rules('city', 'Company City', array('required'));
        $this->form_validation->set_rules('type', 'Company Type', array('required'));

        $config['upload_path']          = './uploads';
        $config['allowed_types']        = 'pdf|doc|docx';
        $config['max_size']             = 0;

        $this->upload->initialize($config);

        if($this->form_validation->run() == TRUE && $this->upload->do_upload('userfile'))
        {
            $conn = $this->sit_db->mssql_connect();
            $query = sqlsrv_query($conn,
            "SELECT * FROM Users INNER JOIN Teachers
             ON Users.UserID= Teachers.TeacherID
             WHERE Users.UserID = '$username'
             AND Users.Administrator = 'N'");

            $data = sqlsrv_fetch_array($query);
            $data['title'] = 'Director | Accredited Companies';
            $data['companies'] = $this->sit_db->fetch_company();
            $data['upload'] = $this->upload->data();

            $newData = array(
                'companyName' =>    $this->input->post('name'),
                'companyAddress' => $this->input->post('address'),
                'companyCity' => $this->input->post('city'),
                'line_business' =>   $this->input->post('type'),
                'companyCredentials' => $this->upload->data('file_name'),
                'orig_file_name' => $this->upload->data('client_name'),);

            $this->sit_db->uploadData($newData);
            redirect('director/accredited');

        }
        else
        {
            $conn = $this->sit_db->mssql_connect();
            $query = sqlsrv_query($conn,
            "SELECT * FROM Users INNER JOIN Teachers
             ON Users.UserID= Teachers.TeacherID 
             WHERE Users.UserID = '$username' 
             AND Users.Administrator = 'N'");

            $data = sqlsrv_fetch_array($query);
            $data['title'] = 'Director | Accredited Companies';
            $data['companies'] = $this->sit_db->fetch_company();
            $data['error'] = array('error' => $this->upload->display_errors());

            $this->load->view('accredit',$data);
        }
    }

    public function deployed()
    {
        $username =  $this->session->userdata('sit');
        $subject =  $this->session->userdata('subject');
        $director =  $this->session->userdata('director');

        $conn = $this->sit_db->mssql_connect();
        $query = sqlsrv_query($conn,
        "SELECT * FROM Users INNER JOIN Teachers
         ON Users.UserID= Teachers.TeacherID
         WHERE Users.UserID = '$username' 
         AND Users.Administrator = 'N'");
  
        $data = sqlsrv_fetch_array($query);
        $query2 = sqlsrv_query($conn,
        "SELECT * FROM Users INNER JOIN Teachers
         ON Users.UserID= Teachers.TeacherID
         WHERE Administrator = 'N'");
  
        $data['faculty'] = array();
        $array = array();

        while($row = sqlsrv_fetch_array($query2))
        {
            if(!in_array($row['UserID'],$array))
            {    
                array_push($data['faculty'], $row);
                array_push($array, $row['UserID']);
            }
        }

        $data['title'] = 'Director | Deployed Training Fields';
        $data['companies'] = $this->sit_db->fetch_company();
        $data['visited'] = $this->sit_db->listCompany();
        $data['registered'] = $this->sit_db->searchBySem($this->semester,$this->schoolyear);

        $this->load->view('deployed',$data);
    }

    public function hours()
    {
        $this->form_validation->set_rules('course', 'Course', array('required'));
        $this->form_validation->set_rules('noOfHrs', 'No of Hours', array('required'));
      
        if($this->form_validation->run() == FALSE)
        {       
            $username =  $this->session->userdata('sit');
            $subject =  $this->session->userdata('subject');
            $director =  $this->session->userdata('director');

            $conn = $this->sit_db->mssql_connect();
            $query = sqlsrv_query($conn,
            "SELECT * FROM Users INNER JOIN Teachers
             ON Users.UserID= Teachers.TeacherID 
             WHERE UserID = '$username' 
             AND Administrator = 'N'");
            
            $data = sqlsrv_fetch_array($query);
            $query2 = sqlsrv_query($conn,
            "SELECT * FROM FacultyTimeLoad INNER JOIN Schedule
             ON FacultyTimeLoad.scheduleid = Schedule.SectionID 
             WHERE Schedule.SectionCode LIKE '%-SIT' AND 
             Schedule.SchoolYear = '". $this->schoolyear ."' AND 
             Schedule.Semester = '". $this->semester ."'
             ORDER BY Schedule.Course ASC");
            
            $query3 = sqlsrv_query($conn,"SELECT * FROM Courses");

            $data['subjects'] = array();
            $data['courses'] = array();
            $array = array();
            $array2 = array();

            while($row2 = sqlsrv_fetch_array($query3))
            {
                array_push($data['courses'], $row2);
            }

            while($row = sqlsrv_fetch_array($query2))
            {
                if(!in_array($row['Course'],$array))
                {    
                    array_push($data['subjects'], $row);
                    array_push($array, $row['Course']);
                }
            }

            $data['hrs'] = $this->sit_db->req_hrs();
            $data['title'] = 'Director | Required Hours';
            $this->load->view('hours',$data);
        }
        else
        {
            $co = explode(";", $this->input->post('course'));
            $data = array(
              'college' => $co[0],
              'stud_course' => $co[1],
              'rqd_hrs' => $this->input->post('noOfHrs'),);
            
            $this->sit_db->saveHrs($data);
            $this->session->set_userdata('message', 'Added '.$this->input->post('noOfHrs').' hours for ' . $co[1]);
            redirect('director/hours');
        }
    }

    public function reports()
    {
        $username =  $this->session->userdata('sit');
        $subject =  $this->session->userdata('subject');
        $director =  $this->session->userdata('director');

        $conn = $this->sit_db->mssql_connect();
        $query = sqlsrv_query($conn,
        "SELECT * FROM Users INNER JOIN teachers 
         ON Users.UserID= Teachers.TeacherID
         WHERE Users.UserID = '$username' AND Users.Administrator = 'N'");
   
        $data = sqlsrv_fetch_array($query);
        $data['title'] = 'Director | Report Lists';
        $this->load->view('dir-reports',$data);
    }

    public function update()
    {
        $id = $this->input->post('id');
        $values = $this->input->post('value');
        $d = explode("|",$values);
        $data = array(
              'companyName' => $d[3],
              'companyAddress' => $d[2],
              'companyCity' => $d[1],
              'line_business' => $d[0],);
  
        $this->sit_db->updateCompany($id,$data);
    }

    public function saveHrs()
    {
        $data = array('rqd_hrs' => $this->input->post('val'),);
        $this->sit_db->updateHrs($this->input->post('ids'),$data);
    }

    public function updatefile()
    {
        $config['upload_path']          = './uploads';
        $config['allowed_types']        = 'pdf|doc|docx';
        $config['max_size']             = 0;

        $this->upload->initialize($config);
        if($this->upload->do_upload('updateFile'))
        {
            $data = array('companyCredentials' => $this->upload->data('file_name'),
            'orig_file_name' => $this->upload->data('client_name'),);

            $this->sit_db->updateFile($data,$this->input->post('compID'));
            $this->session->set_userdata('message', 'Credential has been updated');

            redirect('director/accredited');
        }
    }


}