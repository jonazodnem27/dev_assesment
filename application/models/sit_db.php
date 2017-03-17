    <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sit_Db extends CI_Model
{
	public function mysql_connect()
	{
		$mysqli = new mysqli('localhost','root','','ers');
        if ($mysqli->connect_error)
        {
            die('Error : ('. $mysqli->connect_error .') '. $mysqli->connect_error);
        } 
        return $mysqli;
	}

	public function mssql_connect()
	{
	    $mssql = sqlsrv_connect('CHRISCHANALBO', array('Database'=>'ers')); 
	    return $mssql;     
	}

	public function verify_account($id)
	{
		$this->db->select('*')->from('users')->where('user_ID ="' . $id . '"');
		$query = $this->db->get();
		if($query->num_rows() == 0)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	public function send_message($data){
		$this->db->insert('messages', $data);
	}



	public function findStudentViaManyOptions($subject,$year,$semester,$username){
		$this->db->select('*')->from('users')->where('Section_Code ="' . $subject . '" AND Semester = "'.$semester.'" AND SchoolYear = "'.$year.'"  AND Professor = "'.$username.'" ORDER BY user_name ASC');
		$query = $this->db->get();
		return $query->result();
	}



	public function getMessage($id,$send = NULL){
		if($send == NULL){
		$this->db->select('*')->from('messages')->where('msender ="' . $id . '" ORDER BY mtime DESC');
		}else{
		$this->db->select('*')->from('messages')->where('mreceiver ="' . $id . '" AND msender = "'.$send.'" ORDER BY mtime ASC');
		}
		$query = $this->db->get();
		return $query->result();
	}

	public function enrolled($year,$sem,$course,$faculty){
		if($sem == NULL && $course == NULL && $faculty == NULL){
			$this->db->select('*')->from('users')->where('SchoolYear ="' . $year . '"')->order_by("user_name", "asc");
		}else{
			$semester = str_replace('_', ' ', $sem);
		$this->db->select('*')->from('users')->where('Professor ="'. $faculty .'" AND SchoolYear ="' . $year . '" AND Semester ="'. $semester .'" AND user_college ="'. $course .'"')->order_by("user_name", "asc");
		}
		$query = $this->db->get();
		return $query->result();
	}

	public function uploadNarrative($data){
		$this->db->insert('narrative', $data);
	}

	public function companyDeployed($year, $sem)
	{
		if($sem == NULL){
			$this->db->select('*')->from('users')->where('SchoolYear ="' . $year . '"')->order_by("user_name", "asc");
		}else{
			$semester = str_replace('_', ' ', $sem);
		$this->db->select('*')->from('users')->where('SchoolYear ="' . $year . '" AND Semester ="'. $semester .'"')->order_by("user_name", "asc");
		}
		$query = $this->db->get();
		return $query->result();
	}

	public function checknarrative($user){

		$this->db->select('*')->from('narrative')->where('userid ="' . $user . '"');
		$query = $this->db->get();
		if($query->num_rows() == 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	public function years(){
		$this->db->select('SchoolYear')->from('users')->order_by("SchoolYear", "asc");
		$query = $this->db->get();
		return $query->result();
	}

	public function searchBySem($sem,$year){
		$this->db->select('*')->from('users')->where('Semester = "'.$sem.'" AND SchoolYear = "'.$year.'"')->order_by("user_name", "asc");
		$query = $this->db->get();
		return $query->result();
	}

	public function checkHours($id){
		$this->db->select('*')->from('activities')->where('user_id ="' . $id . '" AND status = "active"');
		$query = $this->db->get();
		if($query->num_rows() != 0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
	}

	public function countHours($id){
		$this->db->select('SUM(totalHours) AS totalHours, user_id')->from('activities')->where('user_id ="' . $id . '" AND status = "active"');
		$query = $this->db->get();
		return $query->result();
	}

	public function reqHRS($course){
		$this->db->select('rqd_hrs')->from('required_hrs')->where('stud_course ="' . $course . '"');
		$query = $this->db->get();
		return $query->result();
	}

	public function getAllMessage($id){
		$this->db->select('*')->from('messages')->where('mreceiver = "' . $id . '"')->order_by('mtime','ASC');
		$query = $this->db->get();
		return $query->result();
	}

	public function getNarrative($id){
		$this->db->select('*')->from('narrative')->where('userid ="' . $id . '"');
		$query = $this->db->get();
			return $query->result();
	}


	public function viewNarratives(){
		$this->db->select('*')->from('narrative');
		$query = $this->db->get();
			return $query->result();
	}

	public function getAllMessageDir($id){
		$this->db->select('*')->from('messages')->where('msender = "' . $id . '"')->order_by('mtime','ASC');
		$query = $this->db->get();
		return $query->result();
	}

	public function checkTIme(){
		$this->db->select('*')->from('activities')->where('status = "active"')->order_by("user_id", "asc");
		$query = $this->db->get();
		if($query->num_rows() != 0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
	}

		public function checkAdmin($username){

		$this->db->select('*')->from('administrator')->where('admin_username ="' . $username . '" AND admin_status = "Y"');
		$query = $this->db->get();
			if($query->num_rows() == 0){
				return FALSE;
			}else{
				return TRUE;
			}
	}

	public function checkAccountAdmin($user,$pass){
		$this->db->select('*')->from('administrator')->where('admin_username ="' . $user . '" AND admin_password = "'.$pass.'" AND admin_status = "Y"');
		$query = $this->db->get();
			if($query->num_rows() == 0){
				return FALSE;
			}else{
				return TRUE;
			}
	}

	public function getMustHours($course){
		$this->db->select('*')->from('required_hrs')->where('stud_course ="' . $course . '"');
		$query = $this->db->get();
		return $query->result();
	}

	public function checkYear($id, $year, $sem){
		$this->db->select('*')->from('users')->where('user_ID ="' . $id . '" AND Semester="'.$sem.'" AND SchoolYear ="'.$year.'"');
		$query = $this->db->get();
		if($query->num_rows() == 0)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	public function getDirector(){
		$this->db->select('*')->from('director')->where('dir_id ="1" AND dir_status="Y"');
		$query = $this->db->get();
		if($query->num_rows() == 0)
		{
			return FALSE;
		}
		else
		{
			return $query->result();
		}
	}

	public function updateDirector($id){
		$this->db->select('*')->from('director')->where('dir_id ="1" AND dir_status="Y"');
		$query = $this->db->get();
		if($query->num_rows() == 0)
		{
			$data = array(
				'dir_id' => 1,
				'dir_accid' => $id,
				'dir_status' => 'Y'
			);
			$this->db->insert('director', $data);
		}
		else
		{
			$data = array('dir_accid' => $id);
			$this->db->set($data);
			$this->db->where('dir_id',1);
	    	$this->db->update('director', $data);
		}
	}

	public function req_hrs()
	{
		$this->db->select('*')->from('required_hrs')->order_by("stud_course", "asc");;
		$query = $this->db->get();
		return $query->result();
	}

	public function fetch_company()
	{
		$this->db->select('*')->from('companies')->order_by('companyName','ASC');
		$query = $this->db->get();
		return $query->result();
	}

	public function findID($id)
	{
		$this->db->select('*')->from('users')->where('user_ID = "' . $id . '"');
		$query = $this->db->get();
		if($query->num_rows() != 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function updateDateStarted($id,$date)
	{
		$data = array(
				'date_started' => $date);
		$this->db->set($data);
		$this->db->where('user_ID',$id);
    	$this->db->update('users', $data);
	}

	public function updateCompany($id,$data)
	{
		$this->db->set($data);
		$this->db->where('id',$id);
    	$this->db->update('companies', $data);
	}

	public function updateSuper($id, $data){
		$this->db->set($data);
		$this->db->where('sup_id',$id);
    	$this->db->update('supervisor', $data);
	}

	public function searchCompany($id)
	{
		$this->db->select('*')->from('companies')->where('id = "' . $id . '"');
		$query = $this->db->get();
		return $query->result();
	}

	public function searchStud($id)
	{
		$this->db->select('*')->from('users')->where('user_ID = "' . $id . '"');
		$query = $this->db->get();
		if($query->num_rows() != 0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
	}

	public function disapproveReport($id,$data){

		$this->db->set($data);
		$this->db->where('act_id',$id);
    	$this->db->update('activities', $data);

	}

	public function fetchMessage($user,$date){
		$this->db->select('*')->from('activities')->where('week = "' . $date . '" AND user_id = "' . $user . '"');
		$query = $this->db->get();
		return $query->result();
	}

	public function fetchFromMessages($id, $level){
		$this->db->select('*')->from('messages')->where('mreceiver = "' . $id . '" AND mlevel = "' . $level . '" AND mstatus = "unread"');
		$query = $this->db->get();
		return $query->result();
	}

	public function getAllMessages($id){
		$this->db->select('*')->from('messages')->where('mreceiver = "' . $id . '"');
		$query = $this->db->get();
		return $query->result();

	}

	public function getRecentMessage($id,$id2){
		$this->db->select('mcontent')->from('messages')->where('msender = "' . $id . '" AND mreceiver = "'.$id2.'"')->order_by('mtime', 'DESC')->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	public function readMessage($data, $id){
		$this->db->set($data);
		$this->db->where('mid',$id);
    	$this->db->update('messages', $data);
	}


	public function searchActIdViaWeekWithComment($user,$date){
		$this->db->select('*')->from('activities')->where('week = "' . $date . '" AND user_id = "' . $user . '" AND comments = ""');
		$query = $this->db->get();
		
		if($query->num_rows() == 0)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	
	public function searchActivitywithDecline($id)
	{
		$this->db->select('*')->from('activities')->where('user_id = "'.$id.'" AND act_json != "" AND status = "pending" AND comments != ""')->order_by("act_id", "asc");
		$query = $this->db->get();
		if($query->num_rows() == 0)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	public function search_all($id,$code=null)
	{
		$this->db->select('*')->from('users')->where('Professor = "' . $id . '" AND Section_Code = "'.$code.'"')->order_by("user_name", "asc");
		$query = $this->db->get();
		return $query->result();
	}

	public function uploadData($data)
	{
		$this->db->insert('companies', $data);
	}

	public function searchByFac($id)
	{
		$this->db->select('*')->from('users')->where('Professor = "' . $id . '"')->order_by("user_name", "asc");
		$query = $this->db->get();
		return $query->result();
	}

	public function fieldByCity($city)
	{
		if($city == 'All')
		{
			$this->db->select('*')->from('companies')->order_by("id", "asc");
		}
		else
		{
			$this->db->select('*')->from('companies')->where('companyCity = "' . $city . '"')->order_by("id", "asc");
		}
		$query = $this->db->get();
		return $query->result();
	}

	public function searchAllStud()
	{
		$this->db->select('*')->from('users');
		$query = $this->db->get();
		return $query->result();
	}

	public function searchSupervisor($id)
	{
		$this->db->select('*')->from('supervisor')->where('comp_id="' . $id . '"');
		$query = $this->db->get();
		return $query->result();
	}

	public function searchSupervisorbyID($id)
	{
		$this->db->select('*')->from('supervisor')->where('sup_id="' . $id . '"');
		$query = $this->db->get();
		return $query->result();
	}

	public function searchSupervisorbyFac($id)
	{
		$this->db->select('*')->from('supervisor')->where('fac_id="' . $id . '"');
		$query = $this->db->get();
		return $query->result();
	}

	public function getSupervisors(){
		$this->db->select('*')->from('supervisor');
		$query = $this->db->get();
		return $query->result();
	}

	public function insertUser($id, $value)
	{
        $mssql = $this->mssql_connect();
        $query2 = sqlsrv_query($mssql,
        "SELECT * FROM Enlist". $this->year ."
         WHERE StudentNo = '$id' 
         AND SubjectCode = 'SIT'");
        
        $result = sqlsrv_fetch_array($query2);
        $facID =  $this->session->userdata('sit');
        $var = explode(";",$value);
        $d = now('Asia/Aden');
        $resu = $this->searchSupervisor($var[0]);
        $course = $result['Course'];

		$query3 = sqlsrv_query($mssql,
        "SELECT * FROM Courses
         WHERE CourseCode = '$course'");
		$result2 = sqlsrv_fetch_array($query3);

        $data = array(
          	'user_ID' => $id,
          	'user_name' => $result['StudentName'],
          	'Section_Code' => $result['SectionCode'],
          	'user_course' => $result['Course'],
          	'user_college' => $result2['SubjectDeptCode'],
          	'companyID' => $var[0],
          	'Supervisor' => $var[1],
          	'sDay' => $result['sDay'],
          	'sTime' => $result['sTime'],
          	'StudentYear' => $result['StudentYear'],
          	'Semester' => $result['Semester'],
          	'SchoolYear' => $result['SchoolYear'],
          	'Professor' => $facID,
          	'logged_in' => $d,);

        $this->db->select('*')->from('users')->where('user_ID = "' . $id . '"')->limit(1);
        $query = $this->db->get();
      	if($query->num_rows() == 0)
      	{
        	$this->db->insert('users', $data);
		}
	}

	public function isThereAnyPending($username){
		$this->db->select('*')->from('activities')->where('user_id = "' . $username . '" AND status = "pending"');
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return 'TRUE';
		}
	}

	public function isApproved($id){
		$this->db->select('*')->from('activities')->where('act_id = "' . $id . '" AND Status = "active"');
		$query = $this->db->get();
		if($query->num_rows() != 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function getComment($id){
		$this->db->select('*')->from('activities')->where('act_id = "' . $id . '" AND comments != ""');
		$query = $this->db->get();
		if($query->num_rows() != 0)
		{
			return $query->result();;
		}
		else
		{
			return FALSE;
		}
	}

	public function searchActivity($id)
	{
		$this->db->select('*')->from('activities')->where('user_id = "' . $id . '" AND act_json = "" ');
		$query = $this->db->get();
		if($query->num_rows() != 0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
	}

		public function searchAct($id)
	{
		$this->db->select('*')->from('activities')->where('act_id = "' . $id . '" AND comments != ""');
		$query = $this->db->get();
			return $query->result();
	}

	public function fetchreports($id)
	{
		$this->db->select('*')->from('activities')->where('user_id = "' . $id . '" AND status = "active"');
		$query = $this->db->get();
		return $query->result();
	}

	public function searchWeek($id,$week)
	{
		$this->db->select('*')->from('activities')->where('user_id = "' . $id . '" AND week = "'. $week .'"');
		$query = $this->db->get();
		if($query->num_rows() != 0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
	}

	public function searchApproval($id,$week)
	{
		$this->db->select('*')->from('activities')->where('user_id = "' . $id . '" AND week = "'. $week .'" AND status = "pending"');
		$query = $this->db->get();
		if($query->num_rows() != 0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
	}

	public function addStatus($data,$id)
	{
		$this->db->set($data);
		$this->db->where('act_id',$id);
    	$this->db->update('activities', $data);
	}

	public function submitReport($id,$json,$hrs)
	{
		$data = array(
			'act_json' => $json,
			'status' => 'pending',
			'totalHours' => $hrs,);
		$this->db->set($data);
		$this->db->where('act_id',$id);
    	$this->db->update('activities', $data);
	}

	public function addActivity($data)
	{
		$this->db->insert('activities', $data);
	}

	public function saveHrs($data)
	{
		$this->db->insert('required_hrs', $data);
	}

	public function updateHrs($id, $data)
	{
		$this->db->set($data);
		$this->db->where('eid',$id);
    	$this->db->update('required_hrs', $data);
	}

	public function display_field()
	{
		$this->db->select('*')->from('companies')->order_by('companyName','ASC');
        $query = $this->db->get();
        return $query->result();
	}

	public function displayfieldjoined()
	{
		$this->db->select('*')->from('companies')->join('visited', 'companies.id = visited.comp_id');
        $query = $this->db->get();
        return $query->result();
	}

	public function visitedCompany($year,$sem)
	{
		if($sem == null){
			$this->db->select('*')->from('visited')->where('year ="'.$year.'"');
		}else{
			$this->db->select('*')->from('visited')->where('year ="'.$year.'" AND semester = "'.$sem.'"');
		}
		$query = $this->db->get();
		return $query->result();
	}

		public function listCompany()
	{
			$this->db->select('*')->from('visited');
		$query = $this->db->get();
		return $query->result();
	}

	public function insert_field($data)
	{
        $this->db->insert('companies', $data);
	}

	public function visited($id,$fac,$year,$semester)
	{
		date_default_timezone_set('Asia/Manila');
		$data = array(
			'comp_id' => $id,
			'visited_date' => time(),
			'semester' => $semester,
			'year' => $year,
			'fac_id' => $fac,);
		$this->db->insert('visited', $data);

	// $this->db->set($data);
	// $this->db->where('id',$id);
    // $this->db->update('visited', $data);
	}

	public function isDirector($id)
	{
		$this->db->select('*')->from('director')->where('dir_accid = "' . $id . '" AND dir_status ="Y"');
		$query = $this->db->get();
		if($query->num_rows() != 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}



	public function addSupervisor($data)
	{
		$this->db->insert('supervisor', $data);
	}

	// public function insertTask($task,$time1,$time2,$no,$id,$time){

	// 	$data = array(
	// 		'user_id' => $id,
	// 		'act_date' => $time,
	// 		//'week' => $no,
	// 		'act_day' => mdate('%D',$time),
	// 		'act_work' => $task,
	// 		'act_hours' => $no,
	// 		'dtr_time_in' => $time1,
	// 		'dtr_time_out' => $time2,
	// 	);

	// 	$this->db->insert('activities', $data);
	// }

	public function updateFile($data, $id)
	{
		$this->db->set($data);
		$this->db->where('id',$id);
    	$this->db->update('companies', $data);
	}

	public function updateStudDeployed($id, $data){
		$this->db->set($data);
		$this->db->where('user_ID',$id);
    	$this->db->update('users', $data);
	}
}