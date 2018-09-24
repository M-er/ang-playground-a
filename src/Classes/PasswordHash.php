<?php
namespace App;
/**
*
 Clase PasswordHash encripta y desencripta las password
*
**/
class PasswordHash {
    // blowfish
    private static $algo = '$2a';
    // cost parameter
    private static $cost = '$10';
    public static function unique_salt() {
        return substr(sha1(mt_rand()), 0, 22);
    }
    public static function hash($password) {

        return crypt($password, self::$algo .
            self::$cost .
            '$' . self::unique_salt());
    }
    public static function check_password($hash, $password) {
        $full_salt = substr($hash, 0, 29);
        $new_hash = crypt($password, $full_salt);
        return ($hash == $new_hash);
    }
    function encrypt($request, $response, array $args){
        $psw = $request->getParsedBody();
        $password = $psw['password'];
        $rta['password'] = crypt($password, self::$algo .self::$cost .'$' . self::unique_salt());
        return $response->withJson($rta);

    }
}
?>
