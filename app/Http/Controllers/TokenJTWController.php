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
        if (is_string($request->email)) {
            TokenJTW::set_jtw_token($request->email);
            return response()->json(['OK' => 'Token generated']);
        } else {
            return response()->json(['Error' => 'You must set email address']);
        }
    }
}
