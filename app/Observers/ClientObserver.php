<?php

namespace App\Observers;

use App\Facades\UtilityFacades;
use App\Models\Client;

class ClientObserver
{
    public function created(Client $client): void
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($client)
            ->withProperties($client->toArray())
            ->event('created')
            ->log('Client created');
    }

        public function updated(Client $client)
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($client)
            ->withProperties(UtilityFacades::getChangedAttributes($client, $client->getChanges()))
            ->event('updated')
            ->log('Client updated');
    }

        public function deleted(Client $client)
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($client)
            ->withProperties($client->toArray())
            ->event('deleted')
            ->log('Client deleted');
    }
}
