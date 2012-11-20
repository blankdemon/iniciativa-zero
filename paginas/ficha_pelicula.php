<?php
	function showStarsForVote($puntaje=0) {
		echo '<input name="adv1" type="radio" class="star "/>';
		
		for($i=1;$i<=10;$i++) {
			echo '<input name="adv1" type="radio" class="star" value="' . $i . '-' . (int) $_REQUEST[id_pelicula]. '"';
			
			if($i==(int)$puntaje)
				echo ' checked';
			
			echo ' />';		
		}
	}
	
	function showAudienceThatLikeIt($audience_that_like_it = 0) {
		if($audience_that_like_it <= 20) {
			$color = '000000';
		} else if ($audience_that_like_it > 20 && $audience_that_like_it <= 50) {
			$color = '0066FF';
		} else if ($audience_that_like_it > 50 && $audience_that_like_it <= 80) {
			$color = 'FF0000';
		} else {
			$color = '009900';
		}
		
		echo 'Audience that like it
		<div id="audience-container" style="border: 1px solid #' . $color . '">
			<div id="porcentaje" style="width:' . $audience_that_like_it . '%;background:#' . $color . ';">' . $audience_that_like_it . '%</div>
		</div>';
	}


	$q = "SELECT 
		p.id_pelicula, p.nombre, p.trama, p.year, p.duracion, p.subtitulo, p.puntaje, p.idioma, p.audience_that_like_it, p.critica,
		cmr.nombre AS mpaa_rating, cmr.descripcion AS mpaa_description
	FROM cart_pelicula p
	LEFT JOIN cart_mpaa_rating cmr ON (p.mpaa_rating = cmr.id_mpaa_rating) 
	LEFT JOIN imagen i ON (p.id_imagen = i.id_imagen)";
	$q .= " WHERE p.id_pelicula = " . (int) $_REQUEST[id_pelicula];
	$q .= " GROUP BY p.id_pelicula";
	$s = mysql_query($q);
	
	if(mysql_num_rows($s)) {
		$r = mysql_fetch_array($s);
		$id_pelicula = $r[id_pelicula];
		$titulo = $r[nombre];
		$descripcion = $r[trama];
		$critic = $r[critica];
		$duracion = $r[duracion];
		$idioma = $r[idioma];
		$puntaje = $r[puntaje];
		$year = $r[year];
		$mpaa_rating = $r[mpaa_rating];
		$mpaa_description = $r[mpaa_description];
		$audience_that_like_it = (int)($r[audience_that_like_it] * 100);
?>
	<div id="movie">
		<div id="left">
			<img src="paginas/imagen.php?id_imagen=<?=$general->getLastIdImgMovie($id_pelicula); ?>&ancho=180&alto=267&default=998">
<?php
	if($config["stay_beautiful"])
		echo ' <div id="clogout"><a href="' . $config["remote_website_downloads"] . '/download.php?idMovie=' . $id_pelicula . '">Log out to subtitles, downloads and external information</a></div>';
		
?>
		</div>
		<div id="right">
			<h1><?=$titulo ?> (<?=$year ?>)</h1>
			<div id="description">
<?php 
			if(!empty($mpaa_rating))
				echo '<a href="javascript:;" title="' . $mpaa_description . '"><img src="imagenes/' . strtolower($mpaa_rating) . '.png" align="left" style="margin:0 10px 0 0;" /></a>';
?>
				<?=stripslashes($descripcion) ?>
			</div>
			<div id="detail_movie">
			    	<div id="l">
					<?=($duracion > 0) ? $duracion . ' min' : '--' ?>, <?php $general->obtenerGeneros($r[id_pelicula], true); ?><br />
					<b>Directed by:</b> <?php $general->obtenerDirectores($id_pelicula); ?><br />
					<b>Language:</b> <?=(!empty($idioma)) ? $idioma : 'Not available' ?><br />
					<b>Year:</b> <?=$year ?>
				</div>
				<div id="r">
					<?php showAudienceThatLikeIt($audience_that_like_it); ?>
                   
                    Rank this movie
                    <div id="votes">
                    	<?php showStarsForVote($puntaje); ?>
                    </div>
				</div>
			</div>
		    
            <h3>Critic</h3>
            <div id="critic">
            	<?=(!empty($critic)) ? $critic : 'No critic yet' ?>
            </div>
            
			<h3>Cast</h3>
			<div id="actors">
				<?php $general->obtenerActoresForMovieCard($id_pelicula); ?>
			</div>
		</div>	
	</div>
<?php
	} else {
		echo 'Not content available';
	}
?>