<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SummerNoteController extends Controller
{
    public function imageDestroy(Request $request)
    {
        $imagePath = public_path(parse_url($request->src, PHP_URL_PATH));

        if (file_exists($imagePath)) {
            unlink($imagePath);

            return response()->json(['message' => 'Image deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'Image not found'], 500);
        }
    }
}
