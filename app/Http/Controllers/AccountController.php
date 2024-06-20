<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAccountRequest;
use App\Models\User;
use Hash;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class AccountController extends Controller
{
    /**
     * Show the specified user's details.
     */
    public function show(User $user): View
    {
        return view('accounts.form', [
            'user' => $user,
        ]);
    }

    /**
     * Show the user's own details.
     */
    public function showOwn(): View
    {
        return view('accounts.form', [
            'user' => auth()->user()
        ]);
    }

    /**
     * Edit the specified user's details.
     */
    public function edit(User $user): View
    {
        return view('accounts.form', [
            'user' => $user
        ]);
    }

    /**
     * Update the user.
     */
    public function update(UpdateAccountRequest $request, User $user): RedirectResponse
    {
        $input = $request->validated();

        if (! Hash::check($input['current_password'], $user->password)) {
            return redirect()->back()->withErrors([
                'Your password was incorrect.',
            ]);
        }

        $user->fill([
            'first_name'    => $input['first_name'],
            'last_name'     => $input['last_name'],
            'email'         => $input['email'],
            'phone_number'  => $input['phone_number'],
        ]);

        if ($input['new_password']) {
            $user->password = Hash::make($input['new_password']);
        }

        $user->save();

        return redirect()->route('account.show', $user)->with([
            'status' => 'Account details updated successfully.',
        ]);
    }
}
