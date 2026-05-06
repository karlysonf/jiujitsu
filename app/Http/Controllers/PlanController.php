<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Services\PlanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PlanController extends Controller
{
    protected $planService;

    public function __construct(PlanService $planService)
    {
        $this->planService = $planService;
    }

    public function index()
    {
        Gate::authorize('manage-plans');
        $plans = $this->planService->getAllPlans();

        return view('plans.index', compact('plans'));
    }

    public function store(Request $request)
    {
        Gate::authorize('manage-plans');
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ], [
            'name.required' => 'O nome do plano é obrigatório.',
            'price.required' => 'O valor do plano é obrigatório.',
            'price.numeric' => 'O valor deve ser um número válido.',
            'price.min' => 'O valor não pode ser negativo.',
        ]);

        $this->planService->createPlan($request->only(['name', 'price']));

        return redirect()->route('plans.index')->with('success', 'Plano criado com sucesso!');
    }

    public function update(Request $request, Plan $plan)
    {
        Gate::authorize('manage-plans');
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ], [
            'name.required' => 'O nome do plano é obrigatório.',
            'price.required' => 'O valor do plano é obrigatório.',
            'price.numeric' => 'O valor deve ser um número válido.',
            'price.min' => 'O valor não pode ser negativo.',
        ]);

        $this->planService->updatePlan($plan, $request->only(['name', 'price']));

        return redirect()->route('plans.index')->with('success', 'Plano atualizado com sucesso!');
    }

    public function destroy(Plan $plan)
    {
        abort_unless(auth()->user()->hasAnyRole(['root', 'admin']), 403, 'Ação não autorizada.');

        $this->planService->deletePlan($plan);

        return redirect()->route('plans.index')->with('success', 'Plano excluído com sucesso!');
    }
}
