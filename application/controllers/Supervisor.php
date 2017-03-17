   <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supervisor extends CI_Controller {

        public function index($user, $email, $date){
      
      $data['title'] = 'SIT | Supervisor';
          $data['username'] = $user;
          $data['email'] = $email;
          $data['date'] = $date;
          $data['disable'] = 'disabled';
      
        if($date == NULL){
            redirect('errors');
        }

        $result = $this->sit_db->searchApproval($user,$date);
          if($result == FALSE){
            redirect('error');
          }


      $this->form_validation->set_rules('email', 'Email Address', array('required'));
      
       if($this->form_validation->run() == FALSE){
                $this->load->view('supervisor', $data);
          }else{
               $postEmail = md5($this->input->post('email'));
                if($email == $postEmail){
               $this->session->set_userdata('supervisor', $email);
          redirect('supervisor/form/'. $user . '/' . $email . '/' . $date);
          }else{
            $data['message'] = 'Incorrect Email Address';
            $this->load->view('supervisor', $data);
          }
            } 
        }
          
        public function form($user, $email,$date){

          if($this->session->userdata('supervisor') != $email){
            redirect('error');
          }else{

            $result = $this->sit_db->searchApproval($user,$date);
              if($result == FALSE){
                redirect('error');
              }

            $data['title'] = 'SIT | Supervisor';
              $data['username'] = $user;
              $data['email'] = $email;
              $data['date'] = $date;

                $res = $this->sit_db->searchWeek($user,$date);
                if($res == FALSE){
                  redirect('index.php/error');
                }else{
                  $data['user'] = $this->sit_db->searchStud($user);
                  $data['week'] = $res;
                  $data2 = array(
                    'user' => $user,
                    'week' => $date,
                  );
                  $this->load->view('week',$data);
                  $this->load->view('form',$data2);
                }
          }
        }


        public function approve($user,$week){
            $res = $this->sit_db->searchApproval($user,$week);
            if($res == FALSE){
                  redirect('error');
                }else{
                $id = $res[0]->act_id;
                $data = array(
                  'status' => 'active',
                );
                  $this->sit_db->addStatus($data,$id);
                  $this->load->view('thank');
                }
        }
}
