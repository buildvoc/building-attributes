<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UprnCollection;
use App\Models\Uprn;
use Illuminate\Http\Request;

class UprnController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/v1/uprn",
     * tags={"UPRN"},
     * @OA\Response(response=200, description="List of UPRN address", @OA\JsonContent()),
     *   @OA\Parameter(
     *      name="uprn",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *          type="string"
     *      ),
     *      example="1",
     *   ),
     *   @OA\Parameter(
     *      name="page",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     * )
     */
    public function index(Request $request)
    {
        $uprn = $request->query('uprn');

        $data = Uprn::query()
        ->when($uprn, function ($query) use ($uprn) {
            $query->where('uprn', $uprn);
        })
        ->paginate(100);

        $data->appends(array('uprn' => $uprn));

        return new UprnCollection($data);
    }
}
