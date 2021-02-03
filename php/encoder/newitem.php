<div class="container p-2 " style="margin-top: 2vh">
	<form id="newStock">
		
<div class="text-center text-bold bg-primary h6" style="padding: 10px;"> ENCODE NEW STOCKS</div><br/>



<div class="columns">
	
	<div class="column col-6 col-xs-12">
		<fieldset>
					<legend class="text-gray p-2">Medicine Info</legend>
			<span class="text-dark text-bold  mx-2 text-uppercase text-center">Generic Name<sup class="text-error">*</sup></span>
			<div class="input-group container">
				
	
				<input type="text" class="col-3 form-input text-bold text-center" name="generic" id="" pattern="[a-zA-Z0-9\s]+"placeholder="Generic name" style="font-size:15px" required>
				
			</div>
		</fieldset>
		<fieldset>
			<span class="text-dark text-bold  mx-2 text-uppercase text-center">Common Name<sup class="text-error">*</sup></span>
			<div class="input-group container">
				
		
				<input type="text" class="col-3 form-input text-bold text-center" name="common" id="" pattern="[a-zA-Z0-9\s]+"placeholder="Common Name" style="font-size:15px" required>
				
			</div>
		</fieldset>
		<fieldset>
			<span class="text-dark text-bold mx-2 text-uppercase text-center">Manufacturer<sup class="text-error">*</sup></span>
			<div class="input-group container">
				
				
		
				<input type="text" class="col-3 form-input text-bold text-center" name="manufacturer" id="" pattern="[a-zA-Z0-9\s]+"placeholder="Manufacturer" style="font-size:15px" required>
				
			</div>
		</fieldset>
				<fieldset>
			<span class="text-dark text-bold mx-2 text-uppercase text-center">Description</span>
			<div class="input-group container">
				
	
				<textarea type="text" class="col-3 form-input text-bold text-center" name="description" id="" pattern="[a-zA-Z0-9\s]+"placeholder="" style="font-size:15px"></textarea>
			</div>
		</fieldset>
			<fieldset>
			<span class="text-dark text-bold mx-2 text-uppercase text-center">Form<sup class="text-error">*</sup></span>
			<div class="input-group container">
				<select type="text" class="col-3 form-input text-bold text-center"  id="" style="font-size:15px" name = "mform" required>
					<option value=""></option>
					<option value="1">Capsule</option>
					<option value="2">Syrup</option>
					<option value="3">Tablet</option>
					<option value="4">Caplet</option>
				</select>
			</div>
		</fieldset>
<fieldset>
			
			<span class="text-dark text-bold mx-2 text-uppercase text-center">Expiration<sup class="text-error">*</sup></span>
			<div class="input-group container">
				
				<input type="date" class="col-3 form-input text-bold text-center" name="expiry" id="" pattern=""placeholder="" style="font-size:15px" required>
			</div>
		</fieldset>


	</div>
	<div class="column col-6 col-xs-12">
<fieldset>
	<legend class="text-gray p-2">Delivery Info</legend>
			<span class="text-dark text-bold mx-2 text-uppercase text-center">Remarks</span>
			<div class="input-group container">
				
		
				<textarea type="text" class="col-3 form-input text-bold text-center" name="remarks" id="fname" pattern="[a-zA-Z0-9\s]+"placeholder="" style="font-size:15px" ></textarea>
				<input type="hidden" name="author" value="123">
			</div>
		</fieldset>
		<fieldset>
			<span class="text-dark text-bold mx-2 text-uppercase text-center">Quantity<sup class="text-error">*</sup></span>
			<div class="input-group container">
				
		
				<input type="number" class="col-3 form-input text-bold text-center" name="quantity" id="" pattern="[0-9]+"placeholder="" style="font-size:15px" required>
				<input type="hidden" name="author" value="123">
			</div>
		</fieldset>
<fieldset>
	<legend class="text-gray p-2">System Generated Barcode</legend>
	<!-- <a class="btn btn-primary p-centered" id="generateBAR" onclick="generateUniqueBar()">Generate</a> -->
	<div class="container columns" style="height:100px">
<div class="column col-3 hide-md"></div>
<div class="column col-8 col-md-12" id="barcode-area">
</div>
<div class="column col-2 hide-md"></div>

		</div>
			
		</fieldset>

	


	<fieldset>
	<div class="container columns" style="margin-left: 20px;">
<div class="column col-4 hide-md"></div>
<div class="column col-4 col-md-12" style="height: 50px;"id="barcode-area">



</div>
<div class="column col-4 hide-md"></div>

		</div>
			
		</fieldset>	


		

		
	</div>


</div>

		<br/>
		<fieldset class="">

			<div class="columns container">
				<div class="column col-3 hide-md">

				</div>
				<div class="column col-3">
					<a class="btn btn-success btn-lg text-bold" id="addstk" onclick ="add_to_stock()"  name="">Submit</a>
				
				</div>
				<div class="column col-3">
					<a href="" class="btn btn-error btn-lg text-bold">CANCEL</a>
				</div>
				<div class="column col-3 hide-md ">

				</div>
			</div>
		</fieldset>






				
	</form>
</div>