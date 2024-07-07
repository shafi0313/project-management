<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class DropZoneController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:jpg,jpeg,png,bmp|max:2048',
        ]);

        if ($request->file('file')) {
            $file = $request->file('file');
            $filename = time() . '-' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);

            return response()->json(['filename' => $filename]);
        }

        return response()->json(['error' => 'File not uploaded'], 400);
    }

    public function destroy(Request $request)
    {
        $filename = $request->get('filename');
        $path = public_path('uploads/' . $filename);

        if (File::exists($path)) {
            File::delete($path);
            return response()->json(['success' => true, 'message' => 'File deleted successfully']);
        }

        return response()->json(['success' => false, 'message' => $path], 404);
    }
}
