<?php

namespace App\Http\Controllers;

use App\Name;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class NameController extends Controller
{
    public function __construct() {
        $this->middleware('jwt.auth', ['except' => []]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
      return Name::orderBy('id', 'desc')->select('id', 'first_name', 'last_name', 'created_at')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|between:2,20',
            'last_name' => 'required|between:2,30'
        ]);

        if ($validator->fails()) {
          return response()->json(['error' => $validator->errors()->all()], 401);
        } else {
          $user = JWTAuth::parseToken()->authenticate();
          $name = new Name;
          $name->user_id = $user->id;
          $name->first_name = $request->first_name;
          $name->last_name = $request->last_name;
          $name->save();
          return response()->json(['saved' => true], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {

      $name = Name::where('id', $id)->select('id', 'first_name', 'last_name', 'created_at')->get();
      if ( is_null($name) ){
        return response()->json(['error' => 'not_found'], 404);
      }
      return $name;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id) {
        $name = Name::find($id);

        if ( is_null($name) ) {
          return response()->json(['error' => 'not_found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'between:2,20',
            'last_name' => 'between:2,30'
        ]);

        if ($validator->fails()) {
          return response()->json(['error' => $validator->errors()->all()], 401);
        } else {
          $user = JWTAuth::parseToken()->authenticate();
          $name->user_id = $user->id;
          $name->first_name = $request->first_name;
          $name->last_name = $request->last_name;
          $name->save();
          return response()->json(['updated' => true], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
      $name = Name::find($id);

      if ( is_null($name) ) {
        return response()->json(['error' => 'not_found'], 404);
      }
      $name->delete();

      return response()->json(['deleted' => true], 200);
    }

}