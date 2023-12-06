<?php

namespace App\Http\Controllers;

use App\Models\Trademark;
use Illuminate\Http\Request;

class TrademarkController extends Controller
{
    public function edit(Trademark $trademark)
    {
        return view('trademarks.edit', ['trademark' => $trademark]);
    }

}
