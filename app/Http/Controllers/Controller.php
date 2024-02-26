<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Validation\ValidationException;

class Controller extends BaseController
{
  use AuthorizesRequests, ValidatesRequests;

  protected $helpers;

  public function __construct(Helpers $helpers)
  {
    $this->helpers = $helpers;
  }

  public function createUser(Request $request)
  {

    $cepController = new CepController($this->helpers);

    $cep = preg_replace('/\D/', '', $request->input('cep'));
    $cpf = $request->input('cpf');
    $cns = $request->input('cns');

    $validateCpf = $this->helpers->validateCpf($cpf);
    $validateCns = $this->helpers->validateCns($cns);
    $dataAddress = $cepController->redisCep($cep);

    if (!$validateCpf) {
      throw ValidationException::withMessages([
        'cpf' => 'CPF inválido.',
      ]);
    }

    if (!$validateCns) {
      throw ValidationException::withMessages([
        'cns' => 'CNS inválido.',
      ]);
    }


    if (!$dataAddress) {
      throw ValidationException::withMessages([
        'cep' => 'CEP inválido.',
      ]);
    }

    $address = Address::where('cep', $cep)->first();

    if (!$address) {
      $address = Address::create([
        'cep' => $cep,
        'address' => $dataAddress['logradouro'],
        'number' => $request->all()['number'],
        'complement' => $request->all()['complement'],
        'province' => $dataAddress['bairro'],
        'city' => $dataAddress['localidade'],
        'state' => $dataAddress['uf']
      ]);
    }


    $user = User::create([
      'name' =>  $request->all()['name'],
      'motherName' => $request->all()['motherName'],
      'birthDate' => $request->all()['birthDate'],
      'cpf' => $request->all()['cpf'],
      'cns' => $request->all()['cns'],
      'imageUrl' => $request->all()['imageUrl'],
      'address_id' => $address->id
    ]);


    return  response()->json($user);
  }

  public function getAllUser()
  {
    $users = User::all();
    return  response()->json($users);
  }

  public function getOneUser($id)
  {
    $user = User::find($id);
    $res = [
      "status" => 200,
      "data" => $user
    ];
    return  response()->json($res);
  }


  public function updateUser(Request $request, $id)
  {
    $user = User::find($id);
    $user->update($request->all());

    return  response()->json($user);
  }

  public function deleteUser($id)
  {
    $user = User::find($id);
    $user->delete();

    $res = [
      "status" => 200,
      "message" => "paciente deletado com sucesso",
      "data" => $user
    ];
    return  response()->json($res);
  }
}
