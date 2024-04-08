<?php

namespace App\Http\Controllers;

use App\Models\GalleryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GalleryCategoryController extends Controller
{
    public function index()
    {
        $gallery = GalleryCategory::orderBy('id', 'asc')->get();
        return view('gallery_category.index')->with('gallerys', $gallery);

    }
    public function create()
    {
        return view('gallery_category.create');
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
            
            $Category = GalleryCategory::create([
                'name' => $request->title,
                'status' => $request->status,
            ]);

            $Category->save();
            if ($Category) {
                return redirect()->route('gallery_category.index')->with('status', 'Category successfully added');
            } else {
                return redirect()->route('gallery_category.index')->with('status', 'Error occurred while adding Category');
            }


        }
        

    }

    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $gallery = GalleryCategory::findOrFail($id);
        return view('gallery_category.edit')->with('gallerys', $gallery);
    }

      /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $gallery = GalleryCategory::findOrFail($id);
        if ($gallery) {
          

            if ($request->filled('name')) {
                $gallery->name = strtolower($request->name);
            }
            if ($request->filled('status')) {
                $gallery->status = $request->status;
            }

            $gallery->save();

            return redirect()->route('gallery_category.index')->with('status', 'Genre successfully updated');

        }
        else{
            return redirect()->route('gallery_category.index')->with('status', ' Error occurred while updating  Genre ');
        } 
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $gallery = GalleryCategory::findOrFail($id);
      
        if($gallery){
            $gallery->delete();
            return redirect()->route('gallery_category.index')->with('status', 'Genre successfully deleted');
        }
        else{
            return redirect()->route('gallery_category.index')->with('status', '  Error occurred while deleting Genre ');

        }
    }

    public function getAllCategory()
    {
        $category = GalleryCategory::with(['gallery'])->where('status','active')->orderBy("created_at", "asc")->get();
        return response()->json([
            "success" => true,
            "category" => $category
        ], 200);
    }

    public function getCategory($id){
        $category = GalleryCategory::with(['gallery'])->find($id);;
        return response()->json([
            'message' => 'category retrievied successfully',
            "success" => true,
            'category' => $category
        ],200);
    }

}
