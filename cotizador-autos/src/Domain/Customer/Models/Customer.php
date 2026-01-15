<?php

namespace Src\Domain\Customer\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'type',
        'name',
        'rfc',
        'email',
        'phone',
        'zip_code',
        'state',
        'city',
        'suburb'
    ];
}
