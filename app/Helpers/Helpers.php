<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class Helpers
{
  /**
   * Busca na api do via feira os dados de enderenço através do cep
   * @param string  $cep
   * @return array
   */
  public function getAddress(String $cep)
  {
    $cep = preg_replace('/\D/', '', $cep);

    if (strlen($cep) != 8) {
      return false;
    }

    $url = "https://viacep.com.br/ws/{$cep}/json";

    $response = Http::get($url);

    if ($response->successful()) {
      return $response->json();
    };

    return false;
  }

  /**
   * Verifica se o cpf é valido
   * @param string  $cpf
   * @return boolean
   */
  public function validateCpf(String $cpf)
  {
    $cpf = preg_replace('/\D/', '', $cpf);

    if (strlen($cpf) != 11) {
      return false;
    }

    $validateCpf = substr($cpf, 0, 9);
    $validateCpf .= self::validateData($validateCpf);
    $validateCpf .= self::validateData($validateCpf);

    return $validateCpf == $cpf;
  }

  /**
   * Verifica se o cns é valido
   * @param string  $cns
   * @return boolean
   */
  public function validateCns(String $cns)
  {
    $cns = preg_replace('/\D/', '', $cns);

    if (strlen($cns) != 15) {
      return false;
    }

    $validateCns = substr($cns, 0, 14);
    $validateCns .= self::validateData($validateCns);

    return $validateCns == $cns;
  }

  /**
   * Faz a verificação do digito verificador
   * @param string  $validate
   * @return string
   */
  public static function validateData(String $validate)
  {
    $length = strlen($validate);

    $count = $length + 1;

    $accumulator = 0;

    for ($i = 0; $i < $length; $i++) {
      $accumulator += $validate[$i] * $count;
      $count--;
    }

    $rest = $accumulator % 11;

    return $rest >= 1 ? 11 - $rest : 0;
  }
}
