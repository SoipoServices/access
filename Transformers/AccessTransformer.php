<?php

namespace Modules\Access\Transformers;

use Modules\Access\Models\Access;
use App\Ninja\Transformers\EntityTransformer;
use Auth;

/**
 * @SWG\Definition(definition="Access", @SWG\Xml(name="Access"))
 */

class AccessTransformer extends EntityTransformer
{
    /**
    * @SWG\Property(property="id", type="integer", example=1, readOnly=true)
    * @SWG\Property(property="user_id", type="integer", example=1)
    * @SWG\Property(property="account_key", type="string", example="123456")
    * @SWG\Property(property="updated_at", type="integer", example=1451160233, readOnly=true)
    * @SWG\Property(property="archived_at", type="integer", example=1451160233, readOnly=true)
    */

    /**
     * @param Access $access
     * @return array
     */
    public function transform(Access $access)
    {
        $user = Auth::user();
        if($access->user->id == $user->id){
            $access->password = Access::decrypt($access->password,$user->password);
        }

        return array_merge($this->getDefaults($access), [
            'name' => $access->name,
            'host' => $access->host,
            'username' => $access->username,
            'password' => $access->password,
            'notes' => $access->notes,
            'client_id' => $access->client_id,
            'id' => (int) $access->public_id,
            'updated_at' => $this->getTimestamp($access->updated_at),
            'archived_at' => $this->getTimestamp($access->deleted_at),
        ]);
    }
}
