<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Subject;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *  name="grades",
 *  description="Grades API"
 * )
 */

// Command: php artisan make:controller GradeController
class GradeController extends Controller
{

    /**
     * @OA\Get(
     *     path="api/grades",
     *     tags={"grades"},
     *     @OA\Response(response="200", description="Get all grades"),
     * )
     */
    public function index()
    {
        $grades = Grade::all();
        return response()->json($grades);
    }


    /**
     * @OA\Get(
     *     path="api/grades/{gradeId}",
     *     tags={"grades"},
     *     @OA\Response(response="200", description="Get 1 grade"),
     * )
     */
    public function show(int $id)
    {
        $grade = Grade::find($id);
        return response()->json($grade);
    }

    /**
     * @OA\Post(
     *     path="api/grade",
     *     tags={"grades"},
     *     @OA\Response(response="200", description="Create a new grade"),
     * )
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'gradeNum' => 'required | numeric | min:0 | max:10',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors()], 400);
        } else {
            $grade = new Grade();
            $grade->gradeNum = $data['gradeNum'];

            $grade->save();

            return response([
                'message' => 'New grade created!',
                'grade created' => response()->json($grade)
            ], 200);
        }
    }

    /**
     * @OA\Put(
     *     path="api/grades/{gradeId}",
     *     tags={"grades"},
     *     @OA\Response(response="200", description="Create a new grade"),
     * )
     */
    public function update(Request $request, int $id)
    {
        $grade = Grade::find($id);

        $data = $request->all();

        $validator = Validator::make($data, [
            'gradeNum' => 'required | numeric | min:0 | max:10',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors()], 400);
        } else {
            $grade->update($data);

            return response([
                'message' => 'Grade updated!',
                'grade updated' => response()->json($grade)
            ], 200);
        }
    }

    /**
     * @OA\Delete(
     *     path="api/grades/{gradeId}",
     *     tags={"grades"},
     *     @OA\Response(response="200", description="Delete a grade"),
     * )
     */
    public function destroy(int $id)
    {
        $grade = Grade::find($id);

        if (is_null($grade)) {
            return response()->json(['message' => 'Grade not found'], 404);
        } else {
            $grade->delete();

            return response([
                'message' => "Grade deleted!",
                'grade deleted' => $grade
            ], 200);
        }
    }


    // SPECIAL FUNCTIONS

    /**
     * @OA\Get(
     *  path="api/students/{studentId}/{subjectId}/grades",
     *  tags={"grades"},    
     *  @OA\Response(response="200", description="Obtenir notes d’assignatures per estudiant"),
     * )
     */
    public function studentSubjectGrades(int $studentId, int $subjectId)
    {
        $studentSubjects = DB::table('student_subject')->where('student_id', $studentId)->where('subject_id', $subjectId)->first();
        $gradesIds = DB::table('student_subject_grade')->where('student_subject_id', $studentSubjects['id'])->pluck('grades_id');

        $grades = DB::table('grades')->whereIn('id', $gradesIds)->get();

        foreach ($grades as $grade) {
            $gradesDictionary[] = [
                'grade_id' => $grade['id'],
                'grade_value' => $grade['gradeNum'],
            ];
        }

        $subject = Subject::find($subjectId)->select('name')->first();

        return response()->json([
            'subject' => $subject->name,
            'min' => $grades->min('gradeNum'),
            'max' => $grades->max('gradeNum'),
            'average' => $grades->avg('gradeNum'),
            'grades' => $gradesDictionary
        ]);
    }


    /**
     * @OA\Get(
     *  path="api/students/{studentId}/grades/average",
     *  tags={"grades"},    
     *  @OA\Response(response="200", description="Obtenir mitjana de les notes de les assignatures per estudiant"),
     * )
     */
    public function averageStudentGrades(int $studentId)
    {
        $studentSubjects = DB::table('student_subject')->where('student_id', $studentId)->get();
        $studentSubjectIds = $studentSubjects->pluck('id');

        $data = [];
        foreach ($studentSubjectIds as $studentSubjectId) {

            $gradesIds = DB::table('student_subject_grade')->where('student_subject_id', $studentSubjectId)->pluck('grades_id');
            $grades = DB::table('grades')->whereIn('id', $gradesIds)->get();

            $subjectId = $studentSubjects->where('id', $studentSubjectId)->select('subject_id')->first();
            $subject = Subject::where('id', $subjectId)->value('name');

            array_push($data, [
                'subject' => $subject,
                'average' => $grades->avg('gradeNum'),
            ]);
        }

        $totalAverage = 0;
        foreach ($data as $item) {
            $totalAverage += $item['average'];
        }

        return response()->json([
            'total_average' => $totalAverage / count($data),
            'subjects' => $data,
        ]);
    }

    /**
     * @OA\Get(
     *  path="api/school/grades/average",
     *  tags={"grades"},    
     *  @OA\Response(response="200", description="Obtenir mitjana de totes les notes de tots els estudiants (escola)"),
     * )
     */
    public function averageSchoolGrades()
    {
        $totalGradesAverage = DB::table('grades')->avg('gradeNum');
        return response()->json([
            'total_average' => $totalGradesAverage,
        ]);
    }

    /**
     * @OA\Get(
     *  path="api/school/{courseId}/grades/average",
     *  tags={"grades"},    
     *  @OA\Response(response="200", description="Obtenir mitjana de totes les notes de tots els estudiants (clase)"),
     * )
     */
    public function averageSchoolCourseGrades(int $courseId)
    {
        $courseStudents = Student::where('classroom_id', $courseId)->get();

        $studentsGrades = [];
        foreach ($courseStudents as $student) {
            $studentGrades = $this->averageStudentGrades($student->id);
            array_push($studentsGrades, $studentGrades->original['total_average']);
        }

        $resultAverageTotal = array_sum($studentsGrades) / count($studentsGrades);

        return response()->json([
            'course_average' => $resultAverageTotal,
        ]);
    }
}
