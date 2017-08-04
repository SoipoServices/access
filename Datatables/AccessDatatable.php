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
                    if($this->isValidURL($model->host)){
                        return link_to($model->host,null,['target'=>'_blank'])->toHtml();
                    }
                    return $model->host;
                }
            ],[
                'username',
                function ($model) {
                    $user = Auth::user();
                    if($model->user_id == $user->id){
                        return  Access::decrypt($model->username,$user->password);
                    }
                    return "****";
                }
            ],[
                'password',
                function ($model) {
                    $user = Auth::user();
                    if($model->user_id == $user->id){
                        return  Access::decrypt($model->password,$user->password);
                    }
                    return "****";
                }
            ],[
                'client',
                function ($model) {
                    if (! Auth::user()->can('viewByOwner', [ENTITY_CLIENT, $model->client_id])) {
                        return Utils::getClientDisplayName($model);
                    }

                    return link_to("clients/{$model->client_public_id}", Utils::getClientDisplayName($model))->toHtml();
                },
                ! $this->hideClient,
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

    protected function isValidURL($url)
    {
        return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
    }
}
