<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeamMemberController extends Controller
{
    public function index()
    {
        $team = TeamMember::orderBy('id', 'asc')->get();
        return view('backend.team.index')->with('team', $team);

    }

    public function create()
    {
        return view('backend.team.create');
    }

    public function store(Request $request)
    {

        $validator = Validator::make(request()->all(), [
           
            'designation' => 'required'
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
                $imagePath = public_path() . '/storage/photos/team/';
                $file->move($imagePath, $imageName);
            } else {
                return response()->json([
                    'message' => 'Image field is required!',
                    'status' => 400,
                    'success' => false
                ],400);
            }
            
            $team = TeamMember::create([
                'name' => $request->title,
                'status' => $request->status,
                'designation' => $request->designation,
                'image' => 'public/storage/photos/team/' . $imageName
            ]);

            $team->save();
            if ($team) {
                return redirect()->route('team.index')->with('status', 'member successfully added');
            } else {
                return redirect()->route('team.index')->with('error', 'Error occurred while adding member');
            }


        }
        

    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $team = TeamMember::findOrFail($id);
        return view('backend.team.edit')->with('team', $team);
    }

    public function update(Request $request, $id)
    {
        $team = TeamMember::findOrFail($id);
        if ($team) {
          
            if (($request->hasFile('photo'))) {
                $file = $request->photo;
                $imageName = time() . '.' . $file->getClientOriginalName();
                $imageName = str_replace(' ', '_', $imageName);
                $imagePath = public_path() . '/storage/photos/team/';
                $file->move($imagePath, $imageName);
                $team->image = 'public/storage/photos/team/' . $imageName;
        }

            if ($request->filled('title')) {
                $team->name = $request->title;
            }

            if ($request->filled('designation')) {
                $team->designation = $request->designation;
            }

            if ($request->filled('status')) {
                $team->status = $request->status;
            }

            $team->save();

            return redirect()->route('team.index')->with('status', 'member successfully updated');

        }
        else{
            return redirect()->route('team.index')->with('error', ' Error occurred while updating  member ');
        } 
    }

    public function destroy($id)
    {
        $team = TeamMember::findOrFail($id);
      
        if($team){
            $team->delete();
            return redirect()->route('team.index')->with('status', 'member uccessfully deleted');
        }
        else{
            return redirect()->route('team.index')->with('error', 'Error occurred while deleting member ');

        }
    }

    public function getAllMember()
    {
        $team = TeamMember::where('status','active')->orderBy("created_at", "desc")->get();
        return response()->json([
            "success" => true,
            "members" => $team
        ], 200);
    }
}
