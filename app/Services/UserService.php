<?php

namespace App\Services;

use App\Helpers\Helpers;
use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserService
{
  protected $helpers;

  public function __construct(Helpers $helpers)
  {
    $this->helpers = $helpers;
  }

  public function createUser($request)
  {
    $cepService = new CepService($this->helpers);

    $cep = preg_replace('/\D/', '', $request->input('cep'));
    $cpf = $request->input('cpf');
    $cns = $request->input('cns');
    $validateCpf = $this->helpers->validateCpf($cpf);
    $validateCns = $this->helpers->validateCns($cns);
    $dataAddress = $cepService->redisCep($cep);

    if (!$validateCpf) {
      throw ValidationException::withMessages([
        'cpf' => 'CPF inválido.',
      ])->status(422);
    }

    if (!$validateCns) {
      throw ValidationException::withMessages([
        'cns' => 'CNS inválido.',
      ])->status(422);
    }

    if (!$dataAddress) {
      throw ValidationException::withMessages([
        'cep' => 'CEP inválido.',
      ])->status(422);
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

    return  $user;
  }

  public function getAllUser()
  {
    $users = User::all();

    if ($users->isEmpty()) {
      throw ValidationException::withMessages([
        'users' => 'Nenhum paciente cadastrado.'
      ])->status(404);
    }

    return  response()->json($users);
  }

  public function getOneUser($id)
  {
    $user = User::find($id);

    if (!$user) {
      throw ValidationException::withMessages([
        'users' => "Paciente de id: $id, não encontrado."
      ])->status(404);
    }

    return  response()->json($user);
  }

  public function updateUser(Request $request, $id)
  {
    $user = User::find($id);

    if (!$user) {
      throw ValidationException::withMessages([
        'users' => "Paciente de id: $id, não encontrado."
      ])->status(404);
    }

    $user->update($request->all());

    $response = [
      "message" => "paciente atualizado com sucesso",
      "data" => $user
    ];

    return  response()->json($response);
  }

  public function deleteUser($id)
  {
    $user = User::find($id);

    if (!$user) {
      throw ValidationException::withMessages([
        'users' => "Paciente de id: $id, não encontrado."
      ])->status(404);
    }

    $user->delete();

    $response = [
      "message" => "paciente deletado com sucesso",
      "data" => $user
    ];
    return  response()->json($response);
  }
}
