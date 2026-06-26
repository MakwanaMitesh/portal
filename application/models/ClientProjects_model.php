<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ClientProjects_model extends CI_Model{
    public $client_id;
    public $project_ids;
    public $client_projects;
    public $emp_ids;

    public function __construct() {      
      $this->client_id = $this->session->userdata('client_id');
    }

    public function get_client_project_ids() {
        $this->db->select('project_id');
        $this->db->from('client_projects_list');
        $this->db->where('client_id',$this->client_id);
        $query = $this->db->get();
        // echo $this->db->last_query();

        if ($query === false) {
            // Log the error or print the last query to debug
            echo "Error executing query: " . $this->db->last_query();
            die();
        }

        $pids = $query->result();

        if (!empty($pids)) {
            foreach($pids as $i => $pid) {
                $this->project_ids[$i] = $pid->project_id;
            }
            return $this->project_ids;
        } else {
            return [];
        }
    }

    function get_client_project_list() {
        $this->project_ids = $this->get_client_project_ids();

        if($this->project_ids != "") {
            $this->db->select('*');
            $this->db->from('project_list');
            $this->db->where_in('project_id', $this->project_ids);
            $query = $this->db->get();

            if($query->num_rows() > 0) {
                $this->client_projects = $query->result();
                if(!empty($this->client_projects)) {
                    return $this->client_projects;
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        }
        else {
            return FALSE;
        }
    }

    function get_client_project_employees() {
        $this->db->distinct();
        $this->db->select('assignee');
        $this->db->from('task_list');
        $this->db->where_in('project_id', $this->project_ids);
        
        $project_emp = $this->db->get()->result();

        if(!empty($project_emp)) {
            foreach($project_emp as $j => $emp_id) {
                $this->emp_ids[$j] = $emp_id->assignee;
            }
        }

        $this->db->distinct();
        $this->db->select('employee_no, name');
        $this->db->from('employees');
        $this->db->where_in('employee_no', $this->emp_ids);
        $this->db->where('status', 1);
        $this->db->where('team_type', 'Development');
        
        $emp_names = $this->db->get()->result();

        if(!empty($emp_names)) {
            return $emp_names;
        } else {
            return FALSE;
        }
    }
}