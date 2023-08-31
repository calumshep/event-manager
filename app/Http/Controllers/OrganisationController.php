<?php

namespace App\Http\Controllers;

use App\Enums\Gender;
use App\Http\Requests\Auth\StoreOrganisationRequest;
use App\Http\Requests\Auth\UpdateOrganisationRequest;
use App\Models\Competitor;
use App\Models\Organisation;
use Illuminate\Http\Request;

class OrganisationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('organisations.index', [
            'organisations' => auth()->user()->organisations,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('organisations.form', [
            'organisation'  => new Organisation(),
            'readonly'      => false,
            'creating'      => true,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrganisationRequest $request)
    {
        $input = $request->validated();

        $organisation = new Organisation([
            'name'          => $input['name'],
            'description'   => $input['description'],
            'website'       => $input['website'],
        ]);
        auth()->user()->organisations()->save($organisation);

        return redirect()->route('organisations.index', [
            'organisation' => $organisation,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Organisation $organisation)
    {
        return view('organisations.form', [
            'organisation'  => $organisation,
            'readonly'      => true,
            'creating'      => false,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Organisation $organisation)
    {
        return view('organisations.form', [
            'organisation'  => $organisation,
            'readonly'      => false,
            'creating'      => false,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrganisationRequest $request, Organisation $organisation)
    {
        $input = $request->validated();

        $organisation->fill([
            'name'          => $input['name'],
            'description'   => $input['description'],
            'website'       => $input['website'],
        ]);
        $organisation->save();

        return redirect()->route('organisations.show', [
            'organisation' => $organisation,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organisation $organisation)
    {
        $organisation->delete();

        return redirect()->route('organisations.index')->with([
            'warning' => 'Organisation deleted.'
        ]);
    }
}
