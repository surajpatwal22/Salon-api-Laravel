<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Contact;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SalonController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'message' => 'required',
            'name' => 'required',
            'number' => 'required',
            'subject' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'status' => 400,
                'success' => false
            ]);
        } else {

            $query = Contact::create([
                'name' => $request->name,
                'number' => $request->number,
                'subject' => $request->subject,
                'message' => $request->message,
            ]);

            $query->save();
            if ($query) {
                return redirect()->route('contact.index')->with('status', 'query successfully added');
            } else {
                return redirect()->route('contact.index')->with('error', 'Error occurred while adding query');
            }


        }
        

    }

    public function getBannerImages()
    {
        $images = Banner::where('status','active')->orderBy("created_at", "asc")->get();
        return response()->json([
            "success" => true,
            "images" => $images
        ], 200);
    }

    public function reservation(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'message' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'date' => 'required',
            'time' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'status' => 400,
                'success' => false
            ]);
        } else {

            $query = Reservation::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'date' => $request->date,
                'time' => $request->time,
                'message' => $request->message,
            ]);

            $query->save();
            if ($query) {
                return redirect()->route('contact.index')->with('status', 'query successfully added');
            } else {
                return redirect()->route('contact.index')->with('error', 'Error occurred while adding query');
            }


        }
        

    }

}
