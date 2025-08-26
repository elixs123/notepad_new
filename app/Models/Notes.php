<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{
    protected $table = 'notes';
    
    protected $fillable = [
        'url', 'share_url', 'raw_url', 'markdown_url', 'code_url', 'content'
    ];
}
