<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Inventory\InventoryProcessor;

class InventoryController extends Controller
{
    public function report(Request $request, InventoryProcessor $processor)
    {
        return $processor->process($request);
    }

    public function heartbeat(Request $request)
    {
        //
    }
}