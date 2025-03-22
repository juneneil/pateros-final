<?php
if (!isset($_SESSION['user_id'])) {
  header('Location: index.php');
  exit();
  }
?>

<body>
<div class="login-box">
  	<div class="login-logo">
  		<p id="date"></p>
      <p id="time" class="bold"></p>
  	</div>
  
  	<div class="login-box-body">
        <h4 class="login-box-msg">Enter Employee ID</h4>

        <form id="attendance" enctype="multipart/form-data">
          <div class="form-group col-sm-4">
              <select class="form-control" name="status">
                  <option value="in">Time In</option>
                  <option value="out">Time Out</option>
              </select>
          </div>

          <div class="form-group has-feedback col-sm-4">
              <input type="text" class="form-control input-lg" id="employee" name="employee" required>
              <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
          </div>

          <div class="form-group col-sm-4">
              <label for="employee_picture">Upload Picture:</label>
              <input type="file" class="form-control" name="employee_picture" accept="image/*" required>
          </div>

          <input type="hidden" name="employee_id" value="<?php echo $_SESSION['employee_id']; ?>">

          <div class="row">
              <div class="col-xs-4">
                  <button type="submit" class="btn btn-primary btn-block btn-flat" name="signin">
                      <i class="fa fa-sign-in"></i> Time
                  </button>
              </div>
          </div>
        </form>

  	</div>
		<div class="alert alert-success alert-dismissible mt20 text-center" style="display:none;">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <span class="result"><i class="icon fa fa-check"></i> <span class="message"></span></span>
    </div>
		<div class="alert alert-danger alert-dismissible mt20 text-center" style="display:none;">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <span class="result"><i class="icon fa fa-warning"></i> <span class="message"></span></span>
    </div>
</div>
	
<?php include 'scripts.php' ?>
<script type="text/javascript">
$(function() {
  var interval = setInterval(function() {
    var momentNow = moment();
    $('#date').html(momentNow.format('dddd').substring(0, 3).toUpperCase() + ' - ' + momentNow.format('MMMM DD, YYYY'));
    $('#time').html(momentNow.format('hh:mm:ss A'));
  }, 100);

  $('#attendance').submit(function(e) {
    e.preventDefault();
    
    var employeeIdFromForm = $('#employee').val();
    var loggedInEmployeeId = '<?php echo $_SESSION['employee_id']; ?>';
    if (employeeIdFromForm != loggedInEmployeeId) {
      $('.alert').hide();
      $('.alert-danger').show();
      $('.message').html('You can only sign in with your own Employee ID.');
      return;
    }
    
    var formData = new FormData(this);
    
    $.ajax({
      type: 'POST',
      url: 'attendance.php',
      data: formData,
      dataType: 'json',
      processData: false,
      contentType: false,
      success: function(response) {
        $('.alert').hide();
        if (response.error) {
          $('.alert-danger').show();
          $('.message').html(response.message);
        } else {
          $('.alert-success').show();
          $('.message').html(response.message);
          $('#employee').val('');
          $('input[name=employee_picture]').val('');
        }
      }
    });
  });
});
</script>
</body>
</html>