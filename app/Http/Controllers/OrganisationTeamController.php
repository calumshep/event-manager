<?php

namespace App\Http\Controllers;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrganisationTeamController extends Controller
{
    public function index(Organisation $organisation): View
    {
        return view('organisations.team', [
            'organisation' => $organisation,
        ]);
    }

    /**
     * Add a user to an organisation's team.
     */
    public function add(Organisation $organisation, Request $request): RedirectResponse
    {
        $user = User::whereEmail($request->email)->first();
        if (!$user) {
            return redirect()->back()->withErrors([
                'No user could be found with the email address "' . $request->email . '"' . ". Are you sure it's right?",
            ]);
        }

        $organisation->team()->attach($user);

        return redirect()->back()->with([
            'status' => $user->first_name . ' ' . $user->last_name . ' added to organisation team successfully.',
        ]);
    }

    /**
     * Remove a user from an organisation's team.
     */
    public function remove(Organisation $organisation, Request $request): RedirectResponse
    {
        $user = User::find($request->user_id);
        if (!$user || !$organisation->team->contains($user)) {
            return redirect()->back()->withErrors([
                "That user could not be found in this organisation's team",
            ]);
        }
        $organisation->team()->detach($user);

        return redirect()->back()->with([
            'status' => $user->first_name . ' ' . $user->last_name . ' removed from organisation team.',
        ]);
    }
}
