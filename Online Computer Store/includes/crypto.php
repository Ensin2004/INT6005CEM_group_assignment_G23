<?php
// includes/crypto.php

// 32-byte encryption key 
// Generate in keygen.php
const ENC_KEY_HEX = '60d4d9476c34544becd52ff72cdfaec819202e5d715b97648f723c69cdeb32ec';

// Convert hex to binary key
const ENC_METHOD = 'aes-256-gcm';

function get_enc_key(): string {
    return hex2bin(ENC_KEY_HEX);
}

/**
 * Encrypt a single field using AES-256-GCM.
 * Returns base64(iv + tag + ciphertext).
 */
function encrypt_field(?string $plain): string {
    if ($plain === null || $plain === '') {
        return '';
    }

    $key = get_enc_key();
    $iv  = random_bytes(12); // 96-bit IV for GCM
    $tag = '';

    $cipher = openssl_encrypt(
        $plain,
        ENC_METHOD,
        $key,
        OPENSSL_RAW_DATA,
        $iv,
        $tag
    );

    if ($cipher === false) {
        throw new RuntimeException('Encryption failed');
    }

    // Pack iv (12) + tag (16) + cipher into one blob
    return base64_encode($iv . $tag . $cipher);
}

/**
 * Decrypt a field stored as base64(iv + tag + ciphertext).
 */
function decrypt_field(?string $encoded): string {
    if ($encoded === null || $encoded === '') {
        return '';
    }

    $blob = base64_decode($encoded, true);
    if ($blob === false || strlen($blob) < 12 + 16) {
        return '';
    }

    $iv   = substr($blob, 0, 12);
    $tag  = substr($blob, 12, 16);
    $cipher = substr($blob, 28);

    $plain = openssl_decrypt(
        $cipher,
        ENC_METHOD,
        get_enc_key(),
        OPENSSL_RAW_DATA,
        $iv,
        $tag
    );

    return $plain === false ? '' : $plain;
}
