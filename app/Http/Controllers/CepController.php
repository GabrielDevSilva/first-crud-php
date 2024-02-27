<?php

namespace App\Http\Controllers;

use App\Services\CepService;
use Dotenv\Exception\ValidationException;

class CepController
{
  protected $cepService;

  public function __construct(CepService $cepService)
  {
    $this->cepService = $cepService;
  }

  public function getAddress(String $cep)
  {
    try {
      $cepData = $this->cepService->redisCep($cep);

      return response()->json($cepData);
    } catch (\Throwable $exception) {
      $errors = $exception->getMessage();

      $code = $exception->status;

      return response()->json(['error' => $errors], $code !== null ? $code : 422);
    }
  }
}
