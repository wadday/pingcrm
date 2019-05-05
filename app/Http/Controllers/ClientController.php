<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class ClientController extends Controller
{
    public function index()
    {
        return Inertia::render('Clients/Index', [
            'filters' => Request::all('search', 'trashed'),
            'clients' => Auth::user()->account->clients()
            ->orderBy('first_name')
            ->filter(Request::only('search', 'trashed'))
            ->paginate()
            ->only('id', 'first_name', 'last_name', 'address', 'phone', 'region', 'deleted_at')
        ]);
    }

    public function create()
    {
        return Inertia::render('Clients/Create');
    }

    public function edit(Client $client)
    {
        return Inertia::render('Clients/Edit', [
            'client' => [
                'id' => $client->id,
                'first_name' => $client->first_name,
                'last_name' => $client->last_name,
                'email' => $client->email,
                'phone' => $client->phone,
                'address' => $client->address,
                'city' => $client->city,
                'region' => $client->region,
                'deleted_at' => $client->deleted_at,
//                'bills' => $client->bills()->orderByName()->get()->map->only('id', 'name', 'city', 'phone'),
            ],
        ]);
    }

    public function store()
    {
        Auth::user()->account->clients()->create(
            Request::validate([
                'first_name' => ['required', 'max:100'],
                'last_name' => ['required', 'max:100'],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
            ])
        );

        return Redirect::route('clients');
    }

    public function update(Client $client)
    {
        $client->update(
            Request::validate([
                'first_name' => ['required', 'max:50'],
                'last_name' => ['required', 'max:50'],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
            ])
        );

        return Redirect::route('clients.edit', $client);
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return Redirect::route('clients.edit', $client);
    }

    public function restore(Client $client)
    {
        $client->restore();

        return Redirect::route('organizations.edit', $client);
    }
}
