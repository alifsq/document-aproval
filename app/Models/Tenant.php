<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;
    protected $table = 'tenants';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'name',
        'code',
        'is_active',
    ];

    public function user()
    {
        return $this->hasMany(User::class, 'tenant_id', 'id');
    }

    public function apitoken()
    {
        return $this->hasMany(ApiToken::class, 'tenant_id', 'id');
    }

    public function document()
    {
        return $this->hasMany(Document::class, 'tenant_id', 'id');
    }
}
