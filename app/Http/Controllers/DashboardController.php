<?php

namespace App\Http\Controllers;

use App\Models\Student;
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
}
