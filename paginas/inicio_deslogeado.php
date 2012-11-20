<div id="home">
<?php
	// Despliega el banner con las peliculas destacadas sin enlaces
	$general->listarPeliculasDestacadas();
	
?>
	<div class="sep"></div>
	<div id="wdescription">
    	<h1>Wiki-Global is a multi language Movie Database that bring all the Hollywood entertainment into your home</h1>
    </div>
	<div class="sep"></div>
	<div id="thumbs_main_page" style="margin-top:30px;">
		<div class="menu">
			<span>Celebrities</span>
			<a href="javascript:<?=$general->displayRestrictContentByGroupsEnabled("displayLastCelebrities(false, 'date_updated');"); ?>" name="reciente" class="sel">Last updated</a>
			<a href="javascript:<?=$general->displayRestrictContentByGroupsEnabled("displayLastCelebrities(false, 'date_inserted');"); ?>" name="estreno">Last added</a>
			<a href="javascript:<?=$general->displayRestrictContentByGroupsEnabled("displayLastCelebrities();"); ?>">All</a>
		</div>
	<div class="thumbs" id="celebritiesThumbs"></div>
</div>
