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
		// Check if email is set, and generate the token
		if (isset($request->email) && filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
			JWT::set_jwt($request->email);
			return response()->json(['OK' => 'JWT generated: '.$_COOKIE['JWT']]);

		// Return error if not
		} else {
			return response()->json(['Error' => 'You must set email key and valid email value (example@gmail.com)']);
		}
	}
}
