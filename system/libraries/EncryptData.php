<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CI_EncryptData
{

    private $secret_key = "9658db1bbd22c04758e411749defd697"; // Replace with a secure key
    private $cipher = "AES-256-CBC";      // Cipher algorithm
    // private $cipher = "AES-256-GCM";      // Cipher algorithm
    private $iv_length;
    private $key;

    public function __construct()
    {
        // Get the IV length based on the cipher
        $this->iv_length = openssl_cipher_iv_length($this->cipher);
        $this->key = hash('sha256', '27d659aa376bead69eab2889b84eddee'); // Hashing the key for consistent length
    }
    /**
     * Encrypt the given data.
     *
     * @param string $data
     * @return string
     */
    public function encrypt($data)
    {
        $iv = openssl_random_pseudo_bytes($this->iv_length);
        $encrypted = openssl_encrypt($data, $this->cipher, $this->secret_key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    /**
     * Decrypt the given encrypted data.
     *
     * @param string $encrypted_data
     * @return string
     */
    public function decrypt($encrypted_data)
    {
        $data = base64_decode($encrypted_data);
        $iv = substr($data, 0, $this->iv_length);
        $encrypted = substr($data, $this->iv_length);
        return openssl_decrypt($encrypted, $this->cipher, $this->secret_key, 0, $iv);
    }


    /**
     * Encrypts the given data.
     *
     * @param string $data The plain text to encrypt.
     * @return string|false The encrypted string or false on failure.
     */
    public function strongencrypt($data)
    {
        $iv_length = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($iv_length); // Generate a secure IV

        $encrypted = openssl_encrypt($data, $this->cipher, $this->key, 0, $iv);
        if ($encrypted === false) {
            return false; // Encryption failed
        }

        // Combine IV and encrypted data for storage
        return base64_encode($iv . $encrypted);
    }

    /**
     * Decrypts the given encrypted data.
     *
     * @param string $data The base64-encoded string to decrypt.
     * @return string|false The decrypted string or false on failure.
     */
    public function strongdecrypt($data)
    {
        $iv_length = openssl_cipher_iv_length($this->cipher);
        $data = base64_decode($data);
        if ($data === false) {
            return false; // Decoding failed
        }

        // Extract IV and encrypted data
        $iv = substr($data, 0, $iv_length);
        $encrypted = substr($data, $iv_length);

        return openssl_decrypt($encrypted, $this->cipher, $this->key, 0, $iv);
    }


    // encoding url
    public function urlencode($data){

        $iv = openssl_random_pseudo_bytes($this->iv_length);
        $encrypted = openssl_encrypt($data, $this->cipher, $this->secret_key, 0, $iv);
        return urlencode(base64_encode($iv . $encrypted));
    }   // function ends

    // decoding url
    public function urldecode($encrypted_data){

        $data = base64_decode(urldecode($encrypted_data));
        $iv = substr($data, 0, $this->iv_length);
        $encrypted = substr($data, $this->iv_length);
        return openssl_decrypt($encrypted, $this->cipher, $this->secret_key, 0, $iv);
    }   // function ends

    // function to encrypt data
    function encryptData($data)
    {
        $iv = substr(hash('sha256', $data, true), 0, 16);
        $key = hash('sha256', $iv, true);
        $encryptedData = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
        return base64_encode($iv . $encryptedData);
    }   // functione ends

    // function to decrypt data
    function decryptData($encryptedData)
    {
        $decodedData = base64_decode($encryptedData);
        $iv = substr($decodedData, 0, 16);
        $encryptedData = substr($decodedData, 16);
        $key = hash('sha256', $iv, true);
        $decryptedData = openssl_decrypt($encryptedData, 'aes-256-cbc', $key, 0, $iv);
        return $decryptedData;
    }   // function ends
}
