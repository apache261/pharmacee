<div class="modal modal-sm" id="updateUserPasswordModal">
  <a href="#" class="modal-overlay" aria-label="Close"></a>
  <div class="modal-container">
    <div class="modal-header">
      <a href="javascript:closeUpdatePasswordModal()" class="btn btn-clear text-light bg-error float-right " aria-label="Close"></a>
      <div class="modal-title h5">Update Password</div>
    </div>
    <div class="modal-body">
      <div class="content">
        <div id="updatePasswordModalErrorMsg" class="text-error text-center"></div>
        <form id="updatePasswordModalForm">
          <input type="hidden" name="update[owner]" id="updatePasswordOwner"/>
          <div class="form-group" >
            <label class="form-label" for="cur_pas">Current Password</label>
            <input class="form-input" name="update[oldPass]" style="border-radius: 3px; height: 30px" type="password" id="cur_pas" placeholder="Current password">
          </div>
          <div class="form-group" style="">
            <label class="form-label" for="new_pass">New Password</label>
            <input class="form-input" name="update[newPass]" style="border-radius: 3px; height: 30px" type="password" id="new_pass" placeholder="New Password">
          </div>
          <div class="form-group" style="">
            <label class="form-label" for="confirm_pass">Confirm Password</label>
            <input class="form-input" name="" style="border-radius: 3px; height: 30px" type="password" id="confirm_pass" placeholder="confirm Password">
          </div>
          <div class="py-2">
            <a class="btn btn-primary p-centered text-bold" id="proceed_updatePasswordBtn" href="javascript:renewPassword()" style="width:100%"><i class="icon icon-arrow-left"></i>Update</a>
          </div>
          <div class="py-2">
            <a class="btn p-centered text-bold" id="cancel_updatePasswordBtn" href="javascript:closeUpdatePasswordModal()" style="width:100%"><i class="icon icon-arrow-left"></i>Cancel</a>
          </div>
          </form>
        </div>
    </div>
  </div>

</div>
</div>