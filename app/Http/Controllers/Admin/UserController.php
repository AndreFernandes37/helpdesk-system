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
            'password' => 'nullable|string|min:8',
        ]);

        $user = User::findOrFail($id);

        $data = $request->only('name', 'email', 'role');

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Utilizador atualizado com sucesso!');
    }


    public function toggleActive($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->active = ! $user->active;
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Estado do utilizador atualizado!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Proteção: não permitir que o admin se apague a si próprio
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Não pode apagar a sua própria conta.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Utilizador eliminado com sucesso.');
    }

    public function show($id)
    {
        $user = User::with('tickets')->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }




}
