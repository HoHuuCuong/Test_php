<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shoe extends Model
{
    protected $fillable = ['image', 'name', 'description', 'price', 'color'];

    use HasFactory;
}
