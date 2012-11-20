	
	/* parametros para hacer las busquedas */
	var searchParameters = {
		searchContainerResults: 'searchResults',
		searchOnlyResults: 0,
		searchKeyWords: '',
		searchType: '1',
		searchOrder: '',
		base: '0'
	};
	
	/* setea palabras de busqueda */
	function searchKeyWords() {
		var words = $("#words").val();
		if(words!=undefined && words=='') return;
		searchParameters.searchKeyWords = words;
		searchParameters.searchContainerResults = 'searchResults';
		searchParameters.searchOnlyResults = 0;
		searchParameters.searchOrder = '';
		searchParameters.base = 0;
		getMoviesCelebritiesSearchResults();
	}

	/* setea el tipo de busqueda: movies o celebrities */
	function searchKeyWordsByType(type) {
		if(type!=undefined && type=='') return;
		searchParameters.searchType = type;
		searchParameters.searchKeyWords = '';
		searchParameters.searchContainerResults = 'searchResults';
		searchParameters.searchOnlyResults = 0;
		searchParameters.searchOrder = '';
		searchParameters.base = 0;

		getMoviesCelebritiesSearchResults();
	}
	
	/* setea el ordenamiento de las busquedas */
	function searchKeyWordsByOrder(order) {
		if(order!=undefined && order=='') return;
		searchParameters.searchKeyWords = '';
		searchParameters.searchOrder = order;
		searchParameters.searchContainerResults = 'onlySearchResults';
		searchParameters.searchOnlyResults = 1;
		searchParameters.base = 0;

		getMoviesCelebritiesSearchResults();
	}

	/* setea el ordenamiento de las busquedas */
	function searchKeyWordsByPageNumber(page) {
		//if(page=='') return;
		searchParameters.base = page;
		searchParameters.searchContainerResults = 'onlySearchResults';
		searchParameters.searchOnlyResults = 1;

		getMoviesCelebritiesSearchResults();
	}

	/* realiza una busqueda de acuerdo a criterios preestablecidos */
	function getMoviesCelebritiesSearchResults() {
		var parametros = '';
		var container = searchParameters.searchContainerResults;

		if(!jQuery("#" + container).length) return;
		
		var cargaComparacion = $.ajax({
			type	: "GET",
			cache	: false,
			url	: "paginas/moviesCelebritiesSearchResults.php?words=" + searchParameters.searchKeyWords + "&searchType=" + searchParameters.searchType + "&searchOrder=" + searchParameters.searchOrder + "&base=" + searchParameters.base + "&searchOnlyResults=" + searchParameters.searchOnlyResults,
			beforeSend: function(){
				//jQuery("#" + container).html("");
				jQuery.loader({
					className:"loader",
					setContent:'...loading...'//,
					//storeContentLoaded: container
				});
	  		},
			success: function(data) {
				jQuery.loader('close');
				if(data!=null) jQuery("#" + container).html(data);	
			},
			error: function () {
				jQuery.loader('close');
			}
		});

		$(".loader").click(function() {				
			jQuery.loader('close');
			cargaComparacion.abort();				
		})
	}



	/* carga el contenido de una noticia */
	function loadAllNewContent(id_new) {
		$.ajax({
			type	: "GET",
			cache	: false,
			data	: {
				id_new: id_new
			},
			url	: "paginas/loadNewContent.php",
			beforeSend: function(){
				jQuery.fancybox.showActivity();
	  		},
			success: function(data) {
				jQuery.fancybox({
					content: data
				});		
			},
			error: function () {
				jQuery.fancybox.hideActivity();
			}
		});
	}

	/* function que deschequeda los enlaces de los menus */
	function letCheckedOne(obj, id_sel) {
		$("#sel_" + id_sel + ">a.sel").removeClass("sel");
		obj.className = 'sel';
	}

	function displayLastCelebrities(display_link, order) {
		var parametros = '';
		var container = 'celebritiesThumbs';

		if(!jQuery("#" + container).length) return;
		if(order!='' && order!=undefined) parametros = "?order=" + order;
		
		var cargaComparacion = $.ajax({
			type	: "GET",
			cache	: false,
			url	: "paginas/lastCelebritiesJson.php" + parametros,
			dataType: 'json',
			beforeSend: function(){
				jQuery("#" + container).html("");
				jQuery.loader({
					className:"loader",
					setContent:'...loading...',
					storeContentLoaded: container
				});
	  		},
			success: function(data) {
				jQuery.loader('close');
				if(data!=null) {
					if(data.length) {
						var html = '';
						for(var i=0;i<data.length;i++) {
							html += '<div>';
							html += '<img src="' + data[i].imagen + '">';
							html += '<span>' + data[i].nombre + '</span>';
							html += '</div>';
						}

						jQuery("#" + container).html(html);
					}
				}		
			},
			error: function () {
				jQuery.loader('close');
			}
		});

		$(".loader").click(function() {				
			jQuery.loader('close');
			cargaComparacion.abort();				
		})
	}

	/* carga el contenido de las peliculas */
	function displayLastMovies(display_link, order) {
		var parametros = '';
		var container = 'moviesThumbs';

		if(!jQuery("#" + container).length) return;
		if(order!='' && order!=undefined) parametros = "?order=" + order;
		
		var cargaComparacion = $.ajax({
			type	: "GET",
			cache	: false,
			url	: "paginas/lastMoviesJson.php" + parametros,
			dataType: 'json',
			beforeSend: function(){
				jQuery("#" + container).html("");
				jQuery.loader({
					className:"loader",
					setContent:'...loading...',
					storeContentLoaded: container
				});
	  		},
			success: function(data) {
				jQuery.loader('close');
				if(data!=null) {
					if(data.length) {
						var html = '';
						for(var i=0;i<data.length;i++) {
							html += '<div>';
	
								html += '<a href="';
								html += (display_link) ? '?id_pagina=36&id_pelicula=' + data[i].id_pelicula + '" title="' + data[i].nombre : 'javascript:displayMessageOfContentLoggedRestricted()';								
								html += '"><img src="' + data[i].imagen + '"></a>';

								html += '<span>';
									html += '<a href="';
									html += (display_link) ? '?id_pagina=36&id_pelicula=' + data[i].id_pelicula + '" title="' + data[i].nombre : 'javascript:displayMessageOfContentLoggedRestricted()';								
									html += '">' + data[i].nombre + '</a>';
								html += '</span>';
							html += '</div>';
						}

						jQuery("#" + container).html(html);
					}
				}		
			},
			error: function () {
				jQuery.loader('close');
			}
		});

		$(".loader").click(function() {				
			jQuery.loader('close');
			cargaComparacion.abort();				
		})
	}
	
	/* carga el contenido de las noticias */
	function displayLastNews(order) {
		var parametros = '';
		var container = 'newsThumbs';

		if(!jQuery("#" + container).length) return;
		if(order!='' && order!=undefined) parametros = "?order=" + order;
		
		var cargaComparacion = $.ajax({
			type	: "GET",
			cache	: false,
			url	: "paginas/lastNewsJson.php" + parametros,
			dataType: 'json',
			beforeSend: function(){
				jQuery("#" + container).html("");
				jQuery.loader({
					className:"loader",
					setContent:'...loading...',
					storeContentLoaded: container
				});
	  		},
			success: function(data) {
				jQuery.loader('close');
				if(data!=null) {
					if(data.length) {
						var html = '';
						for(var i=0;i<data.length;i++) {
							html += '<div class="new">';
							html += '<img src="' + data[i].image + '">';
							html += '<span><a href="javascript:loadAllNewContent(' + data[i].id_new + ');" title="' + data[i].title + '"><b>' + data[i].title + '</b></a><br><br>';
							html += data[i].text;
							html += ' <a href="javascript:loadAllNewContent(' + data[i].id_new + ');" title="view all new content">more</a></span>';
							html += '</div>';
						}

						jQuery("#" + container).html(html);
					}
				}		
			},
			error: function () {
				jQuery.loader('close');
			}
		});

		$(".loader").click(function() {				
			jQuery.loader('close');
			cargaComparacion.abort();				
		})
	}