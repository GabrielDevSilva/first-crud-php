<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class User extends Model
{
  use  HasFactory;

  protected $fillable = [
    'name',
    'motherName',
    'birthDate',
    'cpf',
    'cns',
    'imageUrl',
    'address_id'
  ];

  public function address()
  {
    return $this->belongsTo(Address::class);
  }
}
