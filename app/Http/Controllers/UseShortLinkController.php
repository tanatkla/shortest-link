<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UseShortLinkController extends Controller
{
    public function shortestLink($shortest_url)
    {
        $url = Url::where('short_url', $shortest_url)->first();
        // dd($url);
        if ($url) {
            $original_url = $url->original_url;
            $parsed_url = parse_url($original_url);
    
            if (!isset($parsed_url['scheme'])) {
                $original_url = 'http://' . $original_url;
            }
    
            return redirect($original_url);
        } else {
            if (Auth::user()) {
                return redirect()->route('shortest-links.create');
            } else {
                return redirect()->route('short-link-guest-forms.index');
            }
        }
    }
}
