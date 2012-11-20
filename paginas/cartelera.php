	<h1>Movies</h1>
	<div id="cartelera_container">
		<form name="form1" method="post" onsubmit="searchKeyWords();return false;" action="">
			<div id="buscador-cartelera">
				<div id="inputs">
					<input name="action" type="hidden" value="buscar" />
					<input type="text" id="words" name="words" />
					<input type="button" id="search" name="button" value="search" onclick="searchKeyWords();" />
				</div>
<?php
	/*
				<div id="sel_2">
					<a href="javascript:searchKeyWordsByType(1);" title="Movies" class="sel" onclick="letCheckedOne(this, 2)">Movies</a>
					<a href="javascript:searchKeyWordsByType(2);" title="Celebrities" onclick="letCheckedOne(this, 2)">Celebrities</a>
				</div>	
	*/
?>
			</div>
		</form>
		<div id="searchResults"></div>
	</div>
