<?php include 'config.php'?>

<script type="text/javascript">
	function interpretMedForm(val){
		if(val==1){
			return "Capsule";
		}
		if(val==2){
			return "Syrup";
		}
		if(val==3){
			return "Tablet";
		}if(val==4){
			return "Caplet";
		}
	}
	function interpretGender(val){

		if(val==1){
			return "Female";
		}
		if(val == 2){
			return "LGBTQ+";
		}
		if(val==3){
			return "Male";
		}
		return "notset";
	}
	function interpretEmpStatus(val){

		if(val==1){
			return `<label class="label label-success">Trainee</label>`;
		}
		if(val == 2){
			return `<label class="label label-secondary">Part Time</label>`;
		}
		if(val==3){
			return `<label class="label label-primary">Regular</label>`;
		}
		return "notset";
	}

	function interpretRole(val){
		if(val==1){
			return `<label class="label label-success">Pharmacist</label>`;
		}
		if(val == 2){
			return `<label class="label label-warning">Encoder</label>`;
		}
		if(val==3){
			return `<label class="label label-primary">Manager</label>`;
		}
		if(val==4){
			return `<label class="label label-dark">Admin</label>`;
		}
		return `<label class="label">unknown </label>`;
	}
	function interpretActiveEmp(val){

		if(val==1){
			return `<h6><i class="fas fa-user-check text-success"></i></h6>`;
		}

		return `<h6><i class="fas fa-user-times text-error"></i></h6>`;
	}













	function notif(messagee, status){


		
		if(status == 0){
			alertify.success(messagee);
		}
		if(status ==1){
			alertify.error(messagee);
		}
		if(status == 3){
			alertify.warning(messagee); 
		}

	}

	function parseDataField() {
		var obj = $('#addNew').serializeJSON();
		var jsonString = JSON.stringify(obj);

	// document.getElementById('message').innerText = jsonString;
	return jsonString;
}








function validateUserField(){
	//validate Name
	


}







function sendtoServer(){
	validateUserField();
	$("#addusrbtn").addClass("loading");
	alertify.set('notifier','position', 'top-right');
	$.ajax({
		url:'<?php echo $api_path;?>api/actions/admin/addnew.php',
		type:"POST",
		dataType:'json',
		contentType:'application/json',
		data:parseDataField(),
		error: function(xhr, status, error){
					// var errorMessage = xhr.responseJSON
					// alertify.error(errorMessage.Message);
					// $("#addusrbtn").removeClass("loading");
				},
		success:function (response) {
			

			if(response.Success == 1){
				alertify.success(response.Message);
				$('#addNew')[0].reset();
			}else{
			alertify.error(response.Message);
		}
			$("#addusrbtn").removeClass("loading");
		},
		statusCode: {

		},
		

	});
}









var adminTableList;
function getIDValue(value){

	return `
	<select class="form-select bg-gray" data-item-user="`+value+`" id ="userAction" onchange="getAction(this);">
	<option value="0" class="">Actions</option>
	<option value="1">Update</option>
	<option value="2" >ResetPass</option>
	<option value="3">Unemploy</option>
	</select>`;
}


function fetchTrainee($typee,$value) {
	var ac = function(cell, formatterParams, onRendered){

		return `<label>`+cell.getValue()+`</label>`;

	}
	loadingArea();
	setTimeout(function(){
		$.ajax({
			url:'<?php echo $api_path;?>api/actions/admin/viewuser.php?limit=999&offset=0&active=999&type='+$typee+'&uid=' + $value,
			type:'get',
			dataType:'json',
			error:function(){nothingWasHer();},
			success: function (result){
				resultTablee();
				adminTableList = new Tabulator("#result_table", {
					height:"350px",
					layout:"fitColumns",
					data:result,
					placeholder:"No Data Set",
					pagination:"local",
					paginationSize:6,
				// selectable:1,
				index:"EmployeeID",
				paginationSizeSelector:[3, 6, 8, 10],
				movableColumns:true,
				columns:[
				{title:"Action",field:"EmployeeID", formatter:function(cell, formatterParams){return getIDValue(cell.getValue());}, frozen:true,width:100, hozAlign:"left", },
				{title:"ID", field:"EmployeeID", width:150,frozen:true,sorter:"number",hozAlign:"left"},
				{title:"FirstName", field:"FirstName", width:100,sorter:"string",hozAlign:"left"},
				{title:"LastName", field:"LastName", width:100,sorter:"string",hozAlign:"left"},
				{title:"MiddleName", field:"MiddleName",width:100,sorter:"string",hozAlign:"left"},
				{title:"Gender", field:"Gender",sorter:"number",width:100,hozAlign:"left",formatter:function(cell, formatterParams){return interpretGender(cell.getValue());}},
				{title:"Birthday", field:"Birthdate", sorter:"number",width:150,hozAlign:"left",},
				{title:"Description", field:"status",sorter:"number",width:150,hozAlign:"left",formatter:function(cell, formatterParams){return interpretEmpStatus(cell.getValue());}},
				{title:"Employed", field:"Active", sorter:"number",hozAlign:"center",width:100,formatter:function(cell, formatterParams){return interpretActiveEmp(cell.getValue());}},
				{title:"Role", field:"RoleValue", sorter:"number",hozAlign:"center",width:100,formatter:function(cell, formatterParams){return interpretRole(cell.getValue());}},
				{field:"Street",visible:false},
				{field:"Barangay",visible:false},
				{field:"City",visible:false},
				{field:"Province",visible:false},
				{field:"ZipCode",visible:false},
				{field:"contactinformation",visible:false},
				],


			});

			},
			statusCode: {
			// 401: function () { notif('An Error Occured',1); },
			// 500: function () { notif('An Error Occured',1); },
			// 405: function () { notif('An Error Occured',1); },
		},
	});
	},300);



}
















$(document).ready(function(){
	var newdiv =`<?php include 'resultTable.php';?>`;

	$("#viewTrainee").click(function(){   
		$('#content-area').html(newdiv);

		fetchTrainee();
		// fetchEmployee();
	});
});

$(document).ready(function(){
	var newdiv =`<?php include 'addNew.php';?>`;
	console.log("clicked");
	$("#adduserbtn").click(function(){
		$('#title_area').html('New User');   
		$('#content-area').html(newdiv);
	});
});


$(document).ready(function(){
	$( "#kk" ).keyup(function() {
		var value = $( this ).val();
		var newdiv =`<?php include 'resultTable.php';?>`;
		if((value.length <= 1)){
			newdiv =`<?php include 'addNew.php';?>`;
			$('#content-area').html(newdiv);
		}else{
			loadingArea();
			setTimeout(function(){adminSearchBar(value);},500);
		}
		
	});
});





function nothingWasHer(){
	$('#content-area').html(`<?php include 'nothing.php';?>`);
}
function loadingArea(){
	$('#content-area').html(`<?php include 'loading.php';?>`);
}
function resultTablee(){
	$('#content-area').html(`<?php include 'resultTable.php';?>`);
}



function useAdminSearchBar(ff){
	if(!(ff.value.length < 2)){
		adminSearchBar(ff.value);
		return;
	}
	
}
//Find User
$(document).ready(function(){
	// $('#content-area').html('');
	var frm = `<div class="has-icon-left-right " style="margin-top:3vh; margin-bottom:3vh; margin-left:25%;margin-right:25%">
	<input type="text" class="form-input" id ="kk" onkeyup="useAdminSearchBar(this)" placeholder="Type here" style="height:6vh;border-radius:20px;padding-left:50px; name="ds">
	<i class="form-icon-left fas fa-search text-gray h6 "></i>
	</div>`

	$("#srchuserbtn").click(function(){
		$('#title_area').html(frm);
		$('#content-area').html('');
	});
});

//Pharma
$(document).ready(function(){
	$("#pharma").click(function(){
		$('#title_area').html('Pharmacist List');
		fetchTrainee("4","1");
	});
});
//Encoder 
$(document).ready(function(){
	$("#encoder").click(function(){
		$('#title_area').html('Encoder List');
		fetchTrainee("4","2");
	});
});
//Manager
$(document).ready(function(){
	$("#manager").click(function(){
		$('#title_area').html('Manager List');
		fetchTrainee("4","3");
	});
});
//Admin
function showAdmin(){
	$('#title_area').html('Admin List');
	fetchTrainee("4","4");
}
// $(document).ready(function(){
// 	$("#admin").click(function(){
// 		nothingWasHer();   
// 		fetchTrainee("4","4");
// 	});
// });
//Employed
$(document).ready(function(){
	$("#employed").click(function(){
		$('#title_area').html('Employed List');
		fetchTrainee("3","1");
	});
});
//NOT Employed
$(document).ready(function(){
	$("#notemployed").click(function(){
		$('#title_area').html('Unemployed List');
		fetchTrainee("3","0");
	});
});
//Trainee
$(document).ready(function(){
	$("#train").click(function(){ 
		$('#title_area').html('Trainee List');
		fetchTrainee("2","1");
	});
});
//parttime
$(document).ready(function(){
	$("#parttime").click(function(){
		$('#title_area').html('Part Time Employee List');
		fetchTrainee("2","2");
	});
});
//Regular
$(document).ready(function(){
	$("#regular").click(function(){ 
		$('#title_area').text('Regular Employee List');
		fetchTrainee("2","3");
	});
});


