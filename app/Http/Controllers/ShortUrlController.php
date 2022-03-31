<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ShortUrlController extends Controller
{

	/**
	 * Short url length
	 * @var int
	 */
	private $short_url_lenght = 7;

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return ShortUrl::all();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$aData['name_url_long'] = $request->name_url_long;
		$aData['name_url_short'] = substr(md5(rand()), 0, $this->short_url_lenght);

		try {
			ShortUrl::create($aData);
			return response()->json(['Ok' => 'Short url created: ' . $aData['name_url_short']]);

		} catch (\Exception $exception) {
			return response()->json(['Error' => $exception->getMessage()]);
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}

    /**
     * Search for the long url
     *
     * @param str $name_url_short
     *
	 * @return \Illuminate\Http\Response
     *
     */
    public function search($name_url_short) {
        return ShortUrl::where('name_url_short', $name_url_short)->get();
    }

    /**
     * Search for the long url
     *
     * @param str $name_url_short
     *
	 * @return Illuminate\Support\Facades\Redirect
     *
     */
    public function openurl($name_url_short) {

        $urls = $this->search($name_url_short);
        return redirect()->away($urls[0]->name_url_long);


    }
}
