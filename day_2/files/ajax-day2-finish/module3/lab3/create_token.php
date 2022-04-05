<?php

include "vendor/autoload.php";

use Lcobucci\JWT\Builder;

if(
  $_POST['login'] != 'user' ||
  $_POST['password'] != '123'
) {
  header('HTTP/1.0 401 Unauthorized');
  
  die;
}

$time = time();
$token = (new Builder())->issuedBy('http://mysite.local') // конфигурирует эмитента 
                        ->permittedFor('http://mysite.local') // конфигурирует аудиторию
                        ->identifiedBy('4f1g23a12ba', true) // настраивает идентификатор (утверждение jti), реплицируя как элемент заголовка
                        ->issuedAt($time) // настраивает время, когда токен был выдан (iat заявки)
                        ->canOnlyBeUsedAfter($time + 60) // настраивает время, в течение которого токен может быть использован (утверждение nbf)
                        ->expiresAt($time + 3600) // настраивает время истечения токена (exp заявки)
                        ->withClaim('uid', 1) // настраивает новую заявку под названием «uid»
                        ->getToken(); // получает сгенерированный токен


$token->getHeaders(); // Получает заголовки токена
$token->getClaims(); // Получает токен претензий

//echo $token->getHeader('jti'); // будет "4f1g23a12ba"
//echo $token->getClaim('iss'); // будет "http://mysite.local"
//echo $token->getClaim('uid'); // будет "1"
echo $token; // строковое представление объекта является строкой JWT (довольно просто, правда?)
