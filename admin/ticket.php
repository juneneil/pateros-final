<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
<?php include 'includes/navbar.php'; ?>
<?php include 'includes/menubar.php'; ?>
      <div class="content-wrapper">
          <section class="content-header">
            <h1>Ticket</h1>
            <ol class="breadcrumb">
              <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
              <li class="active">Deductions</li>
            </ol>
          </section>
          <section class="content">
            <?php
              if(isset($_SESSION['error'])){
                echo "
                  <div class='alert alert-danger alert-dismissible'>
                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                    <h4><i class='icon fa fa-warning'></i> Error!</h4> ".$_SESSION['error']."
                  </div>
                ";
                unset($_SESSION['error']);
              }
              if(isset($_SESSION['success'])){
                echo "
                  <div class='alert alert-success alert-dismissible'>
                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                    <h4><i class='icon fa fa-check'></i> Success!</h4> ".$_SESSION['success']."
                  </div>
                ";
                unset($_SESSION['success']);
              }
            ?>
            <div class="row">
              <div class="col-xs-12">
                <div class="box">
                  <div class="box-body">
                    <!-- Added a wrapper with overflow-x: auto for horizontal scroll -->
                    <div style="overflow-x: auto;">
                      <table id="example1" class="table table-bordered">
                        <thead>
                          <th>Ticket Number</th>
                          <th>Resident ID</th>
                          <th>Category</th>
                          <th>Sub Category</th>
                          <th>Booking Date</th>
                          <th>Reason for Inquiry</th>
                          <th>Created at</th>
                        </thead>
                        <tbody>
                          <?php
                            $sql = "SELECT * FROM ticket";
                            $query = $conn->query($sql);
                            while($row = $query->fetch_assoc()){
                              echo "
                                <tr>
                                  <td>".$row['id']."</td>
                                  <td>".$row['resident_id']."</td>
                                  <td>".($row['category'])."</td>
                                  <td>".$row['sub_category']."</td>
                                  <td>".$row['booking_date']."</td>
                                  <td>".($row['reason_for_inquiry'])."</td>
                                  <td>".($row['created_at'])."</td>
                                </tr>
                              ";
                            }
                          ?>
                        </tbody>
                      </table>
                    </div>
                    <!-- End of wrapper with overflow-x: auto -->
                  </div>
                </div>
              </div>
            </div>
          </section>   
      </div>
      <?php include 'includes/footer.php'; ?>
      <?php include 'includes/deduction_modal.php'; ?>
      </div>
      <?php include 'includes/scripts.php'; ?>
      <script>
          $(function() {
              $('.edit').click(function(e) {
                e.preventDefault();
                $('#edit').modal('show');
                var id = $(this).data('id');
                getRow(id);
              });

              $('.delete').click(function(e) {
                e.preventDefault();
                $('#delete').modal('show');
                var id = $(this).data('id');
                getRow(id);
              });
            });

          function getRow(id) {
            $.ajax({
              type: 'POST',
              url: 'deduction_row.php',
              data: {
                id: id
              },
              dataType: 'json',
              success: function(response) {
                $('.decid').val(response.id);
                $('#edit_description').val(response.description);
                $('#edit_amount').val(response.amount);
                $('#del_deduction').html(response.description);
              }
            });
          }
      </script>
      <?php include 'includes/datatable_initializer.php'; ?>
</body>
</html>
