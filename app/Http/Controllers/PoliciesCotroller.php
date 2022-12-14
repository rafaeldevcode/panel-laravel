<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class PoliciesCotroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function indexPrivacy()
    {
        $settings = Setting::first();

        return view('policies/privacy/index', compact(
            'settings'
        ));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function indexTerms()
    {
        $settings = Setting::first();

        return view('policies/terms/index', compact(
            'settings'
        ));
    }
}
