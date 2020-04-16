<!DOCTYPE html>
<html>
<head>
	<!-- Meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Título -->
	<title>Registro - Geld</title>

	<!-- Estilos -->
	<link rel="stylesheet" type="text/css" href="/recursos/estilos/style.css">
	<link rel="stylesheet" type="text/css" href="/vistas/avatar/index.css">
</head>
<body>
	<div class="encabezado">
		<img src="/recursos/imagenes/logo.png" style="height: 7vh; margin-left: 20px;" >
		<div class="encabezado-opciones">
			<a class="opcion" href="/inicio/">Pagina principal</a>
			<a class="opcion" href="#">Inicia Sesión</a>
		</div>
	</div>
	<div class="contenido">
		<div class="seccion">
			<br><h1 align="center">Sube un avatar</h1><br>
			<img src="/vistas/avatar/img/user.png" style= "width: 150px;">
			<form action="/Geld/registro/agregarAvatar" method="POST" enctype="multipart/form-data">
				<label for="avatar">Avatar</label><br>
				<input type="file" name="avatar" id="archivo"><br><br>
				<input type="submit" value="Enviar"/>
			</form>
		</div>
	</div>
	<div class="pie">
		<h4>Geld - Aplicación para el fomento de la cultura del ahorro en México.</h4>
	</div>

	<!-- Scripts -->
	<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script type="text/javascript" src="script.js"></script>
</body>
</html>