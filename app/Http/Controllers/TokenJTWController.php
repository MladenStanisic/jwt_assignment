<?php

namespace App\Http\Controllers;

use App\Models\TokenJTW;
use Illuminate\Http\Request;

class TokenJTWController extends Controller
{

	/**
	 * Store a newly created JTW token in cookie
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		// Check if email is set, and generate the token
		if (isset($request->email) && filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
			TokenJTW::set_jtw_token($request->email);
			return response()->json(['OK' => 'Token generated']);

		// Return error if not
		} else {
			return response()->json(['Error' => 'You must set email key and valid email value (example@gmail.com)']);
		}
	}
}
