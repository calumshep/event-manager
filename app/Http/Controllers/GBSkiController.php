<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GBSkiController extends Controller
{
    public function activeRegistrations(string $query = null)
    {
        // Parse CSV into competitor objects (from https://www.php.net/manual/en/function.str-getcsv.php)
        $data = str_getcsv(Storage::get('competitors.csv'), "\n");
        foreach($data as &$row) {
            $row = str_getcsv($row); // parse the items in rows
        }

        array_walk($data, function(&$a) use ($data) {
            $a = array_combine($data[0], $a); // object-ify data
        });
        array_shift($data); // remove column header

        if ($query) {
            // if a query string is given, respond with results filtered (by name/reg. no) for that query
            return response()->json(collect($data)->filter(function($item) use ($query) {
                return str_contains(strtolower($item['FIRSTNAME']), strtolower($query))
                    || str_contains(strtolower($item['LASTNAME']), strtolower($query))
                    || str_contains(strtolower($item['REGNO']), strtolower($query));
            }));
        }

        return response()->json($data);
    }
}