function postServer(jsondata,endpoint,messagesuc,messagefail){
	
	setTimeout(function(){
		$.ajax({
			url:endpoint,
			type:"post",
			dataType:'json',
			contentType:'application/json',
			data:jsondata,
			success:function (response) {

			// showAdmin();
		},
		statusCode: {

			200: function () { notif(messagesuc,0); },
			500: function () { notif(messagefail,1); },
		},
		

	});
	},1000);
}

function parseField(id) {
	var obj = $(id).serializeJSON();
	var jsonString = JSON.stringify(obj);
	// document.getElementById('message').innerText = jsonString;
	return jsonString;
}

// reset Password
function resetPass(uid){
	var frm = `<h4> Reset Password of ` + uid + `</h4>`;
	var values = `<form id="resetpassfrm">
	<input type="hidden" name="reset[owner]" value="`+uid+`">
	<input type="hidden" name="reset[question]" value="">
	<input type="hidden" name="reset[password]" value="">
	<input type="hidden" name="reset[answer]" value="">
	<input type="hidden" name="reset[active]" value="1">
	</form>`;
	
	alertify.confirm('Reset Password',frm+values, function(){ 
		alertify.set('notifier','position', 'top-right');

		var data = parseField('#resetpassfrm');
		$("#resetpassfrm")[0].reset();
		postServer(data,'<?php echo $api_path;?>api/actions/admin/reset.php','Password Reset Successfully','User is not Employed');

	}
	, function(){ alertify.error('Cancel')}).set('labels', {ok:'Reset', cancel:'Cancel'}).set({transition:'zoom'});

	

}

// UN EMPLOY
function unemploy(uid){
	var frm = `<h4> Mark as resign ` + uid + `</h4>`;
	var values = `<form id="resetpassfrm">
	<input type="hidden" name="unemp[uid]" value="`+uid+`">
	</form>`;
	
	alertify.confirm('Mark as Resign',frm+values, function(){ 
		alertify.set('notifier','position', 'top-right');
		var data = parseField('#resetpassfrm');
		postServer(data,'<?php echo $api_path;?>api/actions/admin/unregister.php','Success','Unemployed Already');
	}
	, function(){ alertify.error('Cancel')}).set('labels', {ok:'Resign', cancel:'Cancel'}).set({transition:'zoom'});

}









function getAction(data){
	var uid  = data.getAttribute("data-item-user");
	// var conceptName = $('#userAction').find(":selected").text();
	var selected= $(data).children("option:selected").val();
	if(selected == 1){
		updateUserPersonalInfo(uid);
		
		return;
	}
	if(selected == 2){
		resetPass(uid);
		personTobeUpdateID = uid;
	}else if(selected == 3){
		unemploy(uid);
		personTobeUpdateID = uid;
	}
	return;

}


function setUserDropDown(gender,status, role){
	$('#gender2').val(gender);
	$('#status2').val(status);
	$('#role2').val(role);
}

function updateUserPersonalInfo(uidd){
	var roww = 	adminTableList.getRow(uidd);
	var data = roww.getData();
	alertify.set('notifier','position', 'top-right');
	var empID = data.EmployeeID;
	var fName = data.FirstName;
	var mName = data.MiddleName;
	var lName = data.LastName;
	var gender = data.Gender;
	var birthday = data.Birthdate;
	var role = data.RoleValue;
	// var id = value;
	setUserDropDown(gender,data.status, role);
	$("#userrrID").val(empID);
	$("#fname2").val(fName);
	$("#lname2").val(lName);
	$("#mname2").val(mName);
	$("#bday2").val(birthday);

	$("#strt2").val(data.Street);
	$("#brgy2").val(data.Barangay);
	$("#city2").val(data.City);
	$("#prov2").val(data.Province);
	$("#zip2").val(data.ZipCode);
	$("#bday2").val(birthday);
	$("#email2").val(data.contactinformation);
	openUpdateModal();
}

function sendUpdatedEmployeeInfo(){
	$("#updtusrbtn").addClass("loading");
	var jsondata = parseField("#UpdateUserFrm");
	$.ajax({
		url:'<?php echo $api_path;?>api/actions/admin/updateinfo.php',
		type:"POST",
		dataType:'json',
		contentType:'application/json',
		data:jsondata,
		error: function(xhr, status, error){
				// var errorMessage = xhr.responseJSON;
				// console.log(errorMessage);
				// $("#addstk").removeClass("loading");
				$("#updtusrbtn").removeClass("loading");
			},
			success:function (response) {
				console.log("dd");
				if(response.Success == 1){
					alertify.success(response.Message);
					$("#UpdateUserFrm")[0].reset();
					closeUpdateModal();
					$("#updtusrbtn").removeClass("loading");
				}else{
					alertify.error(response.Message);
					$("#updtusrbtn").removeClass("loading");
				}
				
			},
		});
}











function adminSearchBar($uid) {
	var ac = function(cell, formatterParams, onRendered){

		return `<label>`+cell.getValue()+`</label>`;

	}
	loadingArea();
	$.ajax({
		url:'<?php echo $api_path;?>api/actions/admin/viewuser.php?limit=999&offset=0&active=999&type=1&uid='+ $uid,
		type:'get',
		dataType:'json',
		error:function(){nothingWasHer()},
		success: function (result){
			resultTablee();
			adminTableList = new Tabulator("#result_table", {
				height:"350px",
				layout:"fitDataStretch",
				data:result,
				placeholder:"No Data Set",
				pagination:"local",
				paginationSize:6,
				// selectable:1,
				index:"EmployeeID",
				paginationSizeSelector:[3, 6, 8, 10],
				movableColumns:true,
				columns:[
				{title:"Action",field:"EmployeeID", formatter:function(cell, formatterParams){return getIDValue(cell.getValue());}, frozen:true,width:100, hozAlign:"center", },
				{title:"ID", field:"EmployeeID", frozen:true,sorter:"number", width:100,hozAlign:"center"},
				{title:"FirstName", field:"FirstName",frozen:true, sorter:"string", width:100,hozAlign:"center"},
				{title:"LastName", field:"LastName", frozen:true,sorter:"string", width:100,hozAlign:"center"},
				{title:"MiddleName", field:"MiddleName", frozen:true,sorter:"string",width:100,hozAlign:"center"},
				{title:"Gender", field:"Gender",sorter:"number",width:100,hozAlign:"center",formatter:function(cell, formatterParams){return interpretGender(cell.getValue());}},
				
				{title:"Description", field:"status",sorter:"number",hozAlign:"center",formatter:function(cell, formatterParams){return interpretEmpStatus(cell.getValue());}},
				{title:"Active", field:"Active", sorter:"number",hozAlign:"center",formatter:function(cell, formatterParams){return interpretActiveEmp(cell.getValue());}},
				{title:"Role", field:"RoleValue", sorter:"number",hozAlign:"center",formatter:function(cell, formatterParams){return interpretRole(cell.getValue());}},
				{title:"Birthday", field:"Birthdate", sorter:"number",hozAlign:"center",},
				{field:"Street",visible:false},
				{field:"Barangay",visible:false},
				{field:"City",visible:false},
				{field:"Province",visible:false},
				{field:"ZipCode",visible:false},
				{field:"contactinformation",visible:false},
				],


			});

		},
		statusCode: {
			// 401: function () { notif('An Error Occured',1); },
			// 500: function () { notif('An Error Occured',1); },
			// 405: function () { notif('An Error Occured',1); },
		},
	});
}
















// GET NEW ITEM ID
function fetchNewItemId($uid) {
	$.ajax({
		url:'<?php echo $api_path;?>api/actions/extra/itemid.php',
		type:'get',
		dataType:'json',
		success: function (result){
			generateBarcode(result.itemid);
		},
		

		statusCode: {
			// 401: function () { notif('An Error Occured',1); },
			// 500: function () { notif('An Error Occured',1); },
			// 405: function () { notif('An Error Occured',1); },

		},
	});
}


