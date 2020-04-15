<?php

	class App
	{
		/* Constantes de la App */
		const URL_CLASE = 0;
		const URL_METODO = 1;

		/* Campos de la App */
		private $url;
		private $controlador;

		function __construct()
		{
			//echo "<h1>Nueva App</h1>";
			$this -> generarURL();
		}

		function start()
		{
			if( isset( $this -> url ) ){
				// Obtiene la clase y el método que se desean invocar
				$claseInvocada = $this -> obtenerElementoURL( self::URL_CLASE );
				$metodoInvocado = $this -> obtenerElementoURL( self::URL_METODO );

				$this -> cargarClase( $claseInvocada );
				$this -> ejecutarMetodo( $metodoInvocado );
			}else{
				$this -> cargarClase( "Main" );
			}
		}

		private function generarURL()
		{
			// Obtiene URL
			if( isset( $_GET[ 'url' ] ) ){
				$url = $_GET[ 'url' ];

				// Ignora los '/' sobrantes
				$url = rtrim( $url, '/' );

				// Separa la URL en Strings por cada '/' encontrada en la URL
				$url = explode( '/', $url );

				$this -> url = $url;
			}
			else{
				$this -> url = null;
			}
		}

		private function cargarClase( $claseAInvocar )
		{
			// Genera la URL del archivo donde se encuentra la clase controladora
			$ubicacionArchivoClase = 'clases/'. $claseAInvocar . '.php';

			// ¿El archivo no existe?
			if( !file_exists( $ubicacionArchivoClase ) ){
				return;
			}

			// Obtiene el archivo de la clase
			require_once $ubicacionArchivoClase;

			// Crea la clase invocada
			$this -> controlador = new $claseAInvocar;
		}

		private function ejecutarMetodo( $metodoAInvocar )
		{
			if( $metodoAInvocar != null && method_exists( $this -> controlador, $metodoAInvocar ) ){
				$this -> controlador -> { $metodoAInvocar }();
			}
		}

		private function obtenerElementoURL( $index )
		{
			// ¿Está dentro de un rango legal?
			if( $index < sizeof( $this -> url ) && $index >= 0){
				return $this -> url[ $index ];
			}

			return null;
		}
}