<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\House;
use App\Models\Professor;
use App\Models\Achievement;
use App\Models\HogwartsProphet;
use App\Models\FacilityPhoto;

class AdminController extends Controller
{
    public function index()
    {
        $admin = auth()->guard('admin')->user();
        $currentYear = now()->year;

        // Debug active students
        // $studentsTotal = Student::where('year', '>=', $currentYear - 6)->count();
        // dd(Student::where('year', '>=', $currentYear - 6)->get()->toArray());

        // 1️⃣ Total Active Students (last 7 years)
        $studentsTotal = Student::whereNotNull('house_id')
                                 ->where('year', '>=', $currentYear - 6)
                                 ->count();

        // 2️⃣ House stats (Active students per house, last 7 years)
        $houses = House::all();
        $houseStats = $houses->map(function ($house) use ($currentYear) {
            $activeCount = Student::where('house_id', $house->id)
                                  ->where('year', '>=', $currentYear - 6)
                                  ->count();
            $house->students_last7years = $activeCount;
            return $house;
        });

        // 3️⃣ Chart: Active students per year (last 7 years)
        $years = [];
        $totals = [];
        for ($i = 6; $i >= 0; $i--) {
            $year = $currentYear - $i;
            $years[] = $year;
            $totals[] = Student::whereNotNull('house_id')
                               ->where('year', $year)
                               ->where('year', '>=', $currentYear - 6) // ✅ jaga konsistensi "active only"
                               ->count();
        }
        $studentPerYear = [
            'years' => $years,
            'totals' => $totals,
        ];

        // 4️⃣ Professors count
        $professorsTotal = Professor::count();

        // 5️⃣ Latest Hogwarts Prophet (3 latest news)
        $latestNews = HogwartsProphet::latest()->take(3)->get();

        // 6️⃣ Latest Achievements (3 latest)
        $latestAchievements = Achievement::latest()->take(3)->get();

        // 7️⃣ School profile
        $school = \App\Models\SchoolProfile::first();
        
        // If no school profile exists, create a default one
        if (!$school) {
            $school = \App\Models\SchoolProfile::create([
                'title' => 'Hogwarts School of Witchcraft and Wizardry',
                'about' => 'A magical school for young wizards and witches.',
                'address' => 'Scotland, United Kingdom',
                'phone' => '+44 123 456 7890',
                'email' => 'info@hogwarts.edu',
                'founded_year' => 990,
                'motto' => 'Draco Dormiens Nunquam Titillandus',
                'vision' => 'To provide the finest magical education in the world.',
                'mission' => 'To nurture young witches and wizards in the magical arts.',
                'house_banners' => [], // Default empty array for house_banners
            ]);
        }

        // 8️⃣ View Statistics
        $totalViews = FacilityPhoto::sum('view_count');
        $mostViewedPhotos = FacilityPhoto::with('category')
            ->orderBy('view_count', 'desc')
            ->take(5)
            ->get();
        $totalPhotos = FacilityPhoto::count();

        return view('admin.dashboard', compact(
            'admin',
            'studentsTotal',
            'houseStats',
            'studentPerYear',
            'professorsTotal',
            'latestNews',
            'latestAchievements',
            'school',
            'totalViews',
            'mostViewedPhotos',
            'totalPhotos'
        ));
    }
}
