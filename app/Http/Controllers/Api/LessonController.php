<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Lesson;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LessonController extends Controller
{
    //
    public function lessonList(Request $request) {
        $id = $request->id;
        try {
            $result = Lesson ::where("course_id", "=", $id)->select(
                "id",
                "name",
                "description",
                "thumbnail",
                "video",
            )->get();
            return response()->json([
                "code" => 200,
                "msg" => "My lesson list is here",
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

    public function lessonDetail(Request $request)
    {
        $id = $request->id;
        try {
            // $result = Lesson::where("id", "=", $id)->select(
            //     "name",
            //     "description",
            //     "thumbnail",
            //     "video",
            // )->get();
            $result = Lesson::where("id", "=", $id)->select(
                "video",
            )->first()["video"];
            return response()->json([
                "code" => 200,
                "msg" => "My lesson detail is here",
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

    public function stream($filename)
    {
        $path = public_path("uploads/files/{$filename}");

        if (!file_exists($path)) {
            abort(404, 'File not found');
        }

        $size = filesize($path);
        $mimeType = mime_content_type($path);

        // Prepare headers
        $headers = [
            'Content-Type' => $mimeType,
            'Accept-Ranges' => 'bytes',
            'Content-Length' => $size,
        ];

        // Create a new StreamedResponse
        $response = new StreamedResponse(function () use ($path) {
            $stream = fopen($path, 'rb');
            while (!feof($stream)) {
                echo fread($stream, 1024 * 8);
                ob_flush();
                flush();
            }
            fclose($stream);
        }, 200, $headers);

        // Handle range requests
        $request = request();
        if ($request->hasHeader('Range')) {
            $range = $request->header('Range');
            if (preg_match('/bytes=(\d+)-(\d*)/', $range, $matches)) {
                $start = intval($matches[1]);
                $end = intval($matches[2]) ?: $size - 1;

                // Update headers for partial content
                $headers['Content-Range'] = "bytes {$start}-{$end}/{$size}";
                $headers['Content-Length'] = $end - $start + 1;

                $response->setStatusCode(206);
                $response->headers->replace($headers);

                // Update callback to stream partial content
                $response->setCallback(function () use ($path, $start, $end) {
                    $stream = fopen($path, 'rb');
                    fseek($stream, $start);
                    while ($start <= $end && !feof($stream)) {
                        echo fread($stream, min(1024 * 8, $end - $start + 1));
                        $start += 1024 * 8;
                        ob_flush();
                        flush();
                    }
                    fclose($stream);
                });
            } else {
                abort(400, 'Invalid range');
            }
        } else {
            // Full content headers (if no range request)
            $response->headers->replace($headers);
        }

        return $response;
    }
}
