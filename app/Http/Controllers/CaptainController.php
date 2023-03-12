<?php

namespace App\Http\Controllers;

use App\Models\Captain;
use Illuminate\Http\Request;

class CaptainController extends Controller
{
    public function getCaptain(Captain $captain)
    {
        $captain = $captain->scopeSelection()->get();
        return response()->json($captain);
    }
}
