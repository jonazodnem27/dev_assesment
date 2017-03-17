    <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search_Fuse extends CI_Model
{
	public function checkAccount($user, $email){

		$this->db->select('*')->from('users')->where('uname ="' . $user . '" AND uemail ="'.$email.'"');
		$query = $this->db->get();
		if($query->num_rows() == 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}

	}

	public function checkAccount2($user, $pass){
		$this->db->select('*')->from('users')->where('uname ="' . $user . '" AND upassword ="'.$pass.'"');
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

	public function AddTask($data){
		$this->db->insert('task', $data);
	}

	public function saveAccount($data){
		$this->db->insert('users', $data);
	}

	public function checkjquery($id, $name){
		$this->db->select('*')->from('users')->where($name . '="' . $id . '"');
		$query = $this->db->get();
		if($query->num_rows() == 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function getTasks($id){
		$this->db->select('*')->from('task')->where('uid ="' . $id . '"')->order_by('taskID', 'DESC');
		$query = $this->db->get();
		return $query->result();
	}

	public function delete($id){
		$this->db->where('taskID', $id);
		$this->db->delete('task');
	}
}