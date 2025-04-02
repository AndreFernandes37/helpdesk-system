<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Resposta;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::query()->with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }
        
        $tickets = $query->latest()->get();
        $categorias = \App\Models\Categoria::all();
        
        return view('admin.tickets.index', compact('tickets', 'categorias'));
        
    }

    public function show($id)
    {
        $ticket = Ticket::with(['respostas' => function ($query) {
            $query->orderBy('created_at', 'asc');
        }, 'respostas.user'])->findOrFail($id);        

        return view('admin.tickets.show', compact('ticket'));
    }

    public function responder(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:5000',
        ]);

        $ticket = Ticket::findOrFail($id);

        Resposta::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return redirect()->route('admin.tickets.show', $ticket->id)->with('success', 'Resposta enviada!');

    }

    public function atualizarStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,closed',
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticket->status = $request->status;
        $ticket->save();

        return redirect()->route('admin.tickets.show', $ticket->id)->with('success', 'Status atualizado!');
    }

    public function respostasJson($id)
    {
        $ticket = Ticket::with(['respostas.user'])->findOrFail($id);
        return response()->json($ticket->respostas);
    }


}
