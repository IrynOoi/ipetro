
     <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                <div class="card-header">
                    <h5>
                        How insert Employee data into database
                        <a href="<?php echo base_url('employee'); ?>" class="btn btn-danger float-right">BACK</a>
                    </h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo base_url('employee/store'); ?>" method="POST">
                        <div class="form-group">
                            <label for="">First Name</label>
                            <input type="text" class="form-control" name="first_name">
                            <small><?php echo form_error('first_name'); ?></small>
                        </div>

                          <div class="form-group">
                            <label for="">Last Name</label>
                            <input type="text" class="form-control" name="last_name">
                            <small><?php echo form_error('last_name'); ?></small>
                        </div>

                          <div class="form-group">
                            <label for="">Phone Number</label>
                            <input type="text" class="form-control" name="phone_number">
                            <small><?php echo form_error('phone_number'); ?></small>
                        </div>

                          <div class="form-group">
                            <label for="">Email ID</label>
                            <input type="text" class="form-control" name="email">
                            <small><?php echo form_error('email'); ?></small>
                           </div>

                               <div class="form-group">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
               
                </div>
                </div>
               
            </div>
        </div>
     </div>
 


    