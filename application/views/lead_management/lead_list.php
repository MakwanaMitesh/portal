<?php

    defined('BASEPATH') OR exit('No direct script access allowed');

    include(APPPATH.'views/common/header.php');

?>

<div class="mb-4">

    <br>

    <div class="mx-n4 px-4 mx-lg-n6 px-lg-3 bg-body-emphasis pt-6 border-top border-bottom">

        <div class="row g-3">

            <div class="col-auto">

                <h4> Leads List </h4><br>

            </div>

            <div class="col-auto scrollbar overflow-hidden-y flex-grow-1"></div>

            <div class="col-auto"><a href="<?php echo base_url();?>create-lead"
                    class="btn btn-subtle-primary me-1 mb-1">Create Lead</a></div>

        </div>

        <div class="table-responsive ms-n1 ps-1 scrollbar">

            <div class="row">

                <div class="col-md-3">

                    <div class="form-floating"><select class="form-select" id="lead_status" required>
                            <option value="">--Select--</option>
                            <?php

if(!empty($get_lead_status)){

foreach ($get_lead_status as $lead_status)

{ 

?>

                            <option value="<?php echo $lead_status->status_name;?>">
                                <?php echo $lead_status->status_name;?></option>

                            <?php }}?>

                        </select><label for="lead_status">Lead Status</label>

                    </div>

                </div>

                <div class="col-sm-3 col-md-3 ">

                    <div class="form-floating"><select class="form-select" id="lead_source">

                            <option value="">--Select--</option>
                            <?php

if(!empty($get_lead_source)){

foreach ($get_lead_source as $lead_source)

{ 

?>

                            <option value="<?php echo $lead_source->sources_name;?>">
                                <?php echo $lead_source->sources_name;?></option>

                            <?php }}?>

                        </select><label for="lead_source">Lead source</label></div>

                </div>

                <div class="col-md-3">

                    <div class="form-floating"><select class="form-select" id="lead_type" required>

                            <option value="">--Select--</option>
                            <?php

if(!empty($get_lead_type)){

foreach ($get_lead_type as $lead_type)

{ 

?>

                            <option value="<?php echo $lead_type->type;?>"><?php echo $lead_type->type;?></option>

                            <?php }}?>

                        </select><label for="lead_type">Lead type</label>

                    </div>
                </div>
            </div>
            <br>
            <table class="table table-sm fs-9 mb-0 overflow-hidden table-hover" id="leadTable">

                <thead class="text-body">

                    <tr>

                        <th class="">Person name</th>
                        <th class="">Contact</th>
                        <th class="">Lead status</th>
                        <th class="">Lead Source</th>
                        <th class="">Lead type</th>
                        <th class="" style="width:200px">Remark</th>
                        <th class="">Last-Updated Date</th>

                        <th class=""></th>

                    </tr>

                </thead>

            </table>

        </div>

        <br><br>

    </div>

</div>
<?php include(APPPATH.'views/common/footer.php');?>