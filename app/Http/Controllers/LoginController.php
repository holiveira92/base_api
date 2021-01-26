<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function index(){
        return "teste index";
    }

    public function register(Request $request){ 
        $responseArray          = [];
        $input                  = $request->all();
        $input['password']      = bcrypt($input['password']);
        $user                   = User::create($input);
        $responseArray['token'] = $user->createToken('MyApp')->accessToken;
        $responseArray['name']  = $user->name;
        return response()->json($responseArray,200);  
    }

    public function login(Request $request){ 
        $responseArray              = [];
        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
            $user                   = Auth::user();
            $responseArray['token'] = $user->createToken('LaravelApp')->accessToken;
            $responseArray['name']  = $user->name;
            return response()->json($responseArray,200);
        }else{
            return response()->json(['error'=>'Unauthenticated'],203);
        }
    }

    public function loginv2(Request $request){
        $credenciais                = request(['email','password']);
        if(!Auth::attempt($credenciais)){
            $resposta = ['error'    => "Não Autorizado"];
            return response()->json($resposta,404);
        }
        $usuario                    = $request->user();
        $resposta['name']           = $usuario->name;
        $resposta['email']          = $usuario->email;
        $resposta['token']          = $usuario->token;
        return response()->json($resposta,200);
    }

    public function logout(Request $request){
        $isUser                     = $request->user()->token()->revoke();
        if($isUser){
            $resposta               = ['message' => "Logou Efetuado com sucesso."];
            return response()->json($resposta,200);
        }else{
            $resposta               = ['message' => "Algo deu errado."];
            return response()->json($resposta,404);
        }
    }
    
}
