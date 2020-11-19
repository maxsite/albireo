// modal elements

https://sabe.io/tutorials/how-to-create-modal-popup-box

<button class="modal-trigger button button1" data-modal="modal1">Show modal</button>

<button class="modal-trigger button button2" data-modal="modal2">Show modal</button>

<div id="modal1" class="modal">
    <div class="modal-content">
        <span class="modal-close-icon">&times;</span>
        
        <div class="t120 mar20">Hello, I am a modal!</div>
        
        <hr class="mar0 bor-gray200">
        
        <div class="pad20 t90"> Quisque vehicula; neque id condimentum varius, metus nisl vehicula orci, sit amet malesuada massa ipsum ut orci. Ut tincidunt congue eleifend. Proin et eros nisi, eget sollicitudin lacus. Maecenas sit amet nibh et felis volutpat vulputate ut sed nisi. Praesent vestibulum, magna in accumsan lacinia, odio dolor laoreet nibh, non cursus nisl dui non augue?</div>

        <hr class="mar0 bor-gray200">
        
        <div class="mar20 t-right">
            <button class="modal-close button button2">Close</button> 
            <button class="button button1">Save changes</button> 
        </div>
    </div>
</div>

<div id="modal2" class="modal" data-modalclose="1">
    <div class="modal-content pad30">
        <span class="modal-close-icon">&times;</span>
        <div>Hello, I am a modal 2!</div>
        
        __ <button class="modal-close button button2">Close</button>
    </div>
</div>

<link rel="stylesheet" href="assets/components/modal1/modal1.css">
<script src="assets/components/modal1/modal1.js"></script>

<?php
    // mso_add_file('assets/sass/parts/berry/components/modal1/modal1.js');
?>
