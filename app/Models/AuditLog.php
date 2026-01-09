<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'audit_logs';
    protected $primaryKey = 'id';
    protected $keyType = 'int';

    public $fillable = [
        'tenant_id',
        'user_id',
        'action',
        'subject_type',
        'subject_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    public function casts()
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
        ];
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id','users');
    }

    public function tenant(){
        return $this->belongsTo(Tenant::class,'tenant_id','id','tenants');
    }
}
