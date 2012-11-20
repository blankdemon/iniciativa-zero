<?php
	class Session {
		
		function cerrar() {
			session_destroy();
			header("Location: ?id_pagina=1");
		}
	};
?>