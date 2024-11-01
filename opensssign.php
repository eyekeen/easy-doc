<?php

$data = 'aldada';

//Создаём новую пару открытый/закрытый ключ
$new_key_pair = openssl_pkey_new(array(
    "private_key_bits" => 2048,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
));

openssl_pkey_export($new_key_pair, $private_key_pem);

$details = openssl_pkey_get_details($new_key_pair);
$public_key_pem = $details['key'];

// Вычисляем подпись
openssl_sign($data, $signature, $private_key_pem, OPENSSL_ALGO_SHA256);

// Сохраняем
file_put_contents('private_key.pem', $private_key_pem);
file_put_contents('public_key.pem', $public_key_pem);
file_put_contents('signature.dat', $signature);

// Сверяем подпись
$ok = openssl_verify($data, $signature, $public_key_pem, "sha256WithRSAEncryption");

if ($ok == 1) {
    echo "корректна";
} elseif ($ok == 0) {
    echo "некорректна";
} else {
    echo "ошибка: ".openssl_error_string();
}
