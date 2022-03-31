<?php

namespace App\Http\Controllers;

use App\Models\JWT;
use Illuminate\Http\Request;

class JWTController extends Controller
{

	/**
	 * Store a newly created JWT token in cookie
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
        // Validation of email
		$request->validate([
			'email' => 'required|email'
		]);

		// Check if token is set
		if (!JWT::validate_jwt()) {

			/**
			 * TODO:
			 * 1. send password as well
			 * 2. Get to repository and get user by email
			 * 3. compare result.password from repository with provided password
			 * 4. create token only if they match
			 */
			JWT::set_jwt($request->email);

			return response()->json(['OK' => 'JWT generated: ' . JWT::get_jwt()]);
		} else {
			return response()->json(['OK' => 'You already have valid JWT: ' . JWT::get_jwt()]);
		}
	}
}
