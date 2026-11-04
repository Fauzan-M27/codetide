<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Display module list (materi page)
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Get overall stats
        $stats = [
            'total_xp' => $user->getTotalXP(),
            'completed_questions' => $user->getCompletedQuestionsCount(),
            'total_questions' => $user->getTotalQuestionsCount(),
            'completion_percentage' => $user->getCompletionPercentage(),
        ];

        $modules = Module::with(['materials.questions'])
            ->orderBy('order')
            ->get()
            ->map(function ($module) use ($user) {
                $materials = $module->materials->map(function ($material) use ($user) {
                    $totalQuestions = $material->questions()->count();
                    $completedQuestions = \App\Models\UserProgress::where('user_id', $user->id)
                        ->whereIn('question_id', $material->questions()->pluck('id'))
                        ->whereNotNull('completed_at')
                        ->count();

                    return [
                        'id' => $material->id,
                        'title' => $material->title,
                        'description' => $material->description,
                        'is_unlocked' => $material->isUnlockedFor($user),
                        'is_completed' => $material->isCompletedBy($user),
                        'total_questions' => $totalQuestions,
                        'completed_questions' => $completedQuestions,
                    ];
                });

                $totalMaterials = $materials->count();
                $completedMaterials = $materials->filter(fn($m) => $m['is_completed'])->count();

                return [
                    'id' => $module->id,
                    'title' => $module->title,
                    'description' => $module->description,
                    'badge_name' => $module->badge_name,
                    'badge_icon' => $module->badge_icon,
                    'order' => $module->order,
                    'is_unlocked' => $module->isUnlockedFor($user),
                    'is_completed' => $module->isCompletedBy($user),
                    'completion_percentage' => $module->getCompletionPercentage($user),
                    'total_materials' => $totalMaterials,
                    'completed_materials' => $completedMaterials,
                    'materials' => $materials,
                ];
            });

        return view('modules.index', compact('modules', 'stats'));
    }

    /**
     * Display specific module details
     */
    public function show(Request $request, Module $module)
    {
        $user = $request->user();

        // Check if module is unlocked
        if (!$module->isUnlockedFor($user)) {
            return redirect()
                ->route('modules.index')
                ->with('error', 'Modul ini masih terkunci. Selesaikan modul sebelumnya terlebih dahulu.');
        }

        $module->load(['materials.questions']);

        $moduleData = [
            'id' => $module->id,
            'title' => $module->title,
            'description' => $module->description,
            'badge_name' => $module->badge_name,
            'is_completed' => $module->isCompletedBy($user),
            'completion_percentage' => $module->getCompletionPercentage($user),
            'materials' => $module->materials->map(function ($material) use ($user) {
                return [
                    'id' => $material->id,
                    'title' => $material->title,
                    'description' => $material->description,
                    'is_unlocked' => $material->isUnlockedFor($user),
                    'is_completed' => $material->isCompletedBy($user),
                    'completion_percentage' => $material->getCompletionPercentage($user),
                    'questions_count' => $material->questions()->count(),
                ];
            }),
        ];

        return view('modules.show', compact('moduleData'));
    }
}
