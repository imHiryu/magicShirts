<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Models\Users;

class UserController extends Controller
{

    public function index()
    {
        return view('user.Login');
    }

    public function indexUsers()
    {
        $listaUsers = User::select('id', 'name', 'created_at', 'tipo', 'bloqueado')->paginate(20);
        //dd($listaUsers[19]->cliente);

        return view('admin.UserManagement')
            ->withUsers($listaUsers);
    }

    public function registerPage()
    {
       echo(csrf_token());
        return view('user.Register');
    }

    public function register(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'bail|required|max:255',
            'email' => 'bail|required|max:255|email:filter',
            'password' => 'bail|required|max:24||min:8confirmed',
            'password_confirmation' => 'bail|required|max:24|min:8'
        ],
        [ // Custom Messages
            'name.required' => 'É obrigatório introduzir o nome',
            'email.required' => 'É obrigatório introduzir o email',
            'password.required' => 'É obrigatório introduzir a password',
            'password.max:24' => 'A password tem de ter entre 8 a 24 carateres',
            'password.min:8' => 'A password tem de ter entre 8 a 24 carateres',
            'password_confirmation.required' => 'É obrigatório introduzir a confirmação de password',
            'password_confirmation.max:24' => 'A password tem de ter entre 8 a 24 carateres',
            'password_confirmation.min:8' => 'A password tem de ter entre 8 a 24 carateres',
            'password.confirmed' => 'As password têm de ser iguais',
        ]);

        $inputName = $request->input('name');
        $inputEmail= $request->input('email');
        $inputPassword= $request->input('password');
        $confirmPassword= $request->input('password_confirmation');

      return response()->json($inputName." ".$inputEmail." ".$inputPassword." ".$confirmPassword, 200);
    }

    public function permission(Request $request, User $user)
    {
        //dd($request['tipo']);
        $user->tipo = $request['tipo'];
        $user->save();
        return redirect()->route('Users');
    }

    public function block(User $user)
    {
        if ($user->bloqueado == 1) {
            $user->bloqueado = 0;
        }
        else{
            $user->bloqueado = 1;
        }

        //dd($user->bloqueado);
        $user->save();
        return redirect()->route('Users');
    }

    public function delete(User $user)
    {
        $oldUserName = $user->name;
        try {
            $user->delete();
            if (!is_null($user->foto_url)) {
                $oldFotoUrl = $user->foto_url;
                Storage::delete('public/fotos/' . $oldFotoUrl);
            }
            return redirect()->route('Users')
            ->with('alert-msg', 'Utilizador "' . $oldUserName . '" foi apagado com sucesso!')
            ->with('alert-type', 'success');

        } catch (\Throwable $th) {
            // $th é a exceção lançada pelo sistema - por norma, erro ocorre no servidor BD MySQL
            // Descomentar a próxima linha para verificar qual a informação que a exceção tem

            if ($th->errorInfo[1] == 1451) {   // 1451 - MySQL Error number for "Cannot delete or update a parent row: a foreign key constraint fails (%s)"
                return redirect()->route('admin.users')
                    ->with('alert-msg', 'Não foi possível apagar o Utilizador "' . $oldUserName . '", porque este utilizador já está em uso!')
                    ->with('alert-type', 'danger');
            } else {
                return redirect()->route('admin.users')
                    ->with('alert-msg', 'Não foi possível apagar o Utilizador "' . $oldUserName . '". Erro: ' . $th->errorInfo[2])
                    ->with('alert-type', 'danger');
            }
        }
    }

    public function destroy_foto(User $user)
    {
        Storage::delete('public/fotos/' . $user->user->url_foto);
        $user->foto_url = null;
        $user->user->save();
        return redirect()->route('admin.users.edit', ['user' => $user])
            ->with('alert-msg', 'Foto do user "' . $user->name . '" foi removida!')
            ->with('alert-type', 'success');
    }
}
