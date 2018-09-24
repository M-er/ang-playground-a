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
        //$db = DBHandler::getHandler();
		$fh = fopen('./logs/dydback.log','r');
		while ($line = fgets($fh)) {
			$acciones[] = $line;
		}
		fclose($fh);
		$rta['acciones'] = $acciones;
		$rta['status'] = 'success';
		return $response->withJson($rta);
	}
	protected function logout(){ Session::destroySession(); }
	function logged($request, $response, array $args){
		if(Session::getSession()){
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
				$sess = Session::createSession($usuario['id'], $usuario['username'], $usuario['type']);
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
