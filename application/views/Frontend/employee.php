
     <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                <div class="card-header">
                    <?php if($this->session->flashdata('status')): ?>
                    <div class="alert alert-success">
                         <?= $this->session->flashdata('status'); ?>
                    </div>
                   <?php endif; ?>
                    <h5>
                        How to fetch data from db in codeigniter
                        <a href="<?php echo base_url('employee/add'); ?>" class="btn btn-primary float-right">Add Employee</a>
                    </h5>
                </div>
                <div class="card-body">
                    <table id="datatable1" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Phone No</th>
                                <th>Email ID</th>
                                <th>Edit</th>
                                <th>Delete</th>
                                <th>Confirm Delete</th>

                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach($employee as $row) : ?>
                           
                                   
                                
                            <tr>
                                <td><?php echo $row->id; ?></td>
                                <td><?php echo $row->first_name; ?></td>
                                <td><?php echo $row->last_name; ?></td>
                                <td><?php echo $row->phone_number; ?></td>
                                <td><?php echo $row->email; ?></td>
                                <td>
                                    <a href="<?php echo base_url('employee/edit/'.$row->id); ?>" class="btn btn-success btn-sm">Edit</a>
                            </td>
                            <td>
                                    <a href="<?php echo base_url('employee/delete/'.$row->id); ?>" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                            <td>
                                    <button type ="button" class="btn btn-danger confirm delete" value ="<?=$row->id; ?>">Confirm Delete</button>
                            </td>
                            </tr>
                            <?php endforeach; ?>
                          
                    </table>
                </div>
                </div>
               
            </div>
        </div>
     </div>
 


    