<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Fornecedor;

class FornecedorController extends Controller
{
    //Index descontinuada em prol do curso.
    // public function index(){
    //     $fornecedores = [ 
    //         0 => ['nome' => 'Fornecedor 1', 'status' => 'N', 'cnpj' => '123', 'ddd' => '11', 'telefone' => '0000-0000'],
    //         1 => ['nome' => 'Fornecedor 2', 'status' => 'S', 'cnpj' => null, 'ddd' => '85', 'telefone' => '0000-0000'],
    //         2 => ['nome' => 'Fornecedor 3', 'status' => 'S', 'cnpj' => null, 'ddd' => '32', 'telefone' => '0000-0000']
    //     ];

    //     // $fornecedores = [];

    //     return view('app.fornecedor.index', compact('fornecedores'));
    // }

    public function index()
    {
        return view('app.fornecedor.index'); 
    }

    public function listar(Request $request)
    {
        $fornecedores = Fornecedor::where('nome', 'like', '%'.$request->input('nome').'%')
        ->where('site', 'like', '%'.$request->input('site').'%')
        ->where('uf', 'like', '%'.$request->input('uf').'%')
        ->where('email', 'like', '%'.$request->input('email').'%')
        ->paginate(10);

        return view('app.fornecedor.listar', ['fornecedores' => $fornecedores, 'request' => $request->all()]);
    }

    public function adicionar(Request $request)
    {
        $msg = '';

        if ($request->input('_token') != '' && $request->input('id') == '') {
            $regras = [
                'nome' => 'required|min:3|max:40',
                'site' => 'required', 
                'uf' =>  'required|min:2|max:2',
                'email' =>  'email'
            ];

            $feedback = [
                'required' => 'O campo :attribute deve ser preenchido!',
                'nome.min' => 'O campo nome deve ter no minimo 3 caracteres',
                'nome.max' => 'O campo nome deve ter no maximo 40 caracteres',
                'uf.min' => 'O campo uf deve ter no minimo 2 caracteres',
                'uf.max' => 'O campo uf deve ter no maximo 2 caracteres',
                'email.email' => 'O campo e-mail não foi preenchido corretamente',
            ];

            $request->validate($regras, $feedback);

            $fornecedor = new Fornecedor();
            $fornecedor->create($request->all());

            $msg = 'Cadastro realizado com sucesso!';
        }

        if ($request->input('_token') != '' && $request->input('id') != '') {
            $fornecedor = Fornecedor::find($request->input('id'));
            $update = $fornecedor->update($request->all());

            if ($update) {
                $msg =  'Atualização realizada com sucesso!';
            } else {
                $msg = 'Erro ao tentar atualizar o registro!';
            }

            return redirect()->route('app.fornecedor.adicionar', ['id' => $request->input('id'),'msg' => $msg]);
        }

        return view('app.fornecedor.adicionar', ['msg' => $msg]);
    }

    public function editar($id, $msg = '') 
    {
        $fornecedor = Fornecedor::find($id);

        return view('app.fornecedor.adicionar', ['fornecedor' => $fornecedor, 'msg' => $msg]);
    }
}
