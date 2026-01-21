<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pets = Pet::with('client')
            ->latest()
            ->paginate(15);

        return Inertia::render('admin/Pets/Index', [
            'pets' => $pets,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::orderBy('fullname')->get(['id', 'fullname']);

        return Inertia::render('admin/Pets/Create', [
            'clients' => $clients,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'petname' => ['required', 'string', 'max:255'],
            'pet_type' => ['required', Rule::in(['canine', 'feline'])],
            'breed' => ['required', 'string', 'max:255'],
            'colormarkings' => ['required', 'string', 'max:255'],
        ]);

        Pet::create($validated);

        return redirect()->route('admin.pets.index')
            ->with('success', 'Pet created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pet $pet)
    {
        $pet->load('client');

        return Inertia::render('admin/Pets/Show', [
            'pet' => $pet,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pet $pet)
    {
        $clients = Client::orderBy('fullname')->get(['id', 'fullname']);

        return Inertia::render('admin/Pets/Edit', [
            'pet' => $pet,
            'clients' => $clients,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pet $pet)
    {
        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'petname' => ['required', 'string', 'max:255'],
            'pet_type' => ['required', Rule::in(['canine', 'feline'])],
            'breed' => ['required', 'string', 'max:255'],
            'colormarkings' => ['required', 'string', 'max:255'],
        ]);

        $pet->update($validated);

        return redirect()->route('admin.pets.index')
            ->with('success', 'Pet updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pet $pet)
    {
        $pet->delete();

        return redirect()->route('admin.pets.index')
            ->with('success', 'Pet deleted successfully.');
    }
}
