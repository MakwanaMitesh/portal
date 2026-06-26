<?php

    defined('BASEPATH') OR exit('No direct script access allowed');

    include(APPPATH.'views/common/header.php');

?>

<div class="mb-4">

<br>

<div class="mx-n4 px-4 mx-lg-n6 px-lg-3 bg-body-emphasis pt-6 border-top border-bottom">

<div class="row g-3">

                <div class="col-auto">

                   <h4> Projects List </h4><br>

                </div>

                <div class="col-auto scrollbar overflow-hidden-y flex-grow-1"></div>

                <div class="col-auto"></div>

              </div>

<div class="table-responsive ms-n1 ps-1 scrollbar">

    <div class="row">

        <div class="col-md-3">

             <div class="form-floating"><select class="form-select"  id="project_phase" required>

                    <?php

                        if(!empty($get_project_phases)){

                        foreach ($get_project_phases as $phases_list)

                        { 

                        ?>

                    <option value="<?php echo $phases_list->phase_name;?>" ><?php echo $phases_list->phase_name;?></option>

                    <?php }}?>

                  </select><label for="project_phase">Defult Project Status</label>

                  </div>

        </div>

        <div class="col-sm-3 col-md-3">

                <div class="form-floating"><select class="form-select" name="category" id="category" >

                    <option value="">--Select--</option>

                    <option value="Digital">Digital</option>

                    <option value="Development">Development </option>

                  </select><label for="service_status">Category</label></div>

          </div>

        <div class="col-md-3">

             <div class="form-floating"><select class="form-select"  id="project_category" required>

                     <option value="">--Select--</option>

                     <?php

                        if(!empty($get_proservice_list)){

                        foreach ($get_proservice_list as $proservice_list)

                        {

                            $this->db->select("*");

		                    $this->db->from('project_service_list');  

		                    $this->db->where('category_name',$proservice_list->category_name ); 

		                    $this->db->order_by('service_name', 'asc');

		                    $queryp = $this->db->get();

		                    $totalre = $queryp->num_rows() ;

                        ?>     
                      <optgroup label="<?php echo $proservice_list->category_name;?>">

                          <?php
                    		foreach($queryp->result() as $row)

                    		{

                         ?>

                        <option value="<?php echo $row->service_name ?>"><?php echo $row->service_name ?></option>

                        <?php } ?>

                      </optgroup>

                    <?php }} ?>

                  </select><label for="project_phase">Project Service</label>

                  </div>
        </div>
        <div class="col-sm-3 col-md-3">

                <div class="form-floating">
                    <select class="form-select" name="search_employee" id="search_employee" >

                    <option value="">--Select--</option>
                    <?php
                        if(!empty($get_members_list)){
                        foreach ($get_members_list as $emp_list)
                        { 
                        ?>
                    <option value="<?php echo $emp_list->employee_no;?>" ><?php echo $emp_list->name;?></option>
                     <?php }}?>
                  </select><label for="search_employee">Employee</label></div>

          </div>
    </div>
            <br> 
              <table class="table table-sm fs-9 mb-0 overflow-hidden table-hover" id="projectTable">

                <thead class="text-body">

                  <tr>

                     <th class="" style="width:100px">Category</th>

                    <th class="" >Project</th>

                    <th class="" >Service</th>

                    <th class="" >Project Status</th>

                    <th class="" >Client Name</th>

                    <th class="" >Created Date</th>

                    <th class="" ></th>

                  </tr>

                </thead>

              </table>

    </div>

            <br><br>

            </div>

      </div>  
<?php include(APPPATH.'views/common/footer.php');?>

