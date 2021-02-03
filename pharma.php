
<!DOCTYPE html>
<html class="no-js">
<head>
	<title>PHARMACIST</title>
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
		// window.location.replace(response.);
		// console.log(response[0].data);
		var uinfo = response[0].data;
		$("#pharmaFname").text(uinfo.firstname);
		$("#fullnameee").text(uinfo.firstname + " " + uinfo.lastname);
		$("#userRoleee").text(typeOfUser[uinfo.userRole-1]);
		$("#updatePasswordOwner").val(uinfo.userId);
		if(uinfo.userRole != 1){
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
<body onload="showPharmaSearchBar()">

	<div class="container">
		<?php include 'php/head.php';?>


		<div class="columns" style="">

			<div class="column col-2 hide-md" style=""><?php include 'php/pharma/offcanvas.php';?></div>
			<div class="column col-10 col-md-12" id="">
				<!-- <div class="columns">
					<div class="column col-3"></div>
					<
					<div class="column col-3"></div>
				</div> -->
				<div id="search_area" class=" h6 text-center text-bold text-uppercase"></div>
				
				
				<div class="" id="content_area"style="margin-top: 5vh"> </div>

			</div>
			<div id="disp"></div>

		</div>
	</div>
	<?php include 'php/updatePassword.php';?>
		<?php include 'php/footer.php';?>
