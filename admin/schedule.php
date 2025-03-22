<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>
  
  <div class="content-wrapper">
    <section class="content-header">
      <h1>Schedules</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Employees</li>
        <li class="active">Schedules</li>
      </ol>
    </section>
    <section class="content">
      <?php
      if (isset($_SESSION["error"])) {
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              " . $_SESSION["error"] . "
            </div> ";
          unset($_SESSION["error"]);
      }
      if (isset($_SESSION["success"])) {
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Success!</h4>
              " . $_SESSION["success"] . " 
            </div>
          ";
          unset($_SESSION["success"]);
      }
      ?>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> New</a>
            </div>
            <div class="box-body">
              <!-- Wrapper for horizontal scroll -->
              <div style="overflow-x: auto;">
                <table id="example1" class="table table-bordered text-center">
                  <thead>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Edit</th>
                    <th>Delete</th>
                  </thead>
                  <tbody>
                    <?php
                    $sql = "SELECT * FROM schedules";
                    $query = $conn->query($sql);
                    while ($row = $query->fetch_assoc()) {
                        echo "
                          <tr>
                            <td>" . date("h:i A", strtotime($row["time_in"])) . "</td>
                            <td>" . date("h:i A", strtotime($row["time_out"])) . "</td>
                            <td>
                            <button class='btn btn-success btn-sm edit btn-flat' data-id='" . $row["id"] . "'><i class='fa fa-edit'></i> Edit</button>
                            </td>
                            <td>
                              <button class='btn btn-danger btn-sm delete btn-flat' data-id='" . $row["id"] . "'><i class='fa fa-trash'></i> Delete</button>
                            </td>
                          </tr> ";
                    } ?>
                  </tbody>
                </table>
              </div>
              <!-- End of wrapper for horizontal scroll -->
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  
  <?php include 'includes/footer.php'; ?>
  <?php include 'includes/schedule_modal.php'; ?>
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
  });

  function getRow(id){
    $.ajax({
      type: 'POST',
      url: 'schedule_row.php',
      data: {id:id},
      dataType: 'json',
      success: function(response){
        $('#timeid').val(response.id);
        $('#edit_time_in').val(response.time_in);
        $('#edit_time_out').val(response.time_out);
        $('#del_timeid').val(response.id);
        $('#del_schedule').html(response.time_in+' - '+response.time_out);
      }
    });
  }
</script>
<?php include 'includes/datatable_initializer.php'; ?>
</body>
</html>
