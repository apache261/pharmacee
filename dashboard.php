
<!DOCTYPE html>
<html class="no-js">
<head>
	<title>ADMIN</title>
	<?php include 'php/header.php';?>
</head>
<script type="text/javascript">
	var typeOfUser = [
	"Pharmacist",
	"Encoder",
	"Manager",
	"Administrator"
	];
	function scann(){
	$.ajax({
		url : '<?php echo $api_path;?>api/actions/auth/verify.php',
		type: 'GET',
		dataType: 'json',
		error: function(xhr, status, error){
			console.log("error");
		},
		success:function (response) {
			try{
		var uinfo = response[0].data;
		$("#pharmaFname").text(uinfo.firstname);
		$("#fullnameee").text(uinfo.firstname + " " + uinfo.lastname);
		$("#userRoleee").text(typeOfUser[uinfo.userRole-1]);
		$("#updatePasswordOwner").val(uinfo.userId);
		if(uinfo.userRole != 4){
			window.location.replace('error.php');
		}
		setTimeout(function(){scann()}, 5000);
	}catch(err){
		window.location.replace('logout.php');
	}
},

});
}

scann();


</script>
<body>
	<div class="container">
		<?php include 'php/head.php';?>
	<div class="columns container" style="">
		<div class="column col-3 hide-md" style=""><?php include 'php/offcanvas.php';?></div>
		<!-- <div class="column col-1 hide-md" style=""></div> -->
		<div class="column col-8 col-md-12" id="" style="margin-top: 5vh">
			<div id="title_area" class="text-bold text-dark text-center h6 bg-gray py-2 text-uppercase">New User</div>
			<div id="content-area"><?php include 'php/addNew.php';?></div></div>

		</div>
		<div id="disp"></div>

	</div>








<div class="modal " id="update-modal">
  <a href="#close" class="modal-overlay" aria-label="Close"></a>
  <div class="modal-container">
    <div class="modal-header">
      <a href="javascript:closeUpdateModal()" class="btn btn-clear float-right" aria-label="Close"></a>
      <div class="modal-title h5"><i class="far fa-user-edit mr-2 text-primary"></i>Update Employee Record</div>
    </div>
    <div class="modal-body">
      <div class="content">
       <form id="UpdateUserFrm"style="" method="POST">

	<br/>
	<!-- Picture -->
