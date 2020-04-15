<?php

	class Database
	{
		private $host;
		private $db;
		private $usuario;
		private $contrasena;
		private $charset;
		private $conexion;
		private $resultado;

		public function __construct()
		{
			$this -> host = constant( 'HOST' );
			$this -> db = constant( 'DB' );
			$this -> usuario = constant( 'USER' );
			$this -> contrasena = constant( 'PASSWORD' );
			$this -> charset = constant( 'CHARSET' );

			$this -> conexion = null;
			$this -> resultado = null;
		}

		function crearConexion()
		{
			$this -> conexion = mysqli_connect( $this -> obtenerHost(), $this -> obtenerUsuario(), $this -> obtenerContrasena(), $this -> obtenerBaseDeDatos() );

			if( !$this -> conexion ){
				die("Connexión fallida: " . mysqli_connect_error() );
			}
		}

		function cerrarConexion()
		{
			mysqli_close( $this -> conexion	);	
		}

		function consultar( $consulta )
		{
			return $this -> resultado = mysqli_query( $this -> conexion, $consulta );
		}

		function obtenerErrorConsulta()
		{
			return mysqli_error( $this -> conexion );
		}

		function establecerHost( $host )
		{
			$this -> host = $host;
		}

		function establecerBaseDeDatos( $baseDatos )
		{
			$this -> db = $baseDatos;
		}

		function establecerUsuario( $usuario )
		{
			$this -> usuario = $usuario;
		}

		function establecerContrasena( $contrasena )
		{
			$this -> contrasena = $contrasena;
		}


		function obtenerHost()
		{
			return $this -> host;
		}

		function obtenerBaseDeDatos()
		{
			return $this -> db;
		}

		function obtenerUsuario()
		{
			return $this -> usuario;
		}

		function obtenerContrasena()
		{
			return $this -> contrasena;
		}

		function hayResultados()
		{
			if( $this -> resultado != null ){
				return mysqli_num_rows( $this -> resultado ) > 0;
			}

			return false;
		}

		function obtenerRenglon()
		{
			if( $this -> resultado != null ){
				return mysqli_fetch_assoc( $this -> resultado );
			}

			return false;
		}

		function finalizarConsulta()
		{
			$this -> resultado = null;
		}
	}