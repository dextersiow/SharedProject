<?php

//take hashed password as key to encrypt data
function encrypt_data($content, $key, $iv) {
    $encrypted_content = openssl_encrypt($content, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
    return bin2hex($encrypted_content);
}

function decrypt_data($cipher, $key, $iv) {
   $decrypted_content = openssl_decrypt(hex2bin($cipher), 'AES-128-CBC', $key, OPENSSL_RAW_DATA, hex2bin($iv));
   return $decrypted_content;
}

function hash_data($data) {
    $hash = password_hash($data, PASSWORD_DEFAULT);
    return bin2hex($hash);
}
?>

