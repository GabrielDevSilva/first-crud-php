<?php

namespace App\Http\Controllers;

use App\Services\CepService;

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

      return response()->json(['error' => $errors], 422);
    }
  }
}
