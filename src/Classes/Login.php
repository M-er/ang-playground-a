<?php
namespace App;
/**
*
 Clase Login para ingreso o salida del sistema
*
**/
class Login
{

	public function __construct($logger) {$this->logger = $logger;}
	function getAcc($request, $response, array $args){
		$sess = Session::loggedInfo();
		$fh = fopen('./logs/app.log','r');
		while ($line = fgets($fh)) {
			$acciones[] = $line;
		}
		fclose($fh);
		$rta['acciones'] = $acciones;
		$rta['status'] = 'success';
		return $response->withJson($rta);
	}
	function logout($request, $response, array $args){
		Session::destroySession();
		$rta['message'] = "Ha salido del sistema, adios guachin";
		$rta['status'] = "success";
		return $response->withJson($rta);
	}
	function logged($request, $response, array $args){
		$sess = Session::getSession();
		if($sess['id']){
			$rta['sigue'] = true;
		}else{
			Session::destroySession();
			$rta['sigue'] = false;
			$rta['message'] = "No esta logueado";
		}
		return $response->withJson($rta);
	}
	function login( $request, $response, array $args ){
		$conn = new DBHandler();
		$usuario = $request->getParsedBody();
		$username = $usuario['username'];
		$password = $usuario['password'];
		$query = "SELECT id, username, password, email, type FROM user WHERE username = '$username'";
		$usuario = $conn->getOneRecord($query);
		if ($usuario != NULL) {
			if(PasswordHash::check_password($usuario['password'],$password)){
				$rta['status'] = "success";
				$rta['message'] = 'Ha ingresado correctamente';
				$this->logger->addInfo("Ingreso | ".$username);
				Session::destroySession();
				$sess = Session::createSession('1', 'matiSim', '1');
				$rta['username'] = $sess['username'];
				$rta['id'] = $sess['id'];
				$rta['tipouser'] = $sess['type'];
			} else {
				$rta['status'] = "error";
				$rta['message'] = 'Error de inicio de sesion. Credenciales incorrectas';
			}
		}else {
			$rta['status'] = "error";
			$rta['message'] = 'Usuario no registrado.';
		}
		return $response->withJson($rta);
	}
}
?>
