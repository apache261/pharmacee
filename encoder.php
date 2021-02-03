<!DOCTYPE html>
<html class="no-js">
<head>
	<title>ENCODER</title>
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
		if(uinfo.userRole != 2){
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
<body onload=" showEncoderSearchBar()">
	<div class="container">
		<?php include 'php/head.php';?>
		<div class="columns " style="">
			<!--  -->
			<div class="column col-2 " style=""><?php include 'php/encoder/offcanvas.php';?></div>
			<div class="column col-10 col-md-12" id="">
				<div id="search_area" class="h6 text-center text-bold text-uppercase py-2 "></div>
				<div id="content-area"></div>
			</div>
			<div id="disp"></div>
		</div>
	</div>
	<?php include 'php/updatePassword.php';?>
		<?php include 'php/footer.php';?>
