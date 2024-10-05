<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *  name="Subjects",
 *  description="Subjects API"
 * )
 */

// Command: php artisan make:controller SubjectController --resource
class SubjectController extends Controller
{

    /**
     * @OA\Get(
     *     path="api/subjects",
     *     tags={"subjects"},
     *     @OA\Response(response="200", description="Get all subjects"),
     * )
     */
    public function index()
    {
        $subjects = Subject::all();

        return response()->json($subjects);
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
     *     path="api/subject",
     *     tags={"subjects"},
     *     @OA\Response(response="200", description="Create a new subject"),
     * )
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required | min:3  | max:20',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors()], 401);
        } else {

            $subject = new Subject();
            $subject->name = $data['name'];

            $subject->save();

            return response([
                'message' => 'New subject created!',
                'subject created' => response()->json($subject)
            ], 201);
        }
    }

    /**
     * @OA\Get(
     *     path="api/subjects/{subjectId}",
     *     tags={"subjects"},
     *     @OA\Response(response="200", description="Get 1 subject"),
     * )
     */
    public function show(string $id)
    {
        $subject = Subject::find($id);

        if (is_null($subject)) {
            return response()->json(['message' => 'Subject not found'], 404);
        }

        return response()->json($subject);
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
     *     path="api/subjects/{subjectId}",
     *     tags={"subjects"},
     *     @OA\Response(response="200", description="Edit 1 subject"),
     * )
     */
    public function update(Request $request, string $id)
    {
        $subject = Subject::find($id);

        if (is_null($subject)) {
            return response()->json(['message' => 'Subject not found'], 404);
        }

        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required | min:3  | max:20',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors()], 401);
        } else {
            $subject->update($data);

            return response([
                'message' => 'Subject updated!',
                'subject updated' => response()->json($subject)
            ], 200);
        }
    }

    /**
     * @OA\Delete(
     *     path="api/subjects/{subjectId}",
     *     tags={"subjects"},
     *     @OA\Response(response="200", description="Delete 1 subject"),
     * )
     */
    public function destroy(string $id)
    {
        $subject = Subject::find($id);

        if (is_null($subject)) {
            return response()->json(['message' => 'Subject not found'], 404);
        } else {
            $subject->delete();

            return response([
                'message' => "Subject deleted!",
                'subject deleted' => $subject
            ], 200);
        }
    }

    // SPECIALS

    public function getStudentSubjects(string $studentId)
    {
        $student = Student::find($studentId);
        $subjects = Subject::all();
        $studentSubjects = DB::table('student_subject')->where('student_id', $student->id)->get()->toArray();

        $subjects = [];
        foreach ($studentSubjects as $studentSubject) {
            $subjects[] = [
                'id' => $studentSubject['subject_id'],
                'name' => Subject::where('id', $studentSubject['subject_id'])->value('name'),
            ];
        };

        return response()->json($subjects);
    }
}
