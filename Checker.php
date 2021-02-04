
<html>
<?php include 'php/config.php'?>



<style type="text/css">
  .container{
    width: 300px;
    height: 300px;
    overflow-x: scroll;
    border:1px solid #000;
    margin:0 auto;
  }
  .mainFont{
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
  }
  .center{
    text-align: center;
  }
  .text-indent{
    text-indent: 4px;
  }
  .pl{
    padding-left: 10px;
  }
  .text-bold{
    font-weight: bold;
  }
  .h6{
    font-size:20px;
  }

</style>

<div class="container">
  <h3 class="mainFont center">Pharmacee Expiry Scanner</h3>
  <div class="text-bold h6">Logs</div>
  <div id="expire" class="pl" style="font-size: 16px"></div>
</div>

<script>












  const getCovidData = async () => {
    console.log("Processing...");
    const request = await fetch("<?php echo $api_path;?>api/actions/manager/expiryScanner.php");
    const data = await request.json();
    return data;
  };

  function loadd(){
    getCovidData().then(expiry => {
      var p = document.getElementById("expire");
      var count  = expiry.Message;
      var toPrint = '';
      if(expiry.Success == 1){
        if(count > 0){
        toPrint = JSON.stringify(expiry.Data);
      }else{
        toPrint = "None";
      }
    }else{
      toPrint = expiry.Message;
    }
      p.innerHTML += toPrint+ `<br/>`;
      setTimeout(function(){ loadd(); }, 3000);
    });
  }



  loadd();


</script>
</html>
