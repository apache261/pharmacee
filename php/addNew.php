
<div class="container p-2  bg-gray" style="margin-top: 2vh">
	<!-- start FORM here -->
	<form id="addNew" name="" method="POST">
		<br/>
		<!-- Picture -->
	<!-- 	<fieldset>
			<div class="input-group">
				<span class="col-3 input-group-addon text-bold text-dark">Picture<sup class="text-error">*</sup></span>
				<input type="file" class="col-4 col-md-8 form-input" id="profile" name="profile" accept="image/png, image/jpeg" >
				<div class="col-5 hide-md"></div>
			</div>
		</fieldset> -->
		<!-- Employee Name -->
		<fieldset>
			<span class="text-dark text-bold mx-2 ">Full Name<sup class="text-error">*</sup></span>
			<div class="input-group">
				<!-- <span class="col-3 input-group-addon text-bold text-dark hide-md">Full Name<sup class="text-error">*</sup></span> -->
				<input type="text" class="col-3 form-input" name="info[firstname]" id="fname" pattern="[a-zA-Z\s]+"placeholder="First Name" required>
				<input type="text" class="col-3 form-input" name="info[middlename]" id="mname" pattern="[a-zA-Z\s]+"placeholder="Middle Name" required>
				<input type="text" class="col-3 form-input" name="info[familyname]" id = "lname" pattern="[a-zA-Z\s]+"placeholder="Last Name" required>
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
						<input type="date" class="col-3 form-input" name="info[birthday]" id="bday" pattern=""placeholder="birthdate" required>
					</div>
				</div>
				<div class="column col-6 hide-md">
					<span class="col-6 input-group-addon text-bold text-dark">Gender<sup class="text-error">*</sup></span>
					<div class="input-group">
						<!-- <span class="col-6 input-group-addon text-bold text-dark">Gender<sup class="text-error">*</sup></span> -->
						<select class="form-select col-7" name="info[gender]" id="gender" required>
							<option value=></option>
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
				<input type="text" class="col-3 form-input" name="address[street]" id="strt" splaceholder="Street #" required>
				<input type="text" class="col-3 form-input" name="address[barangay]" id="brgy" placeholder="Barangay" required>
			</div>
			<div class="input-group ">
				<input type="text" class="col-4 form-input" name="address[city]" id="city" pattern="[a-zA-Z\s]+" placeholder="City/Municipality" required>
				<input type="text" class="col-4 form-input" name="address[province]" id="prov" pattern="[a-zA-Z]+"placeholder="Province" required>
				<input type="text" class="col-4 form-input" name="address[zipcode]" id="zip" pattern="[0-9]+"placeholder="zipcode" required>
			</div>
		</fieldset>
		<input type="hidden" name="address[active]" value="1";>
		<!-- ContactInfo -->
		<fieldset>
			<span class="col-6 input-group-addon text-bold text-dark">Contact Information<sup class="text-error">*</sup></span>
			<div class="input-group ">
				<!-- <input type="text" class="col-6 form-input" name="contact[phone]" id="phone" pattern="[0-9+]+" placeholder="Contact Number" required> -->
				<input type="text" class="col-6 form-input" name="contact[info]" id="email" pattern="[a-zA-Z0-9@.]+" placeholder="Email Address" required>
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
						
						<select class="form-select col-3" name="role[value]" id="role" required>
							<option value=></option>
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
						
						<select class="form-select  col-3 form-select" name="status[type]" id="status" required>
							<option value=></option>
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

<?php include 'defpass.php';?>
		<!-- BUTTONS BIG-->
		<br/>
		<fieldset class="hide-md">

			<div class="columns container">
				<div class="column col-3 ">

				</div>
				<div class="column col-3">
					<!-- <input class="btn btn-success btn-lg text-bold "type="submit" name=""> -->
					<a class="btn btn-success btn-lg text-bold" id="addusrbtn" onclick=" sendtoServer();">Register</a>
				</div>
				<div class="column col-3">
					<button class="btn btn-error btn-lg text-bold">Cancel</button>
				</div>
				<div class="column col-3 ">

				</div>
			</div>
		</fieldset>
	</form>
</div>
