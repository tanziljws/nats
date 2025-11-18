<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;
use App\Models\House;

class AchievementController extends Controller
{
    public function index(Request $request)
    {
        $houseId = $request->query('house_id');

        $achievements = Achievement::when($houseId, function ($q) use ($houseId) {
                $q->where('house_id', $houseId);
            })
            ->latest()
            ->paginate(5)
            ->appends($request->query());

        $houses = House::orderBy('name')->get(['id','name']);

        return view('admin.achievements.index', [
            'achievements' => $achievements,
            'houses' => $houses,
            'selectedHouseId' => $houseId,
        ]);
    }

    public function create(Request $request)
    {
        $house_id = $request->query('house_id'); 
        $houses = House::all(); 
        return view('admin.achievements.create', compact('house_id', 'houses'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'house_id' => 'nullable|exists:houses,id',
            'date' => 'nullable|date',
        ]);

        $data = $request->only(['title', 'description', 'house_id', 'date']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('achievements', 'public');
        }

        Achievement::create($data);

        return redirect()->route('admin.achievements.index')->with('success', 'Achievement added!');
    }

    public function edit(Achievement $achievement)
    {
        $houses = House::all();
        return view('admin.achievements.edit', compact('achievement', 'houses'));
    }

    public function update(Request $request, Achievement $achievement)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        'house_id' => 'nullable|exists:houses,id',
        'date' => 'nullable|date',
    ]);

    $data = $request->only(['title', 'description', 'house_id', 'date']);

    if ($request->hasFile('image')) {
        // Hapus gambar lama jika ada
        if ($achievement->image && file_exists(public_path('storage/' . $achievement->image))) {
            unlink(public_path('storage/' . $achievement->image));
        }

        // Simpan gambar baru
        $data['image'] = $request->file('image')->store('achievements', 'public');
    }

    $achievement->update($data);

    // Redirect to index and preserve house filter if available
    return redirect()->route('admin.achievements.index', [
        'house_id' => $achievement->house_id
    ])->with('success', 'Achievement updated successfully!');
}

    public function destroy(Achievement $achievement)
    {
        $achievement->delete();
        return redirect()->route('admin.achievements.index')->with('success', 'Achievement deleted!');
    }
}
