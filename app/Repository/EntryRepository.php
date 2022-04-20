<?php

namespace App\Repository;

use App\Http\Resources\EntryResource;
use App\Models\Entry;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class EntryRepository
{
    use ApiResponse;

    /**
     * Retrieve the list of entries
     * @return JsonResource
     */
    public function getAll()
    {
        $entries = Entry::query()->select('id', 'name', 'value', 'updated_at')->get();
        return $this->respondWithResourceCollection(EntryResource::collection($entries));
    }

    /**
     * to create the entry
     * @param  Illuminate\Http\Request;
     * @return JsonResource
     */
    public function create(Request $request)
    {
        $entry = Entry::create($request->all());
        return $this->respondCreated(new EntryResource($entry));
    }

    /**
     * retrieve the entry
     * @param  String name ;
     * @return JsonResource
     */

    public function get($name)
    {
        $entry = Entry::search($name)->latest('updated_at')->first();
        $message = "";
        if (!$entry) {
            $message = "No record found";
        }
        return $this->respondWithResource(new EntryResource($entry), $message = $message);
    }
}
