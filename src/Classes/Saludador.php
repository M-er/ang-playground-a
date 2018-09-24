<?php
namespace App;
	/**
	 La clase mas fashion
	 **/
	 class Saludador{

	 	function __construct($logger)
	 	{
	 		$this->logger = $logger;
	 	}
	 	function saluda( $request,  $response, array $args){  
	 		$rta['status'] = 'ok';
	 		return $response->withJson($rta);
	 	}
	 	function hola( $request,  $response, array $args) {
	 		$name = $args['name'];
	 		$this->logger->addInfo('Saludo '.$name);
	 		$rta['status'] = 'ok';
	 		return $response->withJson($rta);
	 	}
	 }

	 ?>
