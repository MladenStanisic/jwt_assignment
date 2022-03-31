<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenJTW extends Model
{
	use HasFactory;

	/**
	 * Header - algorithm
	 */
	public string $header_algorithm = 'HS256';

	/**
	 * Header - token type
	 */
	public string $header_tokentype = 'JWT';

	/**
	 * Verify signature - secret key
	 */
	private string $secret_key = 'somesecretkey';

	/**
	 * Expire time of cookie/token in minutes
	 */
	public int $expire_time_minutes = 10;

	/**
	 * Payload info (for now just email/expire time)
	 */
	public array $payload;



	/**
	 * Method that returns generated jwt token
	 *
	 * @param string $email
	 *
	 * @return string $jtw = token
	 */
	public function generate_jwt(string $email): string
	{
		// 1.Header part
		$headers_encoded = $this->base64url_encode(json_encode($this->get_jtw_header()));

		// 2.Payload part
		$this->payload['email'] = $email;
		$this->payload = $this->add_expire_time_to_payload($this->payload);
		$payload_encoded = $this->base64url_encode(json_encode($this->payload));

        $signature = hash_hmac('SHA256', "$headers_encoded.$payload_encoded", $this->secret_key, true);
        $signature_encoded = $this->base64url_encode($signature);

		// All combined in jtw
        $jwt = "$headers_encoded.$payload_encoded.$signature_encoded";

		return $jwt;
	}


	/**
	 * Return array (1. part of token, header)
	 */
	public function get_jtw_header(): array
	{
		return [
			'alg' => $this->header_algorithm,
			'typ' => $this->header_tokentype
		];
	}

	/**
	 * Method that add exp param to payload array
	 *
	 * @param array $payload
	 *
	 * @return array
	 */
	public function add_expire_time_to_payload(array $payload): array
	{
		$date = new \DateTime();
		$date->modify("+" . $this->expire_time_minutes . " minutes");
		$payload['exp'] = $date->getTimestamp();

		return $payload;
	}

	/**
	 * Encoding string method
	 *
	 * @return string
	 */
	private function base64url_encode(string $str): string
	{
		return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
	}

	/**
	 * Check if token is valid
	 *
	 * @param string $jtw_token
	 *
	 * @return bool
	 */
	public function is_jwt_valid($jtw_token): bool
	{
		// split the jwt
		$data_token = $this->split_jtw_token($jtw_token);

		// check the expiration time
		$is_token_expired = $this->is_token_expired(json_decode($data_token['payload'])->exp);

		// build a signature based on the header and payload using the secret
		$base64_url_header = $this->base64url_encode($data_token['header']);
		$base64_url_payload = $this->base64url_encode($data_token['payload']);
		$signature = hash_hmac('SHA256', $base64_url_header . "." . $base64_url_payload, $this->secret_key, true);
		$base64_url_signature = $this->base64url_encode($signature);

		// verify it matches the signature provided in the jwt
		$is_signature_valid = ($base64_url_signature === $data_token['signature']);

		if (!$is_signature_valid)
			return false;
		elseif ($is_token_expired)
			return false;
		else
			return true;
	}

	/**
	 * This method splits jtw token into 3 parts
	 * 1. header
	 * 2. payload
	 * 3. signature
	 *
	 * @param string $jtw_token
	 *
	 * @return array
	 */
	private function split_jtw_token(string $jtw_token): array
	{
		// split the jwt
		$tokenParts = explode('.', $jtw_token);
		$aData['header'] = base64_decode($tokenParts[0]);
		$aData['payload'] = base64_decode($tokenParts[1]);
		$aData['signature'] = $tokenParts[2];

		return $aData;
	}

	/**
	 * Check if token is expired
	 *
	 * @param $expiration_time (exp)
	 *
	 * @return bool
	 */
	private function is_token_expired($expiration_time): bool
	{
		return ($expiration_time - time()) < 0;
	}

	/**
	 * Static method that generate JTW token and set cookie
	 *
	 * @return void
	 */
	public static function set_jtw_token(string $email): void
	{

		$oJTWToken = new TokenJTW;
		$tokenValue = $oJTWToken->generate_jwt($email);

		setcookie('JTWTokken', $tokenValue, 0, "", "", false, true);
	}


	/**
	 * Static method that verifies JTW token
	 *
	 * @return bool
	 */
	public static function validate_jtw_token():bool
	{

		if (isset($_COOKIE['JTWTokken'])) {
			$oJTWToken = new TokenJTW;
			return $oJTWToken->is_jwt_valid($_COOKIE['JTWTokken']);
		} else {
			return false;
		}
	}
}
