<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHelpArticleRequest;
use App\Http\Requests\UpdateHelpArticleRequest;
use App\Models\HelpArticle;

class HelpArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('help.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHelpArticleRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(HelpArticle $helpArticle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HelpArticle $helpArticle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHelpArticleRequest $request, HelpArticle $helpArticle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HelpArticle $helpArticle)
    {
        //
    }
}
