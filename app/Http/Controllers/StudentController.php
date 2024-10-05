<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *  name="Students",
 *  description="Students API"
 * )
 */

// Command: php artisan make:controller StudentController --resource
class StudentController extends Controller
{
    /**
     * @OA\Get(
     *     path="api/students",
     *     tags={"students"},
     *     @OA\Response(response="200", description="Get all students"),
     * )
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
     * @OA\Post(
     *     path="api/student",
     *     tags={"students"},
     *     @OA\Response(response="200", description="Create a new student"),
     * )
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
            ], 201);
        }
    }

    /**
     * @OA\Get(
     *     path="api/students/{studentId}",
     *     tags={"students"},
     *     @OA\Response(response="200", description="Get 1 student"),
     * )
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
     * @OA\Put(
     *     path="api/students/{studentId}",
     *     tags={"students"},
     *     @OA\Response(response="200", description="Edit 1 student"),
     * )
     */
    public function update(Request $request, string $id)
    {
        $student = Student::find($id);

        // dd($student); // Verifica que sea una instancia de Student, no un stdClass

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
            return response(['error' => $validator->errors()], 400);
        }

        $student->update($data);

        return response()->json([
            'message' => 'Student updated!',
            'student' => $student
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="api/students/{studentId}",
     *     tags={"students"},
     *     @OA\Response(response="200", description="Delete 1 student"),
     * )
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
