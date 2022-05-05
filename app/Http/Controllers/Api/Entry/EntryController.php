<?php

namespace App\Http\Controllers\Api\Entry;

use App\Http\Controllers\Controller;
use App\Http\Requests\EntryRequest;
use App\Http\Resources\EntryResource;
use App\Models\Entry;
use App\Repository\EntryRepository;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Cache;

class EntryController extends Controller
{

    public $entryRepository;

    public function __construct(EntryRepository $entryRepository)
    {
        $this->entryRepository = $entryRepository;
    }

    /**
     * Retrieve the list of entries
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Cache::remember('get-all-records', 5000, function () {
            return $this->entryRepository->getAll();
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EntryRequest $entryRequest)
    {
        return $this->entryRepository->create($entryRequest);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        return $this->entryRepository->get($name);
    }
}
