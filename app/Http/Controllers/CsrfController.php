<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CsrfController extends Controller
{
    /**
     * Get CSRF token for AJAX requests
     */
    public function getToken()
    {
        return response()->json([
            'csrf_token' => csrf_token(),
            'csrf_field' => csrf_field()
        ]);
    }

    /**
     * Refresh CSRF token
     */
    public function refreshToken(Request $request)
    {
        $request->session()->regenerateToken();
        
        return response()->json([
            'csrf_token' => csrf_token(),
            'csrf_field' => csrf_field()
        ]);
    }
}























