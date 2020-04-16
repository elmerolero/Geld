<?php
	class Sesion extends Database
	{
		function __construct()
		{
			parent::__construct();
		}

		static public function sesionIniciada()
		{
			session_start();

			if( isset( $_SESSION[ 'usuario' ] ) ){
				$estado[ 'sesion' ] = true;
				$estado[ 'usuario' ] = $_SESSION[ 'usuario' ];
				return $estado;
			}

			$estado[ 'sesion' ] = false;
			return $estado;
		}

		function iniciarSesion()
		{
			$estado = Sesion::sesionIniciada();
			if( $estado[ 'sesion' ] ){
				echo json_encode( $estado );
				return;
			}

			// Conecta a la base de datos
			$this -> crearConexion();

			$usuario = isset( $_POST[ 'usuario' ] ) ? $_POST[ 'usuario' ] : null;
			// Autentica al usuario
			$estado = $this -> autenticarUsuario( $usuario );
			if( !( $estado[ 'sesion' ] ) ){
				echo json_encode( $estado );
				return;
			}
			$usuario = $estado[ 'usuario' ];

			// Verifica la contraseña
			$contrasena = isset( $_POST[ 'contrasena' ] ) ? $_POST[ 'contrasena' ] : null;
			$estado = $this -> validarContrasena( $usuario, $contrasena );
			if( !( $estado[ 'sesion' ] ) ){
				echo json_encode( $estado );
				return;
			}

			echo $usuario;
			$this -> establecerSesion( $usuario );
			echo json_encode( $estado );
		}

		static function establecerSesion( $sesion )
		{
			$_SESSION[ 'usuario' ] = $sesion;
		}

		function cerrarSesion()
		{
			/* Inicializa la sesiçon */
			SESSION_START();

			/* Elimina todas las variables temporales existentes */
			SESSION_UNSET();

			/* Finaliza la sesión */
			SESSION_DESTROY();

			$estado[ 'sesion' ] = false;
			echo json_encode( $estado );
		}

		private function autenticarUsuario( $usuario )
		{
			$estado['sesion'] = true;

			// ¿No se introdujo nada?
			if( empty( $usuario ) ){
				// Solicita al usuario que introduzca un apodo
				$estado[ 'sesion' ] = false;
				$estado[ 'mensaje' ] = "Introduce un nombre de usuario.";
				return $estado;
			}

			// Filtro de seguridad
			$usuario = Sesion::filtrarString( $usuario );

			// Se asegura que el largo del apodo sea menor a 20 caracteres
			if( strlen( $usuario ) > 20 ){
				$estado[ 'sesion' ] = false;
				$estado[ 'mensaje' ] = "El usuario introducido no es válido.";
				return $estado;
			}

			// ¿Se introdujo un apodo válido?
			if( preg_match( "/^[0-9a-zA-Z_]{5,}$/", $usuario ) === 0 ){
				$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "El usuario introducido no es valido.";
				return $estado;
			}

			// ¿No hay nadie más que utilice ese apodo?
			$consulta = "select nombreUsuarioUtilizado( '" . $usuario . "' ) as esUtilizado";
			$this -> consultar( $consulta );
			if( $this -> hayResultados() ){
				if( !($this -> obtenerRenglon())[ 'esUtilizado' ] ){
					$estado[ 'sesion' ] = false;
					$estado[ 'mensaje' ] = "Usuario no encontrado.";
					$this -> finalizarConsulta();
					return $estado;
				}
			}

			$estado[ 'sesion' ] = true;
			$estado[ 'usuario' ] = $usuario;

			return $estado;
		}

		private function validarContrasena( $usuario, $contrasena )
		{
			// ¿se introdujo contraseña?
			if( empty( $contrasena ) ){
				$estado[ 'sesion' ] = false;
				$estado[ 'mensaje' ] = "Introduzca una contraseña.";
				return $estado;
			}

			// Obtiene la contraseña
			$consulta = "call obtenerContrasena( '" . $usuario . "' )";
			$this -> consultar( $consulta );
			if( $this -> hayResultados() ){
				$hash = ( $this -> obtenerRenglon() )[ 'contrasena' ];
				if( password_verify( $contrasena, $hash ) ){
					$estado[ 'sesion' ] = true;
				}
				else{
					$estado[ 'sesion' ] = false;
					$estado[ 'mensaje' ] = "Contraseña incorrecta.";
				}

				return $estado;
			}
			else{
				echo $this -> obtenerErrorConsulta();
			}

			$estado[ 'sesion' ] = false;
			$estado[ 'mensaje' ] = 'Consultar administrador del sistema. Error raro.';

			return $estado;
		}
		
		public static function filtrarString( $string )
		{
			// Filtro de seguridad
			$string = trim( $string );
			$string = stripslashes( $string );		// Elimina las barras diagonales invertidas
			$string = htmlspecialchars( $string );	// Evita caracteres que ponen en riesgo al usuario

			return $string;
		}

	}