//GENERATE BARCODE
function generateBarcode(data){
	var barr = `<svg class="barcode"
	jsbarcode-value=`+data+`
	jsbarcode-textmargin="0"
	jsbarcode-background="#f7f8f9"
	jsbarcode-marginLeft: 0,
	jsbarcode-marginTop: 0,
	jsbarcode-fontoptions="bold" class="text-center" style>
	</svg>
	<input type="hidden" name="uid" value="`+data+`">`;

	$('#barcode-area').html(barr);
	JsBarcode(".barcode").init();
}


//LISTENER FOR GENERATING BARCODE
function generateUniqueBar(){
	fetchNewItemId();
}
//Read Barcode
// @camContainerID, video container
// @sheight, height of container
// @swidth, width of container,
var _scannerIsRunning = false;
function initScanner(camContainerID, swidth, sheight, ) {
	Quagga.init({
		frequency:4,
		inputStream: {
			name: "Live",
			type: "LiveStream",
			target: document.querySelector(camContainerID),
			constraints: {
				width: swidth,
				height: sheight,
				facingMode: "environment"
			},
		},
		decoder: {
			readers: [
			"code_128_reader",

			],
			debug: {
				showCanvas: true,
				showPatches: true,
				showFoundPatches: true,
				showSkeleton: true,
				showLabels: true,
				showPatchLabels: true,
				showRemainingPatchLabels: true,
				boxFromPatches: {
					showTransformed: true,
					showTransformedBox: true,
					showBB: true
				}
			}
			
		},

	}, function (err) {
		if (err) {
			console.log(err);
			return
		}

		console.log("Initialization finished. Ready to start");
		Quagga.start();

                // Set flag to is running
                _scannerIsRunning = true;
            });
	// Quagga.onProcessed(function (result) {
	// 	var drawingCtx = Quagga.canvas.ctx.overlay,
	// 	drawingCanvas = Quagga.canvas.dom.overlay;

	// 	if (result) {
	// 		if (result.boxes) {
	// 			drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.getAttribute("width")), parseInt(drawingCanvas.getAttribute("height")));
	// 			result.boxes.filter(function (box) {
	// 				return box !== result.box;
	// 			}).forEach(function (box) {
	// 				Quagga.ImageDebug.drawPath(box, { x: 0, y: 1 }, drawingCtx, { color: "green", lineWidth: 2 });
	// 			});
	// 		}

	// 		if (result.box) {
	// 			Quagga.ImageDebug.drawPath(result.box, { x: 0, y: 1 }, drawingCtx, { color: "#00F", lineWidth: 2 });
	// 		}

	// 		if (result.codeResult && result.codeResult.code) {
	// 			Quagga.ImageDebug.drawPath(result.line, { x: 'x', y: 'y' }, drawingCtx, { color: 'red', lineWidth: 3 });
	// 		}
	// 	}
	// });
	//if Detected
	Quagga.onDetected(function (result) {
		

		if(lastDetect !== result.codeResult.code){
		lastDetect = result.codeResult.code;
		console.log(result.codeResult.code);
		var newdiv =` <div class="column col-12" id="pharmaView"></div>`;
		// meddlist.clearData();
		$('#content_area').html(newdiv);
		pharmaBrowse(result.codeResult.code,1);
	}
		
	});
}
 //toggle Scanner
 function startScanner(id,width,height){
 	initScanner(id,width,height);
 }

 function stopScanner(){
 	console.log("Scanner destroyed");
 	if (_scannerIsRunning) {
 		Quagga.stop();
 		 _scannerIsRunning = false;
 	}
 }



//ENCODER SCANNER
var lastDetect = 0;
var _encoderScannerIsRunning = false;
function initEncoderItemScanner(camContainerID, swidth, sheight, ) {

	Quagga.init({
		frequency: 10,
		inputStream: {
			name: "Live",
			type: "LiveStream",
			target: document.querySelector(camContainerID),
			constraints: {
				width: swidth,
				height: sheight,
				facingMode: "environment"
			},
		},
		decoder: {
			readers: [
			"code_128_reader",

			],
			debug: {
				showCanvas: false,
				showPatches: true,
				showFoundPatches: true,
				showSkeleton: true,
				showLabels: true,
				showPatchLabels: true,
				showRemainingPatchLabels: true,
				boxFromPatches: {
					showTransformed: true,
					showTransformedBox: true,
					showBB: true
				}
			}
			
		},

	}, function (err) {
		if (err) {
			console.log(err);
			return
		}

		console.log("Initialization finished. Ready to start");
		Quagga.start();

                // Set flag to is running
                _encoderScannerIsRunning = true;
            });
	//if Detected
	Quagga.onDetected(function (result) {
		
		if(lastDetect != result.codeResult.code){
			stopEncoderScanner();
			lastDetect =result.codeResult.code; 
		console.log(lastDetect);
		alertify.confirm().destroy();
		searchItems_scanner(result.codeResult.code);
		console.log(result.codeResult.code);

		}
		
	});
}
 //toggle Scanner
 function startEncoderScanner(id,width,height){
 	stopEncoderScanner();
 	initEncoderItemScanner(id,width,height);
 }

 function stopEncoderScanner(){
 	
 	if (_encoderScannerIsRunning) {
 		_encoderScannerIsRunning = false;
 		console.log("Scanner destroyed");
 		Quagga.stop();
 	}
 }

function scannerEncoderModal(){
	alertify.set('notifier','position', 'top-right');
	var frm = 	`<div id="scannerArea" style="margin-top:3vh; margin-left:18%"></div>`;

	alertify.confirm('Scan Item',frm, function(){ 

		alertify.confirm().destroy();
		// refetchEncoderItem(encoderItemSearch);


	}
	, function(){ 

		alertify.confirm().destroy(); 
		stopEncoderScanner();
		alertify.error('Cancelled')})
	
	.set({transition:'zoom'})
	.set('resizable',true)
	.set('frameless', true)
	.resizeTo('30%','30%')
	.set({onfocus:function(){ 	startEncoderScanner('#scannerArea',400,200);}});
}






//SEND NEW ITEM TO SERVER
function sendNewItem(jsondata,endpoint){
	alertify.set('notifier','position', 'top-right');
	$.ajax({
		url:endpoint,
		type:"post",
		dataType:'json',
		contentType:'application/json',
		data:jsondata,

		success:function (response) {
			var newData = response.Message;
			// $('#message').html(response.msg);
		},
		statusCode: {
			200: function () { notif('Success',0); },
			500: function () { notif('Failed',1); },
		},
		

	});
}

var encoderItemSearch ="";
var encodertableResult;
var realtime;
function clearEncoderInterval(){
	if(realtime != null){
		clearTimeout(realtime);
	}
}
//LISTENER for SUBMIT
function add_to_stock(){
	
	clearEncoderInterval();
	var jsondata = parseField('#newStock');
	var endpoint = '<?php echo $api_path;?>api/actions/encoder/additem.php';
	$("#addstk").addClass("loading");
alertify.set('notifier','position', 'top-right');
		$.ajax({
			url:endpoint,
			type:"POST",
			dataType:'json',
			contentType:'application/json',
			data:jsondata,
			error: function(xhr, status, error){
				// var errorMessage = xhr.responseJSON
				
				// $("#addstk").removeClass("loading");
			},
			success:function (response) {
				if(response.Success == 1){
					$("#newStock")[0].reset();
					alertify.success(response.Message);
					generateUniqueBar();
					$("#addstk").removeClass("loading");
				}else{
					alertify.error(response.Message);
				}
				$("#addstk").removeClass("loading");
				
			},
		});
}


function add_iteam(){
	clearEncoderInterval();
	$('#search_area').html('');
	$('#title_area').html('');
	var newdiv =`<?php include 'encoder/newitem.php';?>`;
	$('#content-area').html(newdiv);
	generateUniqueBar();
}

