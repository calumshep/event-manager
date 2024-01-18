<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GBSkiController extends Controller
{
    public function activeRegistrations()
    {
        $response = Http::get('https://www.gbski.com/competitors.php?csv&season=2024');

        return $response;
    }
}
