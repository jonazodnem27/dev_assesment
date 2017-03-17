    <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Administrator extends CI_Controller {

        public function login($username){

        	if($username == NULL){
        		redirect('errors');
        	}

        	$result = $this->sit_db->checkAdmin($username);
        	$data['username'] = $username;
        if($result == TRUE){
        $this->form_validation->set_rules('username', 'Username', array('required'));
        $this->form_validation->set_rules('password', 'Password', array('required'));

        if($this->form_validation->run() == TRUE){
        	$username = $this->input->post('username');
        	$password = $this->input->post('password');
        	$result = $this->sit_db->checkAccountAdmin($username,md5($password));
        		if($result == TRUE){

                    $this->session->set_userdata('administrator', $username);
        			redirect('administrator/dashboard/');
        		}else{
        			$this->load->view('admin-login',$data);
        		}
        }else{
        	 $this->load->view('admin-login',$data);
        }

        }

}

		public function dashboard(){
        	$username =  $this->session->userdata('administrator');
        	$result = $this->sit_db->checkAdmin($username);
        	if($result == TRUE){

        		$this->form_validation->set_rules('director', 'Director', array('required'));
        		$this->form_validation->set_rules('year', 'Year', array('required'));
      			$this->form_validation->set_rules('semester', 'Semester', array('required'));

      			if($this->form_validation->run() == TRUE){
      				$director =	 $this->input->post('director');
      				$year =	 $this->input->post('year');
      				$semester =	 $this->input->post('semester');
      				$schoolYear = strval($year) .'-'. strval($year+1);
           			write_file('assets/database/year.txt',$year, "w");
           			write_file('assets/database/semester.txt',$semester, "w");
           			write_file('assets/database/schoolyear.txt',$schoolYear, "w");
           			$this->sit_db->updateDirector($director);
           			 redirect('administrator/dashboard/');

        		}else{
        			$data['Semester'] = read_file('assets/database/semester.txt');
        			$data['Year'] = read_file('assets/database/year.txt');
        			$res = $this->sit_db->getDirector();
        			if($res == TRUE){
        				$data['Director'] = $res[0]->dir_accid;
        			}else{
        				$data['Director'] = '';
        			}

        			$this->load->view('admin-dashboard',$data);
        		}
        	}else{
        		redirect('error');
        	}
		}
}