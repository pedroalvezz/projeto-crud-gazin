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

        if ($request->has('min_price')) {
            $query->where('preco', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('preco', '<=', $request->max_price);
        }
        return response()->json($query->get());
        return Produto::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric',
            'quantidade' => 'nullable|integer|min:1',



        ]);

        $produto = Produto::create($validatedData);
        return response()->json($produto, 201);

        $produto = Produto::where('nome', $request->nome)->first();

        if ($produto) {
            $produto->increment('quantidade', $request->quantidade ?? 1);
        } else {
            Produto::create([
                'nome' => $request->nome,
                'preco' => $request->preco,
                'descricao' => $request->descricao,
                'quantidade' => $request->quantidade ?? 1,

            ]);
        }

        return response()->json(['message' => 'Produto adicionado com sucesso!'], 201);
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


    public function updateQuantidade(Request $request, $id)
    {
        $request->validate([
            'quantidade' => 'required|integer|min:1'
        ]);

        $produto = Produto::findOrFail($id);
        $produto->increment('quantidade', $request->quantidade);
        $produto->save();

        return response()->json(['message' => 'Quantidade atualizada com sucesso!', 'produto' => $produto], 200);
    }

    public function removeQuantity(Request $request, $id)
    {
        $produto = Produto::findOrFail($id);

        if ($produto->quantidade > 0) {
            $produto->quantidade -= 1;
            $produto->preco_total = $produto->quantidade * $produto->preco_unitario;
            $produto->save();

            return response()->json([
                'message' => 'Quantidade removida com sucesso',
                'produto' => $produto
            ]);
        }

        return response()->json(['error' => 'A quantidade já é zero'], 400);
    }
}
