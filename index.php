<?php
	require "Clases/Usuario.php";

	$nuevoUsuario = new Usuario(); 	
	$nuevoUsuario -> establecerNombre( "Juan" );
	$nuevoUsuario -> establecerApellidos( "Perez Robles" );
	$nuevoUsuario -> establecerFechaNacimiento( "Perez Robles" );

	echo $nuevoUsuario -> toString();