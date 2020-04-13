<?php
	require "Clases/Usuario.php";

	$nuevoUsuario = new Usuario(); 	
	$nuevoUsuario -> establecerNombre( "Ismael" );
	$nuevoUsuario -> establecerApellidos( "Salas López" );
	$nuevoUsuario -> establecerFechaNacimiento( "1999-03-11" );
	$nuevoUsuario -> establecerAvatar( "Avatar" );
	$nuevoUsuario -> establecerCorreoElectronico( "isma.salas24@gmail.com" );
	$nuevoUsuario -> establecerUsuario( "elmerolero" );
	$nuevoUsuario -> establecerContrasena( "dsslot#@" );
	$nuevoUsuario -> establecerNivel( 0 );
	$nuevoUsuario -> establecerExperiencia( 0 );


	echo $nuevoUsuario -> toString();