<!-- 	<fieldset >
	<div class="input-group">
	<span class="col-3 input-group-addon text-bold text-dark">Picture<sup class="text-error">*</sup></span>
	<input type="file" class="col-4 col-md-8 form-input" id="profile2" name="profile" accept="image/png, image/jpeg" >
	<div class="col-5 hide-md"></div>

	</div>
	</fieldset> -->
	<!-- Employee Name -->
	<fieldset>
	<input type="hidden" name="info[uid]" id="userrrID"/>
	<span class="text-dark text-bold mx-2 ">Full Name<sup class="text-error">*</sup></span>
	<div class="input-group">
	<!-- <span class="col-3 input-group-addon text-bold text-dark hide-md">Full Name<sup class="text-error">*</sup></span> -->
	<input type="text" class="col-3 form-input" name="info[firstname]" id="fname2" pattern="[a-zA-Z\s]+"placeholder="First Name" required>
	<input type="text" class="col-3 form-input" name="info[middlename]" id="mname2" pattern="[a-zA-Z\s]+"placeholder="Middle Name" required>
	<input type="text" class="col-3 form-input" name="info[familyname]" id = "lname2" pattern="[a-zA-Z\s]+"placeholder="Last Name" required>
	</div>
	</fieldset>
	<input type="hidden" name="info[active]" value="1"/>

	<!-- Birthdate for BIG SCREEN -->
	<fieldset class="hide-md">
	<div class="columns">
	<div class="column col-6">
	<span class="col-6 input-group-addon text-bold text-dark ">Birthday<sup class="text-error">*</sup></span>
	<div class="input-group">
	<!-- 	<span class="col-6 input-group-addon text-bold text-dark hide-md">Birthday<sup class="text-error">*</sup></span> -->
	<input type="date" class="col-3 form-input" name="info[birthday]" id="bday2" pattern=""placeholder="birthdate" required>

	</div>
	</div>
	<div class="column col-6 hide-md">
	<span class="col-6 input-group-addon text-bold text-dark">Gender<sup class="text-error">*</sup></span>
	<div class="input-group">
	<!-- <span class="col-6 input-group-addon text-bold text-dark">Gender<sup class="text-error">*</sup></span> -->
	<select class="form-select col-7" name="info[gender]" id="gender2" required>
	<option value=""></option>
	<option value=1>Female</option>
	<option value=2>LGBT++</option>
	<option value=3>Male</option>
	<!-- <option value=4>Administrator</option> -->
	</select>
	</div>
	</div>
	</div>
	</fieldset>




	<!-- Address -->
	<fieldset>
	<span class="col-3 input-group-addon text-bold text-dark">Address<sup class="text-error">*</sup></span>
	<div class="input-group ">
	<input type="text" class="col-3 form-input" name="address[street]" id="strt2" splaceholder="Street #" value="`+data.Street+`"required>
	<input type="text" class="col-3 form-input" name="address[barangay]" id="brgy2" placeholder="Barangay" value="`+data.Barangay+`"required>
	</div>
	<div class="input-group ">
	<input type="text" class="col-4 form-input" name="address[city]" id="city2" pattern="[a-zA-Z\s]+" placeholder="City/Municipality" value="`+data.City+`"required>
	<input type="text" class="col-4 form-input" name="address[province]" id="prov2" pattern="[a-zA-Z]+"placeholder="Province" value="`+data.Province+`"required>
	<input type="text" class="col-4 form-input" name="address[zipcode]" id="zip2" pattern="[0-9]+"placeholder="zipcode" value="`+data.ZipCode+`"required>
	</div>
	</fieldset>
	<input type="hidden" name="address[active]" value="1";>
	<!-- ContactInfo -->
	<fieldset>
	<span class="col-6 input-group-addon text-bold text-dark">Contact Information<sup class="text-error">*</sup></span>
	<div class="input-group ">
	<!-- <input type="text" class="col-6 form-input" name="contact[phone]" id="phone" pattern="[0-9+]+" placeholder="Contact Number" required> -->
	<input type="text" class="col-6 form-input" name="contact[info]" id="email2" pattern="[a-zA-Z0-9@.]+" placeholder="Email Address" value="`+data.contactinformation+`"required>
	<input type="hidden" name="contact[active]" value="1">
	<input type="hidden" name="contact[type]" value="1">
	</div>
	</fieldset>

	<!-- role and Status BIG -->
	<fieldset>
	<div class="columns hide-md">
	<div class="column col-6">
	<span class="col-6 input-group-addon text-bold text-dark">User Role<sup class="text-error">*</sup></span>
	<div class="input-group ">

	<select class="form-select col-3" name="role[value]" id="role2" required>
	<option value=""></option>
	<option value=1>Pharmacist</option>
	<option value=2>Encoder</option>
	<option value=3>Manager</option>
	<option value=4>Administrator</option>
	</select>
	</div>
	<input type="hidden" name="role[active]" value="1">
	</div>
	<div class="column col-6 hide-md">
	<span class="col-6 input-group-addon text-bold text-dark">Employee Status<sup class="text-error">*</sup></span>
	<div class="input-group ">

	<select class="form-select  col-3 form-select" name="status[type]" id="status2" required>
	<option value=""></option>
	<option value=1>Trainee</option>
	<option value=2>Part Time</option>
	<option value=3>Regular</option>
	<!-- <option value=4></option> -->
	</select>
	<!-- 	Not Included -->
	<input type="hidden" name="status[active]" value="1">
	</div>
	</div>
	</div>
	</fieldset>
	<!-- BUTTONS BIG-->
	<br/>
	</form>
      </div>
    </div>
    <div class="modal-footer">
     <fieldset class="">

			<div class="columns container">
				<div class="column col-3 ">

				</div>
				<div class="column col-3">
					<!-- <input class="btn btn-success btn-lg text-bold "type="submit" name=""> -->
					<a class="btn btn-success btn-lg text-bold" id="updtusrbtn" href="javascript:sendUpdatedEmployeeInfo();">Update</a>
				</div>
				<div class="column col-3">
					<a href="javascript:closeUpdateModal()" class="btn btn-error btn-lg text-bold">Cancel</a>
				</div>
				<div class="column col-3 ">

				</div>
			</div>
		</fieldset>
    </div>
  </div>
</div>


<script type="text/javascript">
function openUpdateModal(){
	$("#update-modal").addClass("active");
}
function closeUpdateModal(){
	$("#update-modal").removeClass("active");
}
</script>




















<?php include 'php/updatePassword.php';?>
	<?php include 'php/footer.php';?>
