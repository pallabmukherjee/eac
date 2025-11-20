<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SigningAuthority extends Model
{
    use HasFactory;

    protected $table = 'signing_authorities';

    protected $fillable = [
        'name',
        'designation',
    ];
}
