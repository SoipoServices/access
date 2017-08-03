<?php

namespace Modules\Access\Datatables;

use Modules\Access\Models\Access;
use Utils;
use URL;
use Auth;
use App\Ninja\Datatables\EntityDatatable;

class AccessDatatable extends EntityDatatable
{
    public $entityType = 'access';
    public $sortCol = 1;

    public function columns()
    {
        return [
            [
                'name',
                function ($model) {
                    return $model->name;
                }
            ],[
                'host',
                function ($model) {
                    return $model->host;
                }
            ],[
                'username',
                function ($model) {
                    return $model->username;
                }
            ],[
                'password',
                function ($model) {
                    $user = Auth::user();
                    if($model->user_id == $user->id){
                        return  Access::decrypt($model->password,$user->password);
                    }
                    return $model->password;
                }
            ],[
                'notes',
                function ($model) {
                    return $model->notes;
                }
            ],[
                'client_id',
                function ($model) {
                    return $model->client_id;
                }
            ],
            [
                'created_at',
                function ($model) {
                    return Utils::fromSqlDateTime($model->created_at);
                }
            ],
        ];
    }

    public function actions()
    {
        return [
            [
                mtrans('access', 'edit_access'),
                function ($model) {
                    return URL::to("access/{$model->public_id}/edit");
                },
                function ($model) {
                    return Auth::user()->can('editByOwner', ['access', $model->user_id]);
                }
            ],
        ];
    }

}