function showEncoderSearchBar(){
	$('#content-area').html('');
lastDetect = 0;
	
	var frm = `<div class="has-icon-left-right " style="margin-top:3vh; margin-bottom:3vh; margin-left:25%;margin-right:25%">
	<input type="text" class="form-input" id ="search_item_encoder" onkeyup="searchItemss(this)" placeholder="Type here" style="height:6vh;border-radius:20px;padding-left:50px; name="ds">
	<i class="form-icon-left fas fa-search text-gray h6 "></i>
	<i class="form-icon-right h4 fas fa-barcode-read text-primary " onclick="scannerEncoderModal()" style="padding-right:40px"></i>


	</div>`
	$('#search_area').html(frm);

}
// <div class="input-group input-inline has-icon-left mx-2 shadow-search" style="width:50%;margin-top: 2vh">
// 	<input class="form-input" type="text" id ="search_item_encoder" placeholder="Type here" onkeyup="searchItemss('search_item_encoder')" style="height:6vh;padding-left:20px">
// 	<i class="form-icon icon fas fa-search h3"></i>
// 	<button class="btn input-group-btn bg-primary" style=" border: none; height: 6vh; width: 60px"><i class="fas fa-search h3"></i></button>
// 	</div>
function searchItemss(isd){
	clearEncoderInterval();
	var value = isd.value;

	var newdiv =` <?php include 'resultTable.php';?>`;
	if((value.length <= 1)){
		newdiv =``;
		// generateUniqueBar();

	}
	encoderItemSearch = value;
	$('#content-area').html(newdiv);
	if((value.length > 1)){

		loadingArea();
		EncoderSearchDelay(value);

	}




}
function searchItems_scanner(value){
	clearEncoderInterval();


	var newdiv =` <?php include 'resultTable.php';?>`;
	if((value.length <= 1)){
		newdiv =``;
		// generateUniqueBar();

	}
	encoderItemSearch = value;
	$('#content-area').html(newdiv);
	if((value.length > 1)){

		loadingArea();
		EncoderSearchDelay(value);
	// 	var endpoint = 'pharmacee/api/actions/encoder/viewitem.php?key='+value;
	// setTimeout(function(){fetchItem(endpoint)},200);

	}




}

// UPDATE ITEM COUNTS
function showDetails(animal) {
  // var animalType = animal.getAttribute("data-animal-type");
  // alert("The " + animal.innerHTML + " is a " + animalType + ".");
  // d="salmon" data-animal-type="fish">Salmon</li>  
}
function addMedQuan(data){
	var itemid = data.getAttribute("data-item-id");
	var frm = `<form id="updateItemCount">
	<fieldset>
			<span class="text-dark text-bold mx-2 text-uppercase text-center">Quantity<sup class="text-error">*</sup></span>
			<div class="input-group container">
				
		
				<input type="number" class="col-3 form-input text-bold text-center" name="update[quantity]" id="" pattern="[0-9]+"placeholder="" style="font-size:15px" required>
				<input type="hidden" name="author" value="123">
			</div>
		</fieldset>
	<fieldset>
			
			<span class="text-dark text-bold mx-2 text-uppercase text-center">Expiration<sup class="text-error">*</sup></span>
			<div class="input-group container">
				
				<input type="date" class="col-3 form-input text-bold text-center" name="update[expiry]" id="" pattern=""placeholder="" style="font-size:15px" required>
			</div>
		</fieldset>
	<input type="hidden" name="update[itemid]" value="`+itemid+`"></form>`;
	alertify.set('notifier','position', 'top-right');
	alertify.confirm('Add Stocks to  '+ itemid + ``,frm, function(){ 

		var jsondata = parseField('#updateItemCount');
		$("#updateItemCount")[0].reset();
		// 
		alertify.set('notifier','position', 'top-right');
		$.ajax({
			url:'<?php echo $api_path;?>api/actions/encoder/updateStock.php',
			type:"post",
			dataType:'json',
			contentType:'application/json',
			data:jsondata,
			error: function(xhr, status, error){
				// var errorMessage = xhr.responseJSON
				// a
			},
			success:function (response) {
				if(response.Success == 1){
				loadingArea();
				refetchEncoderItem(encoderItemSearch);
				alertify.success(response.Message);
				}else{
					alertify.error(response.Message);
				}
				
			},
		});
	}
	, function(){ alertify.error('Cancelled')}).set('labels', {ok:'Add', cancel:'Cancel'}).set({transition:'zoom'});

}


function EncoderSearchDelay($id){
	var endpoint = '<?php echo $api_path;?>api/actions/encoder/viewitem.php?key='+ $id;
	setTimeout(function(){fetchItem(endpoint)},1000);
}


function encoderActionBtn(valcue){
	var me = `<a class="btn btn-link" onclick ="showModifyItem(`+valcue+`)"><i class="fas fa-pen text-success"></i><a class="btn btn-link warning ml-2" onclick ="addMedQuan(this)" data-item-id="`+valcue+`"><i class="fas fa-plus text-error "></i>`;
	return me;
}



function addModifyItemInDOM(){

}
function setMedFormVal(value){
	$('#medicineFrmm').val(value);
// console.log("Loadded");

}
function showModifyItem(value){

	var roww = 	encodertableResult.getRow(value);
	var data = roww.getData();
	// $('#content-area').html(`<div class = "disp"></div>`);
	alertify.set('notifier','position', 'top-right');
	var CommonName = data.CommonName;
	var ItemID = data.ItemID;
	var GenericName = data.GenericName;
	var Manufacturer = data.Manufacturer;
	// var Description = data.Description;
	var Form = data.form;
	var Expiration = data.Expiration;
	// var Remarks = data.Remarks;
	// console.log(value);
	// console.log(data.CommonName);
	var frm = 	`
	<form  id ="updateForm" style="margin-top: 2vh; background-color: #fff">
	<div class="columns">
	<input type="hidden" name = "update[itemID]" value="`+ItemID+`"/>
	<div class="column col-6 col-xs-12">
	<fieldset>
	<span class=" mx-2 text-uppercase  text-bold text-center" style="font-size: 12px">Generic Name<sup class="text-error">*</sup></span>
	<div class="input-group container">
	<input type="text" class="col-3 form-input text-bold text-center" name="update[generic]"  pattern="[a-zA-Z0-9\s]+"placeholder="Generic name" style="font-size:15px" value ="` + GenericName+  `" required>
	</div>
	</fieldset>
	<fieldset>
	<span class="text-dark text-bold  mx-2 text-uppercase text-center"style="font-size: 12px">Common Name<sup class="text-error">*</sup></span>
	<div class="input-group container">
	<input type="text" class="col-3 form-input text-bold text-center" name="update[common]"  pattern="[a-zA-Z0-9\s]+"placeholder="Common Name" style="font-size:15px" value ="` + CommonName+  `"  required>

	</div>
	</fieldset>
	<fieldset>
	<span class="text-dark text-bold mx-2 text-uppercase text-center"style="font-size: 12px">Manufacturer<sup class="text-error">*</sup></span>
	<div class="input-group container">

	<input type="text" class="col-3 form-input text-bold text-center" name="update[manufacturer]"  pattern="[a-zA-Z0-9\s]+"placeholder="Manufacturer" style="font-size:15px" value="`+ Manufacturer +`" required>

	</div>

	</div>
	<div class="column col-6 col-xs-12">
	<fieldset>
	<span class="text-dark text-bold mx-2 text-uppercase text-center"style="font-size: 12px">Form<sup class="text-error">*</sup></span>
	<div class="input-group container">
	<select type="text" class="col-3 form-input text-bold text-center"  id="medicineFrmm" style="font-size:15px" name = "update[form]" required>
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

	<input type="date" class="col-3 form-input text-bold text-center" name="update[expiration]"  pattern=""placeholder="" style="font-size:15px" value ="` + Expiration+  `" required>
	</div>
	</fieldset>

	</div>
	</div>			
	</form>
	</div>`;


	alertify.confirm('Update Info '+ ItemID + ``,frm, function(){ 
		var jsondata = parseField("#updateForm");
		$("#updateForm")[0].reset();
		var endpoint = '<?php echo $api_path;?>api/actions/encoder/update.php';
		
		alertify.confirm().destroy();
		alertify.set('notifier','position', 'top-right');
		$.ajax({
			url:endpoint,
			type:"post",
			dataType:'json',
			contentType:'application/json',
			data:jsondata,
			error: function(xhr, status, error){
				// var errorMessage = xhr.responseJSON
				// alertify.error(errorMessage.Message);
			},
			success:function (response) {
				if(response.Success ==1){
					loadingArea();
					alertify.success(response.Message);
					refetchEncoderItem(encoderItemSearch);
				}else{
					alertify.error(response.Message);
				}
			},
		});

	}
	, function(){
		alertify.confirm().destroy(); 
		alertify.error('Cancelled')})
	.set('labels', {ok:'Update', cancel:'Cancel'})
	.set({transition:'zoom'})
	.set('resizable',true)
	.resizeTo('60%','50%')
	.set({onfocus:function(){ setMedFormVal(Form);}});
}

