<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function index2($id)
    {
        $student = Student::find($id);
        return view('dashboard2', compact('student'));
    }

    public function index3($studentId, $subjectName)
    {
        $student = Student::find($studentId);
        $subject = Subject::where('name', str_replace('_', ' ', $subjectName))->first();

        return view('dashboard3', compact('student', 'subject'));
    }
}
