<div class="modal fade" id="edit" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Remarks</h4>
      </div>
      <form method="POST" action="tickets_update.php">
        <div class="modal-body">
          <input type="hidden" id="edit_id" name="id">
          <div class="form-group">
            <label for="edit_remarks">Remarks</label>
            <select class="form-control" id="edit_remarks" name="remarks">
              <option value="Serve">Serve</option>
              <option value="Not Serve" selected>Not Serve</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" name="update">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
