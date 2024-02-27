<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
  use AuthorizesRequests, ValidatesRequests;

  protected $userService;

  public function __construct(UserService $userService)
  {
    $this->userService = $userService;
  }

  public function createUser(Request $request)
  {
    try {
      $data = $this->userService->createUser($request);
      return response()->json($data);
    } catch (\Throwable $exception) {
      if ($exception->getCode() == "23000") {
        return response()->json(['error' => 'CPF já cadastrado'], 422);
      };

      $errors = $exception->getMessage();
      $code = $exception->status;

      return response()->json(['error' => $errors], $code !== null ? $code : 422);
    }
  }

  public function getAllUser()
  {
    try {
      $users = $this->userService->getAllUser();
      return  response()->json($users);
    } catch (\Throwable $exception) {
      $errors = $exception->getMessage();

      $code = $exception->status;

      return response()->json(['error' => $errors], $code !== null ? $code : 422);
    }
  }

  public function getOneUser($id)
  {
    try {
      $user = $this->userService->getOneUser($id);

      return  response()->json($user);
    } catch (\Throwable $exception) {
      $errors = $exception->getMessage();

      $code = $exception->status;

      return response()->json(['error' => $errors], $code !== null ? $code : 422);
    }
  }

  public function updateUser(Request $request, $id)
  {
    try {
      $user = $this->userService->updateUser($request, $id);

      return  response()->json($user);
    } catch (\Throwable $exception) {
      $errors = $exception->getMessage();

      $code = $exception->status;

      return response()->json(['error' => $errors], $code !== null ? $code : 422);
    }
  }

  public function deleteUser($id)
  {
    try {
      $user = $this->userService->deleteUser($id);

      return  response()->json($user);
    } catch (\Throwable $exception) {
      $errors = $exception->getMessage();

      $code = $exception->status;

      return response()->json(['error' => $errors], $code !== null ? $code : 422);
    }
  }

  public function uploadUsers(Request $request)
  {
    try {
      $this->userService->uploadUsers($request);

      return response()->json(['Sucesso:' => 'Upload realizado com sucesso, dados salvos!!']);
    } catch (\Throwable $th) {
      return response()->json($th);
    }
  }
}
