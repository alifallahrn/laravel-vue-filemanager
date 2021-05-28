<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image as ImageManager;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|mimes:gif,png,jpg|max:2048'
        ]);
        if ($request->file('image')) {
            $file = new Image();
            $title = $request->file('image')->getClientOriginalName();
            $extention = $request->file('image')->getClientOriginalExtension();
            $filename = md5(uniqid()) . '.' . $extention;
            $title = rtrim($title, ".{$extention}");
            $size = $request->file('image')->getSize();
            $request->file('image')->storeAs('uploads', $filename, 'public');
            // Thumbnail
            $request->file('image')->storeAs('uploads/thumbnail', $filename, 'public');
            $thumbnailPath = public_path('storage/uploads/thumbnail/' . $filename);
            $thumbnail = ImageManager::make($thumbnailPath)->fit(100, 100);
            $thumbnail->save($thumbnailPath);
            // Database
            $file->title = $title;
            $file->name = $filename;
            $file->size = $size;
            $file->save();

            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully.'
            ]);
        }
    }

    public function list()
    {
        $images = Image::all();
        return response()->json($images);
    }

    public function delete(Request $request)
    {
        $image = Image::find($request->id);
        if ($image) {
            $file = public_path('storage/uploads/' . $image->name);
            if (File::exists($file)) File::delete($file);
            $thumbnail = public_path('storage/uploads/thumbnail/' . $image->name);
            if (File::exists($thumbnail)) File::delete($thumbnail);
            $image->delete();
            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Image not found.'
        ], 404);
    }
}
