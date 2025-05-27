<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->clients;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'contact_person' => 'required',
        ]);

        return $request->user()->clients()->create($data);
    }

    public function show(Client $client)
    {
        return $client;
    }

    public function update(Request $request, Client $client)
    {
        $client->update($request->only(['name', 'email', 'contact_person']));
        return $client;
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return response()->noContent();
    }
}
