<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *  name="Classrooms",
 *  description="Classrooms API"
 * )
 */

class ClassroomController extends Controller
{

    /**
     * @OA\Get(
     *     path="api/school/classrooms",
     *     tags={"classrooms"},
     *     @OA\Response(response="200", description="Get all classrooms"),
     * )
     */
    public function index()
    {
        $classrooms = ClassRoom::all();

        return response()->json($classrooms);
    }
}
