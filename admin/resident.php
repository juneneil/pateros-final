<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Residents List
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Resident</li>
        <li class="active">Resident List</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              ".$_SESSION['error']." 
            </div>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Success!</h4>
              ".$_SESSION['success']." 
            </div>
          ";
          unset($_SESSION['success']);
        }
      ?>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> New</a>
            </div>
            <div class="box-body">
              <div style="overflow-x: auto;">
                <table id="example1" class="table table-bordered text-center">
                  <thead>
                    <th>Resident ID</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Adress</th>
                    <th>Birthdate</th>
                    <th>Contact Info</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>Created at</th>
                    <th>Edit</th>
                    <th>Delete</th>
                  </thead>
                  <tbody>
                    <?php
                      $sql = "SELECT *, residents.id AS empid FROM residents";
                      $query = $conn->query($sql);
                      while($row = $query->fetch_assoc()){
                        ?>
                          <tr>
                            <td><?php echo $row['resident_id']; ?></td>
                            <td>
                              <a href="javascript:void(0);" class="photo" data-id="<?php echo $row['empid']; ?>" data-photo="<?php echo (!empty($row['photo']))? '../images/'.$row['photo']:'../images/profile.jpg'; ?>" data-firstname="<?php echo $row['firstname']; ?>">
                                <img src="<?php echo (!empty($row['photo']))? '../images/'.$row['photo']:'../images/profile.jpg'; ?>" width="40px" height="35px" style="cursor: pointer;">
                              </a>
                              <a href="javascript:void(0);" class="pull-right photo" data-id="<?php echo $row['empid']; ?>" data-photo="<?php echo (!empty($row['photo']))? '../images/'.$row['photo']:'../images/profile.jpg'; ?>" data-firstname="<?php echo $row['firstname']; ?>">
                                <span class="fa fa-eye"></span>
                              </a>
                            </td>
                            <td><?php echo $row['firstname'].' '.$row['lastname']; ?></td>
                            <td><?php echo $row['age']; ?></td>
                            <td><?php echo $row['address']; ?></td>
                            <td><?php echo $row['birthdate']; ?></td>
                            <td><?php echo $row['contact_info']; ?></td>
                            <td><?php echo $row['gender']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo date('M d, Y', strtotime($row['created_on'])) ?></td>
                            <td>
                              <button class="btn btn-success btn-sm edit btn-flat" data-id="<?php echo $row['empid']; ?>"><i class="fa fa-edit"></i> Edit</button>
                            </td>
                            <td>
                              <button class="btn btn-danger btn-sm delete btn-flat" data-id="<?php echo $row['empid']; ?>"><i class="fa fa-trash"></i> Delete</button>
                            </td>
                          </tr>
                        <?php
                      }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>   
  </div>
  <?php include 'includes/footer.php'; ?>
  <?php include 'includes/resident_modal.php'; ?>
<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="imageModalLabel">Resident Photo: <span id="ResidentFirstName"></span></h5>
      </div>
      <div class="modal-body text-center">
        <img src="" id="modalImage" class="img-fluid" alt="Resident Photo" style="width: 60%; height: 410px; cursor: pointer;">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
</div>
<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
  $('.photo').click(function(e){
    e.preventDefault();
    var photoUrl = $(this).data('photo');
    var firstName = $(this).data('firstname');
    $('#modalImage').attr('src', photoUrl);
    $('#ResidentFirstName').text(firstName);
    $('#imageModal').modal('show');
  });
});

$(function(){
  $('.edit').click(function(e){
    e.preventDefault();
    $('#edit').modal('show');
    var id = $(this).data('id');
    getRow(id);
  });

  $('.delete').click(function(e){
    e.preventDefault();
    $('#delete').modal('show');
    var id = $(this).data('id');
    getRow(id);
  });

  $('.photo').click(function(e){
    e.preventDefault();
    var photoUrl = $(this).data('photo');
    $('#modalImage').attr('src', photoUrl); // Set the modal's image source
    $('#imageModal').modal('show'); // Show the modal
  });
});

function getRow(id){
  $.ajax({
    type: 'POST',
    url: 'resident_row.php',
    data: {id:id},
    dataType: 'json',
    success: function(response){
      $('.empid').val(response.empid);
      $('.resident_id').html(response.resident_id);
      $('.del_resident_name').html(response.firstname+' '+response.lastname);
      $('#resident_name').html(response.firstname+' '+response.lastname);
      $('#edit_firstname').val(response.firstname);
      $('#edit_lastname').val(response.lastname);
      $('#edit_age').val(response.age);
      $('#edit_email').val(response.email);
      $('#edit_address').val(response.address);
      $('#datepicker_edit').val(response.birthdate);
      $('#edit_contact').val(response.contact_info);
      $('#gender_val').val(response.gender);
      $('#edit_photo').val(response.photo);
      $('#edit_empid').val(response.empid);
    }
  });
}
</script>
<?php include 'includes/datatable_initializer.php'; ?>
</body>
</html>