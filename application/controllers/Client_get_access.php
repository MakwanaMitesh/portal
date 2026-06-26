<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client_get_access extends CI_Controller {
    public function __construct(){
		parent::__construct();
        $this->load->model("Common_model");
	}

	public function login()
	{   
		$username = $this->input->post('username');
		$password = $this->input->post('usercode');
			if(!empty($username) && !empty($password)){
			    $con['table_name'] = 'clients';
				$con['returnType'] = 'single';
				$con['conditions'] = array(
					'username' => $username,
					'password' => sha1($password),
				);
				$client = $this->Common_model->getRows($con);
				if($client) {
					$client_id = $client['id'];
                    $client_name = $client['username'];
                    
                    $position = strpos($client_name, "_");
        
                    if($position!==false) {
                        $client_name = ucwords(str_replace("_", " ", $client_name));
                    }
                    
					$data = array(
						'client_id'=> $client_id,
                        'client_name' => $client_name,
						'logged_in'=>TRUE
					);
					$this->session->set_userdata($data);
					echo '1';
				}else{   
					 echo '0';
				}
			} else {
				echo '2';
			}
	}
	
	public function logout()
	{
		session_destroy();
		redirect(base_url(), 'refresh');	
	}
}
