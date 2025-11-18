<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Professor;
use App\Models\House;
use Illuminate\Http\Request;

class ProfessorController extends Controller
{
    /**
     * Display a listing of the professors.
     */
    public function index(Request $request)
    {
        $houses = House::orderBy('name')->get();
        $houseId = $request->query('house_id');
        $search = $request->query('search');

        $query = Professor::with('house')
            ->when($houseId, fn($q) => $q->where('house_id', $houseId))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('name', 'like', "%{$search}%")
                       ->orWhere('subject', 'like', "%{$search}%");
                });
            });

        $professors = $query->orderBy('id', 'desc')
            ->paginate(7)
            ->appends($request->query());

        return view('admin.professors.index', [
            'professors' => $professors,
            'houses' => $houses,
            'selectedHouseId' => $houseId,
            'searchTerm' => $search,
        ]);
    }


    /**
     * Show the form for creating a new professor.
     */
    public function create()
    {
        $houses = House::all();
        return view('admin.professors.create', compact('houses'));
    }

    /**
     * Store a newly created professor in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'house_id' => 'required|exists:houses,id',
            'position' => 'nullable|string',
            'subject' => 'nullable|string',
        ]);

        Professor::create($request->all());

        return redirect()->route('admin.professors.index')->with('success', 'Professor added!');
    }

    /**
     * Show the form for editing the specified professor.
     */
    public function edit(Professor $professor)
    {
        $houses = House::all();
        return view('admin.professors.edit', compact('professor', 'houses'));
    }

    /**
     * Update the specified professor in storage.
     */
    public function update(Request $request, Professor $professor)
    {
        $request->validate([
            'name' => 'required',
            'house_id' => 'required|exists:houses,id',
            'email' => 'nullable|email|unique:professors,email,' . $professor->id,
            'subject' => 'nullable|string',
        ]);

        $professor->update($request->all());

        return redirect()->route('admin.professors.index')->with('success', 'Professor updated!');
    }

    /**
     * Remove the specified professor from storage.
     */
    public function destroy(Professor $professor)
    {
        $professor->delete();
        return redirect()->route('admin.professors.index')->with('success', 'Professor deleted!');
    }
}
