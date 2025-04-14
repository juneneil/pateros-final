<?php include 'includes/session.php'; ?>
<?php
  include 'timezone.php';
  $range_to = date('m/d/Y');
  $range_from = date('m/d/Y', strtotime('-30 day', strtotime($range_to)));
  $_SESSION['id'] = $user['id'];
?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>
  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        Tickets
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Ticket</li>
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
              <div class="pull-right">
                <form method="POST" class="form-inline" id="payForm">
                  <button type="button" class="btn btn-success btn-sm btn-flat" id="payroll"><span class="glyphicon glyphicon-print"></span> Ticket</button>
                </form>
              </div>
            </div>
            <div class="box-body">
              <div style="overflow-x: auto;">
                <table id="example1" class="table table-bordered text-center">
                  <thead>
                        <th>Ticket Number</th>
                        <th>Resident ID</th>
                        <th>Category</th>
                        <th>Sub Category</th>
                        <th>Booking Date</th>
                        <th>Reason for Inquiry</th>
                        <th>Remarks</th>
                        <th>Approval</th>
                        <th>Created at</th>
                        <th>Edit Remarks</th>
                        <th>Edit Approval</th>
                  </thead>
                  <tbody>
                  <?php
                    $sql = "SELECT * FROM ticket";
                    $query = $conn->query($sql);
                    while($row = $query->fetch_assoc()){
                      echo "<tr>";
                      echo "<td>".$row['id']."</td>";
                      echo "<td>".$row['resident_id']."</td>";
                      echo "<td>".$row['category']."</td>";
                      echo "<td>".$row['sub_category']."</td>";
                      echo "<td>".$row['booking_date']."</td>";
                      echo "<td>".$row['reason_for_inquiry']."</td>";
                      echo "<td>".$row['remarks']."</td>";
                      echo "<td>".$row['approval']."</td>";
                      echo "<td>".$row['created_at']."</td>";
                      echo "<td><button class='btn btn-success btn-sm btn-flat edit' data-id='".$row['id']."'><i class='fa fa-edit'></i> Edit</button></td>";

                      $categoryAccess = [
                        'Civil Registry' => 2,
                        'Business Registration' => 3,
                        'Job Opportunities' => 4,
                        'DSWD' => 5
                      ];
                      $currentUserId = $_SESSION['id'];
                      $assignedApprover = $categoryAccess[$row['category']] ?? null;
                      $disablePayment = ($currentUserId == 4 || $currentUserId == 5) ? 'disabled style="color:gray;"' : '';
                      if ($currentUserId == 1 || $currentUserId == $assignedApprover) {
                          echo "<td><button class='btn btn-success btn-sm btn-flat approval' data-id='".$row['id']."'><i class='fa fa-edit'></i> Approval</button></td>";
                      } else {
                          echo "<td><button class='btn btn-default btn-sm btn-flat' disabled><i class='fa fa-ban'></i> No Access</button></td>";
                      }
                      echo "</tr>";
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
  <?php include 'includes/tickets_modal.php'; ?>
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

  $('.approval').click(function(e){
    e.preventDefault();
    $('#approval').modal('show');
    var id = $(this).data('id');
    getRow(id);
  });

  $('.delete').click(function(e){
    e.preventDefault();
    $('#delete').modal('show');
    var id = $(this).data('id');
    getRow(id);
  });

  $("#reservation").on('change', function(){
    var range = encodeURI($(this).val());
    window.location = 'payroll.php?range='+range;
  });

  $('#payroll').click(function(e){
    e.preventDefault();
    $('#payForm').attr('action', 'tickets_generate.php');
    $('#payForm').submit();
  });

  $('#payslip').click(function(e){
    e.preventDefault();
    $('#payForm').attr('action', 'tickets_generate.php');
    $('#payForm').submit();
  });

});

function getRow(id){
  $.ajax({
    type: 'POST',
    url: 'tickets_row.php',
    data: {id:id},
    dataType: 'json',
    success: function(response){
      $('#edit_id').val(response.id);
      $('#edit_remarks').val(response.remarks);
      $('#edit_approval_id').val(response.id);
      $('#edit_approval').val(response.approval);
    }
  });
}

$(document).on('click', '.edit', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    console.log("Clicked Edit for ID:", id);
    getRow(id);
});

$(document).on('click', '.approval', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    console.log("Clicked Edit for ID:", id);
    getRow(id);
});
</script>

<?php include 'includes/datatable_initializer.php'; ?>
</body>
</html>
