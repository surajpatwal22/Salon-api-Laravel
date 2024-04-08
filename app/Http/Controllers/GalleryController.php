<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\GalleryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GalleryController extends Controller
{
    public function index()
    {
        $gallery = Gallery::orderBy('id', 'asc')->get();
        return view('backend.gallery.index')->with('gallerys', $gallery);

    }

    public function create()
    {
        $category = GalleryCategory::all();
        return view('backend.gallery.create')->with('category', $category);
    }

    public function store(Request $request)
    {

        $validator = Validator::make(request()->all(), [
            'title' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'status' => 400,
                'success' => false
            ]);
        } else {

            if ($request->photo) {
                $file = $request->photo;
                $imageName = time() . '.' . $file->getClientOriginalName();
                $imageName = str_replace(' ', '_', $imageName);
                $imagePath = public_path() . '/storage/photos/gallery/';
                $file->move($imagePath, $imageName);
            } else {
                return response()->json([
                    'message' => 'Image field is required!',
                    'status' => 400,
                    'success' => false
                ],400);
            }
            
            $gallery = Gallery::create([
                'title' => $request->title,
                'status' => $request->status,
                'gallery_category_id' => $request->gallery_category_id,
                'image' => 'public/storage/photos/gallery/' . $imageName
            ]);

            $gallery->save();
            if ($gallery) {
                return redirect()->route('gallery.index')->with('status', 'gallery successfully added');
            } else {
                return redirect()->route('gallery.index')->with('error', 'Error occurred while adding gallery');
            }


        }
        

    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $gallery = Gallery::findOrFail($id);
        $category = GalleryCategory::all();
        return view('backend.gallery.edit')->with('gallerys', $gallery)->with('category', $category);
    }

    public function update(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);
        if ($gallery) {
          
            if (($request->hasFile('photo'))) {
                $file = $request->photo;
                $imageName = time() . '.' . $file->getClientOriginalName();
                $imageName = str_replace(' ', '_', $imageName);
                $imagePath = public_path() . '/storage/photos/gallery/';
                $file->move($imagePath, $imageName);
                $gallery->image = 'public/storage/photos/gallery/' . $imageName;
        }

            if ($request->filled('name')) {
                $gallery->title = $request->name;
            }
            if ($request->filled('status')) {
                $gallery->status = $request->status;
            }
            if ($request->filled('gallery_category_id')) {
                $gallery->gallery_category_id = $request->gallery_category_id;
            }

            $gallery->save();

            return redirect()->route('gallery.index')->with('status', 'image successfully updated');

        }
        else{
            return redirect()->route('gallery.index')->with('error', ' Error occurred while updating  image ');
        } 
    }

    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);
      
        if($gallery){
            $gallery->delete();
            return redirect()->route('gallery.index')->with('status', 'gallery successfully deleted');
        }
        else{
            return redirect()->route('gallery.index')->with('error', 'Error occurred while deleting gallery ');

        }
    }

    public function getAllImages()
    {
        $images = Gallery::with(['category'])->where('status','active')->orderBy("created_at", "desc")->get();
        return response()->json([
            "success" => true,
            "images" => $images
        ], 200);
    }


}
