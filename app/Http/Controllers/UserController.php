<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Plan;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        Gate::authorize('manage-users');
        $users = $this->userService->getAllUsers($request->search);

        return view('users.index', compact('users'));
    }

    public function create(Request $request)
    {
        Gate::authorize('manage-users');
        $plans = Plan::orderBy('name')->get();
        $role = $request->query('role', 'aluno');

        return view('users.create', compact('plans', 'role'));
    }

    public function store(StoreUserRequest $request)
    {
        Gate::authorize('manage-users');
        $this->userService->createUser($request->validated());

        return redirect()->route('users.index')->with('success', 'Usuário cadastrado com sucesso!');
    }

    public function show(User $user)
    {
        Gate::authorize('manage-users', $user);
        $user->load(['payments', 'attendances', 'plan']);

        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        Gate::authorize('manage-users', $user);
        $plans = Plan::orderBy('name')->get();

        return view('users.edit', compact('user', 'plans'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        Gate::authorize('manage-users', $user);
        $this->userService->updateUser($user, $request->validated());

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        abort_unless(auth()->user()->hasAnyRole(['root', 'admin']), 403, 'Ação não autorizada.');

        $this->userService->deleteUser($user);

        return redirect()->route('users.index')->with('success', 'Usuário excluído com sucesso!');
    }
}
