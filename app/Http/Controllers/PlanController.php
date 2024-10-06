<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    // 計画作成画面の表示
    public function create()
    {
        return view('plans.create');
    }

    // 計画の保存処理
    public function store(Request $request)
    {
        // バリデーション
        $validated = $request->validate([
            'transportation' => 'required|string',
            'spots' => 'required|array',  // スポットは配列で受け取る
            'comment' => 'nullable|string',
        ]);

        // 計画の保存
        $plan = Plan::create([
            'transportation' => $validated['transportation'],
            'spots' => json_encode($validated['spots']), // スポットをJSON形式で保存
            'memo' => $validated['memo'],
        ]);

        return redirect()->route('plans.show', $plan->id)->with('success', '旅行計画が保存されました');
    }

    // 計画の表示
    public function show($id)
    {
        $plan = Plan::findOrFail($id);
        return view('plans.show', compact('plan'));
    }
}
