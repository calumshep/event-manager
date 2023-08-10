<?php

namespace App\Http\Controllers;

use App\Enums\Gender;
use App\Http\Requests\StoreCompetitorRequest;
use App\Http\Requests\UpdateCompetitorRequest;
use App\Models\Competitor;

class CompetitorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('competitors.index', [
            'competitors' => auth()->user()->competitors
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('competitors.form', [
            'competitor'    => new Competitor(),
            'genders'       => array_column(Gender::cases(), 'value'),
            'readonly'      => false,
            'creating'      => true,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompetitorRequest $request)
    {
        $input = $request->validated();

        $competitor = new Competitor([
            'name'      => $input['name'],
            'dob'       => $input['dob'],
            'gender'    => $input['gender'],
            'reg_id'    => $input['reg_id'] ?: '',
        ]);
        auth()->user()->competitors()->save($competitor);

        return redirect()->route('competitors.index', [
            'competitor' => $competitor,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Competitor $competitor)
    {
        return view('competitors.form', [
            'competitor'    => $competitor,
            'genders'       => array_column(Gender::cases(), 'value'),
            'readonly'      => true,
            'creating'      => false,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Competitor $competitor)
    {
        return view('competitors.form', [
            'competitor'    => $competitor,
            'genders'       => array_column(Gender::cases(), 'value'),
            'readonly'      => false,
            'creating'      => false,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompetitorRequest $request, Competitor $competitor)
    {
        $input = $request->validated();

        $competitor->fill([
            'name'      => $input['name'],
            'dob'       => $input['dob'],
            'gender'    => $input['gender'],
            'reg_id'    => $input['reg_id'] ?: '',
        ]);

        auth()->user()->competitors()->save($competitor);

        return redirect()->route('competitors.show', $competitor);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Competitor $competitor)
    {
        //
    }
}
