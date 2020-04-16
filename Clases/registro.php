<?php

	class Registro extends Database
	{
		function __construct()
		{
			parent::__construct();
		}

		function sitio()
		{
			header('Location: /vistas/registro/');
		}

		function sitioAvatar()
		{
			$estado = Sesion::sesionIniciada();
			if( !$estado[ 'sesion' ] ){
				header('Location: /inicio/');
				return;
			}

			require_once 'vistas/avatar/index.php';
		}

		function registrarUsuario()
		{	// Verifica que no exista una sesión iniciada
			$estado = Sesion::sesionIniciada();
			if( $estado[ 'sesion' ] ){
				echo json_encode( $estado );
				return;
			}

			// Crea una conexión a la base de datos
			$this -> crearConexion();

			// ¿El nombre introducido es un nombre válido?
			$estado = Registro::stringValido( isset( $_POST[ 'nombre' ] ) ? $_POST[ 'nombre' ] : null , 'nombre', 100 );
			if( $estado[ 'exitoso' ] ){
				// Lo guarda en la variable $nombre
				$nombre = $estado[ 'nombre' ];
				unset( $estado );
			}
			else{
				// Retorna el error encontrado
				echo json_encode( $estado );
				$this -> cerrarConexion(); 
				return;
			}
			
			// ¿El apellido introducido es válido?
			$estado = Registro::stringValido( isset( $_POST[ 'apellidos' ] ) ? $_POST[ 'apellidos' ] : null, 'apellido', 100 );
			if( $estado[ "exitoso" ] ){
				$apellidos = $estado[ 'apellido' ];
				unset( $estado );
			}
			else{
				// Retorna el error encontrado
				echo json_encode( $estado );
				$this -> cerrarConexion();
				return;
			}

			// Verifica que introdujo una fecha de nacimiento válida
			$estado = Registro::fechaNacimientoValida( isset( $_POST[ 'nacimiento' ] ) ? $_POST[ 'nacimiento' ] : null );
			if( $estado[ "exitoso" ] ){
				$cumpleanios = $estado[ 'fecha' ];
				unset( $estado );
			}
			else{
				// Retorna el error encontrado
				echo json_encode( $estado ); 
				$this -> cerrarConexion();
				return;
			}

			// Validación de apodo
			$estado = $this -> usuarioValido( isset( $_POST[ 'usuario' ] ) ? $_POST[ 'usuario' ] : null );
			if( $estado[ "exitoso" ] ){
				$usuario = $estado[ 'usuario' ];
				unset( $estado );
			}
			else{
				// Retorna el error encontrado
				echo json_encode( $estado ); 
				$this -> cerrarConexion();
				return;
			}

			// Validación de correo electrónico
			$estado = $this -> correoValido( isset( $_POST[ 'correo' ] ) ? $_POST[ 'correo' ] : null );
			if( $estado[ 'exitoso' ] ){
				$correo = $estado[ 'correo' ];
				unset( $estado );
			}
			else{
				// Retorna el error encontrado
				echo json_encode( $estado );
				$this -> cerrarConexion();
				return;
			}

			// Validación de contraseña
			$estado = Registro::contrasenaValida( isset( $_POST[ 'contrasena' ] ) ? $_POST[ 'contrasena' ] : null );
			if( $estado[ 'exitoso' ] ){
				$contrasena = $estado[ 'contrasena' ];
				unset( $estado );

				// ¿No se introdujo la confirmación de contraseña?
				if( empty( $_POST['ccontrasena'] ) ){
					$estado[ 'exitoso' ] = false;
					$estado[ 'mensaje' ] = "Confirma tu contraseña.";
					echo json_encode( $estado );
					return; 
				}

				// Se comparan las contraseñas
				if( strcmp( $contrasena, $_POST[ 'ccontrasena' ] ) != 0 ){
					$estado[ 'exitoso' ] = false;
					$estado[ 'mensaje' ] = "Las contraseñas no coinciden. Inténtalo de nuevo.";
					echo json_encode( $estado );
					return; 
				}
					
				// Encripta la contraseña
				$contrasena = password_hash( $contrasena, PASSWORD_BCRYPT );
				unset( $estado );
			}
			else{
				// Retorna el error encontrado
				echo json_encode( $estado );
				$this -> cerrarConexion();
				return;
			}
			
			if( !( $this -> registrarDatosUsuario( $nombre, $apellidos, $cumpleanios, $usuario, $correo, $contrasena ) ) ){
				echo $this -> obtenerErrorConsulta();
				return;
			} 

			// Se inicia una nueva sessión y se guarda el nombre de usuario
			Sesion::establecerSesion( $usuario ); 

			// Si llegó aquí reiniciamos estado y lo reestablecemos en exitoso como verdad
			unset( $estado );
			$estado[ 'exitoso' ] = true;

			// Devolvemos el estado del registro.
			echo json_encode( $estado ); 

			// Cerramos la conexión con la base de datos
			$this -> cerrarConexion();
		}

		function agregarAvatar()
		{
			// Para subir un avatar es necesario que exista una sesión iniciada
			$estado = Sesion::sesionIniciada();
			if( !$estado[ 'sesion' ] ){
				echo json_encode( $estado );
				return;
			}else{
				unset( $estado );
			}

			echo "<p>Agregar avatar.</p>";

			if( !isset( $_FILES[ 'avatar' ] ) )
			{
				$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "No se subió ningun archivo.";
				echo json_encode( $estado );
				return;
			}

			$permitido = array('gif', 'png', 'jpg');
			$filename = $_FILES[ 'avatar' ][ 'name' ];
			$extension = pathinfo($filename, PATHINFO_EXTENSION);
			if ( !in_array( $extension, $permitido ) ) {
	    		$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "Archivo de imagen no válido.";
				echo json_encode( $estado );
				return;
			}

			// Obtiene la ruta temporal del archivo
			$nombre = $_FILES[ 'avatar' ][ 'tmp_name' ];

			// Obtiene el nombre del usuario que desea subir el Avatar
			$usuario = $_SESSION[ 'usuario' ];

			// Contruye el directorio donde se guardara el Avatar del usuario
			$directorio = "usuarios/" . $usuario . "/avatar/";

			// Si el directorio no existe...
			if( !file_exists( $directorio ) ){
				mkdir( $directorio, 0755, true ); 
			}

			// Mueve el archivo
			$directorio = $directorio . $usuario . "." . $extension;
			
			// Mueve el archivo al directorio especificado
			if( !move_uploaded_file( $nombre, $directorio ) ){
				$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "Archivo de imagen no válido.";
				echo json_encode( $estado );
				return;
			}

			// Conecta con la base de datos
			$this -> crearConexion();

			// Realiza la consulta
			$consulta = "call agregarAvatar( '" . $usuario . "', '" . $directorio . "' )";
			if( !( $this -> consultar( $consulta ) ) ){
				$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "Error al registrar el avatar, solicitar a soporte.";
				echo json_encode( $estado );
				return;
			}

			$estado[ 'exitoso' ] = true;
			echo json_encode( $estado );
		}

		// Registra al usuario
		private function registrarDatosUsuario( $nombre, $apellidos, $fechaNacimiento, $usuario, $correo, $contrasena )
		{
			$consulta = "INSERT INTO usuarios VALUES ( '$usuario', '$nombre', '$apellidos', '$fechaNacimiento', null, 0, 0 )";
			
			if( $this -> consultar( $consulta ) ){
				$consulta = "INSERT INTO datos_sesion VALUES ( '$usuario', '$correo', '$contrasena' )";
				return $this -> consultar( $consulta );
			}

			return false;
		}

		/* Aspectos a validar */
		/* - Solo puede contener letras del alfabeto (ni caracteres expeciales o números). 
		   - El tamaño máximo del String debe ser 100 caracteres.
		*/
		// String es el string que se desea validar
		// Objeto es el objeto que se desea mostrar 
		// Length es el tamaño maximo que puede medir el string a validar
		public static function stringValido( $string, $objeto, $length )
		{
			// Se asume que la validación será exitosa
			$estado[ 'exitoso' ] = true;

			// ¿Si introdujo un nombre?
			if( empty( $string ) ){
				$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "Introduce " . $objeto . ".";
				return $estado;
			}

			// Filtro de seguridad
			$string = Registro::filtrarString( $string );

			// Se asegura que el largo del nombre sea menor a $length caracteres
			if( strlen( $string ) > $length ){
				$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "El " . $objeto . " excede el tamaño máximo (" . $length . " letras).";
				return $estado;
			}

			// Verifica que se introduzca un nombre sin caracteres especiales
			if( preg_match( "/^[A-Z][a-zA-Z -]+$/", $string ) === 0 ){
				$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "Introduzca un " . $objeto . " válido.";
				return $estado;
			}

			$estado[ $objeto ] = $string;

			return $estado;
		}

		// Devuelve verdadero su los apellidos introducidos son válidos
		public static function fechaNacimientoValida( $fecha )
		{
			$estado['exitoso'] = true;

			if( empty( $fecha ) ){
				// Solicita al usuario que introduzca una fecha de nacimiento
				$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "Introduce una fecha de nacimiento.";
				return $estado;
			}

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

		public function usuarioValido( $usuario )
		{
			$estado['exitoso'] = true;

			// ¿No se introdujo nada?
			if( empty( $usuario ) ){
				// Solicita al usuario que introduzca un apodo
				$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "Introduce un nombre de usuario.";
				return $estado;
			}

			// Filtro de seguridad
			$usuario = Registro::filtrarString( $usuario );

			// Se asegura que el largo del apodo sea menor a 20 caracteres
			if( strlen( $usuario ) > 20 ){
				$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "El apodo es muy largo (máximo veinte letras).";
				return $estado;
			}

			// ¿Se introdujo un apodo válido?
			if( preg_match( "/^[0-9a-zA-Z_]{5,}$/", $usuario ) === 0 ){
				$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "El apodo debe tener almenos 6 letras y solo puede contener letras, dígitos o guión bajo.";
				return $estado;
			}

			// ¿No hay nadie más que utilice ese usuario?
			$consulta = "select * from usuarios where nombre_usuario = '" . $usuario . "'";
			$this -> consultar( $consulta );
			if( $this -> hayResultados() ){
				$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "Este apodo ya está siendo utilizado, intenta con otro.";
				$this -> finalizarConsulta();
				return $estado;
			}else{
				echo $this -> obtenerErrorConsulta();
			}

			$estado['usuario'] = $usuario;

			return $estado;
		}

		public function correoValido( $correo )
		{
			$estado[ 'exitoso' ] = true;

			if( empty( $correo ) ){
				// Solicita al usuario que introduzca un correo electrónico
				$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "Introduce un correo electrónico.";
				return $estado;
			}

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
			$consulta = "select * from datos_sesion where correo_electronico = '" . $correo . "'";;
			$this -> consultar( $consulta );
			if( $this -> hayResultados() ){
				$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "Este correo electrónico ya está siendo utilizado, intenta con otro.";
				$this -> finalizarConsulta();
				return $estado;
			}

			$estado['correo'] = $correo;

			return $estado;
		}

		public static function contrasenaValida( $contrasena )
		{
			$estado['exitoso'] = true;

			// ¿Si se introdujo una contraseña?
			if( empty( $contrasena ) ){
				// Indica que es necesaria una contraseña
				$estado[ 'exitoso' ] = false;
				$estado[ 'mensaje' ] = "Introduce una contrasena.";
				return $estado;
			}

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