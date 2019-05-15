<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use JWTAuth;
use JWTAuthException;
use Validator;
use Hash;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    private function getToken($email, $password)
    {
        $token = null;
        //$credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt( ['email'=>$email, 'password'=>$password])) {
                return response()->json([
                    'response' => 'error',
                    'message' => 'Correo o contraseña invalidos',
                    'token'=>$token
                ]);
            }
        } catch (JWTAuthException $e) {
            return response()->json([
                'response' => 'error',
                'message' => 'No se pudo generar el token',
            ]);
        }
        return $token;
    }

    public function index()
    {
        $users = User::all();
        
        $response = ['success'=>true, 'data'=>$users];
        return response()->json($response, 201);
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->get()->first();
        if ($user && Hash::check($request->password, $user->password)) // The passwords match...
        {
            $token = self::getToken($request->email, $request->password);
            $user->auth_token = $token;
            $user->save();
            $response = ['success'=>true, 'data'=>['id'=>$user->id,'auth_token'=>$user->auth_token,'name'=>$user->name, 'email'=>$user->email]];           
        }
        else 
          $response = ['success'=>false, 'data'=>'Correo o contraseña invalidos'];
      
        return response()->json($response, 201);
    }
    public function register(UserRequest $request)
    { 
        
        $payload = [
            'password'=>Hash::make($request->password),
            'email'=>$request->email,
            'name'=>$request->name,
            'auth_token'=> ''
        ];


        $user = new User($payload);
        if ($user->save())
        {
            
            $token = self::getToken($request->email, $request->password); // generate user token
            
            if (!is_string($token))  return response()->json(['success'=>false,'data'=>'El token no ha sido generado'], 201);
            
            $user = User::where('email', $request->email)->get()->first();
            
            $user->auth_token = $token; // update user token
            
            $user->save();
            
            $response = ['success'=>true, 'data'=>['name'=>$user->name,'id'=>$user->id,'email'=>$request->email,'auth_token'=>$token]];        
        }
        else
            $response = ['success'=>false, 'data'=>'No se pudo registrar el usuario'];
        
        
        return response()->json($response, 201);
    }

    public function update($id,UpdateUserRequest $request)
    {
    	$user = User::find($id);
    	
    	if ($user) {
	    	$user->fill($request->only('name','email'));
	    	$user->password = $request->password ? Hash::make($request->password) : $user->password;
	    	$user->save();
	    	$response = ['success'=>true, 'data'=>$user->toArray()];
    	}else{
    		$response = ['success'=>false, 'data'=>'Usuario no encontrado'];
    	}

    	return response()->json($response, 201);
    }

    public function delete($id)
    {
    	$user = User::find($id);
    	if ($user) {
	    	$user->delete();
	    	$response = ['success'=>true, 'data'=>$user->toArray()];
    	}else{
    		$response = ['success'=>false, 'data'=>'Error al eliminar usuario'];
    	}

    	return response()->json($response, 201);
    }
}