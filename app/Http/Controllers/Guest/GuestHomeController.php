<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\SchoolProfile;
use App\Models\House;
use App\Models\Student;
use App\Models\FacilityCategory;
use App\Models\HogwartsProphet;
use App\Models\Founder;
use App\Models\Professor;
use App\Models\Achievement;

class GuestHomeController extends Controller
{
    public function index()
    {
        $currentYear = now()->year;

        // House Stats
        $houseStats = House::with('achievements')->get()->map(function ($house) use ($currentYear) {
            $house->students_last7years = Student::where('house_id', $house->id)
                ->where('year', '>=', $currentYear - 6)
                ->count();

            $house->professors_count = Professor::where('house_id', $house->id)->count();
            $house->total_alumni = Student::where('house_id', $house->id)->count();

            return $house;
        });

        $schoolProfile = SchoolProfile::first();
        
        // Ensure we always have a profile object (even if null, views will handle it)
        // This prevents errors when profile doesn't exist in database

        $houses = House::withCount('students')->get();

        $news = HogwartsProphet::latest()->take(3)->get();

        $categories = FacilityCategory::with('coverPhoto')
            ->orderBy('sort_order')
            ->limit(8)
            ->get();

        // Hero Slideshow
        $achievements = Achievement::latest()->take(6)->get()->map(function($achievement){
            $achievement->image = $achievement->image ?? 'placeholder.jpg';
            return $achievement;
        });

        $founders = Founder::all();
        $totalStudents = Student::count();
        $totalProfessors = Professor::count();

        return view('guest.home', compact(
            'houses',
            'news',
            'categories',
            'founders',
            'houseStats',
            'totalStudents',
            'totalProfessors',
            'achievements'
        ))->with('profile', $schoolProfile);
    }
}
