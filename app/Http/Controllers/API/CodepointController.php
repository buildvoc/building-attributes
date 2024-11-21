<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CodepointCollection;
use App\Models\Codepoint;
use Illuminate\Http\Request;

class CodepointController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/v1/codepoint",
     * tags={"Codepoint"},
     * @OA\Response(response=200, description="List of codepoint", @OA\JsonContent()),
     *   @OA\Parameter(
     *      name="postcode",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *          type="string"
     *      ),
     *      example="BA1 0AH",
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
        $postcode = $request->query('postcode');

        $data = Codepoint::query()
        ->when($postcode, function ($query) use ($postcode) {
            $query->where('postcode', 'ILIKE', '%'.$postcode.'%');
        })
        ->paginate(100);

        $data->appends(array('postcode' => $postcode));

        return new CodepointCollection($data);
    }
}
