<?php

namespace App\Http\Controllers;

use App\Models\AccountType;

class AccountTypesController extends Controller
{

    /**
     * List all accounts types.
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
        $accountTypes = AccountType::all();

        return response()->json($accountTypes);
    }
}
