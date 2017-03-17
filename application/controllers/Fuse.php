<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fuse extends CI_Controller
{
   public function index(){
     $data['title'] = 'Welcome';
    $this->load->view('header.php', $data);
   }


   public function signin(){

    $data['title'] = 'Welcome';
        $this->form_validation->set_rules('inputUsername', 'Username', array('required', 'min_length[8]'));
        $this->form_validation->set_rules('inputEmail', 'Email', array('required'));
        $this->form_validation->set_rules('inputPassword', 'Password', array('required', 'min_length[8]'));

        if($this->form_validation->run() == FALSE)
        {
            $this->load->view('header.php',$data);
        }
        else
        {

            $username = $this->input->post('inputUsername');
            $email = $this->input->post('inputEmail');
            $password = $this->input->post('inputPassword');

            $res = $this->search_fuse->checkAccount($username, $email);
                if($res == TRUE){

                    $data = array(
                      'uname' => $username,
                      'uemail' => $email,
                      'upassword' => md5($password)
                    );

                    $this->search_fuse->saveAccount($data);
                    $this->session->set_userdata('user', $username);
                    $this->session->set_userdata('id', $this->db->insert_id());
                    redirect('fuse/home');
                }else{
                    $this->session->set_userdata('error', 'Username or E-mail Already existed!');
                    redirect('fuse/');
                }

                    
        }
}

public function home(){
    if($this->session->userdata('user') == NULL){
        redirect('error');
    }else{
        $id = $this->session->userdata('id');
        $data['title'] = 'TASK AREA';


        $data['tasks'] = $this->search_fuse->getTasks($id);
        $this->load->view('task.php',$data);
    }
}

public function addtask(){
    $id = $this->session->userdata('id');
    $task = $this->input->post('addTask');

    $data = array(
        'task' => $task,
        'uid'  => $id,
        'taskTime' => time(),
    );
    $this->search_fuse->AddTask($data);
    redirect('fuse/home/');
}

public function logout(){
     $this->session->unset_userdata('id');
     $this->session->unset_userdata('user');
     redirect('fuse/');
}

public function removeTask($id){
    $this->search_fuse->delete($id);
     redirect('fuse/home/');
}

   public function login(){

    $data['title'] = 'Welcome';
        $this->form_validation->set_rules('inputUsername2', 'Username', array('required'));
        $this->form_validation->set_rules('inputPassword2', 'Password', array('required'));

        if($this->form_validation->run() == FALSE)
        {
            $this->load->view('header.php',$data);
        }
        else
        {

            $username = $this->input->post('inputUsername2');
            $password = $this->input->post('inputPassword2');

            $res = $this->search_fuse->checkAccount2($username, md5($password));
                if($res == TRUE){
                    $this->session->set_userdata('user', $username);
                    $this->session->set_userdata('id', $this->db->insert_id());
                    redirect('fuse/home');
                }else{
                    $this->session->set_userdata('error', ' Incorrect Username and Password combination!');
                    redirect('fuse/');
                }

                    
        }
}





    public function check(){
        header('Content-type: application/json');
        if($this->input->get('inputUsername') != NULL){
        $username = $this->input->get('inputUsername');
        $res = $this->search_fuse->checkjquery($username, 'uname');
        if($res == TRUE){
            $d =  'true';
            echo json_encode($d);
        }else{
            $d = 'Username is already exists!';
            echo json_encode($d);
        }
        }

        else if($this->input->get('inputEmail') != NULL){
        $email = $this->input->get('inputEmail');
        $res = $this->search_fuse->checkjquery($email, 'uemail');
        if($res == TRUE){
            $d =  'true';
            echo json_encode($d);
        }else{
            $d = 'Email Address is already exists!';
            echo json_encode($d);
        }
        }
    }
}

?>