<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\StoreOrganisationRequest;
use App\Http\Requests\Auth\UpdateOrganisationRequest;
use App\Models\Organisation;

class OrganisationController extends Controller
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
        return view('organisations.index', [
            'organisations' => auth()->user()->organisations->sortBy('updated_at')->paginate(6),
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

        $organisation = auth()->user()->organisations()->create([
            'name'          => $input['name'],
            'description'   => $input['description'],
            'website'       => $input['website'],
        ]);

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
        if ($organisation->events->count()) {
            return redirect()->back()->withErrors([
                "You cannot delete an organisation which has events associated with it. Try deleting the events first."
            ]);
        }

        $organisation->delete();

        return redirect()->route('organisations.index')->with([
            'warning' => 'Organisation deleted.'
        ]);
    }
}
