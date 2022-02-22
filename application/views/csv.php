<style type="text/css">

</style>

<div class="container-fluid dashboard-page">
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <div class="dashboard-section">
          <h1>List of Orders <span class="orange-text"></span></h1>
          
          <div class="row" style="margin-top: 20px;">
            <div class="col-md-12"> 
              <div class="table-list">        
                <table class="table-responsive table-bordered table-striped table-hover" width="100%" id="leads_csv">
                <thead>
                <tr>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Email</th>
                  <th>Mobile No</th>
                </tr>
                </thead>
                <tbody>
                <?php
                     if(!empty($leads)){
                      foreach($leads as $lead){
                ?>
                  <tr>
                    <td><?php echo $lead['first_name'];?></td>
                    <td><?php echo $lead['last_name'];?></td>
                    <td><?php echo $lead['email'];?></td>
                    <td><?php echo $lead['contact_mobile'];?></td>
                  </tr>
                <?php } } ?>
                </tbody>
                </table>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>   
</div>
