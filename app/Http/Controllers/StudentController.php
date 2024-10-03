<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

// Command: php artisan make:controller StudentController --resource
class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::all();

        return response()->json($students);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required | min:3  | max:20',
            'surname' => 'required | min:3 | max:50',
            'age' => 'required | numeric|min:12 | numeric|max:16',
            'classroom_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors()], 400);
        } else {

            $student = new Student();
            $student->name = $data['name'];
            $student->surname = $data['surname'];
            $student->age = $data['age'];
            $student->classroom_id = $data['classroom_id'];

            $student->save();

            return response([
                'message' => 'New students created!',
                'student created' => response()->json($student)
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student = Student::find($id);

        if (is_null($student)) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        return response()->json($student);
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
        $student = Student::find($id);

        if (is_null($student)) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required | min:3  | max:20',
            'surname' => 'required | min:3 | max:50',
            'age' => 'required | numeric|min:12 | numeric|max:16',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors()], 401);
        } else {
            $student->fill($data);
            $student->save($data);

            return response([
                'message' => 'Student updated!',
                'student updated' => response()->json($student)
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = Student::find($id);

        if (is_null($student)) {
            return response()->json(['message' => 'Student not found'], 404);
        } else {
            $student->delete();

            return response([
                'message' => "Student deleted!",
                'student deleted' => $student
            ], 200);
        }
    }
}
