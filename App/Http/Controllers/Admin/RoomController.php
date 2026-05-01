<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\Room;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
   public function create()
{
    $buildings = Building::all(); // Traemos bloques para el select
    return view('admin.rooms.create', compact('buildings'));
}

public function store(Request $request)
{
    $validated = $request->validate([
        'building_id' => 'required|exists:buildings,id',
        'name'        => 'required|string|max:100',
        'floor'       => 'required|integer',
    ]);

    Room::create($validated);
    return redirect()->route('rooms.index')->with('success', 'Oficina creada.');
}
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
