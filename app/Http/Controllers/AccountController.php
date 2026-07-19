<?php

namespace App\Http\Controllers;

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
}
