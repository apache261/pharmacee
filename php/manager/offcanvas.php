<div class="container bg-gray " style="height:100vh;">
  <div class="columns">
    <div class="column col-12 text-center py-2">
      <span class="text-bold text-dark text-uppercase h5">Manager Tools</span>
    </div>
  </div>
  <div class="accordion" style="margin-top: 3vh">
    <input type="checkbox" id="accordion-1" name="accordion-checkbox" checked hidden>
    <label class="accordion-header" for="accordion-1">
      <i class="fas fa-chevron-right mr-1"></i>
      <span href="#" class="text-bold"><i class="fas fa-eye mr-2"></i>Overview</span>
    </label>
    <div class="accordion-body">
      <ul class="nav">
       <li class="nav-item">
        <ul class="nav">
          <li class="nav-item">
            <a href="#"  onclick="showBrowseSearchBar()"><i class="fas fa-search mr-2"style=""></i>Find Item</a>
          </li>
          <li class="nav-item">
           <a href="#" onclick="getlowStocks()"><i class="fas fa-exclamation " style="margin-right:10px;margin-left:5px"></i>Low Stocks</a> 	  
         </li>
       </ul>
     </li>
   </ul>
 </div>
</div>
<div class="accordion">
  <input type="checkbox" id="accordion-2" name="accordion-checkbox" checked hidden>
  <label class="accordion-header" for="accordion-2">
    <i class="fas fa-chevron-right mr-1"></i>
    <span href="#" class="text-bold"><i class="fas fa-file-invoice mr-2"></i>Reports</span>
  </label>
  <div class="accordion-body">
    <ul class="nav">
      <li class="nav-item ">

        <ul class="nav">
          <li class="nav-item">
            <a href="#" onclick="searchExpire(3)"><i class="fas fa-receipt mr-2"></i>Expired</a>
          </li>
          <li class="nav-item">
            <a href="#" onclick="showCardSearchBar(this)"><i class="fas fa-receipt mr-2"></i>Stock Card</a>
          </li>

        </ul>
      </li>
    </ul>
  </div>
</div>
</div>
