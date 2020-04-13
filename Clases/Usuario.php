<?php

class Usuario
{
	/* Campos */
	private $nombre;
	private $apellidos;
	private $fechaNacimiento;
	private $avatar;
	private $nivel;
	private $experiencia;
	private $usuario;
	private $contrasena;


	/* Métdos */
	function establecerNombre( $nombre )
	{
		$this -> nombre = $nombre;
	}

	function establecerApellidos( $apellidos )
	{
		$this -> apellidos = $apellidos;
	}

	function establecerFechaNacimiento( $fechaNacimiento )
	{
		$this -> fechaNacimiento = $fechaNacimiento;
	}



	function toString()
	{
		return "Nombre: " . $this -> nombre . "<br>" .
			   "Apellidos: " . $this -> apellidos . "<br>" .
			   "Fecha nacimiento: " . $this -> fechaNacimiento . "<br>";
	}
}