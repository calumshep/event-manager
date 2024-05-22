<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class GBSkiController extends Controller
{
    public function activeRegistrations()
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

        return response()->json($data);
    }
}
