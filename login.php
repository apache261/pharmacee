<!DOCTYPE html>
<html>
<head>
	<?php include 'php/config.php'?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="ie-edge">
	<title>Login</title>
<!-- 	<link rel="stylesheet" type="text/css" href = "assets/css/spectre.css"/> -->
<link rel="stylesheet" type="text/css" href = "<?php echo $asset_path;?>assets/css/spectre.css"/>
</head>
<body class="">
	<div class="columns" style="margin-top: 4vh">
		<div class="column col-4 hide-md"></div>
		<div class="column col-4 col-md-10" >
			<div class="toast d-invisible text-center" id="msgbox">
				<p>sdfds</p>
			</div>	
		</div>
		<div class="column col-4 hide-md"></div>
	</div>
	<div class="columns" style="margin-top: 3vh">
		<div class="column col-4 hide-md"></div>

		<div class="column col-4 col-md-12 text-dark bg-gray" style="border-left:5px solid #5755d9; border-top: 1px solid #5755d9; border-right: 1px solid #5755d9;border-bottom: 1px solid #5755d9; ">
			<form id="login">
				<div class="h2 text-center text-uppercase text-bold">Login</div>
				<div class="divider text-primary"></div>
				<div class="container"style=" padding: 10px 10px 10px 10px">
					<!-- uID -->

					<div class="form-group" >
						<label class="form-label h6 tetx-bold" for="login_id">User ID</label>
						<input class="form-input" name="authenticate[uid]" style="border-radius: 10px; height: 40px" type="text" id="login_id" placeholder="User ID">
					</div>
					<!-- uPass -->
					<br/>
					<div class="form-group" style="">
						<label class="form-label h6 tetx-bold" for="login_id">Password</label>
						<input class="form-input" name="authenticate[pass]" style="border-radius: 10px; height: 40px" type="password" id="login_pass" placeholder="Password">
					</div><br/>
					<div class="py-2">
						<a class="btn btn-primary btn-lg p-centered text-bold" id="submit_btn" href="javascript:doLogin()" style="width:100%"><i class="icon icon-arrow-left"></i> Login</a>
					</div>
					<br/>
					<br/>
					<div class="h6 text-center"> PHARMACEE | 2020</div>
				</div>
			</form>
		</div>
		<div class="column col-4 hide-md"></div>
		
	</div>
	<div class="columns" >
		<div class="column col-4 hide-md"></div>
		<div class="column col-4 col-md-10" >
			<div class="toast d-invisible" id="msgbox">
				<p>sdfds</p>
			</div>	
		</div>
		<div class="column col-4 hide-md"></div>
	</div>


	<script type="text/javascript">
		function doLogin(){
			addLoading();
			setTimeout(function(){checkLogin()},1000);
		}


		function checkLogin(){

			var credentials = parseField('#login');
			$.ajax({
				url:'<?php echo $api_path;?>api/actions/auth/authenticate.php',
				type:"POST",
				dataType:'json',
				contentType:'application/json',
				data:credentials,
				error: function(xhr, status, error){
					// var errorMessage = xhr.responseJSON
					// console.log(errorMessage.Message);
					// removeLoading(errorMessage.Message);
					// errorBox(errorMessage.Message);
				},
				success:function (response) {

					if(response.Success == 1){
						$("#login")[0].reset();
						// console.log(response.token);
						setCookie("token", response.token, 1);
						successBox(response.Message);
						setTimeout(function(){rredirect()}, 2000);
					}else{
						errorBox(response.Message);
					}
					removeLoading();
				},
				statusCode: {

				},


			});
		}

		function rredirect(){
			$.ajax({
				url: '<?php echo $api_path;?>api/actions/auth/Arbiter.php',
				type: 'POST',
				dataType:'json',
				error: function(xhr, status, error){
					console.log("failed to redirect");
				},
				success:function (response) {
					window.location.replace(response.path);
					console.log(response.path);
				},
			});
		}
		function parseField(id) {
			var obj = $(id).serializeJSON();
			var jsonString = JSON.stringify(obj);
			return jsonString;
		}
		function addLoading(){
			$('#submit_btn').addClass("loading");
		}
		function removeLoading(){
			$('#submit_btn').removeClass("loading");
		}

		function errorBox(msg){
			$("#msgbox").removeClass("d-invisible");
			$("#msgbox").removeClass("toast-success");
			$("#msgbox").addClass("toast-error");
			$("#msgbox").text(msg);
		}
		function successBox(msg){
			$("#msgbox").removeClass("d-invisible");
			$("#msgbox").removeClass("toast-error");
			$("#msgbox").addClass("toast-success");
			$("#msgbox").text(msg);
		}
	// get or read cookie
	function getCookie(cname){
		var name = cname + "=";
		var decodedCookie = decodeURIComponent(document.cookie);
		var ca = decodedCookie.split(';');
		for(var i = 0; i <ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' '){
				c = c.substring(1);
			}

			if (c.indexOf(name) == 0) {
				return c.substring(name.length, c.length);
			}
		}
		return "";
	}
	function setCookie(cname, cvalue, exdays) {
		var d = new Date();
		d.setTime(d.getTime() + (exdays*24*60*60*1000));
		var expires = "expires="+ d.toUTCString();
		document.cookie = cname + "=" + cvalue + ";" + expires + ";secure=false;path=/";
	}
</script>

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.serializeJSON/3.1.1/jquery.serializejson.min.js" integrity="sha512-czywXrb/msTMh+lhgSe/vQ0GT0OraNiD8Knd7A7fMqEjDmQxljn/b39skl45eu+iyG0wxC9SuqcaUatZ4S0kdA==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
</body>
</html>
