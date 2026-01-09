<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'tenant_id',
        'created_at',
        'title',
        'content',
        'status',
        'submitted_at',
        'approved_at',
        'rejected_at',
    ];

    public function casts()
    {
        return [
            'submitted_at' => 'timestamp',
            'approved_at' => 'timestamp',
            'rejected_at' => 'timestamp',
        ];
    }

    public function tenant(){
        return $this->belongsTo(Tenant::class,'tenant_id','id','tenants');
    }

    public function user(){
        return $this->belongsTo(User::class,'created_by','id','users');
    }

    public function document_approval(){
        return $this->hasMany(DocumentAproval::class,'document_id','id');
    }
}
