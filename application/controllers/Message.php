   <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message extends CI_Controller
{

	public $year;
    public $schoolyear;
    public $semester;

    public function __construct()
    {
        parent::__construct();
        $this->year = file_get_contents(base_url()."assets/database/year.txt");
        $this->schoolyear = file_get_contents(base_url()."assets/database/schoolyear.txt");
        $this->semester = file_get_contents(base_url()."assets/database/semester.txt");
    }

	public function student(){
		$idNumber = $this->input->post('id');
		$a = 0;
		$result = $this->sit_db->fetchFromMessages($idNumber, '2');
		$conn = $this->sit_db->mssql_connect();
		foreach ($result as $key => $value) {
			$username = $result[$key]->msender;
			$query = sqlsrv_query($conn,
            "SELECT Users.UserName FROM Users INNER JOIN Teachers
             ON Users.UserID= Teachers.TeacherID 
             WHERE Users.UserID = '$username'");
			$name = sqlsrv_fetch_array($query);

			echo '<div class="sit-alert carding'.$result[$key]->malert.'">
				    <a class="sit-close" onclick="closeNotification(this.id, \''.$result[$key]->mid.'\')" id="notif'.$a.'">&times;</a>
				    <h3>Message from '.$name['UserName'].'</h3>
				    <p>'.$result[$key]->mcontent.'</p>
				</div>';
		$a++; }
	}


	public function read(){
		$idNumber = $this->input->post('read');
		$data = array(
			'mstatus'  => 'read', 
		);
		$this->sit_db->readMessage($data, $idNumber);
	}

	public function readToo($idNumber){
		$data = array(
			'mstatus'  => 'read', 
		);
		$this->sit_db->readMessage($data, $idNumber);
	}

	public function display(){

		$username =  $this->session->userdata('sit');
		$conn = $this->sit_db->mssql_connect();
		$query2 = sqlsrv_query($conn,
        "SELECT * FROM FacultyTimeLoad INNER JOIN Schedule
         ON FacultyTimeLoad.scheduleid = Schedule.SectionID 
         WHERE FacultyTimeLoad.fid = '$username' AND 
         Schedule.SectionCode LIKE '%-SIT' AND 
         Schedule.SchoolYear = '". $this->schoolyear ."' AND 
         Schedule.Semester = '". $this->semester ."'
         ORDER BY Schedule.SectionID ASC");
        
        $subjects = array();
        while($row = sqlsrv_fetch_array($query2))
        {
            if(!in_array($row['SectionCode'],$subjects))
            {    
                array_push($subjects, $row['SectionCode']);
            }
        }

		$uid = $this->input->post('uid');
		if($this->input->post('all') != NULL){
			$result = $this->sit_db->getMessage($uid);
   	 		$array = array();

			foreach ($result as $key => $value) {
			//$this->readToo($result[$key]->mid);

			if(!in_array($result[$key]->mreceiver, $array)){
			$res = $this->sit_db->searchStud($result[$key]->mreceiver);

			foreach ($subjects as $keyss => $valuess) {
				if($valuess == $res[0]->Section_Code){
			echo '<div class="nameholder" onclick="showMessages(\''.$res[0]->user_ID.'\',\''.$res[0]->user_name.'\',\''.$res[0]->user_ID.'\')"  style="border: 0;height: initial;">
				<img src="https://d30y9cdsu7xlg0.cloudfront.net/png/363633-200.png">
				<h4 >'.$res[0]->user_name.'</h4>
				<p style="border-left: 4px solid #c41e3a;padding-left:5px;">'.$result[$key]->mcontent.'</p>
				</div>';
				array_push($array, $result[$key]->mreceiver);
			}}

			}
			}


		}else if($this->input->post('decline') != NULL){
			$array = array();
						$result = $this->sit_db->getMessage($uid);
						foreach ($result as $key => $value) {
						//$this->readToo($result[$key]->mid);

						if(!in_array($result[$key]->mreceiver, $array)){
						$res = $this->sit_db->searchStud($result[$key]->mreceiver);
						$resu = $this->sit_db->searchActivitywithDecline($result[$key]->mreceiver);
						
						if($resu == TRUE){

							foreach ($subjects as $keyss => $valuess) {
							if($valuess == $res[0]->Section_Code){

						echo '<div class="nameholder" onclick="showMessages(\''.$res[0]->user_ID.'\',\''.$res[0]->user_name.'\',\''.$res[0]->user_ID.'\')"  style="border: 0;height: initial;">
							<img src="https://d30y9cdsu7xlg0.cloudfront.net/png/363633-200.png">
							<h4 >'.$res[0]->user_name.'</h4>
							<p style="border-left: 4px solid #c41e3a;padding-left:5px;">'.$result[$key]->mcontent.'</p>
							</div>';
							array_push($array, $result[$key]->mreceiver);
						}}

						}
						}
						}

						if(count($array) == 0){
							echo '<center><br><br><br><br><img style="width: 90px;" src="https://cdn1.iconfinder.com/data/icons/user-ui-vol-2/16/cancel_chat_close_message_no_ui_notification-512.png"><h4 style="font-size: 30px;color: #5a5a5a;margin: 0;">NO RESULTS FOUND</h4></center>';
						}


		}else if($this->input->post('done') != NULL){
				$array = array();
				$result = $this->sit_db->getMessage($uid);
						foreach ($result as $key => $value) {
						//$this->readToo($result[$key]->mid);

						if(!in_array($result[$key]->mreceiver, $array)){
						$res = $this->sit_db->searchStud($result[$key]->mreceiver);
						$count = $this->sit_db->countHours($result[$key]->mreceiver);
						$range = $this->sit_db->reqHRS($res[0]->user_course);

						if($count[0]->totalHours >= $range[0]->rqd_hrs){
						echo '<div class="nameholder" onclick="showMessages(\''.$res[0]->user_ID.'\',\''.$res[0]->user_name.'\',\''.$res[0]->user_ID.'\')"  style="border: 0;height: initial;">
							<img src="https://d30y9cdsu7xlg0.cloudfront.net/png/363633-200.png">
							<h4 >'.$res[0]->user_name.'</h4>
							<p style="border-left: 4px solid #c41e3a;padding-left:5px;">'.$result[$key]->mcontent.'</p>
							</div>';
							array_push($array, $result[$key]->mreceiver);
						}
						}
						}

						if(count($array) == 0){
							echo '<center><br><br><br><br><img style="width: 90px;" src="https://cdn1.iconfinder.com/data/icons/user-ui-vol-2/16/cancel_chat_close_message_no_ui_notification-512.png"><h4 style="font-size: 30px;color: #5a5a5a;margin: 0;">NO RESULTS FOUND</h4></center>';
						}

		}else if($this->input->post('message') != NULL){
			$id = $this->input->post('message');
			if($id == 'director'){
			$result = $this->sit_db->getAllMessageDir($id);
			}else{
			$result = $this->sit_db->getAllMessage($id);
			}

			if($id == 'director'){
			foreach ($result as $key => $value) {
				if($result[$key]->mreply == 0){
					echo '<li><div class="message-receiver">'.$result[$key]->mcontent.'<span class="date">'.date('h:i A, d M',$result[$key]->mtime).'</span>
						</div></li>';
				}else{
					echo '<li style="display: inline-block;"><div class="message-sender">'.$result[$key]->mcontent.'<span class="date">'.date('h:i A, d M',$result[$key]->mtime).'</span>
						</div></li>';
				}
			} 

			if($result == NULL){
				echo '<center><br><br><br><br><img style="width: 90px;" src="https://cdn1.iconfinder.com/data/icons/user-ui-vol-2/16/cancel_chat_close_message_no_ui_notification-512.png"><h4 style="font-size: 30px;color: #5a5a5a;margin: 0;">NO RESULTS FOUND</h4></center>';
				}
			}else{
				foreach ($result as $key => $value) {
				if($result[$key]->mreply == 1){
					echo '<li><div class="message-receiver">'.$result[$key]->mcontent.'<span class="date">'.date('h:i A, d M',$result[$key]->mtime).'</span>
						</div></li>';
				}else{
					echo '<li style="display: inline-block;"><div class="message-sender">'.$result[$key]->mcontent.'<span class="date">'.date('h:i A, d M',$result[$key]->mtime).'</span>
						</div></li>';
				}
			} 
			if($result == NULL){
				echo '<center><br><br><br><br><img style="width: 90px;" src="https://cdn1.iconfinder.com/data/icons/user-ui-vol-2/16/cancel_chat_close_message_no_ui_notification-512.png"><h4 style="font-size: 30px;color: #5a5a5a;margin: 0;">NO RESULTS FOUND</h4></center>';
				}
				
			}
		}

	}

	public function solomessage(){
		
		$year = $this->schoolyear;
		$semester = $this->semester;
		$username =  $this->session->userdata('sit');
		$conn = $this->sit_db->mssql_connect();
		$message = $this->input->post('message');
		$sender = $this->input->post('sender');
		$receiver = $this->input->post('receiver');
		$reply = 0;
		$date = time();

		$data = array(
			'msender'		=> $sender,
			'mreceiver'		=> $receiver,
			'mtime'			=> $date,
			'mcontent'		=> $message,
			'mreply'		=> $reply,
			'mlevel'		=> 2,
			'semester'		=> $this->semester,
			'schoolyear'	=> $this->schoolyear,
			'mstatus'		=> 'unread',
		);

		$this->sit_db->send_message($data);


	}

	public function sendmessage(){

		$year = $this->schoolyear;
		$semester = $this->semester;
		$username =  $this->session->userdata('sit');
		$conn = $this->sit_db->mssql_connect();
		$message = $this->input->post('message');
		$sender = $this->input->post('sender');
		$receiver = $this->input->post('receiver');
		$reply = $this->input->post('reply');
		$date = time();


		$query2 = sqlsrv_query($conn,
        "SELECT * FROM FacultyTimeLoad INNER JOIN Schedule
         ON FacultyTimeLoad.scheduleid = Schedule.SectionID 
         WHERE FacultyTimeLoad.fid = '$username' AND 
         Schedule.SectionCode LIKE '%-SIT' AND 
         Schedule.SchoolYear = '". $this->schoolyear ."' AND 
         Schedule.Semester = '". $this->semester ."'
         ORDER BY Schedule.SectionID ASC");

		 $subjects = array();
        while($row = sqlsrv_fetch_array($query2))
        {
            if(!in_array($row['SectionCode'],$subjects))
            {    
                array_push($subjects, $row['SectionCode']);
            }
        }



		if($this->input->post('status') != NULL){
			$status = $this->input->post('status');
			if($status == 'all'){
				
				foreach ($subjects as $key => $value) {
					echo 'Section_Code ="' . $value . '" AND Semester = "'.$semester.'" AND SchoolYear = "'.$year.'"  AND Professor = "'.$username.'" ORDER BY user_name ASC';
					$res = $this->sit_db->findStudentViaManyOptions($value,$year,$semester,$username);
					foreach ($res as $kessady => $value) {
					$data = array(
						'msender'		=> $username,
						'mreceiver'		=> $res[$kessady]->user_ID,
						'mtime'			=> $date,
						'mcontent'		=> $message,
						'mreply'		=> 0,
						'mlevel'		=> 2,
						'semester'		=> $this->semester,
						'schoolyear'	=> $this->schoolyear,
						'mstatus'		=> 'unread',
					);

					$this->sit_db->send_message($data);
				}
			}
		
	
		


			}else if($status == 'decline'){
				echo 'descline';
			}else if($status == 'done'){
				echo 'done';
			}


		}else{

		$data = array(
			'msender'		=> $sender,
			'mreceiver'		=> $receiver,
			'mtime'			=> $date,
			'mcontent'		=> $message,
			'mreply'		=> $reply,
			'semester'		=> $this->semester,
			'schoolyear'	=> $this->schoolyear,
			'mstatus'		=> 'unread',
		);

		$this->sit_db->send_message($data);
		}
	}

	public function getmessage(){
		$sem = $this->semester;
		$uid = $this->input->post('uid'); 
		$fac = $this->input->post('fac');
		$usend = $this->input->post('usend');
		
		$username =  $this->session->userdata('sit');
		$conn = $this->sit_db->mssql_connect();
		$query2 = sqlsrv_query($conn,
        "SELECT * FROM FacultyTimeLoad INNER JOIN Schedule
         ON FacultyTimeLoad.scheduleid = Schedule.SectionID 
         WHERE FacultyTimeLoad.fid = '$username' AND 
         Schedule.SectionCode LIKE '%-SIT' AND 
         Schedule.SchoolYear = '". $this->schoolyear ."' AND 
         Schedule.Semester = '". $this->semester ."'
         ORDER BY Schedule.SectionID ASC");
        
        $subjects = array();
        while($row = sqlsrv_fetch_array($query2))
        {
            if(!in_array($row['SectionCode'],$subjects))
            {    
                array_push($subjects, $row['SectionCode']);
            }
        }


		if($fac == 'true'){
   				echo '<div style="background: #ededed;padding: 10px;"><center><h2 style="margin: 0;color: #707070;">Filter By</h3></center>
   				<span style="font-size: 18px;"><input type="radio" onchange="remote(this.value)" checked  value="all" name="Filter">All Students</span><br><span style="font-size: 18px;"><input type="radio" onchange="remote(this.value)" name="Filter" value="decline">Students with Declined Reports</span><br><span style="font-size: 18px;"><input type="radio"  onchange="remote(this.value)" value="done" name="Filter">Students done taking the SIT</span></div>';
   	 		

   	 		$result = $this->sit_db->getMessage($uid);
   	 		if(count($result) != 0){
   	 		$array = array();
   	 		echo '<div style="overflow-y:scroll;height: 279px;" id="studentFilter">';
   	 		foreach ($result as $key => $value) {
			//$this->readToo($result[$key]->mid);

			if(!in_array($result[$key]->mreceiver, $array)){
				
			$res = $this->sit_db->searchStud($result[$key]->mreceiver);

			foreach ($subjects as $keyss => $valuess) {
				if($valuess == $res[0]->Section_Code){
			echo '<div class="nameholder" onclick="showMessages(\''.$res[0]->user_ID.'\',\''.$res[0]->user_name.'\',\''.$res[0]->user_ID.'\')"  style="border: 0;height: initial;">
				<img src="https://d30y9cdsu7xlg0.cloudfront.net/png/363633-200.png">
				<h4 >'.$res[0]->user_name.'</h4>
				<p style="border-left: 4px solid #c41e3a;padding-left:5px;">'.$result[$key]->mcontent.'</p>
				</div>';
				array_push($array, $result[$key]->mreceiver);
			}}
			}


			} }
			echo '</div>';
		}

   	 	else if($fac == 'false'){
   	 		$result = $this->sit_db->getMessage($uid, $usend);
		if(count($result) == 0){
   		echo '<center><br><br><br><br><br><img style="width: 90px;" src="https://cdn1.iconfinder.com/data/icons/user-ui-vol-2/16/cancel_chat_close_message_no_ui_notification-512.png"><h4 style="font-size: 30px;color: #5a5a5a;margin: 0;">NO MESSAGES YET</h4></center>';
   		}else{

echo '<ul class="messaging" id="appendMessage">';
			foreach ($result as $key => $value) {
				if($result[$key]->mreply == 0){
					echo '<li><div class="message-receiver">'.$result[$key]->mcontent.'<span class="date">'.date('h:i A, d M',$result[$key]->mtime).'</span>
						</div></li>';
				}else{
					echo '<li style="display: inline-block;"><div class="message-sender">'.$result[$key]->mcontent.'<span class="date">'.date('h:i A, d M',$result[$key]->mtime).'</span>
						</div></li>';
				}
			} echo '</ul>';
		} }else{
			
		$subjects = array();
        $users = array();
		$username =  $this->session->userdata('sit');
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
            "SELECT UserID,UserName FROM Users INNER JOIN Teachers
            ON Users.UserID=Teachers.TeacherID ");

            while($row1 = sqlsrv_fetch_array($query3)){
                array_push($users, $row1);
            }

            while($row = sqlsrv_fetch_array($query2)){
                if(!in_array($row['fid'],$subjects)){    
                    array_push($subjects, $row['fid']);
                }
            }

            // ECHO '<PRE>';
            // var_dump($subjects);
            // var_dump($users);
            // ECHO '</PRE>';

            foreach ($subjects as $key => $value){ 
                
            foreach ($users as $key => $val){
                
            if($value == $val['UserID']){
            	$res = $this->sit_db->getRecentMessage('director', $val['UserID']);
            	if(count($res) != 0){
            		$mcontent = $res[0]->mcontent;
            	}else{
            		$mcontent = '&nbsp;';
            	}
                 echo '<div class="nameholder" onclick="showMessages(\''.$val['UserID'].'\',\''.$val['UserName'].'\',\''.$val['UserID'].'\')"  style="border: 0;height: initial;"><img src="https://d30y9cdsu7xlg0.cloudfront.net/png/363633-200.png">
				<h4 >'.$val['UserName'].'</h4>
				<p style="border-left: 4px solid #c41e3a;padding-left:5px;">'.$mcontent.'</p>
				</div>';

            } } }


		}
	}
}
?>