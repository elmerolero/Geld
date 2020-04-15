<?php

	class Registro extends Database
	{
		function __construct()
		{
			parent::__construct();
		}

		function registrarUsuario()
		{
			
	
		}

		function validarFormulario()
		{
			// Crea una conexión a la base de datos
			$this -> crearConexion();

			// Validación de nombre
			if( !empty( $_POST[ 'nombre' ] ) ){
				// Verifica que el nombre introducido sea un nombre válido
				$estado = Registro::nombreValido( $_POST[ 'nombre' ] );
				if( $estado[ 'exitoso' ] ){
					$nombre = $estado[ 'nombre' ];
					unset( $estado );
				}
				else{
					// Returna el error encontrado
					echo json_encode( $estado ); 
					return;
				}
			}
			else{
				// Solicita al usuario que introduzca un nombre
				$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "Introduce un nombre.";
				echo json_encode( $estado );
				return;
			}

			// Validación de apellidos
			if( !empty( $_POST[ 'apellidos' ] ) ){
				// Verifica que los apellidos introducidos sean válidos.
				$estado = Registro::apellidosValidos( $_POST[ 'apellidos' ] );
				if( $estado[ "exitoso" ] ){
					$apellidos = $estado[ 'apellidos' ];
					unset( $estado );
				}
				else{
					// Returna el error encontrado
					echo json_encode( $estado ); 
					return;
				}
			}
			else{
				// Solicita al usuario que introduzca al menos un apellido
				$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "Introduce al menos un apellido.";
				echo json_encode( $estado );
				return;
			}

			// Validación de fecha de nacimiento
			if( !empty( $_POST[ 'cumpleanios' ] ) ){
				// Verifica que introdujo una fecha de nacimiento válida
				$estado = Registro::fechaNacimientoValida( $_POST[ 'cumpleanios' ] );
				if( $estado[ "exitoso" ] ){
					$cumpleanios = $estado[ 'fecha' ];
					unset( $estado );
				}
				else{
					// Returna el error encontrado
					echo json_encode( $estado ); 
					return;
				}
			}
			else{
				// Solicita al usuario que introduzca una fecha de nacimiento
				$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "Introduce una fecha de nacimiento.";
				echo json_encode( $estado );
				return;
			}

			// Validación de apodo
			if( !empty( $_POST[ 'apodo' ] ) ){
				$estado = $this -> apodoValido( $_POST[ 'apodo' ] );
				if( $estado[ "exitoso" ] ){
					$apodo = $estado[ 'apodo' ];
					unset( $estado );
				}
				else{
					// Returna el error encontrado
					echo json_encode( $estado ); 
					return;
				}
			}
			else{
				// Solicita al usuario que introduzca un apodo
				$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "Introduce un apodo.";
				echo json_encode( $estado );
				return;
			}

			// Validación de correo electrónico
			if( !empty( $_POST[ 'correo' ] ) ){
				$estado = $this -> correoValido( $_POST[ 'correo' ] );
				if( $estado[ 'exitoso' ] ){
					$correo = $estado[ 'correo' ];
					unset( $estado );
				}
				else{
					// Returna el error encontrado
					echo json_encode( $estado ); 
					return;
				}
			}
			else{
				// Solicita al usuario que introduzca un correo electrónico
				$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "Introduce un correo electrónico.";
				echo json_encode( $estado );
				return;
			}


			// Validación de contraseña
			if( !empty( $_POST[ 'contrasena' ] ) ){
				$estado = Registro::contrasenaValida( $_POST[ 'contrasena' ] );
				if( $estado[ 'exitoso' ] ){
					$contrasena = $estado[ 'contrasena' ];
					
					// Encripta la contraseña
					$contrasena = password_hash( $contrasena, PASSWORD_BCRYPT );
					unset( $estado );
				}
				else{
					// Returna el error encontrado
					echo json_encode( $estado ); 
					return;
				}
			}
			else{
				// Indica que es necesaria una contraseña
				$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "Introduce una contrasena.";
				echo json_encode( $estado );
				return;
			}

			echo "<p><strong>Nombre: </strong>" . $nombre . "</p>" .
				 "<p><strong>Apellidos: </strong>" . $apellidos . "</p>" .
				 "<p><strong>Fecha de nacimiento: </strong>" . $cumpleanios . "</p>" .
				 "<p><strong>Apodo: </strong>" . $apodo . "</p>" .
				 "<p><strong>Correo electrónico: </strong>" . $correo . "</p>" .
				 "<p><strong>Contraseña encriptada: </strong>" . $contrasena . "</p>";

			// Si llegó aquí reiniciamos estado y lo reestablecemos en exitoso como verdad
			unset( $estado );
			$estado[ 'exitoso' ] = true;

			// Cerramos la conexión con la base de datos
			$this -> cerrarConexion();

			// Devolvemos el estado del registro.
			echo json_encode( $estado );
		}

		/* Aspectos a validar */
		/* - Solo puede contener letras del alfabeto (ni caracteres expeciales o números). 
		   - El tamaño máximo del String debe ser 100 caracteres.
		*/
		public static function nombreValido( $nombre )
		{
			// Se asume que la validación será exitosa
			$estado[ 'exitoso' ] = true;

			// Filtro de seguridad
			$nombre = Registro::filtrarString( $nombre );

			// Se asegura que el largo del nombre sea menor a 100 caracteres
			if( strlen( $nombre ) > 100 ){
				$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "El nombre excede el tamaño máximo (cien letras).";
				return $estado;
			}

			// Verifica que se introduzca un nombre sin caracteres especiales
			if( preg_match( "/^[A-Z][a-zA-Z -]+$/", $nombre ) === 0 ){
				$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "Introduzca un nombre válido.";
				return $estado;
			}

			$estado[ 'nombre' ] = $nombre;

			return $estado;
		}

		// Devuelve verdadero su los apellidos introducidos son válidos
		public static function apellidosValidos( $apellidos )
		{
			$estado['exitoso'] = true;

			// Filtro de seguridad
			$apellidos = Registro::filtrarString( $apellidos );

			// Se asegura que el largo del nombre sea menor a 100 caracteres
			if( strlen( $apellidos ) > 100 ){
				$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "Los apellidos no deberían superar las cien letras.";
				return $estado;
			}

			// Verifica que se introduzca un nombre sin caracteres especiales
			if( preg_match( "/^[A-Z][a-zA-Z -]+$/", $apellidos ) === 0 ){
				$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "Introduzca apellidos válidos.";
				return $estado;
			}

			$estado['apellidos'] = $apellidos;

			return $estado;
		}

		// Devuelve verdadero su los apellidos introducidos son válidos
		public static function fechaNacimientoValida( $fecha )
		{
			$estado['exitoso'] = true;

			// Filtro de seguridad
			$fecha = Registro::filtrarString( $fecha );

			// ¿Se intrudujo la fecha en un formato válido?
			if( preg_match( "/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/", $fecha ) === 0 ){
				$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "Formato de fecha inválido.";
				return $estado;
			}

			$estado['fecha'] = $fecha;

			return $estado;
		}

		public function apodoValido( $apodo )
		{
			$estado['exitoso'] = true;

			// Filtro de seguridad
			$apodo = Registro::filtrarString( $apodo );

			// Se asegura que el largo del apodo sea menor a 20 caracteres
			if( strlen( $apodo ) > 20 ){
				$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "El apodo es muy largo (máximo veinte letras).";
				return $estado;
			}

			// ¿Se introdujo un apodo válido?
			if( preg_match( "/^[0-9a-zA-Z_]{5,}$/", $apodo ) === 0 ){
				$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "El apodo debe tener almenos 6 letras y solo puede contener letras, dígitos o guión bajo.";
				return $estado;
			}

			// ¿No hay nadie más que utilice ese apodo?
			$consulta = "select nombreUsuarioUtilizado( '" . $apodo . "' ) as esUtilizado";
			$this -> consultar( $consulta );
			if( $this -> hayResultados() ){
				if( ($this -> obtenerRenglon())[ 'esUtilizado' ] ){
					$estado[ 'exitoso' ] = false;
					$estado[ 'mensaje' ] = "Este apodo ya está siendo utilizado, intenta con otro.";
					$this -> finalizarConsulta();
					$this -> cerrarConexion();
					return $estado;
				}
			}

			$estado['apodo'] = $apodo;

			return $estado;
		}

		public function correoValido( $correo )
		{
			$estado[ 'exitoso' ] = true;

			// Filtro de seguridad
			$correo = Registro::filtrarString( $correo );

			// Se asegura que el largo del correo electrónico sea menor que 320 caracteres
			if( strlen( $correo ) > 320 ){
				$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "El correo electrónico es excesivamente largo ¿estás seguro que lo escribiste correctamente?";
				return $estado;
			}

			// ¿Se introdujo un correo electrónico válido?
			if( preg_match( "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $correo ) === 0 ){
				$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "No has introducido un correo electrónico valido.";
				return $estado;
			}

			// ¿No hay nadie más que utilice ese correo electronico?
			$consulta = "select correoElectronicoUtilizado( '" . $correo . "' ) as esUtilizado";
			$this -> consultar( $consulta );
			if( $this -> hayResultados() ){
				if( ($this -> obtenerRenglon())[ 'esUtilizado' ] ){
					$estado[ 'exitoso' ] = false;
					$estado[ 'mensaje' ] = "Este correo electrónico ya está siendo utilizado, intenta con otro.";
					$this -> finalizarConsulta();
					$this -> cerrarConexion();
					return $estado;
				}
			}
			else{
				echo "Revisar consulta, no arrojó resultados.";
			}

			$estado['correo'] = $correo;

			return $estado;
		}

		public static function contrasenaValida( $contrasena )
		{
			$estado['exitoso'] = true;

			$contrasena = Registro::filtrarString( $contrasena );

			// Se asegura que el largo de la contraseña sea menor a 20 caracteres
			if( strlen( $contrasena ) > 20 ){
				$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "Contraseña enorme (máximo veinte caracteres).";
				return $estado;
			}

			if( preg_match( '/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,20}$/', $contrasena ) === 0 ){
				$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "Contraseña no válida, debe tener al menos un número, una letra (o éstos símbolos '!@#$%') y debe de tener entre ocho y veinte caracteres.";
				return $estado;
			}

			$estado['contrasena'] = $contrasena;

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