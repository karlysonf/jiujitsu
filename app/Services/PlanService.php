<?php

namespace App\Services;

use App\Models\Plan;

class PlanService
{
    public function getAllPlans()
    {
        return Plan::orderBy('name')->get();
    }

    public function createPlan(array $data)
    {
        return Plan::create($data);
    }

    public function updatePlan(Plan $plan, array $data)
    {
        $plan->update($data);

        return $plan;
    }

    public function deletePlan(Plan $plan)
    {
        return $plan->delete();
    }
}
