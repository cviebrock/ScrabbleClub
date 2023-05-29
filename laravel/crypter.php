<?php namespace Laravel; defined('DS') or die('No direct script access.');

class Crypter {

  /**
   * The encryption cipher.
   *
   * @var string
   */
  public static $cipher = 'aes-256-cbc-hmac-sha256';

  /**
   * Encrypt a string.
   *
   * The string will be encrypted using the AES-256 scheme and will be base64 encoded.
   *
   * @param  string  $value
   * @return string
   */
  public static function encrypt($value)
  {
    $iv = openssl_random_pseudo_bytes(static::iv_size());

    $value = openssl_encrypt($value, static::$cipher, static::key(), OPENSSL_RAW_DATA, $iv);

    return base64_encode($iv.$value);
  }

  /**
   * Decrypt a string.
   *
   * @param  string  $value
   * @return string
   */
  public static function decrypt($value)
  {
    $value = base64_decode($value);

    // To decrypt the value, we first need to extract the input vector and
    // the encrypted value. The input vector size varies across different
    // encryption ciphers and modes, so we'll get the correct size.
    $iv = substr($value, 0, static::iv_size());

    $value = substr($value, static::iv_size());

    $value = openssl_decrypt($value, static::$cipher, static::key(), OPENSSL_RAW_DATA, $iv);

    return $value;
  }

  /**
   * Get the input vector size for the cipher and mode.
   *
   * @return int
   */
  protected static function iv_size()
  {
    return openssl_cipher_iv_length(static::$cipher);
  }

  /**
   * Get the encryption key from the application configuration.
   *
   * @return string
   */
  protected static function key()
  {
    return Config::get('application.key');
  }

}
