<?php

namespace App\Http\Controllers;

use App\Enums\Gender;
use App\Http\Requests\StoreEntrantRequest;
use App\Http\Requests\UpdateEntrantRequest;
use App\Models\Entrant;

class EntrantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('entrants.index', [
            'entrants' => auth()->user()->entrants
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('entrants.form', [
            'entrant'    => new Entrant(),
            'genders'       => array_column(Gender::cases(), 'value'),
            'readonly'      => false,
            'creating'      => true,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEntrantRequest $request)
    {
        $input = $request->validated();

        $entrant = new Entrant([
            'name'      => $input['name'],
            'dob'       => $input['dob'],
            'gender'    => $input['gender'],
            'reg_id'    => $input['reg_id'] ?: '',
        ]);
        auth()->user()->entrants()->save($entrant);

        return redirect()->route('entrants.index', [
            'entrant' => $entrant,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Entrant $entrant)
    {
        return view('entrants.form', [
            'entrant'    => $entrant,
            'genders'       => array_column(Gender::cases(), 'value'),
            'readonly'      => true,
            'creating'      => false,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Entrant $entrant)
    {
        return view('entrants.form', [
            'entrant'    => $entrant,
            'genders'       => array_column(Gender::cases(), 'value'),
            'readonly'      => false,
            'creating'      => false,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEntrantRequest $request, Entrant $entrant)
    {
        $input = $request->validated();

        $entrant->fill([
            'name'      => $input['name'],
            'dob'       => $input['dob'],
            'gender'    => $input['gender'],
            'reg_id'    => $input['reg_id'] ?: '',
        ]);

        auth()->user()->entrants()->save($entrant);

        return redirect()->route('entrants.show', $entrant);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entrant $entrant)
    {
        $entrant->delete();

        return redirect()->route('entrants.index')->with([
            'warning' => 'Entrant deleted.'
        ]);
    }
}