var rowitemCount = 0;
function fetchItem(endpoint) {
	rowitemCount = 0;
	$.ajax({
		url:endpoint,
		type:'get',
		dataType:'json',
		error:function(){nothingWasHer();},
		success: function (result){
			// resultTablee();
			// nothingWasHer();

			resultTablee();
			encodertableResult = new Tabulator("#result_table", {
				height:"500px",
				layout:"fitDataFill",
				// responsiveLayout:"collapse",
				data:result,
				responsiveLayoutCollapseStartOpen:false,
				placeholder:"No Data Set",
				pagination:"local",
				paginationSize:6,
				index:"ItemID",
				// selectable:1,
				paginationSizeSelector:[3, 6, 8, 10],
				movableColumns:true,
				columns:[
				// {formatter:"responsiveCollapse", width:30, minWidth:30, hozAlign:"left", resizable:false, headerSort:false},

				{title:"Action",field:"ItemID", width:150, frozen:true,formatter:function(cell, formatterParams){return encoderActionBtn(cell.getValue());},  hozAlign:"center", },

				{title:"ID", field:"ItemID", sorter:"number",width:100, hozAlign:"center"},
				{title:"Common Name", field:"CommonName", width:150, sorter:"string", hozAlign:"left"},
				{title:"Generic Name", field:"GenericName",width:150,sorter:"string", hozAlign:"left"},
				{title:"Manufacturer", field:"Manufacturer",width:150, sorter:"string",hozAlign:"left"},
				{title:"Expiration", field:"Expiration" ,sorter:"number",hozAlign:"left"},
				{title:"Form", field:"form",sorter:"number",hozAlign:"center", formatter:function(cell, formatterParams){return interpretMedForm(cell.getValue());}},
				{title:"Stocks", field:"remaining", sorter:"number",hozAlign:"center",formatter:function(cell, formatterParams){return formatStocks(cell.getValue());}},
				],

			});

		},
		

		statusCode: {
			// 401: function () { notif('An Error Occured',1); },
			// 500: function () { alert('') },
			// 405: function () { notif('An Error Occured',1); },

		},
	});
}

function refetchEncoderItem($uid) {
		var endpoint = '<?php echo $api_path;?>api/actions/encoder/viewitem.php?key='+ $uid;
		fetchItem(endpoint);
}


function encoderItemList(){

	$('#search_area').html('');
	$('#title_area').html('');
	$('#content-area').html('');
	clearEncoderInterval();
	$('#search_area').html('Recent List');
	var endpoint = '<?php echo $api_path;?>api/actions/manager/viewItems.php';
	realtime = setTimeout(fetchItem(endpoint),3000);
	

}








// function encoderTable(){
// 	encodertableResult = new Tabulator("#result_table", {
// 				height:"400px",
//     			layout:"fitColus",

// 				placeholder:"No Data Set",
// 				pagination:"local",
// 				paginationSize:6,
// 				// selectable:1,
// 				paginationSizeSelector:[3, 6, 8, 10],
// 				movableColumns:true,
// 				columns:[
// 				 {title:"Action",field:"ItemID",formatter:function(cell, formatterParams){return additionalStocks(cell.getValue());}, frozen:true, hozAlign:"center", },
// 				{title:"ID", field:"ItemID", frozen:true,sorter:"number",width:150, hozAlign:"center"},
// 				{title:"Common Name", frozen:true,field:"CommonName",width:150, sorter:"string", hozAlign:"center"},
// 				{title:"Generic Name", frozen:true,field:"GenericName",width:150,sorter:"string", hozAlign:"center"},
// 				{title:"Manufacturer", field:"Manufacturer",width:150, sorter:"string",hozAlign:"center"},
// 				{title:"Expiration", field:"Expiration" ,width:150,sorter:"number",hozAlign:"center"},
// 				{title:"Form", field:"form",sorter:"number",width:100,hozAlign:"center", formatter:function(cell, formatterParams){return interpretMedForm(cell.getValue());}},
// 				{title:"Stocks", field:"remaining",width:90, sorter:"number",hozAlign:"center", formatter:function(cell, formatterParams){return formatStocks(cell.getValue());}},
// 				],
// });
// }














var meddlist;
var searchKeywordPharma;
// 0 search 1 scanner
var pharmaAction = 0


function pharmaSubtract(data){
	itemid = data.getAttribute("data-item-id");
	var frm = `<form id="subtructItemCount"><input type="number" placeholder="Number to be Deduct" class="form-input"  name="deduct[quantity]" value="" ><input type="hidden" name="deduct[itemid]" value="`+itemid+`"></form>`;
	alertify.set('notifier','position', 'top-right');
	alertify.confirm('Deduct stocks of '+ itemid,frm, function(){ 
		var data = parseField('#subtructItemCount');
		$("#subtructItemCount")[0].reset();
		$.ajax({
			url:'<?php echo $api_path;?>api/actions/pharma/deduct.php',
			type:"POST",
			dataType:'json',
			contentType:'application/json',
			data:data,
			error: function(xhr, status, error){
				// var errorMessage = xhr.responseJSON
				
			},
			success:function (response) {

				if(response.Success == 1){
					alertify.warning('Deduction has made');
					if(pharmaAction == 0){
					pharmaBrowse(searchKeywordPharma);
				}else{
					pharmaBrowse(itemid);
				}
				}
				else{
					alertify.error(response.Message);
				}
				
				
			},
		});
	}
	, function(){ alertify.error('Cancelled'); }
	).set('labels', {ok:'Deduct', cancel:'Cancel'}).set({transition:'zoom'});

}

function pharmaSubtract_Scanner(action,value,data){
	var itemid;
	itemid = value;
	var frm = `<form id="subtructItemCount"><input type="number" placeholder="Number to be Deduct" class="form-input"  name="deduct[quantity]" value="" ><input type="hidden" name="deduct[itemid]" value="`+itemid+`"></form>`;
	alertify.set('notifier','position', 'top-right');

	alertify.confirm('Deduct stocks of '+ itemid,frm, function(){ 
		alertify.warning('Deduction has made'); 
		var data = parseField('#subtructItemCount');
		$("#subtructItemCount")[0].reset();
		$.ajax({
			url:'<?php echo $api_path;?>api/actions/pharma/deduct.php',
			type:"POST",
			dataType:'json',
			contentType:'application/json',
			data:data,
			error: function(xhr, status, error){
				var errorMessage = xhr.responseJSON
				alertify.error(errorMessage.Message);
			},
			success:function (response) {
				alertify.warning('Deduction has made');
				showPharmaScanner();			},
			});
	}
	, function(){ alertify.error('Cancelled'); showPharmaScanner();}
	).set('labels', {ok:'Deduct', cancel:'Cancel'}).set({transition:'zoom'});

}




function showPharmaSearchBar(){
	stopScanner();
	pharmaAction = 0;
	$('#content_area').html('');


	var frm = `<div class="has-icon-left-right " style="margin-top:3vh; margin-bottom:3vh; margin-left:25%;margin-right:25%">
	<input type="text" class="form-input"  id="pharmaSearchField" placeholder="Type here"  onkeyup="pharmaBrowse(this)" style="height:6vh;border-radius:20px; padding-left:50px; name="ds">
	<i class="form-icon-left fas fa-search text-gray h6"></i>

	</div>`;
	// var frm = `<div class="input-group input-inline mx-2 shadow-search" style="width:100%;margin-top: 2vh">
	// <input class="form-input" type="text" id="pharmaSearchField" placeholder="Type here"  onkeyup="pharmaBrowse('pharmaSearchField')"  style="height:6vh">
	// <button class="btn input-group-btn bg-primary" style=" border: none; height: 6vh; width: 60px"><i class="fas fa-search h3"></i></button>
	// </div>`;

	$('#search_area').html(frm);
}
function showPharmaScanner(){
	$('#content_area').html('');
	pharmaAction = 1;
	$('#search_area').html('');
	var newdiv =` <div class="column col-12" id="pharmaView"></div>`;
	$('#content_area').html(newdiv);
	stopScanner();
	startScanner('#search_area', 400,200);
}



function pharmaBrowse(isd){
	var keyword;
	if(pharmaAction == 0){
		keyword=isd.value;
	searchKeywordPharma = isd;
}
	if(pharmaAction == 1){
		
		keyword = isd;
	}
	var newdiv =` <div class="column col-12" id="pharmaView"></div>`;
	if((keyword.length <= 1)){
		newdiv =``;
		meddlist.clearData();
		$('#content_area').html(newdiv);
	}else{
		// nothingWasHer();
		// $('#content-area').html(newdiv);
		$('#content_area').html(newdiv);
		PharmaloadingArea();
			pharmaBrowseMedicine(keyword);
			
			// Quagga.start();
			// startScanner('#search_area', 400,200);
		
	}
}


function PharmanothingWasHer(){
	$('#pharmaView').html(`<?php include 'nothing.php';?>`);
}
function PharmaloadingArea(){
	$('#pharmaView').html(`<?php include 'loading.php';?>`);
}


function subtractStocksBTN(valcue){
	var me = `<a class="btn btn-error mr-2" onclick ="pharmaSubtract(this)" data-item-id="`+valcue+`"><i class="fas fa-edit"></i></a>`;
	return me;
}

