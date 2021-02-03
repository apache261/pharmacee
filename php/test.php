

<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<body>

<form id="hello" name="frm1">
  
  <input type="text" id="name" name="id1"/>
  <input type="text" id="name2" name="id2" />
  <input type="submit" name="">
</form>
<a href="javascript:submitInfo();">sadfds</a>
 


<div id="disp"></div>





<script type="text/javascript">
  

function submitInfo(){

var obj = $('hello').serializeJSON();
var jsonString = JSON.stringify(obj);

document.getElementById('disp').innerText = jsonString;
alert(jsonString);
console.log('dfdsf');
}

</script>





<script type = "text/javascript" src="https://unpkg.com/ionicons@5.2.3/dist/ionicons.js"></script>
<!-- <script type="text/javascript" src="assets/js/notify.js"></script> -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script type="text/javascript" src = "../assets/js/jquery.serializejson.js"></script>
<!-- <script type="text/javascript" src = "../assets/js/custom.js"></script> -->
</body>
</html>











