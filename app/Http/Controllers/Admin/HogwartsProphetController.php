<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HogwartsProphet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HogwartsProphetController extends Controller
{
    public function index(Request $request)
    {
        $query = HogwartsProphet::query()->withCount('likes');

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('writer', 'like', "%{$search}%");
        }

        $news = $query->orderBy('created_at', 'desc')->paginate(5)->withQueryString();

        return view('admin.hogwarts-prophet.index', compact('news'));
    }

    public function create()
    {
        return view('admin.hogwarts-prophet.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'writer'  => 'required|string|max:100',
            'image'   => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'date'    => 'required|date',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('hogwarts-prophet', 'public');
            $data['image'] = $path;
        }

        HogwartsProphet::create($data);

        return redirect()->route('admin.hogwarts-prophet.index')->with('success', 'News created successfully!');
    }

    public function edit(HogwartsProphet $hogwartsProphet)
    {
        return view('admin.hogwarts-prophet.edit', ['news' => $hogwartsProphet]);
    }

    public function update(Request $request, $id)
    {
        $news = HogwartsProphet::findOrFail($id);

        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'writer'  => 'nullable|string|max:255',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'date'    => 'required|date',
        ]);

        if ($request->hasFile('image')) {
            if ($news->image && !Str::startsWith($news->image, 'http') && file_exists(public_path('storage/' . $news->image))) {
                unlink(public_path('storage/' . $news->image));
            }
            $path = $request->file('image')->store('hogwarts-prophet', 'public');
            $validated['image'] = $path;
        } else {
            $validated['image'] = $news->image;
        }

        $news->update($validated);

        return redirect()->route('admin.hogwarts-prophet.index')->with('success', 'News updated successfully!');
    }

    public function destroy(HogwartsProphet $hogwartsProphet)
    {
        if ($hogwartsProphet->image) {
            Storage::disk('public')->delete($hogwartsProphet->image);
        }

        $hogwartsProphet->delete();

        return redirect()->route('admin.hogwarts-prophet.index')->with('success', 'News deleted successfully!');
    }

    public function archive(Request $request, $id)
    {
        $article = HogwartsProphet::findOrFail($id);
        $article->is_public = $request->input('is_public');
        $article->save();

        return redirect()->route('admin.hogwarts-prophet.index')
                        ->with('success', 'Article status updated successfully.');
    }
}
