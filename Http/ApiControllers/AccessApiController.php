<?php

namespace Modules\Access\Http\ApiControllers;

use App\Http\Controllers\BaseAPIController;
use Modules\Access\Repositories\AccessRepository;
use Modules\Access\Http\Requests\AccessRequest;
use Modules\Access\Http\Requests\CreateAccessRequest;
use Modules\Access\Http\Requests\UpdateAccessRequest;

class AccessApiController extends BaseAPIController
{
    protected $AccessRepo;
    protected $entityType = 'access';

    public function __construct(AccessRepository $accessRepo)
    {
        parent::__construct();

        $this->accessRepo = $accessRepo;
    }

    /**
     * @SWG\Get(
     *   path="/access",
     *   summary="List access",
     *   operationId="listAccesss",
     *   tags={"access"},
     *   @SWG\Response(
     *     response=200,
     *     description="A list of access",
     *      @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/Access"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function index()
    {
        $data = $this->accessRepo->all();

        return $this->listResponse($data);
    }

    /**
     * @SWG\Get(
     *   path="/access/{access_id}",
     *   summary="Individual Access",
     *   operationId="getAccess",
     *   tags={"access"},
     *   @SWG\Parameter(
     *     in="path",
     *     name="access_id",
     *     type="integer",
     *     required=true
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="A single access",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Access"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function show(AccessRequest $request)
    {
        return $this->itemResponse($request->entity());
    }




    /**
     * @SWG\Post(
     *   path="/access",
     *   summary="Create a access",
     *   operationId="createAccess",
     *   tags={"access"},
     *   @SWG\Parameter(
     *     in="body",
     *     name="access",
     *     @SWG\Schema(ref="#/definitions/Access")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="New access",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Access"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function store(CreateAccessRequest $request)
    {
        $access = $this->accessRepo->save($request->input());

        return $this->itemResponse($access);
    }

    /**
     * @SWG\Put(
     *   path="/access/{access_id}",
     *   summary="Update a access",
     *   operationId="updateAccess",
     *   tags={"access"},
     *   @SWG\Parameter(
     *     in="path",
     *     name="access_id",
     *     type="integer",
     *     required=true
     *   ),
     *   @SWG\Parameter(
     *     in="body",
     *     name="access",
     *     @SWG\Schema(ref="#/definitions/Access")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Updated access",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Access"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function update(UpdateAccessRequest $request, $publicId)
    {
        if ($request->action) {
            return $this->handleAction($request);
        }

        $access = $this->accessRepo->save($request->input(), $request->entity());

        return $this->itemResponse($access);
    }


    /**
     * @SWG\Delete(
     *   path="/access/{access_id}",
     *   summary="Delete a access",
     *   operationId="deleteAccess",
     *   tags={"access"},
     *   @SWG\Parameter(
     *     in="path",
     *     name="access_id",
     *     type="integer",
     *     required=true
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Deleted access",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Access"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function destroy(UpdateAccessRequest $request)
    {
        $access = $request->entity();

        $this->accessRepo->delete($access);

        return $this->itemResponse($access);
    }

}
