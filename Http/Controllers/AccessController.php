<?php

namespace Modules\Access\Http\Controllers;

use Auth;
use App\Http\Controllers\BaseController;
use App\Services\DatatableService;
use Modules\Access\Datatables\AccessDatatable;
use Modules\Access\Models\Access;
use Modules\Access\Repositories\AccessRepository;
use Modules\Access\Http\Requests\AccessRequest;
use Modules\Access\Http\Requests\CreateAccessRequest;
use Modules\Access\Http\Requests\UpdateAccessRequest;
use Input;
use App\Models\Client;

class AccessController extends BaseController
{
    protected $AccessRepo;
    //protected $entityType = 'access';

    public function __construct(AccessRepository $accessRepo)
    {
        //parent::__construct();

        $this->accessRepo = $accessRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('list_wrapper', [
            'entityType' => 'access',
            'datatable' => new AccessDatatable(),
            'title' => mtrans('access', 'access_list'),
        ]);
    }

    public function datatable(DatatableService $datatableService)
    {
        $search = request()->input('sSearch');
        $userId = Auth::user()->filterId();

        $datatable = new AccessDatatable();
        $query = $this->accessRepo->find($search, $userId);

        return $datatableService->createDatatable($datatable, $query);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(AccessRequest $request)
    {
        $data = [
            'access' => null,
            'method' => 'POST',
            'url' => 'access',
            'title' => mtrans('access', 'new_access'),
            'clientPublicId' => Input::old('client') ? Input::old('client') : ($request->client_id ?: 0),
            'clients' => Client::scope()->with('contacts')->orderBy('name')->get(),
        ];

        return view('access::edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(CreateAccessRequest $request)
    {
        $data =  $request->input();
        if(array_key_exists('username',$data)){
            $userPassword = Auth::user()->password;
            $data['username'] = Access::encrypt($data['username'],$userPassword);
        }
        if(array_key_exists('password',$data)){
            $userPassword = Auth::user()->password;
            $data['password'] = Access::encrypt($data['password'],$userPassword);
        }
        $access = $this->accessRepo->save($data);

        return redirect()->to($access->present()->editUrl)
            ->with('message', mtrans('access', 'created_access'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(AccessRequest $request)
    {
        $access = $request->entity();

        $user = Auth::user();
        $userId = $user->filterId();

        $data = [
            'access' => $access,
            'method' => 'PUT',
            'url' => 'access/' . $access->public_id,
            'title' => mtrans('access', 'edit_access'),
            'client' => $access->client,
            'clientPublicId' => $access->client->public_id,
            'clients' => null
        ];

        if($access->user->id == $user->id){
            $data['clearData'] = true;
            $access->username = Access::decrypt($access->username,$user->username);
            $access->password = Access::decrypt($access->password,$user->password);
            $data['access'] = $access;
        }

        return view('access::edit', $data);
    }

    /**
     * Show the form for editing a resource.
     * @return Response
     */
    public function show(AccessRequest $request)
    {
        return redirect()->to("access/{$request->access}/edit");
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(UpdateAccessRequest $request)
    {
        $data =  $request->input();
        if(array_key_exists('username',$data)){
            $userPassword = Auth::user()->password;
            $data['username'] = Access::encrypt($data['username'],$userPassword);
        }
        if(array_key_exists('password',$data)){
            $userPassword = Auth::user()->password;
            $data['password'] = Access::encrypt($data['password'],$userPassword);
        }
        $access = $this->accessRepo->save($data, $request->entity());

        return redirect()->to($access->present()->editUrl)
            ->with('message', mtrans('access', 'updated_access'));
    }

    /**
     * Update multiple resources
     */
    public function bulk()
    {
        $action = request()->input('action');
        $ids = request()->input('public_id') ?: request()->input('ids');
        $count = $this->accessRepo->bulk($ids, $action);

        return redirect()->to('access')
            ->with('message', mtrans('access', $action . '_access_complete'));
    }

}
