<?php

class Usuario
{
	/* Campos */
	private $nombre;
	private $apellidos;
	private $fechaNacimiento;
	private $avatar;
	private $correoElectronico;
	private $usuario;
	private $contrasena;
	private $nivel;
	private $experiencia;


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

	function establecerAvatar( $fotoAvatar )
	{
		$this -> avatar = $fotoAvatar;
	}

	function establecerCorreoElectronico( $correoElectronico )
	{
		$this -> correoElectronico = $correoElectronico;
	}

	function establecerUsuario()
	{
		$this -> usuario = $usuario;
	}

	function establecerContrasena( $contrasena )
	{
		$this -> contrasena = $contrasena;
	}

	function establecerNivel( $nivel )
	{
		$this -> nivel = $nivel;
	}

	function establecerExperiencia( $experiencia )
	{
		$this -> experiencia = $experiencia;
	}

	// Getters
	function obtenerNombre()
	{
		return $this -> nombre;
	}

	function obtenerApellidos()
	{
		return $this -> apellidos;
	}

	function obtenerFechaNacimiento()
	{
		return $this -> fechaNacimiento;
	}

	function obtenerAvatar()
	{
		return $this -> avatar;
	}

	function obtenerCorreoElectronico()
	{
		return $this -> correoElectronico;
	}

	function obtenerUsuario()
	{
		return $this -> usuario;
	}

	function obtenerContrasena()
	{
		return $this -> contrasena;
	}

	function obtenerNivel()
	{
		return $this -> nivel;
	}

	function obtenerExperiencia()
	{
		return $this -> experiencia;
	}


	function toString()
	{
		return "Nombre: " . $this -> obtenerNombre() . "<br>" .
			   "Apellidos: " . $this -> obtenerApellidos() . "<br>" .
			   "Fecha nacimiento: " . $this -> obtenerFechaNacimiento() . "<br>" .
			   "Avatar: " . $this -> obtenerAvatar() . "<br>" .
			   "Correo electrónico: " . $this -> obtenerCorreoElectronico() . "<br>" .
			   "Usuario: " . $this -> obtenerUsuario() . "<br>" .
			   "Contraseña: " . $this -> obtenerContrasena() . "<br>" .
			   "Nivel: " . $this -> obtenerNivel() . "<br>" .
			   "Experiencia: " . $this -> obtenerExperiencia() . "<br>";
	}
}