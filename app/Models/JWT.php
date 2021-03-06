<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JWT extends Model
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
	 * @return string $jwt = token
	 */
	public function generate_jwt(string $email): string
	{
		// 1.Header part
		$headers_encoded = $this->base64url_encode(json_encode($this->get_jwt_header()));

		// 2.Payload part
		$this->payload['email'] = $email;
		$this->payload = $this->add_expire_time_to_payload($this->payload);
		$payload_encoded = $this->base64url_encode(json_encode($this->payload));

        // 3. Signature part
        $signature = $this->generate_jwt_signature($headers_encoded, $payload_encoded);
        $signature_encoded = $this->base64url_encode($signature);

		// All combined in jwt
        $jwt = "$headers_encoded.$payload_encoded.$signature_encoded";

		return $jwt;
	}


	/**
	 * Return array (1. part of token, header)
	 */
	public function get_jwt_header(): array
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
     * Generate jtw signature
     *
     * @return string
     */
    private function generate_jwt_signature(string $header, string $payload) :string
    {
        return hash_hmac('SHA256', $header . "." . $payload, $this->secret_key, true);
    }

	/**
	 * Check if token is valid
	 *
	 * @param string $jwt
	 *
	 * @return bool
	 */
	public function is_jwt_valid($jwt): bool
	{
		// split the jwt
		$data_token = $this->split_jwt($jwt);

		// check the expiration time
		if($this->is_jwt_expired(json_decode($data_token['payload'])->exp))
            return false;

		// build a signature based on the header and payload using the secret
		$base64_url_header = $this->base64url_encode($data_token['header']);
		$base64_url_payload = $this->base64url_encode($data_token['payload']);
		$signature = $this->generate_jwt_signature($base64_url_header, $base64_url_payload);
		$base64_url_signature = $this->base64url_encode($signature);

		// verify it matches the signature provided in the jwt
		$is_signature_valid = ($base64_url_signature === $data_token['signature']);

		if (!$is_signature_valid)
			return false;

		return true;
	}

	/**
	 * This method splits jwt token into 3 parts
	 * 1. header
	 * 2. payload
	 * 3. signature
	 *
	 * @param string $jwt
	 *
	 * @return array
	 */
	private function split_jwt(string $jwt): array
	{
		// split the jwt
		$tokenParts = explode('.', $jwt);
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
	private function is_jwt_expired($expiration_time): bool
	{
		return ($expiration_time - time()) < 0;
	}

	/**
	 * Static method that generate JWT token and set cookie
	 *
	 * @return void
	 */
	public static function set_jwt(string $email): void
	{
            $oJWTToken = new JWT;
            $tokenValue = $oJWTToken->generate_jwt($email);

            setcookie('JWT', $tokenValue, 0, "", "", false, true);

	}

    /**
     * Return JWT COOKIE if it is set
     *
     * @return bool|string
     */
    public static function get_jwt()
    {
        if (isset($_COOKIE['JWT']))
            return $_COOKIE['JWT'];
        else
            return false;
    }


	/**
	 * Static method that verifies JWT token
	 *
	 * @return bool
	 */
	public static function validate_jwt():bool
	{

		if (self::get_jwt()) {
			$oJWTToken = new JWT;
			return $oJWTToken->is_jwt_valid(self::get_jwt());
		} else {
			return false;
		}
	}
}
