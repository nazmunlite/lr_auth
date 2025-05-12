<?php
namespace Zems\LrAuth;

use App\Models\User;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends ZemsAuth{
    public function profile(){
        return Auth::user();
        $user_id = Auth::id();
        $user = User::select('name', 'phone')->find($user_id);
        return $user;
    }

    public function update(Request $request){
        $user_id = Auth::id();
        // return $request->all();   
        $user = User::find($user_id);
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->image = $request->image;
        $user->save();
        return 'Data Updated Successfully';

    }

    public function change_password(Request $request){
        // return $request->old_password;
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]); 
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $user = User::find(Auth::id());
        // return $user->password;
        if(Hash::check($request->old_password, $user->password)){
            $user->password = bcrypt($request->password);
            $user->save();
            return ['style'=>'bg-success', 'value'=>'Your password Successfully changed'];
        }
        return ['style'=>'bg-error', 'value'=>'Your password Not matched'];

    }

    
}
