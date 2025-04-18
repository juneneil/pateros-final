<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<?php
if (isset($_SESSION['user_id'])) {
    $_SESSION['firstname'] = 'New First Name';
    $_SESSION['lastname'] = 'New Last Name';
    $_SESSION['position'] = 'New Position';
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['employee'] != $_SESSION['employee_id']) {
        echo json_encode([
            'error' => true,
            'message' => 'You can only sign in with your own Employee ID.'
        ]);
        exit();
    }
    $employee_id = $_POST['employee'];
    $status = $_POST['status'];
    $sql = "INSERT INTO attendance (employee_id, status, time_in, time_out, date) 
            VALUES (?, ?, NOW(), NULL, CURDATE()) 
            ON DUPLICATE KEY UPDATE status = ?, time_in = NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $employee_id, $status, $status);
    $stmt->execute();
    echo json_encode([
        'error' => false,
        'message' => 'Attendance recorded successfully.'
    ]);
}
?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>
  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        Attendance
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Attendance</li>
      </ol>
    </section>
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
              <form method="POST" class="form-inline" id="payForm">
                <button type="button" class="btn btn-success btn-sm btn-flat" id="attendance_pdf"><span class="glyphicon glyphicon-print"></span> Attendance</button>
              </form>
            </div>
            <div class="box-body">
              <div class="table-responsive">
                <table id="example1" class="table table-bordered text-center">
                  <thead>
                    <th class="hidden"></th>
                    <th>Date</th>
                    <th>Employee ID</th>
                    <th>Name</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Number of hours</th>
                    <th>Picture In</th>
                    <th>Picture Out</th>
                    <th>Edit</th>
                    <th>Delete</th>
                  </thead>
                  <tbody>
                    <?php
                      $sql = "SELECT *, employees.employee_id AS empid, picture_in, attendance.id AS attid FROM attendance 
                              LEFT JOIN employees ON employees.id=attendance.employee_id 
                              ORDER BY attendance.date DESC, attendance.time_in DESC";
                      $query = $conn->query($sql);
                      while ($row = $query->fetch_assoc()) {
                          $status = ($row['status']) ? '<span class="label label-warning pull-right">ontime</span>' : '<span class="label label-danger pull-right">late</span>';
                          echo "
                            <tr>
                              <td class='hidden'></td>
                              <td>" . date('M d, Y', strtotime($row['date'])) . "</td>
                              <td>" . $row['empid'] . "</td>
                              <td>" . $row['firstname'] . ' ' . $row['lastname'] . "</td>
                              <td>" . date('h:i A', strtotime($row['time_in'])) . $status . "</td>
                              <td>" . date('h:i A', strtotime($row['time_out'])) . "</td>
                              <td>" . $row['num_hr'] . "</td>
                              <td>";
                              if (!empty($row['picture_in'])) {
                                  echo "<a href='#' class='photo' data-photo='../employees/" . $row['picture_in'] . "' data-firstname='" . $row['firstname'] . "'>
                                          <img src='../employees/" . $row['picture_in'] . "' alt='Employee Picture In' width='50' height='50' class='img-circle'>
                                        </a>
                                        &nbsp;
                                        <button class='btn btn-info btn-sm photo' 
                                            data-photo='../employees/" . $row['picture_in'] . "' 
                                            data-firstname='" . $row['firstname'] . "'>
                                            <i class='fa fa-eye'></i>
                                        </button>";
                              } else {
                                  echo "N/A";
                              }
                              echo "</td>
                              <td>";
                              if (!empty($row['picture_out'])) {
                                  echo "<a href='#' class='photo' data-photo='../employees/" . $row['picture_out'] . "' data-firstname='" . $row['firstname'] . "'>
                                          <img src='../employees/" . $row['picture_out'] . "' alt='Employee Picture Out' width='50' height='50' class='img-circle'>
                                        </a>
                                        &nbsp;
                                        <button class='btn btn-info btn-sm photo' 
                                            data-photo='../employees/" . $row['picture_out'] . "' 
                                            data-firstname='" . $row['firstname'] . "'>
                                            <i class='fa fa-eye'></i>
                                        </button>";
                              } else {
                                  echo "N/A";
                              }
                              echo "</td>
                              <td>
                                <button class='btn btn-success btn-sm btn-flat edit' data-id='" . $row['attid'] . "'><i class='fa fa-edit'></i> Edit</button>
                              </td>
                              <td>
                                <button class='btn btn-danger btn-sm btn-flat delete' data-id='" . $row['attid'] . "'><i class='fa fa-trash'></i> Delete</button>
                              </td>
                            </tr>
                          ";
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="modal_script.js"></script>
<script>
  $(document).ready(function() {
      $('.photo').click(function() {
          var imageUrl = $(this).data('photo'); 
          var firstName = $(this).data('firstname'); 

          $('#modalImage').attr('src', imageUrl);
          $('#employeeFirstName').text(firstName);

          $('#imageModal').modal('show');
      });
  });
</script>
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="imageModalLabel">Employee Photo: <span id="employeeFirstName"></span></h5>
      </div>
      <div class="modal-body text-center">
        <img src="" id="modalImage" class="img-fluid" alt="Employee Photo" style="width: 60%; height: 410px; cursor: pointer;">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
  <?php include 'includes/footer.php'; ?>
  <?php include 'includes/attendance_modal.php'; ?>
  </div>
  <?php include 'includes/scripts.php'; ?>
  <script>
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
    $('#attendance_pdf').click(function(e){
      e.preventDefault();
      $('#payForm').attr('action', 'attendance_pdf.php');
      $('#payForm').submit();
    });

  });
  function getRow(id){
    $.ajax({
      type: 'POST',
      url: 'attendance_row.php',
      data: {id:id},
      dataType: 'json',
      success: function(response){
        $('#datepicker_edit').val(response.date);
        $('#attendance_date').html(response.date);
        $('#edit_time_in').val(response.time_in);
        $('#edit_time_out').val(response.time_out);
        $('#attid').val(response.attid);
        $('#employee_name').html(response.firstname+' '+response.lastname);
        $('#del_attid').val(response.attid);
        $('#del_employee_name').html(response.firstname+' '+response.lastname);
      }
    });
  }
  setInterval(function(){
    loadAttendanceData();
  }, 30000);
  </script>
  <?php include 'includes/datatable_initializer.php'; ?>
</body>
</html>
