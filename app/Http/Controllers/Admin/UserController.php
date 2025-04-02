<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('name')->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function edit($id)
    {
        $user = \App\Models\User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,client',
        ]);

        $user = \App\Models\User::findOrFail($id);
        $user->update($request->only('name', 'email', 'role'));

        return redirect()->route('admin.users.index')->with('success', 'Utilizador atualizado com sucesso!');
    }

    public function toggleActive($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->active = ! $user->active;
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Estado do utilizador atualizado!');
    }


}
