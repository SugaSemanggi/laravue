<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use Image;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $this->authorize('isAdmin');
        if (\Gate::allows('isAdmin') || \Gate::allows('isOperator')) {
            // The user can update the post...
            return User::latest()->paginate(10);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:191',
            'email' => 'required|email|max:191|unique:users',
            'password'=> 'required|max:191|min:6',
        ]);
        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'type' => $request['type'],
            'bio' => $request['bio'],
            'photo' => $request['photo'],
        ]);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|max:191',
            'email' => 'required|email|max:191|unique:users,email,'.$user->$id,
            'password'=> 'sometimes|min:6',
        ]);

        $user->update($request->all());
        return $id;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user=User::findOrFail($id);

        $user->delete();
        // return ['message' => 'delete'];
    }

    public function profile(){
        return auth('api')->user();
    }


    public function updateProfile(Request $request){

        $user = auth('api')->user();

        $this->validate($request, [
            'name' => 'required|max:191',
            'email' => 'required|email|max:191|unique:users,email,'.$user->id,
            'password'=> 'sometimes|min:6',
        ]);


        $currentPhoto = $user->photo;

        if ($request->photo != $currentPhoto) {
            $name = time().'.'.explode('/',explode(':',substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];

            Image::make($request->photo)->save(public_path('img/profile/').$name);

            $request->merge(['photo' => $name]);

            $userPhoto = \public_path('img/profile/').$currentPhoto;
            if (file_exists($userPhoto)) {
                @unlink($userPhoto);
            }
        }

        if (!empty($request->password)) {
            $request->merge(['password' => Hash::make($request['password'])]);
        }

        $user->update($request->all());

        return ['message' => 'success'];
    }

    public function search(){
        if ($search = \Request::get('q')) {
            $users = User::where(function($query) use ($search) {
                $query->where('name','LIKE',"%$search%")->orWhere('email','LIKE',"%$search%");
            })->paginate(20);
        } else {
            $users = User::latest()->paginate(10);
        }
        return $users;
    }
}
