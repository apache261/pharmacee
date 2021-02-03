		
	<form style="margin-top: 2vh; background-color: #fff">
<div class="columns">
	
	<div class="column col-6 col-xs-12">
		<fieldset>
			<span class=" mx-2 text-uppercase  text-bold text-center" style="font-size: 12px">Generic Name<sup class="text-error">*</sup></span>
			<div class="input-group container">
				<input type="text" class="col-3 form-input text-bold text-center" name="generic" id="" pattern="[a-zA-Z0-9\s]+"placeholder="Generic name" style="font-size:15px" required>
			</div>
		</fieldset>
		<fieldset>
			<span class="text-dark text-bold  mx-2 text-uppercase text-center"style="font-size: 12px">Common Name<sup class="text-error">*</sup></span>
			<div class="input-group container">
				<input type="text" class="col-3 form-input text-bold text-center" name="common" id="" pattern="[a-zA-Z0-9\s]+"placeholder="Common Name" style="font-size:15px" required>
				
			</div>
		</fieldset>
		<fieldset>
			<span class="text-dark text-bold mx-2 text-uppercase text-center"style="font-size: 12px">Manufacturer<sup class="text-error">*</sup></span>
			<div class="input-group container">

				<input type="text" class="col-3 form-input text-bold text-center" name="manufacturer" id="" pattern="[a-zA-Z0-9\s]+"placeholder="Manufacturer" style="font-size:15px" required>
				
			</div>
		</fieldset>
				<fieldset>
			<span class="text-dark text-bold mx-2 text-uppercase text-center"style="font-size: 12px">Description<sup class="text-error">*</sup></span>
			<div class="input-group container">

				<textarea type="text" class="col-3 form-input text-bold text-center" name="description" id="" pattern="[a-zA-Z0-9\s]+"placeholder="" style="font-size:15px" required></textarea>
			</div>
		</fieldset>



	</div>
	<div class="column col-6 col-xs-12">
					<fieldset>
			<span class="text-dark text-bold mx-2 text-uppercase text-center"style="font-size: 12px">Form<sup class="text-error">*</sup></span>
			<div class="input-group container">
				<select type="text" class="col-3 form-input text-bold text-center"  id="" style="font-size:15px" name = "mform" required>
					<option value="0">Select Form</option>
					<option value="1">Capsule</option>
					<option value="2">Syrup</option>
					<option value="3">Tablet</option>
					<option value="4">Caplet</option>
				</select>
			</div>
		</fieldset>

		<fieldset>
			
			<span class="text-dark text-bold mx-2 text-uppercase text-center"style="font-size: 12px">Expiration<sup class="text-error">*</sup></span>
			<div class="input-group container">

				<input type="date" class="col-3 form-input text-bold text-center" name="expiry" id="" pattern=""placeholder="" style="font-size:15px" required>
			</div>
		</fieldset>
<fieldset>
			<span class="text-dark text-bold mx-2 text-uppercase text-center"style="font-size: 12px">Remarks<sup class="text-error">*</sup></span>
			<div class="input-group container">
				<textarea type="text" class="col-3 form-input text-bold text-center" name="remarks" id="fname" pattern="[a-zA-Z0-9\s]+"placeholder="" style="font-size:15px" required></textarea>
				<input type="hidden" name="author" value="123">
			</div>
		</fieldset>
		
	</div>
</div>			
	</form>
</div>
