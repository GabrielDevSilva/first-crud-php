<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
  use HasFactory;

  protected $fillable = [
    'cep',
    'address',
    'number',
    'complement',
    'province',
    'city',
    'state'
  ];

  public function user()
  {
    return $this->hasMany(User::class);
  }
}
