<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
 

    public function index()
    {
        $products = Contact::orderBy('id', 'asc')->paginate(100);
        return view('contact.index')->with('products',$products);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
    }

 
    public function store(Request $request)
    {

        $validator = Validator::make(request()->all(), [
            'message' => 'required',
            'name' => 'required',
            'email' => 'required',
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
                'email' => $request->email,
                'message' => $request->message,
                'subject' => $request->subject,

            ]);

            $query->save();
            if ($query) {
                return redirect()->route('contact.index')->with('status', 'query successfully added');
            } else {
                return redirect()->route('contact.index')->with('error', 'Error occurred while adding query');
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
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
      
    }

   
    public function destroy($id)
    {
        $query = Contact::findOrFail($id);
      
        if($query){
            $query->delete();
            return redirect()->route('admin')->with('status', 'query successfully deleted');
        }
        else{
            return redirect()->route('contact.index')->with('error', 'Error occurred while deleting query ');

        }
    }

}
