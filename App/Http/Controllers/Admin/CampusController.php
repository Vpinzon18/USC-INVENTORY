<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use Illuminate\Http\Request;

class CampusController extends Controller
{
    public function index()
    {
        $campuses = Campus::all();
        return view('admin.campuses.index', compact('campuses'));
    }

    public function create()
    {
        return view('admin.campuses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:campuses,name',
        ]);

        Campus::create($validated);

        return redirect()->route('campuses.index')->with('success', 'Sede creada correctamente.');
    }

    public function edit(Campus $campus) {
    return view('admin.campuses.edit', compact('campus'));
}

public function update(Request $request, Campus $campus) {
    $validated = $request->validate([
        'name' => 'required|string|max:255|unique:campuses,name,' . $campus->id,
        'address' => 'nullable|string|max:255',
    ]);
    $campus->update($validated);
    return redirect()->route('campuses.index')->with('success', 'Sede actualizada.');
}

public function destroy(Campus $campus) {
    $campus->delete();
    return redirect()->route('campuses.index')->with('success', 'Sede eliminada.');
}
}