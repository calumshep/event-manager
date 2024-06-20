<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\StoreOrganisationRequest;
use App\Http\Requests\Auth\UpdateOrganisationRequest;
use App\Models\Organisation;
use App\Support\StripeHelper;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Stripe\Exception\ApiErrorException;

class OrganisationController extends Controller
{
    public function __construct()
    {
        // Use the App\Policies\EventPolicy class to authorize
        $this->authorizeResource(Organisation::class, 'organisation');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('organisations.index', [
            'organisations' => auth()->user()->organisations->sortBy('updated_at')->paginate(6),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
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
    public function store(StoreOrganisationRequest $request): RedirectResponse
    {
        $input = $request->validated();

        try {
            $account = StripeHelper::createNewAccount();

            // Create the organisation, connected to Stripe's API
            $organisation = auth()->user()->organisations()->create([
                'name'          => $input['name'],
                'description'   => $input['description'],
                'website'       => $input['website'],
                'stripe_id'     => $account,
            ]);

            // Send user to Stripe onboarding
            return redirect(
                to: StripeHelper::accountOnboarding($organisation)->url
            );
        } catch (ApiErrorException $e) {
            return redirect()->back()->withErrors([
                'Stripe API error: ' . $e->getMessage()
            ]);
        }
    }

    public function refresh(Organisation $organisation): RedirectResponse
    {
        try {
            // Send user to Stripe onboarding
            return redirect(
                to: StripeHelper::accountOnboarding($organisation)->url
            );
        } catch (ApiErrorException $e) {
            return redirect()->back()->withErrors([
                'Stripe API error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Organisation $organisation): View
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
    public function edit(Organisation $organisation): View
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
    public function update(UpdateOrganisationRequest $request, Organisation $organisation): RedirectResponse
    {
        $input = $request->validated();

        $organisation->fill([
            'name'          => $input['name'],
            'description'   => $input['description'],
            'website'       => $input['website'],
        ]);
        $organisation->save();

        return redirect()->route('organisations.show', $organisation)->with([
            'status' => 'Organisation updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organisation $organisation): RedirectResponse
    {
        if ($organisation->events->count()) {
            return redirect()->back()->withErrors([
                'You cannot delete an organisation which has events associated with it. Try deleting the events first.',
            ]);
        }

        $organisation->delete();

        return redirect()->route('organisations.index')->with([
            'warning' => 'Organisation deleted successfully.',
        ]);
    }
}