function formatStocks(value){
	value = parseInt(value);
	if(value <= 150){
		return `<label class="label label-error text-bold " style="width:100%">`+value+`</label>`;
	}else if(value < 500){
		return `<label class="label label-warning text-bold " style="width:100%">`+value+`</label>`;
	}
	return `<label class="label label-success text-bold " style="width:100%">`+value+`</label>`;


}

function pharmaBrowseMedicine($uid) {

	$.ajax({
		url:'<?php echo $api_path;?>api/actions/pharma/viewitem.php?key='+ $uid,
		type:'get',
		dataType:'json',
		error:function(){PharmanothingWasHer()},
		success: function (result){
			// resultTablee();
			// nothingWasHer();


			meddlist = new Tabulator("#pharmaView", {
				height:"300px",
				layout:"fitColumns",
				// responsiveLayout:"collapse",
				data:result.Results,
				// responsiveLayoutCollapseStartOpen:false,
				placeholder:"No Data Set",
				pagination:"local",
				
				paginationSize:6,
				// selectable:1,
				paginationSizeSelector:[3, 6, 8, 10],
				movableColumns:true,
				columns:[
				// {formatter:"responsiveCollapse", width:30, minWidth:30, hozAlign:"left", resizable:false, headerSort:false},
				{title:"Action",field:"ItemID", width:100,formatter:function(cell, formatterParams){return subtractStocksBTN(cell.getValue());},  hozAlign:"center", },
				{title:"ID", field:"ItemID", sorter:"number", hozAlign:"left"},
				{title:"Common Name", field:"CommonName", width:150,sorter:"string", hozAlign:"left"},
				{title:"Generic Name", field:"GenericName",width:150,sorter:"string", hozAlign:"left"},
				{title:"Manufacturer", field:"Manufacturer", width:150,sorter:"string",hozAlign:"left"},
				{title:"Expiration", field:"Expiration" ,sorter:"number",hozAlign:"left"},
				{title:"Form", field:"form",sorter:"number",hozAlign:"left", formatter:function(cell, formatterParams){return interpretMedForm(cell.getValue());}},
				{title:"Stocks", field:"remaining", sorter:"number",hozAlign:"center", formatter:function(cell, formatterParams){return formatStocks(cell.getValue());}},
				],

			});

		},
		

		statusCode: {
			// 401: function () { notif('An Error Occured',1); },
			// 500: function () { alert('') },
			// 405: function () { notif('An Error Occured',1); },

		},
	});
}



function popViewMedicine(ItemID){
	var popover = `<div class="popover popover-right">
	<button class="btn btn-primary">right popover</button>
	<div class="popover-container">
	<div class="card">
	<div class="card-header">
	...
	</div>
	<div class="card-body">
	...
	</div>
	<div class="card-footer">
	...
	</div>
	</div>
	</div>
	</div>`


}




// Manager
var managerItemList;
var itemMessage = "";
var docbtn = `<a class="btn btn-primary mr-2" style="font-size:25px" onclick="downloadXLS()"><i class="fas fa-file-download"></i></a><a class="btn btn-primary ml-2" style="font-size:25px" onclick="printTable()"><i class="fas fa-print "></i></a>`;
var docbtn2 = `<div class="" style="margin-top: 2vh">
	<a class="btn btn-error" href="javascript:searchExpire(3)"> Show Today</a>
	</div>`;


function formatID(value){
	return `<div class="py-2">`+value+`</div>`;
}
function browseInventory(endpoint) {
	$.ajax({
		url:endpoint,
		type:'get',
		dataType:'json',
		error:function(){managernothingWasHer()},
		success: function (result){
		// resultTablee();
		// nothingWasHer();
		showDocBtn();
		managerItemList = new Tabulator("#managerView", {
			height:"300px",
			layout:"fitDataFill",
			// responsiveLayout:"collapse",
			data:result,
			// responsiveLayoutCollapseStartOpen:false,
			placeholder:"No Data Set",
			pagination:"local",
			paginationSize:6,
			printAsHtml:true,
			printHeader:`<h5 class="text-bold text-center my-2">`+ itemMessage+`</h5>`,
			printFooter:`<small class="my-2">Based on the as data of ` + createCurrentDateFilename() +`</small>`,
			printStyled:true,
			// selectable:1,
			paginationSizeSelector:[3, 6, 8, 10],
			movableColumns:true,
			columns:[
			// {formatter:"responsiveCollapse", width:30, minWidth:30, hozAlign:"left", resizable:false, headerSort:false},
			// {title:"Action",field:"ItemID",formatter:function(cell, formatterParams){return subtractStocksBTN(cell.getValue());},  hozAlign:"left", },
			{title:"ID", field:"ItemID", width:150, frozen:true, formatter:function(cell,formatterParams){return formatID(cell.getValue());},sorter:"number", hozAlign:"left"},
			{title:"Common Name", field:"CommonName",width:150, sorter:"string", hozAlign:"left"},
			{title:"Generic Name", field:"GenericName",width:150,sorter:"string", hozAlign:"left"},
			{title:"Manufacturer", field:"Manufacturer", width:150, sorter:"string",hozAlign:"left"},
			// {title:"Expiration", field:"Expiration" ,width:150,sorter:"date",hozAlign:"left"},
			// {title:"Form", field:"form",sorter:"number",hozAlign:"left", formatter:function(cell, formatterParams){return interpretMedForm(cell.getValue());}},
			{title:"Total In", field:"totalin", sorter:"number", hozAlign:"center"},
			{title:"Total Out", field:"totalout", sorter:"number", hozAlign:"center"},
			{title:"Remaining", field:"remaining", with:150,sorter:"number",hozAlign:"center",formatter:function(cell, formatterParams){return formatStocks(cell.getValue());} },
			],

		});

	},
	

	statusCode: {
		// 401: function () { notif('An Error Occured',1); },
		// 500: function () { alert('') },
		// 405: function () { notif('An Error Occured',1); },

	},
});
}



function getlowStocks(){
	// meddlist.clearData();
	clearDocBtn();
	clearCardInfo();
	clearCardSearch();
	$('#titleTable').html(``);
	var min=100;
	var newdiv =`<div class="column col-12" id="managerView"></div>`;
	var tblTitle ="Low Stocks" ;
	itemMessage = tblTitle;
	// $('#manager-Table-Title').html(tblTitle);
	$('#content_area').html(newdiv);
	managerloadingArea();
	var endpoint = '<?php echo $api_path;?>api/actions/manager/lowstocks.php?min='+ min
	setTimeout(function(){browseInventory(endpoint);},500);
}


function searchExpire(key){
		clearDocBtn();
	clearCardInfo();
	itemMessage = "Items Expired Today";
	// var keyword= document.getElementById(isd).value;
	if(key == 2){

		itemMessage = "To be expire this week";
	}
	if(key == 3){

		itemMessage = "Items Expired Today";
	}

	var newdiv =`<div class="column col-12" id="managerView"></div>`;


	var frm = `<div class="" style="margin-top: 2vh">
	<a class="btn btn-error" href="javascript:searchExpire(2)"> Show for this week</a>
	</div>`;
	if(key == 2){

		frm = `<div class="" style="margin-top: 2vh">
	<a class="btn btn-error" href="javascript:searchExpire(3)"> Show Today</a>
	</div>`;
	}
	clearCardSearch();
	$('#titleTable3').html(frm);
	$('#titleTable').html(itemMessage);
	$('#content_area').html(newdiv);
	managerloadingArea();
$.ajax({
		url:'<?php echo $api_path;?>api/actions/manager/browseExpire.php?action='+key,
		type:'get',
		dataType:'json',
		error:function(){console.log("dfsdf");},
		success: function (result){

		// resultTablee();
		// nothingWasHer();
		showDocBtn();
		managerItemList = new Tabulator("#managerView", {
			height:"300px",
			layout:"fitDataFill",
			// responsiveLayout:"collapse",
			data:result,
			// responsiveLayoutCollapseStartOpen:false,
			placeholder:"No Data Set",
			pagination:"local",
			paginationSize:6,
			printAsHtml:true,
			printHeader:`<h5 class="text-bold text-center my-2">`+ itemMessage+`</h5>`,
			printFooter:`<small class="my-2">Based on the as data of ` + createCurrentDateFilename() +`</small>`,
			printStyled:true,
			// selectable:1,
			paginationSizeSelector:[3, 6, 8, 10],
			movableColumns:true,
			columns:[
			// {formatter:"responsiveCollapse", width:30, minWidth:30, hozAlign:"left", resizable:false, headerSort:false},
			// {title:"Action",field:"ItemID",formatter:function(cell, formatterParams){return subtractStocksBTN(cell.getValue());},  hozAlign:"left", },
			{title:"ID", field:"ItemID", frozen:true,width:150, formatter:function(cell,formatterParams){return formatID(cell.getValue());},sorter:"number", hozAlign:"left"},
			{title:"Expiration", field:"expiration",frozen:true,width:200, sorter:"string", hozAlign:"left"},
			{title:"Affected Quantity", field:"quantity",frozen:true,width:150,sorter:"string", hozAlign:"left"},
			{title:"Common Name", field:"CommonName", width:150, sorter:"string",hozAlign:"left"},
			{title:"Generic Name", field:"GenericName" ,width:150,sorter:"date",hozAlign:"left"},
			// {title:"Form", field:"form",sorter:"number",hozAlign:"left", formatter:function(cell, formatterParams){return interpretMedForm(cell.getValue());}},
			{title:"Received Date", field:"receivedDate", width:150,sorter:"number", hozAlign:"center"}
			],

		});

	},
	

	statusCode: {
		// 401: function () { notif('An Error Occured',1); },
		// 500: function () { alert('') },
		// 405: function () { notif('An Error Occured',1); },

	},
});


}







