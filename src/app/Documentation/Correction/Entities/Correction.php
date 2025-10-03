<?php

namespace App\Documentation\Correction\Entities;

use App\User\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Correction extends Model
{
    protected $fillable = [
        "release_name",
        "edit_date",
        "user_crm_id",
        "user_name",
        "is_approved",
        "is_merged",
        "is_archived",
        "page_url",
        "page_xpath",
        "html_eng",
        "html_rus_old",
        "html_rus_new",
        "created_at",
        "updated_at",
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_crm_id", "id");
    }
}


