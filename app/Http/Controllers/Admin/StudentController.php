<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\House;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    // Index / Dashboard Students Page
    public function index(Request $request)
    {
        $houses = House::all(); // semua house, buat filter
        $currentYear = now()->year;

        $query = Student::query();

        // Filter house
        if ($request->filled('house_id')) {
            $query->where('house_id', $request->house_id);
        }

        // Filter status
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('year', '>=', $currentYear - 6); // active
            } elseif ($request->status == 'alumni') {
                $query->where('year', '<', $currentYear - 6); // alumni 
            }
        }

        // Pencarian nama / kode siswa
        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('student_code', 'like', "%{$search}%");
            });
        }

        // Gunakan pagination supaya tampilannya sama dengan professors
        $students = $query
            ->with('house')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // 
        $totalStudents = Student::whereNotNull('house_id')
                                ->where('year', '>=', $currentYear - 6)
                                ->count();

        // 
        $houseStats = House::withCount([
            'students as students_last7years' => function ($query) use ($currentYear) {
                $query->where('year', '>=', $currentYear - 6);
            }
        ])->get();

        return view('admin.students.index', compact('students', 'houses', 'totalStudents', 'houseStats'));
    }

    // Create form
    public function create()
    {
        $houses = House::all();
        return view('admin.students.create', compact('houses'));
    }

    // Store new student
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'house_id' => 'required|exists:houses,id',
            'year' => 'required|integer',
            'birth_date' => 'required|date',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('students', 'public');
        }

        $lastStudent = Student::where('year', $data['year'])->latest('id')->first();

        $nextNumber = $lastStudent 
            ? intval(substr($lastStudent->student_code, -3)) + 1 
            : 1;

        $prefix = ($data['year'] < now()->year - 6) ? 'ALU-' : 'STU-';

        $data['student_code'] = $prefix . $data['year'] . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);



        Student::create($data);

        return redirect()->route('admin.students.index')->with('success', 'Student added successfully.');
    }

    // Edit form
    public function edit(Student $student)
    {
        $houses = House::all();
        return view('admin.students.edit', compact('student', 'houses'));
    }

    // Update student
    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'house_id' => 'nullable|exists:houses,id',
            'year' => 'nullable|integer',
            'birth_date' => 'nullable|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // hapus foto lama kalau ada
            if ($student->photo && Storage::disk('public')->exists($student->photo)) {
                Storage::disk('public')->delete($student->photo);
            }
            $data['photo'] = $request->file('photo')->store('students', 'public');
        }

        $student->update($data);

        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully.');
    }

    // Delete student
    public function destroy(Student $student)
    {
        if ($student->photo && Storage::disk('public')->exists($student->photo)) {
            Storage::disk('public')->delete($student->photo);
        }

        $student->delete();

        return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully.');
    }
}
