<?php

namespace App\Services;

use App\Helpers\Helpers;
use Illuminate\Support\Facades\Cache;

class CepService
{
  protected $helpers;

  public function __construct(Helpers $helpers)
  {
    $this->helpers = $helpers;
  }

  /**
   * verifica se ha cep no cache, caso nao, cria no banco e retorna os dados de endereço, caso exista apenas retorna
   * @param string  $cep
   * @return array
   */
  public function redisCep(String $cep)
  {
    $cep = preg_replace('/\D/', '', $cep);

    $cacheKey = "cache_$cep";
    $cacheData = Cache::get($cacheKey);

    if ($cacheData) {
      return $cacheData;
    }

    $cepData = $this->helpers->getAddress($cep);

    Cache::put($cacheKey, $cepData, now()->addHour());

    return $cepData;
  }
}
