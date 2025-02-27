<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $query = Produto::query();

        if ($request->has('search')) {
            $query->where('nome', 'like', '%' . $request->search . '%');
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric',

        ]);

        $produto = Produto::create($validatedData);
        return response()->json($produto, 201);
    }


    public function show(Produto $produto)
    {
        return $produto;
    }

    public function update(Request $request, Produto $produto)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric',
        ]);

        $produto->update($validatedData); // Atualiza o produto existente

        return response()->json($produto, 200);
    }

    public function destroy($id)
    {
        try {
            $produto = Produto::find($id);

            if (!$produto) {
                return response()->json(['message' => 'Produto não encontrado'], 404);
            }

            $produto->delete();

            return response()->json(['message' => 'Produto excluído com sucesso'], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao excluir produto: ' . $e->getMessage());

            return response()->json([
                'message' => 'Erro ao excluir produto',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function buscar(Request $request) {}
}
