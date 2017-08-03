<?php

namespace Modules\Access\Repositories;

use DB;
use Modules\Access\Models\Access;
use App\Ninja\Repositories\BaseRepository;
//use App\Events\AccessWasCreated;
//use App\Events\AccessWasUpdated;

class AccessRepository extends BaseRepository
{
    public function getClassName()
    {
        return 'Modules\Access\Models\Access';
    }

    public function all()
    {
        return Access::scope()
                ->orderBy('created_at', 'desc')
                ->withTrashed();
    }

    public function find($filter = null, $userId = false)
    {
        $query = DB::table('access')
                    ->where('access.account_id', '=', \Auth::user()->account_id)
                    ->select(
                        'access.name', 'access.host', 'access.username', 'access.password', 'access.notes', 'access.client_id',
                        'access.public_id',
                        'access.deleted_at',
                        'access.created_at',
                        'access.is_deleted',
                        'access.user_id'
                    );

        $this->applyFilters($query, 'access');

        if ($userId) {
            $query->where('clients.user_id', '=', $userId);
        }

        /*
        if ($filter) {
            $query->where();
        }
        */

        return $query;
    }

    public function save($data, $access = null)
    {
        $entity = $access ?: Access::createNew();

        $entity->fill($data);
        $entity->save();

        /*
        if (!$publicId || $publicId == '-1') {
            event(new ClientWasCreated($client));
        } else {
            event(new ClientWasUpdated($client));
        }
        */

        return $entity;
    }

}
