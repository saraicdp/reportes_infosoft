<?php

	function conectar () {

	$conexion=mysql_connect("localhost", "root", "superman");
			  mysql_select_db(antonio);

			if ($conexion->connect_errno) {

				return "No se puede conectar a la Base de Datos";

			} else {
				
				return "Conectado";
			}
	}
	
?>