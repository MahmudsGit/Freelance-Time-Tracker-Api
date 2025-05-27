<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Http\Resources\ClientResource;
use Illuminate\Http\JsonResponse;

class ClientController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $clients = $request->user()->clients;
        
        return response()->json(ClientResource::collection($clients), 200);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'contact_person' => 'required',
        ]);

        $client = $request->user()->clients()->create($data);

        return response()->json(new ClientResource($client), 201);
    }

    public function show(Client $client): JsonResponse
    {
        return response()->json(new ClientResource($client), 200);
    }

    public function update(Request $request, Client $client): JsonResponse
    {
        $client->update($request->only(['name', 'email', 'contact_person']));

        return response()->json(new ClientResource($client), 200);
    }

    public function destroy(Client $client): JsonResponse
    {
        $client->delete();

        return response()->json(null, 204);
    }
}
