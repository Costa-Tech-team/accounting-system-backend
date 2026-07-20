<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccountRequest;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::whereNull('parent_id')
            ->with([
                'childrenRecursive',
                'accountType',
            ])
            ->get();

        return AccountResource::collection($accounts);
    }

    public function show(Account $account)
    {
        $account->load([
            'childrenRecursive',
            'accountType',
        ]);

        return new AccountResource($account);
    }

    public function store(StoreAccountRequest $request)
    {
        $validatedData = $request->validated();

        $account = Account::create($validatedData);

        return (new AccountResource($account))->additional([
            'meta' => [
                'message' => 'Account created successfully.',
                'status' => 201,
            ],
        ])->response()->setStatusCode(201);
    }

}