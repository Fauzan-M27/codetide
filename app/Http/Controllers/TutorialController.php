<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TutorialController extends Controller
{
    /**
     * Mark tutorial as completed for the authenticated user
     */
    public function complete(Request $request): JsonResponse
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $user->tutorial_completed = true;
        $user->save();

        // Log activity
        $user->logActivity('tutorial_completed');

        return response()->json([
            'success' => true,
            'message' => 'Tutorial completed successfully'
        ]);
    }

    /**
     * Reset tutorial for the authenticated user
     */
    public function reset(Request $request): JsonResponse
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $user->tutorial_completed = false;
        $user->save();

        // Set session flag agar tutorial muncul kembali di dashboard berikutnya
        $request->session()->put('show_tutorial', true);

        return response()->json([
            'success' => true,
            'message' => 'Tutorial reset successfully'
        ]);
    }

    /**
     * Get tutorial status
     */
    public function status(Request $request): JsonResponse
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'completed' => $user->tutorial_completed
        ]);
    }
}
