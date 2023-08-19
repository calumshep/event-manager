<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAccountRequest;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function show(User $user)
    {
        return view('accounts.form', [
            'user' => $user
        ]);
    }

    /**
     * Show the user's own details
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function showOwn()
    {
        return view('accounts.form', [
            'user' => auth()->user()
        ]);
    }

    /**
     * Edit the specified user's details.
     *
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(User $user)
    {
        return view('accounts.form', [
            'user' => $user
        ]);
    }

    /**
     * Update the user.
     *
     * @param \App\Http\Requests\UpdateAccountRequest $request
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateAccountRequest $request, User $user)
    {
        $input = $request->validated();

        if (! Hash::check($input['current_password'], auth()->user()->password)) {
            return redirect()->back()->withErrors(['Your password was incorrect.']);
        }

        $user->fill([
            'first_name'    => $input['first_name'],
            'last_name'     => $input['last_name'],
            'email'         => $input['email'],
        ]);

        if ($input['new_password']) {
            $user->password = Hash::make('new_password');
        }

        $user->save();

        return redirect()->route('account.show', $user)->with([
            'status' => 'Account details updated successfully.'
        ]);
    }
}
