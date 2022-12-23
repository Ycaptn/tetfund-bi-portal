<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hasob\FoundationCore\Traits\GuidId;
use Illuminate\Database\Eloquent\SoftDeletes;

class NominationSetting extends Model
{
    use GuidId;
    
    use SoftDeletes;
    use HasFactory;
}
