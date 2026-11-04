<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /**
     * Display the "Materi Pembelajaran" reading page (before questions).
     */
    public function materi(Request $request, Material $material)
    {
        $user = $request->user();

        // Check if material is unlocked
        if (!$material->isUnlockedFor($user)) {
            return redirect()
                ->route('modules.index')
                ->with('error', 'Materi ini masih terkunci. Selesaikan materi sebelumnya terlebih dahulu.');
        }

        $material->load('module');

        return view('materials.materi', compact('material'));
    }

    /**
     * Display material with its questions (level page)
     */
    public function show(Request $request, Material $material)
    {
        $user = $request->user();

        // Check if material is unlocked
        if (!$material->isUnlockedFor($user)) {
            return redirect()
                ->route('modules.index')
                ->with('error', 'Materi ini masih terkunci. Selesaikan materi sebelumnya terlebih dahulu.');
        }

        $material->load(['questions', 'module']);

        $materialData = [
            'id' => $material->id,
            'title' => $material->title,
            'description' => $material->description,
            'module_title' => $material->module->title,
            'module_id' => $material->module->id,
            'is_completed' => $material->isCompletedBy($user),
            'completion_percentage' => $material->getCompletionPercentage($user),
            'questions' => $material->questions->sortBy('order')->map(function ($question) use ($user) {
                $progress = $question->userProgress($user);
                
                return [
                    'id' => $question->id,
                    'order' => $question->order,
                    'title' => $question->title,
                    'type' => $question->type,
                    'xp_reward' => $question->xp_reward,
                    'is_unlocked' => $question->isUnlockedFor($user),
                    'is_completed' => $question->isCompletedBy($user),
                    'attempts' => $progress ? $progress->attempts : 0,
                ];
            })->values(),
        ];

        return view('materials.show', ['material' => $materialData]);
    }
}
