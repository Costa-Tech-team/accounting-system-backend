<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccountRequest;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * List all accounts.
     * 
     * @group Accounts
     * 
     * @authenticated
     * 
     * List all root accounts with their hierarchical children.
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
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

    /**
     * Show account
     * 
     * @group Accounts
     * 
     * @authenticated
     * 
     * Show a single account with its hierarchical children.
     *
     * @param  \App\Models\Account  $account
     * @return \App\Http\Resources\AccountResource
     */
    public function show(Account $account)
    {
        $account->load([
            'childrenRecursive',
            'accountType',
        ]);

        return new AccountResource($account);
    }

    /**
     * Create a new account.
     * 
     * @group Accounts
     * @authenticated
     * 
     * Creates a new account in the chart of accounts. 
     * @param  \App\Http\Requests\StoreAccountRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
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