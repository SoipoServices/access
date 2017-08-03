<?php

namespace Modules\Access\Http\Requests;

use App\Http\Requests\EntityRequest;

class AccessRequest extends EntityRequest
{
    protected $entityType = 'access';
}
