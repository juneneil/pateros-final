<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><b>Add Employee</b></h4>
            </div>
            <div class="modal-body">
                <!-- New Form Start -->
                <form class="form-horizontal" method="POST" action="employee_add.php" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="firstname" class="col-sm-3 control-label">Firstname</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="firstname" name="firstname" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="lastname" class="col-sm-3 control-label">Lastname</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="lastname" name="lastname" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>

                    <!-- Keep the other existing form fields as they are -->
                    <div class="form-group">
                        <label for="address" class="col-sm-3 control-label">Address</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="address" id="address"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="datepicker_add" class="col-sm-3 control-label">Birthdate</label>
                        <div class="col-sm-9"> 
                            <div class="date">
                                <input type="text" class="form-control" id="datepicker_add" name="birthdate">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contact" class="col-sm-3 control-label">Contact Info</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="contact" name="contact">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="gender" class="col-sm-3 control-label">Gender</label>
                        <div class="col-sm-9"> 
                            <select class="form-control" name="gender" id="gender" required>
                                <option value="" selected>- Select -</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="position" class="col-sm-3 control-label">Position</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="position" id="position" required>
                                <option value="" selected>- Select -</option>
                                <?php
                                    $sql = "SELECT * FROM position";
                                    $query = $conn->query($sql);
                                    while ($prow = $query->fetch_assoc()) {
                                        echo "<option value='".$prow['id']."'>".$prow['description']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="schedule" class="col-sm-3 control-label">Schedule</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="schedule" name="schedule" required>
                                <option value="" selected>- Select -</option>
                                <?php
                                    $sql = "SELECT * FROM schedules";
                                    $query = $conn->query($sql);
                                    while ($srow = $query->fetch_assoc()) {
                                        echo "<option value='".$srow['id']."'>".$srow['time_in'].' - '.$srow['time_out']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="photo" class="col-sm-3 control-label">Photo</label>
                        <div class="col-sm-9">
                            <input type="file" name="photo" id="photo">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                        <button type="submit" class="btn btn-primary btn-flat" name="add"><i class="fa fa-save"></i> Save</button>
                  </div>
                </form>
                <!-- New Form End -->
            </div>
        </div>
    </div>
</div>





<!-- Edit -->
<div id="edit" class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><b>Edit Employee</b></h4>
            </div>
            <div class="modal-body">
                <!-- New Form Start -->
                <form class="form-horizontal" method="POST" action="employee_edit.php" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="edit_empid" value="">

                    <div class="form-group">
                        <label for="firstname" class="col-sm-3 control-label">Firstname</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="edit_firstname" name="firstname" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="lastname" class="col-sm-3 control-label">Lastname</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="edit_lastname" name="lastname" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                    </div>

                    <!-- Keep the other existing form fields as they are -->
                    <div class="form-group">
                        <label for="address" class="col-sm-3 control-label">Address</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="address" id="edit_address"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="datepicker_add" class="col-sm-3 control-label">Birthdate</label>
                        <div class="col-sm-9"> 
                            <div class="date">
                                <input type="text" class="form-control" id="datepicker_edit" name="birthdate">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contact" class="col-sm-3 control-label">Contact Info</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="edit_contact" name="contact">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="gender" class="col-sm-3 control-label">Gender</label>
                        <div class="col-sm-9"> 
                            <select class="form-control" name="gender" id="gender_val" required>
                                <option value="" selected>- Select -</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="position" class="col-sm-3 control-label">Position</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="position" id="position_val" required>
                                <option value="" selected>- Select -</option>
                                <?php
                                    $sql = "SELECT * FROM position";
                                    $query = $conn->query($sql);
                                    while ($prow = $query->fetch_assoc()) {
                                        echo "<option value='".$prow['id']."'>".$prow['description']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="schedule" class="col-sm-3 control-label">Schedule</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="schedule_val" name="schedule" required>
                                <option value="" selected>- Select -</option>
                                <?php
                                    $sql = "SELECT * FROM schedules";
                                    $query = $conn->query($sql);
                                    while ($srow = $query->fetch_assoc()) {
                                        echo "<option value='".$srow['id']."'>".$srow['time_in'].' - '.$srow['time_out']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="photo" class="col-sm-3 control-label">Photo</label>
                        <div class="col-sm-9">
                            <input type="file" name="photo" id="photo">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                        <button type="submit" class="btn btn-primary btn-flat" name="edit"><i class="fa fa-save"></i> Save</button>
                  </div>
                </form>
                <!-- New Form End -->
            </div>
        </div>
    </div>
</div>






<!-- Delete -->
<div class="modal fade" id="delete">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b><span class="employee_id"></span></b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" method="POST" action="employee_delete.php">
            		<input type="hidden" class="empid" name="id">
            		<div class="text-center">
	                	<p>DELETE OVERTIME</p>
	                	<h2 class="bold" id="employee_name"></h2>
	            	</div>
          	</div>
          	<div class="modal-footer">
            	<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            	<button type="submit" class="btn btn-danger btn-flat" name="delete"><i class="fa fa-trash"></i> Delete</button>
            	</form>
          	</div>
        </div>
    </div>
</div>
