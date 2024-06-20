<?php

namespace App\Observers;

use App\Models\Organisation;
use App\Support\StripeHelper;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;

class OrganisationObserver
{
    /**
     * Handle the Organisation "created" event.
     */
    public function creating(Organisation $organisation): void
    {
        try {
            $organisation->stripe_id = StripeHelper::createNewAccount();
        } catch (ApiErrorException $e) {
            Log::error($e);
        }
    }

    /**
     * Handle the Organisation "updated" event.
     */
    public function updating(Organisation $organisation): void
    {
        //
    }

    /**
     * Handle the Organisation "deleted" event.
     */
    public function deleting(Organisation $organisation): void
    {
        //
    }
}
