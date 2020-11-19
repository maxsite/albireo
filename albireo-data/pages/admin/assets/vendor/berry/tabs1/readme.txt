// tabs

<script src="assets/components/tabs1/tabs1.js"></script>

<div>
    <button class="button tab-nav bg-red" data-tab-active="bg-red" onclick="tabs1(event, 'tab-group', 'tabFirst')">Fade</button>

    <button class="button tab-nav" data-tab-active="bg-pink" onclick="tabs1(event, 'tab-group', 'tabSecond')">Left</button>
</div>

<div id="tab-group">
    <div id="tabFirst" class="tab-content animate-opacity">
        <h2>Fade in</h2>
        <p>London is the capital city of England.</p>
    </div>

    <div id="tabSecond" class="tab-content b-hide animate-left">
        <h2>Slide in from left</h2>
        <p>Paris is the capital of France.</p>
    </div>
</div>

<div class="mar20-tb button-group">
    <button class="button tab-nav bg-red" data-tab-active="bg-red" onclick="tabs1(event, 'tab-group1', 'tab1')">Fade</button>

    <button class="button tab-nav" data-tab-active="bg-pink" onclick="tabs1(event, 'tab-group1', 'tab2')">Left</button>

    <button class="button tab-nav" data-tab-active="bg-orange" onclick="tabs1(event, 'tab-group1', 'tab3')">Right</button>

    <button class="button tab-nav" data-tab-active="bg-blue" onclick="tabs1(event, 'tab-group1', 'tab4')">Top</button>

    <button class="button tab-nav" data-tab-active="bg-green" onclick="tabs1(event, 'tab-group1', 'tab5')">Bottom</button>

    <button class="button tab-nav" data-tab-active="bg-red" onclick="tabs1(event, 'tab-group1', 'tab6')">Zoom</button>
</div>

<div id="tab-group1">
    <div id="tab1" class="tab-content animate-opacity">
        <h2>Fade in</h2>
        <p>London is the capital city of England.</p>
        <p>It is the most populous city in the United Kingdom, with a metropolitan area of over 13 million inhabitants.</p>
    </div>

    <div id="tab2" class="tab-content b-hide animate-left">
        <h2>Slide in from left</h2>
        <p>Paris is the capital of France.</p>
        <p>The Paris area is one of the largest population centers in Europe, with more than 12 million inhabitants.</p>
    </div>

    <div id="tab3" class="tab-content b-hide animate-top">
        <h2>Slide in from top</h2>
        <p>Tokyo is the capital of Japan.</p>
        <p>It is the center of the Greater Tokyo Area, and the most populous metropolitan area in the world.</p>
    </div>

    <div id="tab4" class="tab-content b-hide animate-right">
        <h2>Slide in from right</h2>
        <p>London is the capital city of England.</p>
        <p>It is the most populous city in the United Kingdom, with a metropolitan area of over 13 million inhabitants.</p>
    </div>

    <div id="tab5" class="tab-content b-hide animate-bottom">
        <h2>Slide in from bottom</h2>
        <p>Paris is the capital of France.</p>
        <p>The Paris area is one of the largest population centers in Europe, with more than 12 million inhabitants.</p>
    </div>

    <div id="tab6" class="tab-content b-hide animate-zoom">
        <h2>Zoom in</h2>
        <p>Tokyo is the capital of Japan.</p>
        <p>It is the center of the Greater Tokyo Area, and the most populous metropolitan area in the world.</p>
    </div>
</div>

<hr>

<div class="mar20-tb button-group">
    <button class="button tab-nav" data-tab-active="bg-orange" onclick="tabs1(event, 'tab-group2', 'tabA')">Fade</button>
    <button class="button tab-nav bg-orange" data-tab-active="bg-orange" onclick="tabs1(event, 'tab-group2', 'tabB')">Left</button>
</div>

<div id="tab-group2">
    <div id="tabA" class="tab-content b-hide animate-opacity">
        <h2>Fade in</h2>
        <p>London is the capital city of England.</p>
        <p>It is the most populous city in the United Kingdom, with a metropolitan area of over 13 million inhabitants.</p>
    </div>

    <div id="tabB" class="tab-content animate-opacity">
        <h2>Slide in from left</h2>
        <p>Paris is the capital of France.</p>
        <p>The Paris area is one of the largest population centers in Europe, with more than 12 million inhabitants.</p>
    </div>
</div>
