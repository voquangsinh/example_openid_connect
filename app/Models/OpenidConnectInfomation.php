<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenidConnectInfomation extends Model
{
    use HasFactory;

    protected $table = 'openid_connect_infomation';

    protected $guarded = [];

    /**
     * Get the user that owns the OpenidConnectInfomation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
