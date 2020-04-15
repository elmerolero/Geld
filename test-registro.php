<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<form action="/Geld/registro/registrarUsuario" method="POST">
		<input type="text" placeholder="Nombre" name="nombre"><br>
		<input type="text" placeholder="Apellidos" name="apellidos"><br>
		<label for="fecha-nacimiento">Fecha de nacimiento</label><br><input type="date" name="cumpleanios"><br><br>
		<input type="text" placeholder="Apodo" name="apodo" ><br>
		<input type="email" placeholder="Correo Electrónico" name="correo"><br>
		<input type="password" placeholder="Contraseña" name="contrasena"><br>
		<input type="submit" value="Enviar"/>
	</form>
</body>
</html>