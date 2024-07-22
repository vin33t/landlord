<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($client) {
            $client->uuid = (string) \Illuminate\Support\Str::uuid();
            $client->created_by = auth()->id();
        });
    }

    protected $fillable = [
        'uuid',
        'first_name',
        'last_name',
        'street',
        'city',
        'county',
        'country',
        'postal_code',
        'phone',
        'email',
        'dob',
        'client_type',
        'has_passport',
        'passport_number',
        'place_of_issue',
        'passport_expire_date',
        'passport_issue_date',
        'passport_front',
        'passport_back',
        'permission_letter',
        'is_permanent',
        'credit_limit',
        'currency',
        'created_by',
    ];
}
