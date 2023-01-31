<?php

namespace App\Http\Controllers\Api;

use App\Api\Data;
use App\Api\Messages;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePessoaRequest;
use App\Http\Requests\UpdatePessoaRequest;
use App\Models\HistoricoStatus;
use App\Models\Pessoa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PessoaController extends Controller
{
    public function index()
    {
        try {
            $pessoas = Pessoa::all();
        } catch (\Exception $e) {
            return response()->json(Messages::message(false, $e->getMessage()), 500);
        }
        return response()->json(['data' => $pessoas], 200);
    }

    public function consultarPeloNome(Request $request)
    {
        try {
            $pessoas = Pessoa::where('nome', 'like', '%' . $request->nome . '%')->get();
        } catch (\Exception $e) {
            return response()->json(Messages::message(false, $e->getMessage()), 500);
        }
        return response()->json(['data' => $pessoas], 200);
    }

    public function store(StorePessoaRequest $request)
    {
        try {
            if ($request->has('email') && $request->has('password')) {
                $user = User::create($request->all());
            } else {
                $user = User::create(['email' => 'email@email.com', 'password' => Hash::make('password')]);
            }

            Pessoa::create([
                'nome' => $request->nome,
                'documento' => $request->documento,
                'telefone' => $request->telefone,
                'user_id' => $user->id,
                'status' => $request->status
            ]);
        } catch (\Exception $e) {
            return response()->json(Messages::message(false, $e->getMessage()), 500);
        }

        return response()->json(Messages::message(true, 'Pessoa criada com sucesso!'), 201);
    }

    public function update(UpdatePessoaRequest $request, $id)
    {
        try {
            $pessoa = Pessoa::findOrFail($id);
            if ($pessoa->status != $request->status) {
                if (isset($request->user_id)) {
                    HistoricoStatus::create([
                        'user_id' => $request->user_id,
                        'status' => $request->status,
                        'pessoa_id' => $pessoa->id
                    ]);
                } else {
                    HistoricoStatus::create([
                        'user_id' => $pessoa->user->id,
                        'status' => $request->status,
                        'pessoa_id' => $pessoa->id
                    ]);
                }
            }

            $pessoa->update([
                'nome' => $request->nome,
                'documento' => $request->documento,
                'telefone' => $request->telefone,
                'status' => $request->status
            ]);
        } catch (\Exception $e) {
            return response()->json(Messages::message(false, $e->getMessage()), 500);
        }

        return response()->json(Messages::message(true, 'Pessoa atualizada com sucesso!'), 204);
    }

    public function listaHistoricoStatus($id)
    {
        try {
            $listaHistoricoStatus = HistoricoStatus::where('pessoa_id', $id)->get();
        } catch (\Exception $e)
        {
            return response()->json(Messages::message(false, $e->getMessage()), 500);
        }

        return response()->json(['data' => $listaHistoricoStatus], 200);
    }
}