function showStockCardButton(valcue){

	return `<a class="btn btn-error mr-2" onclick ="getStockCard(this)" data-item-id="`+valcue+`"><i class="fas fa-eye"></i></a>`;
}


function searchStockCard(endpoint) {
	$.ajax({
		url:endpoint,
		type:'get',
		dataType:'json',
		error:function(){managernothingWasHer()},
		success: function (result){
		// resultTablee();
		// nothingWasHer();
		managerItemList = new Tabulator("#managerView", {
			height:"300px",
			layout:"fitColumns",
			// responsiveLayout:"collapse",
			data:result,
			// responsiveLayoutCollapseStartOpen:false,
			placeholder:"No Data Set",
			pagination:"local",
			paginationSize:6,
			index:"ItemID",
			printAsHtml:true,
			printHeader:`<h5 class="text-bold text-center my-2">`+ itemMessage+`</h5>`,
			printStyled:true,
			// selectable:1,
			paginationSizeSelector:[3, 6, 8, 10],
			movableColumns:true,
			columns:[
			// {formatter:"responsiveCollapse", width:30, minWidth:30, hozAlign:"left", resizable:false, headerSort:false},
			// {title:"Action",field:"ItemID",formatter:function(cell, formatterParams){return subtractStocksBTN(cell.getValue());},  hozAlign:"left", },

			{title:"Action",field:"ItemID",sorter:"number",width:100,hozAlign:"center",formatter:function(cell,formatterParams){return showStockCardButton(cell.getValue());}},
			{title:"ID", field:"ItemID", sorter:"number", hozAlign:"left"},
			{title:"Common Name", field:"CommonName", sorter:"string", hozAlign:"left"},
			{title:"Generic Name", field:"GenericName",sorter:"string", hozAlign:"left"},
			],

		});

	},
	

	statusCode: {
		// 401: function () { notif('An Error Occured',1); },
		// 500: function () { alert('') },
		// 405: function () { notif('An Error Occured',1); },

	},
});
}

function interpretReason(reason){
	if(reason ==1 ){
		return "IN";
	}
	if(reason ==2){
		return "SOLD";
	}
	if(reason == 3){
		return "EXPIRED";
	}
	return "unknown";
}
function formatIn(amount){
	if(amount  > 0){
		return `<span class="text-bold text-success">`+amount+`</span>`;
	}
	return "";
}
function formatOut(amount){
	if(amount > 0){
		return `<span class="text-bold text-error">` + amount + `</span>`;
	}
	return "";
}

  // {"ItemID":18125975,"commonName":"Advil ","genericName":"Ibuprofen",

  function loadStockCard(endpoint) {
  	$.ajax({
  		url:endpoint,
  		type:'get',
  		dataType:'json',
  		error:function(){managernothingWasHer()},
  		success: function (result){
		// resultTablee();
		// nothingWasHer();
		showDocBtn();
		showAllCardInfo();
		managerItemList = new Tabulator("#managerView", {
			height:"300px",
			layout:"fitColumns",
			// responsiveLayout:"collapse",
			data:result,
			// responsiveLayoutCollapseStartOpen:false,
			placeholder:"No Data Set",
			pagination:"local",
			paginationSize:6,
			printAsHtml:true,
			printHeader:`
			<div class="columns my-2 " style="font-size:13px">
			<div class="column col-4 text-capitalize">
			<div id="productID" class="column col-12 ">`+cardItemID+`</div>
			<div id="productName" class="column col-12">`+cardCommon+`</div>
			<div id="productGeneric" class="column col-12">`+cardGeneric+`</div>
			</div>
			<div class="column col-4">
			<h5 class="text-bold text-center">`+ itemMessage+`</h5>
			</div>
			<div class="column col-4"></div>
			</div>`,
			printStyled:true,
			printFooter:`<div class="my-2" style="font-size:10px">Printed On ` + createCurrentDateFilename() +`</div>`,
			// selectable:1,
			paginationSizeSelector:[3, 6, 8, 10],
			movableColumns:true,
			columns:[
			// {title:"ItemCode",field:"ItemID",sorter:"number",width:100,hozAlign:"left",formatter:function(cell,formatterParams){return showStockCardButton(cell.getValue());}},
			// {title:"ID", field:"ItemID", sorter:"number", hozAlign:"left"},
			
			{title:"Date", field:"entry",hozAlign:"left"},
			{title:"In", field:"quantityIN",formatter:function(cell,formatterParams){return formatIn(cell.getValue());}, hozAlign:"left"},
			{title:"Out", field:"quantityOut",formatter: function(cell, formatterParams){return formatOut(cell.getValue());},hozAlign:"left"},
			{title:"Reason", field:"reason", formatter:function(cell,formatterParams){return interpretReason(cell.getValue());}, hozAlign:"left"},
			{title:"Balance", field:"balance", hozAlign:"left"},
			],

		});

	},
	

	statusCode: {
		// 401: function () { notif('An Error Occured',1); },
		// 500: function () { alert('') },
		// 405: function () { notif('An Error Occured',1); },

	},
});
  }

// formatter:function(cell, formatterParams){return formatStocks(cell.getValue());}

function managernothingWasHer(){
	$('#managerView').html(`<?php include 'nothing.php';?>`);
}
function managerloadingArea(){
	$('#managerView').html(`<?php include 'loading.php';?>`);
}

function showBrowseSearchBar(){
	clearDocBtn();
	clearCardInfo();
	$('#content_area').html(``);
	// var keyword= document.getElementById(isd).value;
	itemMessage = "Inventory Browser";
	// var newdiv =`<div class="column col-12" id="managerView"></div>`;

	// var frm = `<div class="input-group input-inline mx-2 shadow-search" style="width:50%;">
	// <input class="form-input" type="text" id ="search_item_manager" placeholder="Type here" onkeyup="managerBrowse('search_item_manager')" style="height:40px">
	// <button class="btn btn-primary px-2 input-group-btn" style="height:40px"><i class="fas fa-search"></i></button>
	// </div>`

	var frm = `<div class="input-group input-inline mx-2 shadow-search" style="width:50%;margin-top: 2vh">
	<input class="form-input" type="text" id ="search_item_manager" placeholder="Find Item" onkeyup="managerBrowse(this)" style="height:6vh">
	<button class="btn input-group-btn bg-primary" style=" border: none; height: 6vh; width: 60px"><i class="fas fa-search h3"></i></button>
	</div>`;

	$('#titleTable2').html(frm);
	$('#titleTable').html('');
	// $('#content_area').html(newdiv);
	// managerloadingArea();
	// var endpoint = 'pharmacee/api/actions/manager/viewItems.php';
	// setTimeout(function(){searchStockCard(endpoint);},500);
}







function managerBrowse(isd){
	clearDocBtn();
	clearCardInfo();

	// clearCardSearch();
	var keyword= isd.value;
	itemMessage ="Inventory Browser";
	var newdiv =` <div class="column col-12" id="managerView"></div>`;
	if((keyword.length <= 1)){
		newdiv =``;
		// meddlist.clearData();
		$('#content_area').html(newdiv);
		$('#titleTable').html(``);
	}else{
	// nothingWasHer();
	$('#content_area').html(newdiv);
	$('#titleTable').html(itemMessage);
	managerloadingArea();
	var endpoint = '<?php echo $api_path;?>api/actions/manager/viewinventory.php?keyword='+ keyword;
	setTimeout(function(){browseInventory(endpoint);},300);
}

}


