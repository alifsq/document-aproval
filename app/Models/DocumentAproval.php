<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentAproval extends Model
{
    protected $table = 'document_aprovals';
    protected $primaryKey = 'id';
    protected $keyType = 'int';

    protected $fillable =[
        'document_id',
        'approved_by',
        'decission',
        'comment',
    ];

    public function user(){
        return $this->belongsTo(User::class,'approved_by','id','users');
    }

    public function document(){
        return $this->belongsTo(Document::class,'document_id','id','documents');
    }
}
