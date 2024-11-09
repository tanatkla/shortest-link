<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class ShortLinkGuestController extends Controller
{

    public function index()
    {
        return view('short-link-guest-form');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'original_link' => ['required', 'string'],

            ],
            [],
            [
                'original_link' => 'ลิงก์ที่ต้องการแปลง',
            ]
        );

        $prefix_url = request()->getSchemeAndHttpHost() . '/myshort-';
        // random short link
        $short_random = Str::random(7);
        while (Url::where('short_url', $short_random)->count() > 0) {
            $short_random = Str::random(7);
        }

        $url_model = new Url();
        $url_model->original_url = $request->original_link;
        $url_model->short_url = $short_random;
        $url_model->short_path_name = $prefix_url . $short_random;
        if (Auth::user()) {
            $url_model->user_id = Auth::user()->id;
            $url_model->link_name = '-';
        }
        $url_model->save();

        $response_data = $url_model->short_path_name;
        return response()->json(
            [
                'success' => true,
                'data' => $response_data
            ]
        );
    }
}
