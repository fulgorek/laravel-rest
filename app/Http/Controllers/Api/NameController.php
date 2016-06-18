<?php

namespace App\Http\Controllers\Api;

use App\Name;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

// we can search all our db, but logged user only can modify their own content
class NameController extends Controller
{
    public function __construct()
    {
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

        if ( $validator->fails() )
        {
          return response()->json(['error' => $validator->errors()->all()], 401);
        } else {
          $user = JWTAuth::parseToken()->authenticate();
          $name = new Name;
          $name->user_id = $user->id;
          $name->first_name = $request->first_name;
          $name->last_name = $request->last_name;
          $name->save();

          return response()->json(['created' => true], 200);
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
      if ( is_null($name) )
      {
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
        $user = JWTAuth::parseToken()->authenticate();
        $name = Name::where('user_id', $user->id)->find($id);

        if ( is_null($name) )
        {
          return response()->json(['error' => 'not_found'], 404);
        }

        if ( $name->user_id !== $user->id )
        {
          return response()->json(['error' => 'not_access'], 401);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'between:2,20',
            'last_name' => 'between:2,30'
        ]);

        if ($validator->fails()) {
          return response()->json(['error' => $validator->errors()->all()], 400);
        } else {
          $new_values = array(
            'first_name' => $request->input('first_name', $name['first_name']),
            'last_name' => $request->input('last_name', $name['last_name'])
          );

          $updated = false;
          if ( count(array_diff($new_values, $name->getOriginal())) > 0 )
          {
            $name->first_name = $new_values['first_name'];
            $name->last_name = $new_values['last_name'];
            $name->save();
            $updated = true;
          }
          return response()->json(['updated' => $updated ], 200);
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
      $user = JWTAuth::parseToken()->authenticate();
      $name = Name::where('user_id', $user->id)->find($id);

      if ( is_null($name) )
      {
        return response()->json(['error' => 'not_found'], 404);
      }
      $name->delete();

      return response()->json(['deleted' => true], 200);
    }

}