//GET LOW STOCKS
function getlowStocks(){
	// meddlist.clearData();
	clearDocBtn();
	clearCardInfo();
	clearCardSearch();
	$('#titleTable').html(``);
	var min=100;
	var newdiv =`<div class="column col-12" id="managerView"></div>`;
	var tblTitle ="Low Stocks" ;
	itemMessage = tblTitle;
	// $('#manager-Table-Title').html(tblTitle);
	$('#content_area').html(newdiv);
	managerloadingArea();
	var endpoint = '<?php echo $api_path;?>api/actions/manager/lowstocks.php?min='+ min
	setTimeout(function(){browseInventory(endpoint);},500);
}
//Stock Card Search Bar
function showCardSearchBar(isd){
	clearDocBtn();
	clearCardInfo();
	// var keyword= document.getElementById(isd).value;
	itemMessage = "Stock Card Form";
	var newdiv =`<div class="column col-12" id="managerView"></div>`;

	var frm = `<div class="input-group input-inline mx-2 shadow-search" style="width:50%;margin-top: 2vh">
	<input class="form-input" type="text"id ="search_card" placeholder="Type here"  onkeyup="filterCardStock(this)" style="height:6vh">
	<button class="btn input-group-btn bg-primary" style=" border: none; height: 6vh; width: 60px"><i class="fas fa-search h3"></i></button>
	</div>`;

	$('#titleTable3').html('');
	$('#titleTable2').html(frm);
	$('#titleTable').html(itemMessage);
	$('#content_area').html(newdiv);
	managerloadingArea();
	var endpoint = '<?php echo $api_path;?>api/actions/manager/viewItems.php';
	setTimeout(function(){searchStockCard(endpoint);},500);

}

function filterCardStock(isd){
	clearDocBtn();
	clearCardInfo();
	var keyword= isd.value;
	if(keyword.length < 2){
		var endpoint = '<?php echo $api_path;?>api/actions/manager/viewItems.php';

	}else{
		var endpoint = '<?php echo $api_path;?>api/actions/manager/viewinventory.php?keyword='+ keyword;
	}
	managerloadingArea();
	setTimeout(function(){searchStockCard(endpoint);},500);
}



function setCardProductName(value){
	cardCommon = value;
}
function setCardItemID(value){
	cardItemID = value;
}
function setCardGeneric(value){
	cardGeneric = value;
}
var cardCommon = "ds";
var cardItemID = "";
var cardGeneric = "";

function getStockCard(data){
	clearDocBtn();
	clearCardInfo();
	var itemid = data.getAttribute("data-item-id");
	var roww = 	managerItemList.getRow(itemid);
	var data = roww.getData();

	setCardItemID(itemid);
	setCardGeneric(data.GenericName);
	setCardProductName(data.CommonName);
	managerloadingArea();
	var endpoint = '<?php echo $api_path;?>api/actions/manager/cardsinout.php?keyword='+itemid;
	setTimeout(function(){loadStockCard(endpoint);},500);
}


//Generate DATe
var today = new Date();
function getCurYear(){
	return today.getFullYear();
}
//Get Current Month
function getCurMonth(){
	return today.getMonth()+1;
}
//Get Current Day
function getCurrentDay(){
	return today.getDate();
}
// get Current hour 24Hrs
function getCurrentHour(){
	return today.getHours();
}
//getCurrent Minute
function getCurrentMin(){
	return today.getMinutes();
}
function getCurrentSecond(){
	return today.getSeconds();
}
//create Document FileName Date
function createCurrentDateFilename(){
	return  getCurMonth() + '-' + getCurrentDay()+ '-' +getCurYear() + '-' +getCurrentHour()+ '-' +getCurrentMin() + '-' +getCurrentSecond();
}
function createFullFilename(){
	return itemMessage+'_'+createCurrentDateFilename();
}

//Export to XLS
function downloadXLS(){
	managerItemList.download("xlsx", createFullFilename()+".xlsx", {sheetName:itemMessage},);
}
//PRInt TABLE
function printTable(){
	managerItemList.print(false, true);
}

//show document Button
function showDocBtn(){
	$('#titleTable').html(itemMessage);
	$('#docbtn').html(docbtn);
}
function showDocBtn2(){
	// $('#titleTable').html(itemMessage);
	$('#docbtn2').html(docbtn2);
}

function clearDocBtn(){
		// $('#titleTable').html(``);
		$('#docbtn').html(``);
	}
function clearDocBtn2(){
		// $('#titleTable').html(``);
		$('#docbtn2').html(``);
	}
	function showProductName(){
		cardCommon = "NAME: " + cardCommon;
		$('#productName').html(cardCommon);
	}
	function showProductGeneric(){
		cardGeneric = "GENERIC: " + cardGeneric;
		$('#productGeneric').html(cardGeneric);
	}
	function showProductID(){
		cardItemID = "CODE: " +cardItemID
		$('#productID').html(cardItemID);
	}
	function showAllCardInfo(){
		showProductName();
		showProductID();
		showProductGeneric();
	}
	function clearCardSearch(){
		$('#titleTable2').html('');
	}
	function clearCardInfo(){
		$('#productName').html('');
		$('#productGeneric').html('');
		$('#productID').html('');
	}



</script>


<!-- Update Password User -->
<script type="text/javascript">

	function openUpdatePasswordModal(){
		$("#updateUserPasswordModal").addClass("active");
	}
	function closeUpdatePasswordModal(){
		$("#updateUserPasswordModal").removeClass("active");
		clearFieldsUpdatePasswordModal();
		clearErrorUpdatePasswordModal();
	}
	function setPasswordUpdateErrMsg(msg){
		$("#updatePasswordModalErrorMsg").text(msg);
	}
	function validateNewPassword(){
		newPass = $("#new_pass").val();
		confirmPass = $("#confirm_pass").val();
		return newPass == confirmPass;
	}
	function getupdateUserPasswordtoJson(){
		var obj = $('#updatePasswordModalForm').serializeJSON();
		var jsonString = JSON.stringify(obj);
		return jsonString;
	}
	function clearFieldsUpdatePasswordModal(){
		$("#updatePasswordModalForm")[0].reset()
	}
	function clearErrorUpdatePasswordModal(){
		setPasswordUpdateErrMsg("");
	}
	function renewPassword(){
		alertify.set('notifier','position', 'top-right');
		if(!validateNewPassword()){
			setPasswordUpdateErrMsg("Password Mismatch");
			return;
		}
		if(!(newPass.length > 5 || confirm_pass.length > 5)){
			setPasswordUpdateErrMsg("Password too short");
			return;
		}
		$.ajax({
			url: '<?php echo $api_path;?>api/actions/auth/updatePass.php',
			type: 'POST',
			dataType: 'json',
			data: getupdateUserPasswordtoJson(),
			success: function(result){
				if(result.Success != 1){
					setPasswordUpdateErrMsg(result.Message);
				}else{
					closeUpdatePasswordModal();
					alertify.success(result.Message);
				}
			}

		});
	}





</script>








<script type="text/javascript">
	







// new Chart(document.getElementById("chartjs-0"),{"type":"line",
// 	"data":
// 	{"labels":
// 	["January","February","March","April","May","June","July"],
// 	"datasets":
// 	[
// 	{"label":"My First Dataset",
// 	"data":[65,59,80,81,56,55,40],"fill":false,"borderColor":"rgb(75, 192, 192)","lineTension":0.1}

// 	]

// },"options":{}});


var barcodeItems= null;
function getBarCodes() {
	var endpoint = '<?php echo $api_path;?>api/actions/manager/viewItems.php';
	$.ajax({
		url:endpoint,
		type:'get',
		dataType:'json',
		error: function(){barcodeItems = null;},
		success: function (result){
			printAllBarCodes(result);

		},


		statusCode: {
		// 401: function () { notif('An Error Occured',1); },
		// 500: function () { alert('') },
		// 405: function () { notif('An Error Occured',1); },

	},
});
	
}


function printAllBarCodes(dataArr){
	var count = 1;
	if(dataArr!= null){
		
		$.each(dataArr, function(key,value){
			if(count == 1 || count % 3 == 1){

				$('#barcodesss').append(`<div class="columns">`);
			}
			$('#barcodesss').append(`<div class="columns col-3>`);
			$('#barcodesss').append(`<svg class="barcode text-center" jsbarcode-value=`+value.ItemID+` jsbarcode-textmargin="0" jsbarcode-background="#f7f8f9" jsbarcode-marginLeft: 0, jsbarcode-marginTop: 0, jsbarcode-fontoptions="bold"></svg>`);
			$('#barcodesss').append(`</div>`);

			if(count % 3 == 0){
				console.log(count);
				$('#barcodesss').append(`</div>`);
			}
			++count;
		}
		);
		// $('#barcodesss').append(`</div>`);
		JsBarcode(".barcode").init();
		
	}else{
		console.log("empty barcode");
	}
}











</script>


