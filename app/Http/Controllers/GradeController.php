<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Subject;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

// Command: php artisan make:controller GradeController
class GradeController extends Controller
{
    public function index()
    {
        $grades = Grade::all();

        return response()->json($grades);
    }

    // SPECIAL FUNCTIONS

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

    public function averageSchoolGrades()
    {
        $totalGradesAverage = DB::table('grades')->avg('gradeNum');
        return response()->json([
            'total_average' => $totalGradesAverage,
        ]);
    }

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
