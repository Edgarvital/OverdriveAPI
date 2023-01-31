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

    /**
     * @OA\Get(
     *     tags={"ListarPessoas"},
     *     summary="Retorna a lista de todas as pessoas cadastradas no banco",
     *     description="Recupera todas as linhas da tabela de pessoa do banco e retorna como um json",
     *     path="/pessoas",
     *     @OA\Response(
     *         response="200", description="Success"
     *     ),
     *     @OA\Response(
     *         response="500", description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Not authorized"),
     *             @OA\Property(property="success", type="boolean", example="false"),
     *         )
     * )
     * )
     */
    public function index()
    {
        try {
            $pessoas = Pessoa::all();
        } catch (\Exception $e) {
            return response()->json(Messages::message(false, $e->getMessage()), 500);
        }
        return response()->json(['data' => $pessoas], 200);
    }

    /**
     * @OA\Get(
     *     tags={"ConsultarPeloNome"},
     *     summary="Recupera uma lista de pessoas que possuam o nome passado",
     *     description="Recupera a lista de pessoas a partir de um parametro com o nome e busca no banco as pessoas que tenham aquele nome",
     *     path="/pessoas/consultarPeloNome?nome={nome}",
     *     @OA\Parameter(
     *         description="nome de uma pessoa",
     *         in="path",
     *         name="nome",
     *         required=true,
     *         example="Edgar",
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200", description="Success",
     *     ),
     *     @OA\Response(
     *         response="500", description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Not authorized"),
     *             @OA\Property(property="success", type="boolean", example="false"),
     *         )
     * )
     * )
     */

    public function consultarPeloNome(Request $request)
    {
        try {
            $pessoas = Pessoa::where('nome', 'like', '%' . $request->nome . '%')->get();
        } catch (\Exception $e) {
            return response()->json(Messages::message(false, $e->getMessage()), 500);
        }
        return response()->json(['data' => $pessoas], 200);
    }

    /**
     * @OA\Post(
     * path="/pessoas",
     * summary="Cadastro de Pessoa",
     * description="Cadastro de pessoa através dos campos: nome, documento, telefone, status e email/password como opcionais.
     O campo status deve ser informado como uma das seguintes opções: (Ativo, Inativo, Pendente).",
     * tags={"CadastrarPessoa"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Dados da pessoa",
     *    @OA\JsonContent(
     *       required={"nome","documento", "telefone", "status"},
     *       @OA\Property(property="nome", type="string", format="text", example="Edgar Vinicius"),
     *       @OA\Property(property="documento", type="string", format="text", example="105.977.514-44"),
     *       @OA\Property(property="telefone", type="string", format="text", example="(87) 99814-5678"),
     *       @OA\Property(property="status", type="string", format="text", example="Inativo"),
     *       @OA\Property(property="email", type="string", format="email", example="email1@email.com"),
     *       @OA\Property(property="password", type="string", format="password", example="password"),
     *    ),
     * ),
     *     @OA\Response(
     *         response="201", description="Created",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Usuário Cadastrado com Sucesso!"),
     *             @OA\Property(property="success", type="boolean", example="true"),
     *         )
     *     ),
     *     @OA\Response(
     *         response="500", description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Not authorized"),
     *             @OA\Property(property="success", type="boolean", example="false"),
     *         )
     *     )
     * )
     */

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

    /**
     * @OA\Post(
     * path="/pessoas/{id}",
     * summary="Atualizar Pessoa",
     * description="Atualizacao de pessoa através dos campos: nome, documento, telefone, status.
     O campo user_id pode ser informado opcionalmente para quando uma alteração do status ocorra, dessa forma essa informação será inserida no historico status.
     O campo status deve ser informado como uma das seguintes opções: (Ativo, Inativo, Pendente).",
     * tags={"AtualizarPessoa"},
     *     @OA\Parameter(
     *         description="Id da pessoa",
     *         in="path",
     *         name="id",
     *         required=true,
     *         example="1",
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Dados da pessoa",
     *    @OA\JsonContent(
     *       required={"nome","documento", "telefone", "status"},
     *       @OA\Property(property="nome", type="string", format="text", example="Osmar Osvaldo"),
     *       @OA\Property(property="documento", type="string", format="text", example="105.977.514-45"),
     *       @OA\Property(property="telefone", type="string", format="text", example="(87) 99814-5679"),
     *       @OA\Property(property="status", type="string", format="text", example="Ativo"),
     *       @OA\Property(property="user_id", type="integer", format="number", example="3"),
     *    ),
     * ),
     *     @OA\Response(
     *         response="201", description="Created",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Usuário Cadastrado com Sucesso!"),
     *             @OA\Property(property="success", type="boolean", example="true"),
     *         )
     *     ),
     *     @OA\Response(
     *         response="500", description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Not authorized"),
     *             @OA\Property(property="success", type="boolean", example="false"),
     *         )
     *     )
     * )
     */

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
        return response()->json(Messages::message(true, 'Pessoa atualizada com sucesso!'), 200);

    }

    /**
     * @OA\Get(
     *     tags={"ListarHistoricoStatus"},
     *     summary="Recupera uma lista com todas as alteracoes de status de uma pessoa",
     *     description="Recupera a lista de pessoas a partir de um parametro com o id de uma pessoa e busca todos os historicos referentes a essa pessoa",
     *     path="/pessoas/{id}/historicoStatus",
     *     @OA\Parameter(
     *         description="id de uma pessoa",
     *         in="path",
     *         name="id",
     *         required=true,
     *         example="1",
     *         @OA\Schema(
     *           type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200", description="Success",
     *     ),
     *     @OA\Response(
     *         response="500", description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Not authorized"),
     *             @OA\Property(property="success", type="boolean", example="false"),
     *         )
     * )
     * )
     */

    public function listaHistoricoStatus($id)
    {
        try {
            $listaHistoricoStatus = HistoricoStatus::where('pessoa_id', $id)->get();
        } catch (\Exception $e) {
            return response()->json(Messages::message(false, $e->getMessage()), 500);
        }

        return response()->json(['data' => $listaHistoricoStatus], 200);
    }
}
