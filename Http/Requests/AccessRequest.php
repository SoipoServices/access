<?php

namespace Modules\Access\Http\Requests;

use App\Http\Requests\EntityRequest;
use App\Libraries\HistoryUtils;

class AccessRequest extends EntityRequest
{
    protected $entityType = 'access';

    public function authorize()
    {
        if ($this->entity()) {
            if ($this->user()->can('view', $this->entity()) &&  $this->entity()->user->id == $this->user()->id ) {
                HistoryUtils::trackViewed($this->entity());

                return true;
            }
        } else {
            return $this->user()->can('create', $this->entityType);
        }
    }

}
