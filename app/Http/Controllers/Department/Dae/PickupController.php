<?php

namespace App\Http\Controllers\Department\Dae;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\SouvenirOrder;
use Inertia\Inertia;
use App\Models\SouvenirUser;

class PickupController extends Controller
{
    public function index(){
        return Inertia::render('Department/Dae/Pickup',[
            'department'=>session('department')
        ]);
    }
    public function pickupCode(Request $request){
            // Validate the incoming request
            $request->validate([
                'code' => 'required|string|max:255', // Ensure 'code' is present and is a string
            ]);

            // Fetch the user based on your business logic; for testing, we'll just get the first user
            $user = SouvenirUser::with('orders')->first();

            // Check if user exists
            if (!$user) {
                return response()->json([
                    'message' => 'No user found.',
                    'request' => $request->all(),
                    'user'=>$user
                ], 404); // Return 404 if no user found
            }

            // Return the response with the request data and user details
            return response()->json([
                'message' => 'Success',
                'request' => $request->all(),
                'user' => $user,
            ]);
    }

    public function pickupConfirm(Request $request){
        // Validate the incoming request
        $request->validate([
            'code' => 'required|string|max:255', // Ensure 'code' is present and is a valid string
        ]);

        // Split the code by '-'
        $parts = explode('-', $request->code);
        // Check if the code is valid
        // return response()->json($request->all(), $userId, $hashCode);
        if (count($parts) !== 2) {
            return response()->json(['message' => 'Invalid code format.'], 400);
        }

        $userId = $parts[0]; // First part is the user ID
        $hashCode = $parts[1]; // Second part is the hash code
        // Find the user by ID
        $user = SouvenirUser::find($userId);

        // Check if user exists
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $salt=env('SALT','dae-souvenir');
        if (hash('sha256',$user->id.$salt) !== $hashCode) {
            return response()->json(['message' => 'Invalid hash code.'], 403);
        }

        // Update all order statuses to 3
        // $updated = SouvenirOrder::where('souvenir_user_id', $user->id)->update(['status' => 3]);
        $updated=$user->orders()->where('status',2)->update(['status'=>3]);
        // return response()->json([
        //     "code"=>$request->code, 
        //     'parts'=>$parts,
        //     'userId'=>$userId,
        //     'userId2'=>$user->id,
        //     'hashCode'=>$hashCode,
        //     'cc'=>hash('sha256',$user->id.$salt),
        //     'ccb'=>hash('sha256',$user->id.$salt)==$hashCode,
        //     'salt'=>env('SALT'),
        //     'user'=>$user->toArray(),
        //     'pp'=>$updated,
        // ]);

        // Check if any orders were updated
        if ($updated) {
            return response()->json(['message' => 'Pickup confirmed successfully.']);
        } else {
            return response()->json(['message' => 'No orders found for this user.'], 404);
        }

    }
    public function store(Request $request){
        dd($request->all());
    }
    public function getOrder(Request $request){
        dd($request->all());
    }

}
