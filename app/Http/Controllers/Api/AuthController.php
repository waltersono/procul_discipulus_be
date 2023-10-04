<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ],[
            'email.required' => 'O campo email é obrigatório.',
            'password.required' => 'O campo senha é obrigatório.'
        ]);

        $user = User::where('email', $request['email'])->first();

        if(isset($user) && $user->hasRole('Estudante') && Auth::attempt($request->only('email', 'password'))){
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json(['access_token' => $token,'user' => $user]);
        }else{
            return response()->json([
                'success' => false,
                'message' => "Usuário ou senha inválido/a"
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function register(Request $request)
    {
        // Validação dos dados
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // Verifica se a validação falhou
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['access_token' => $token,'token_type' => 'Bearer'], Response::HTTP_CREATED);
    }

    public function tokenVerify()
    {
        $user = Auth::user();
        if ($user) {
            return response()->json(['status' => true], Response::HTTP_OK);
        } else {
            return response()->json(['status' => false], Response::HTTP_BAD_REQUEST);
        }
    }

    public function profile(Request $request)
    {
        return response()->json(['user' => $request->user()], Response::HTTP_OK);
    }

    public function sendResetLinkEmail(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'email' => 'required'
        ],[
            'email.required' => 'O campo email é obrigatório.'
        ]);
        
        $status = Password::sendResetLink(
            $request->only('email')
        );
        
        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Enviamos-lhe um token para redefinir a senha. Verifique o seu e-mail.'])
            : response()->json(['message' => 'O seu e-mail não foi encontrado nos nossos registros.'], Response::HTTP_NOT_FOUND);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required'
        ]);

        // $status = Password::reset(
        //     $request->only('email', 'password', 'password_confirmation', 'token'),
        //     function ($user, $password) {
        //         $user->forceFill([
        //             'password' => bcrypt($password)
        //         ])->setRememberToken(Str::random(60));

        //         $user->save();
        //     }
        // );

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );
        
        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Senha redefinida com sucesso'])
            : response()->json(['message' => 'Não foi possível redefinir a senha'], Response::HTTP_NOT_FOUND);
    }
}
