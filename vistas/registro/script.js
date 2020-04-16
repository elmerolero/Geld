$( document ).ready( main );

$( "#formulario-registro" ).submit( function( event ){ event.preventDefault(); registrarUsuario(); } );

function main()
{
	console.log( "Geld" );
}

function registrarUsuario()
{
	// Crea un archivo JSON para enviar los datos
	var usuario = { nombre 		: $("#nombre").val(),
					apellidos 	: $("#apellidos").val(),
					nacimiento  : $("#nacimiento").val(),
					usuario 	: $("#usuario").val(),
					correo 		: $("#correo").val(),
					contrasena 	: $("#contrasena").val(),
					ccontrasena : $("#ccontrasena").val()  };

	// Creamos el objeto de peticion
	var peticion = { type		: 'POST',
					 url		: "/registro/registrarUsuario",
					 data		: usuario,
					 dataType	: 'json',
					 encode		: true };

	$.ajax( peticion ).done( function( data ){ mostrarEstado( data ); });
}

function mostrarEstado( data )
{
	if( !data.exitoso ){
		$( "#estado" ).css( "background", "rgb(179, 33, 14)" );
		$( "#estado" ).css( "color", "white" );
		$( "#estado" ).text( data.mensaje );
		$( "#estado" ).show();
	}
	else{
		$( "#estado" ).css( "background", "rgb(17, 179, 14)" );
		$( "#estado" ).css( "color", "white" );
		$( "#estado" ).text( "Registro exitoso" );
		$( "#estado" ).show();
	}
}