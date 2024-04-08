<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Testimonial;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function index()
    {
        $review = Testimonial::orderBy('id', 'asc')->paginate(20);
        return view('testimonial.index')->with('testimonial',$review);

    }
 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('testimonial.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make(request()->all(), [
            'message' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'status' => 400,
                'success' => false
            ]);
        } else {

            $review = Testimonial::create([
                'name' => $request->name,
                'status' => $request->status,
                'message' => $request->message
            ]);

            $review->save();
            if ($review) {
                return redirect()->route('testimonial.index')->with('status', 'Review successfully added');
            } else {
                return redirect()->route('testimonial.index')->with('error', 'Error occurred while adding Review');
            }


        }
        

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $data = Testimonial::findOrFail($id);
        return view('testimonial.edit')->with('testimonial',$data);
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
        $test = Testimonial::findOrFail($id);
        if ($test) {

            if ($request->filled('name')) {
                $test->name = $request->name;
            }
            if ($request->filled('status')) {
                $test->status = $request->status;
            }
            if ($request->filled('message')) {
                $test->message = $request->message;
            }

            $test->save();

            return redirect()->route('testimonial.index')->with('status', 'review successfully updated');

        }
        else{
            return redirect()->route('testimonial.index')->with('error', ' Error occurred while updating  review ');
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
        $test = Testimonial::findOrFail($id);
      
        if($test){
            $test->delete();
            return redirect()->route('testimonial.index')->with('status', 'review uccessfully deleted');
        }
        else{
            return redirect()->route('testimonial.index')->with('error', 'Error occurred while deleting review ');

        }
    }

    public function getAllReview()
    {
        $reviews = Testimonial::where('status','active')->orderBy("created_at", "asc")->get();
        return response()->json([
            "success" => true,
            "reviews" => $reviews
        ], 200);
    }
}
