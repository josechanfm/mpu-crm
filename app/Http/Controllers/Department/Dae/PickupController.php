<?php

namespace App\Http\Controllers\Department\Dae;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SouvenirOrder;
use Inertia\Inertia;
use App\Models\SouvenirUser;

class PickupController extends Controller
{
    public function index(){
        // $codeParts[0]=3;
        // $user = SouvenirUser::with('orders')->find($codeParts[0]);
        //dd($user->orders[0]->items);

        return Inertia::render('Department/Dae/Pickup',[
            'department'=>session('department'),
            //'user'=>$user

        ]);
    }
    public function pickupCode(Request $request){
            // Validate the incoming request
            $request->validate([
                'code' => 'required|string|max:255', // Ensure 'code' is present and is a string
            ]);
            //dd($request->all());
            
            $codeParts = explode('-', $request->code);

            $salt=env('SALT','dae-souvenir');
            $hash=hash('sha256',$codeParts[0].$salt); //$codePart[0]== souvenirUser->id;
            if($hash!==$codeParts[1]){
                return response()->json([
                    'message'=>'Invalid access...',
                    'request'=> $request->all(),
                ], 404);
            }
            // Fetch the user based on your business logic; for testing, we'll just get the first user
            $user = SouvenirUser::with('orders')->find($codeParts[0]);
            //dd($request->all(), $codeParts, $salt, $hash, $user);
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
        //$parts = explode('-', $request->code,2);
        list($userId, $hashCode)=$parts = explode('-', $request->code,2);
        // Check if the code is valid
        //dd($request->all(),$parts, $id, $uuid);
        // return response()->json($request->all(), $userId, $hashCode);
        if ($userId === null || $hashCode === null) {
            return response()->json(['message' => 'Invalid code format.'], 400);
        }
        //dd($this->genPickupCode($userId));
        $salt=env('SALT','dae-souvenir');
        if ($this->genPickupCode($userId) !== $request->code) {
            return response()->json(['message' => 'Invalid hash code.'], 403);
        }
    
        // $userId = $parts[0]; // First part is the user ID
        // $hashCode = $parts[1]; // Second part is the hash code
        // Find the user by ID
        $user = SouvenirUser::find($userId);
        // Check if user exists
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        //dd($request->all(), $parts, $userId, $hashCode, $salt, $user, SouvenirOrder::$status['PICKUP']);

        // Update all PAID orders status to PICKUP
        $updated=$user->orders()->where('status',SouvenirOrder::$status['PAID'])->update(['status'=>SouvenirOrder::$status['PICKUP']]);
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
    private function genPickupCode($str){
        $salt=env('SALT','dae-souvenir');
        return $str.'-'.hash('sha256',$str.$salt);
    }

    public function store(Request $request){
        dd($request->all());
    }
    public function getOrder(Request $request){
        dd($request->all());
    }

}
