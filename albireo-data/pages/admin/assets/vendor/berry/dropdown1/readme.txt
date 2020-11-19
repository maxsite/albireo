// dropdown elements

<?php
  //  mso_add_file('assets/sass/parts/berry/components/dropdown1/dropdown1.js');
?>

<link rel="stylesheet" href="assets/components/dropdown1/dropdown1.css">
<script src="assets/components/dropdown1/dropdown1.js"></script>

<div class="dropdown">
  <button onclick="dropdownToggle(this, 'myDropdown')" class="dropdown-button button button1 fas fa-angle-down">Dropdown</button>
  
  <div id="myDropdown" class="dropdown-content">
      <ul class="dropdown-menu">
        <li><a href="#">Link 1</a></li>
        <li><a href="#">Link 2</a></li>
        <li><a href="#">Link 3</a></li>
      </ul>
  </div>
</div>

<div class="dropdown">
  <button onclick="dropdownToggle(this, 'myDropdown2', 'right')" class="dropdown-button button button2 t90 pad5-tb">Dropdown <i class="mar10-l icon0 fas fa-angle-right"></i></button>
  
  <div id="myDropdown2" class="dropdown-content pad40 w300px">
        Quisque vehicula; neque id condimentum varius, metus nisl vehicula orci, sit amet malesuada massa ipsum ut orci. Ut tincidunt congue eleifend. 
  </div>
</div>
