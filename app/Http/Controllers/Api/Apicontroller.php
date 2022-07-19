<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Recommendation;
use App\Models\Friend;

class Apicontroller extends Controller
{
    public function createRecommendation(Request $request)
    {      
        $rules = [
        	"name" => "required",
            "cpf" => "required|cpf",
            "phone" => "required",
            "email" => "required|email",
            "title" => "required|min:3|max:255",
            "description" => "required|min:3|max:255"
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $friend = $this->createFriend($request);

        $recommendation = new Recommendation;
        $recommendation->title = $request->title;
        $recommendation->description = $request->description;
        $recommendation->friend_id = $friend->getOriginalContent()->id;
        $recommendation->save();

        return response()->json($recommendation, 201);
    }

    public function createFriend(Request $request)
    {  
        $friend = Friend::where('cpf', $request->cpf)->first();
        
        if($friend) {
            return response()->json($friend, 200);
        }       
         
        $friend = new Friend;
        $friend->name = $request->name;
        $friend->cpf = $request->cpf;
        $friend->phone = $request->phone;
        $friend->email = $request->email;
        $friend->save();
  
        return response()->json($friend, 201);
    }

    public function updateFriend(Request $request)
    {  
        $friend = Friend::where('cpf', $request->cpf)->first();
 
        if($request->name) {
            $friend->name = $request->name;
        }
        if($request->phone) {
            $friend->phone = $request->phone;
        }
        if($request->email) {
            $friend->email = $request->email;
        }
        $friend->save();  
        return response()->json($friend, 204);
    }
    
    public function getAllRecommendations()
    {
        $recommendations = Recommendation::all();
        foreach ($recommendations as $recommendation) {
            $recommendation->friend;
        }
        return response()->json($recommendations, 200);
    }
    
    public function getRecommendation($id)
    { 
        $recommendation = Recommendation::find($id);
        $recommendation->friend;

        return response()->json($recommendation, 200);
    }

    public function updateRecommendation(Request $request, $id)
    {
        $rules = [
            "cpf" => "cpf",
            "email" => "email",
            "title" => "min:3|max:255",
            "description" => "min:3|max:255"
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $this->updatefriend($request);

        $recommendation = Recommendation::find($id);
        if($request->title) {
            $recommendation->title = $request->title;
        }
        if($request->description) {
            $recommendation->description = $request->description;
        }
        
        $recommendation->save();
        return response()->json($recommendation, 204);
    }

    public function removeRecommendation($id)
    {
        $recommendation = Recommendation::find($id);

        $recommendation->delete();
        return response()->json(null, 204);
    }

    public function updateRecommendationStatus($id)
    {
        $recommendation = Recommendation::find($id);
         
        if($recommendation->status == 'initiated') {
            $recommendation->status = 'in process';
        } elseif ($recommendation->status = 'in process') {
            $recommendation->status = 'finished';
        }
        
        $recommendation->save();
        return response()->json($recommendation, 204);
    }
}
