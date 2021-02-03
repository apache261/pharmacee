<!-- <!DOCTYPE html>
<html>
<head>
	<title>Checker</title>
</head>
<body>
	<p>
		

	</p>

</body>
</html> -->
<html>
<?php include 'php/config.php'?>





  <div id="container">
    <h3>Pharmacee Expiry Scanner</h3>
    <h2 id="expire"></h2>
  </div>

  <script>











    console.log("index.html 7 | Get Covid Data");

    const getCovidData = async () => {
      console.log("index.html 10 | Processing...");
      const request = await fetch("<?php echo $api_path;?>api/actions/manager/expiryScanner.php");
      const data = await request.json();
      return data;
    };

    function loadd(){
    getCovidData().then(expiry => {
      console.log("ukggguiug", expiry);
    var p = document.getElementById("expire");
     p.innerHTML += expiry.Message + `<br/>`;
      setTimeout(function(){ loadd(); }, 3000);
    });
}



	loadd();


  </script>
</html>
