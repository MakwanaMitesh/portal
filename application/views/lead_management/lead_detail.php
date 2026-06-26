<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    include(APPPATH.'views/common/header.php');
?>
<div class="mb-5">
    <div class="d-flex justify-content-between">
        <h4 class="text-body-emphasis fw-bolder mb-2">Person's Name: <?php echo $get_lead_row->person_name;?> </h4>
        
        <!-- <div class="btn-reveal-trigger"><button class="btn btn-sm dropdown-toggle dropdown-caret-none transition-none btn-reveal" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h"></span></button>
        <div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="<?php echo base_url();?>create-lead?lead_id=<?php echo $get_lead_row->lead_id;?>">Edit</a></div>
        </div> -->
    </div>
    <p class="text-body-emphasis fw-bolder mb-2">Company Name: <?php echo $get_lead_row->company_name;?> </p>
    <?php if(!empty($get_lead_row->requirement_message)){?><b>Client Requirements:</b> <p><?php echo $get_lead_row->requirement_message;?></p><?php }?>
    <p> <span class="badge badge-phoenix badge-phoenix-primary"><?php echo $get_lead_row->lead_status;?><span class="ms-1 uil uil-stopwatch"></span></span> &nbsp; <a class="badge badge-phoenix fs-10 badge-phoenix-warning" href="<?php echo base_url();?>create-lead?lead_id=<?php echo $get_lead_row->lead_id;?>">Add Conversation <span class="ms-1 uil uil-edit-alt"></a></p>
        
</div>

<div class="mx-n4 px-4 mx-lg-n6 px-lg-3 ">
        <div class="row g-0">
          
          <div class="col-12 col-xxl-12 px-0 ">
            
              <div class="p-4 p-lg-6">
                <h3 class="text-body-highlight mb-4 fw-bold">Recent activity</h3><br>
                <div class="timeline-vertical timeline-with-details">
                    <?php 
                    $this->db->select("*");
                    $this->db->from('lead_history');  
                    $this->db->where('lead_id',$get_lead_row->lead_id ); 
                    $this->db->order_by('lead_history_id', 'desc');
                    $qu = $this->db->get();
                    
                    //$qu = $querypci->num_rows() ;
                    foreach($qu->result() as $rec)
		{
                    ?>

                  <div class="timeline-item position-relative">
                    <div class="row g-md-3">
                      <div class="col-12 col-md-auto d-flex">
                        <div class="timeline-item-date order-1 order-md-0 me-md-4">
                          <p class="fs-10 fw-semibold text-body-tertiary text-opacity-85 text-end"><?php echo $newDate = date("d M, Y", strtotime($rec->created_at));?> <br class="d-none d-md-block" /> <?php echo $newtime = date("h:i A", strtotime($rec->created_at));?></p>
                        </div>
                        <div class="timeline-item-bar position-md-relative me-3 me-md-0">
                          <div class="icon-item icon-item-sm rounded-7 shadow-none bg-primary-subtle">
                            <span class="fa-solid fa-chess text-primary-dark fs-10"></span>
                         </div>
                         <?php if ($rec != end($qu->result())) { ?>
                         <span class="timeline-bar border-end border-dashed"></span>
                        <?php }?>
                            
                        
                        </div>
                      </div>
                      <div class="col">
                        <div class="timeline-item-content ps-6 ps-md-3">
                          <h5 class="fs-9 lh-sm"><?php echo $rec->lead_hist_status;?></h5>
                          
                          <p class="fs-9 text-body-secondary mb-5"><?php echo $rec->remark;?></p>
                        </div>
                      </div>
                    </div>
                  </div>

                <?php 
        } 
                ?>

                </div>
              </div>
              
              
            
          </div>
        </div>
        </div>   
<?php
    include(APPPATH.'views/common/footer.php');
?>