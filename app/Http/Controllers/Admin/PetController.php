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
    public function index(Request $request)
    {
        $query = Pet::with('client');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('petname', 'like', "%{$search}%")
                  ->orWhere('breed', 'like', "%{$search}%")
                  ->orWhere('colormarkings', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($q) use ($search) {
                      $q->where('fullname', 'like', "%{$search}%");
                  });
            });
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $pets = $query->latest()->paginate(15)->withQueryString();

        return Inertia::render('admin/Pets/Index', [
            'pets' => $pets,
            'filters' => [
                'search' => $request->search ?? '',
                'date_from' => $request->date_from ?? '',
                'date_to' => $request->date_to ?? '',
            ],
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
