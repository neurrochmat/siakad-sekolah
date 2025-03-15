<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSchoolAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $schoolId = $request->route('school_id') ?? $request->input('school_id');

        if (!$user || !$schoolId) {
            return response()->json([
                'message' => 'Unauthorized. Data sekolah tidak ditemukan.'
            ], 403);
        }

        // Admin super bisa mengakses semua sekolah
        if ($user->role === 'admin_super') {
            return $next($request);
        }

        // Cek apakah user memiliki akses ke sekolah tersebut
        if ($user->school_id != $schoolId) {
            return response()->json([
                'message' => 'Unauthorized. Anda tidak memiliki akses ke sekolah ini.'
            ], 403);
        }

        return $next($request);
    }
}
