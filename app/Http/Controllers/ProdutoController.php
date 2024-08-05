<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Exception;

class ProdutoController extends Controller implements \Illuminate\Routing\Controllers\HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(middleware: 'auth:api'),
        ];
    }
    public function index(): JsonResponse
    {
        try{
            $produtos = Produto::all();

            return response()->json([
                'status' => 'success',
                'data' => $produtos
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'mensagem' => 'Erro ao listar os produtos',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function create()
    {
        //
    }

    public function store(Request $request): JsonResponse
    {
        try{
            $request->validate([
                'nome' => 'required|string',
                'descricao' => 'required|string',
            ]);

            Produto::create([
                'nome' => $request->input('nome'),
                'descricao' => $request->input('descricao'),
            ]);

            return response()->json([
                'status' => 'success',
                'mensagem' => 'Produto cadastrado com sucesso',
            ]);
            
        }
        catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'mensagem' => 'Erro ao criar o produto',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function show(string $produtoBuscado): JsonResponse
    {
        try{
            $produto = Produto::where(
                'nome', 'like', '%'.$produtoBuscado.'%'
            )->get();

            return response()->json([
                'status' => 'success',
                'produto' => $produto,
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'mensagem' => 'Erro ao buscar o produto',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function edit(string $id): JsonResponse
    {
        try{
            $produto = Produto::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'mensagem' => 'Produto encontrado',
                'data' => $produto,
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'mensagem' => 'Erro ao editar o produto',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request,  string $id): JsonResponse
    {
        try{
            Produto::findOrFail($id)->update([
                'nome' => $request->input('nome'),
                'descricao' => $request->input('descricao'),
            ]);

            return response()->json([
                'status' => 'success',
                'mensagem' => 'Produto atualizado com sucesso',
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'mensagem' => 'Erro ao editar o produto',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try{
            $produto = Produto::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $produto,
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'mensagem' => 'Erro ao deletar o produto',
                'error' => $e->getMessage()
            ]);
        }   
    }

    public function delete(Request $request): JsonResponse
    {
        try{
            Produto::findOrFail($request->input('id'))->delete();

            return response()->json([
                'status' => 'success',
                'mensagem' => 'Produto deletado com sucesso',
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'mensagem' => 'Erro ao deletar o produto',
                'error' => $e->getMessage()
            ]);
        }
    }
}
