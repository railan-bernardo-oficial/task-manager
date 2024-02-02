<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::all();

        return response()->json(['data'=> $users]);
    }

    public function show($id)
    {
        $user = User::find($id);

        if(empty($user)){
            return response()->json(['message'=> 'Esse usuário não existe.', 'status'=> 'error'], 409);
        }

        return response()->json(['data'=> $user]);

    }

    public function store(Request $request)
    {
        $user = new User;

        $findUser = $user::where('email', $request->email)->first();

        if(!empty($findUser)){
            return response()->json(['message'=> 'Tente outro, este e-mail já está em uso!', 'status'=> 'error'], 409);
        }

        $user->name = $request->name;
        $user->username = Str::lower(str_replace(' ', '.', $request->name));
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        if($user->save()){
            return response()->json(['message'=> 'Criado com sucesso!', 'status'=>'success']);
        }

        return response()->json(['message'=> 'Não foi possível cadastrar tente novamente mais tarde!', 'status'=> 'error'], 500);
    }

    public function edit(Request $request, $id)
    {
        $user = User::find($id);

        if(empty($user)){
            return response()->json(['message'=> 'Esse usuário não existe!', 'status'=> 'error'], 409);
        }

        $user->name = $request->name;
        $user->username = Str::lower(str_replace(' ', '.', $request->name));
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        if($request->hasFile('avatar') && $request->file('avatar')->isValid()){
            $path = $request->file('avatar')->store('avatars');

            $user->avatar = $path;
        }

        if($user->save()){
            return response()->json(['message'=> 'Atualizado com sucesso!', 'status'=>'success']);
        }

        return response()->json(['message'=> 'Tente outro, este e-mail já está em uso!', 'status'=> 'error'], 500);
    }

    public function delete($id)
    {
        $user = User::find($id);

        if(empty($user)){
            return response()->json(['message'=> 'Esse usuário não existe.', 'status'=> 'error'], 409);
        }

        Storage::delete($user->avatar);
        $user->delete();

        return response()->json(['message'=> 'Deletado com sucesso!', 'status'=> 'success']);
    }

}
