<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchoolProfile;
use Illuminate\Support\Facades\Storage;

class SchoolProfileController extends Controller
{
    public function index()
    {
        $profile = SchoolProfile::with('founders')->first();
        return view('admin.school_profiles.index', compact('profile'));
    }

    public function edit()
    {
        $profile = SchoolProfile::with('founders')->first();
        return view('admin.school_profiles.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $profile = SchoolProfile::firstOrFail();

        // ✅ Validasi input
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'about'          => 'nullable|string',
            'address'        => 'nullable|string',
            'phone'          => 'nullable|string|max:50',
            'email'          => 'nullable|email|max:255',
            'map_embed'      => 'nullable|string',
            'vision'         => 'nullable|string',
            'mission'        => 'nullable|string',
            'facebook_url'   => 'nullable|url',
            'instagram_url'  => 'nullable|url',
            'youtube_url'    => 'nullable|url',
            'twitter_url'    => 'nullable|url',
            'founded_year'   => 'nullable|numeric',
            'logo'           => 'nullable|image|max:2048',
            'hero_image'     => 'nullable|image|max:4096',
            'history'        => 'nullable|string',
        ]);


        if ($request->hasFile('logo')) {
            if ($profile->logo) {
                Storage::disk('public')->delete($profile->logo);
            }
            $validated['logo'] = $request->file('logo')->store('school', 'public');
        }

     
        if ($request->hasFile('hero_image')) {
            if ($profile->hero_image) {
                Storage::disk('public')->delete($profile->hero_image);
            }
            $validated['hero_image'] = $request->file('hero_image')->store('hero_images', 'public');
        }


        $changesDetected = false;
        foreach ($validated as $key => $value) {
            if ($profile->{$key} != $value) {
                $changesDetected = true;
                break;
            }
        }

        if (!$changesDetected) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Nothing updated',
                    'type' => 'info',
                ]);
            }
            return redirect()->back()->with('info', 'Nothing was updated.');
        }


        try {
            $profile->update($validated);

            if ($request->ajax()) {
                return response()->json([
                    'success'  => true,
                    'message'  => 'Successfully updated',
                    'type'     => 'success',
                    'redirect' => route('admin.school-profile.index'),
                ]);
            }

            return redirect()
                ->route('admin.school-profile.index')
                ->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update: ' . $e->getMessage(),
                    'type'    => 'error',
                ], 500);
            }

            return redirect()
                ->back()
                ->with('error', 'Failed to update: ' . $e->getMessage());
        }
    }
}
