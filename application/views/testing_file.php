<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include('common/header.php');
$todaydate =date('Y-m-d'); 
?>
<div class="pb-5">
            
    <?php
    
    
    $this->db->select("*");
	$this->db->from('project_services');  
	$this->db->join('project_list', 'project_services.project_id  = project_list.project_id');
	$this->db->where('assignees like','%'.$get_login_user->employee_no.'%' );
	$this->db->group_by('project_services.project_id' );
	$quers = $this->db->get();
	$totals = $quers->num_rows() ;
	foreach($quers->result() as $rows)
		{
	
	echo   $rows->project_name.'<br>';
	$this->db->select("*");
	$this->db->from('project_services');
	$this->db->where('project_id',$rows->project_id );
	$this->db->where('assignees like','%'.$get_login_user->employee_no.'%' );
	//$this->db->where('service_name',$rows->service_name );
	//$this->db->group_by('project_services.project_id' );
	$quers = $this->db->get();
	$totals = $quers->num_rows() ;
	foreach($quers->result() as $rows)
		{
		   
		 echo   $rows->service_name.'<br>';
		  
		}
		echo '<br>';
		  //  }
		}
    ?>
    
    
</div>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include('common/footer.php');

?>