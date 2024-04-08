<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banner = Banner::orderBy('id', 'DESC')->paginate(100);
        return view('banner.index')->with('banners', $banner);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('banner.create');
    }


    public function store(Request $request)
    {

        $validator = Validator::make(request()->all(), [
            'photo' => 'mimes:jpeg,png,jpg,gif|max:10240'
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
                $imagePath = public_path() . '/storage/photos/banner';
                $file->move($imagePath, $imageName);
            } else {
                return response()->json([
                    'message' => 'Image field is required!',
                    'status' => 400,
                    'success' => false
                ], 400);
            }

            $banner = Banner::create([
                'status' => $request->status,
                'title' => $request->title,
                'sub_title' => $request->sub_title,
                'photo' => 'public/storage/photos/banner/' . $imageName
            ]);

            $banner->save();
            if ($banner) {
                return response()->json([
                    'message' => 'created successfully',
                    'status' => 200,
                    'success' => true
                ], 200);
            } else {
                return response()->json([
                    'message' => 'something went wrong',
                    'status' => 400,
                    'success' => false
                ], 400);
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
        $banner = Banner::findOrFail($id);
        return view('banner.edit')->with('banner', $banner);
    }


    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);
        if ($banner) {

            if (($request->hasFile('photo'))) {
                $file = $request->photo;
                $imageName = time() . '.' . $file->getClientOriginalName();
                $imageName = str_replace(' ', '_', $imageName);
                $imagePath = public_path() . '/storage/photos/banner/';
                $file->move($imagePath, $imageName);
                $banner->photo = 'public/storage/photos/banner/' . $imageName;
            }


            if ($request->filled('status')) {
                $banner->status = $request->status;
            }
            if ($request->filled('title')) {
                $banner->title = $request->title;
            }
            if ($request->filled('sub_title')) {
                $banner->sub_title = $request->sub_title;
            }

            $banner->save();

            return response()->json([
                'message' => 'updated successfully',
                'status' => 200,
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'message' => 'something went wrong',
                'status' => 400,
                'success' => false
            ], 400);
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
        $banner = Banner::findOrFail($id);
      
        if($banner){
            $banner->delete();
            return response()->json([
                'message' => 'updated successfully',
                'status' => 200,
                'success' => true
            ], 200);
        }
        else{
            return response()->json([
                'message' => 'something went wrong',
                'status' => 400,
                'success' => false
            ], 400);

        }
    }

}
