<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    //
    public function courseList()
    {
        //$result = Course::get(["name", "thumbnail", "lesson_num", "price", "id"]);
        $result = Course::select("name", "thumbnail", "lesson_num", "price", "id")->get();

        return response()->json([
            "code" => 200,
            "msg" => "My course list is here",
            "data" => $result,
        ], 200);
    }

    public function courseDetail(Request $request)
    {
        $id = $request->id;
        try {
            $result = Course::where("id", "=", $id)->select(
                "id",
                "name",
                "user_token",
                "description",
                "thumbnail",
                "lesson_num",
                "video_length",
                "price",
            )->first();
            return response()->json([
                "code" => 200,
                "msg" => "My course detail is here",
                "data" => $result,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                "code" => 500,
                "msg" => "Server internal error",
                "data" => $e->getMessage(),
            ], 500); 
        }
    }
}
