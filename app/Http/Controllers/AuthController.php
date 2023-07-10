<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\adm_usuario;
use Exception;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => ['login', 'logout']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'us_login' => 'required|string|max:25',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->toJson(),
                "status" => 400
            ], 400);
        }
        $modulos = DB::select(' SELECT u.us_login , ru.url_login, r.rol_codigo, urm.urm_codigo_rol, mo.mo_id
        from adm_usuario u
        JOIN adm_rol_usuario ru ON ru.url_login = u.us_login
        JOIN adm_rol r ON r.rol_codigo  = ru.url_rol
        JOIN ccc_usuario_rol_modulo urm ON urm.urm_id_modulo =r.rol_codigo
        JOIN ccc_modulo mo ON mo.mo_id= urm.urm_id_modulo
        ');
        $user = adm_usuario::select(
            'us_login',
            'us_contrasenia',
            'us_nombre',
        )->where('us_login', '=', $request->us_login)->first();
        if ($user->us_contrasenia == md5($request->us_contrasenia)) {
            $token = auth()->login($user);
            $moduloUsuario='';
            foreach ($modulos as $modulo) {
                if (($request->us_login == $modulo->us_login) &&($modulo->mo_id == 2))
                {
                    $moduloUsuario='CONTROL DE CERRAMIENTO';
                }
                if (($request->us_login == $modulo->us_login) &&($modulo->mo_id == 1))
                {
                    $moduloUsuario='CONTROL DE CONSTRUCCIONES';
                }
                if (($request->us_login == $modulo->us_login) &&($modulo->mo_id == 3))
                {
                    $moduloUsuario='CONTROL DE PUBLICIDAD';
                }
            }
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60,
                'modulo_Usuario'=> $moduloUsuario,
            ]);
            return response()->json(['error' => 'No autorizado'], 401);
        }

        

        
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = auth()->user();
        unset(
            $user['us_contrasenia'],
        );
        // return response()->json($user);
        return response()->json(['message' => 'Bienvenido']);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Sesion cerrada correctamente']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function refresh()
    {
        return $this->respondWithToken(JWTAuth::factory()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ]);
    }

    /* public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(),400);
        }

        $user = User::create(array_merge(
            $validator->validate(),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json([
            'message' => '¡Usuario registrado exitosamente!',
            'user' => $user
        ], 201);
    } */
    
}
