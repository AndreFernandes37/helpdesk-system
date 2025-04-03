<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Categoria;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Models\Resposta;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::where('user_id', auth()->id());

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }   

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $tickets = $query->latest()->get();

        $categorias = Categoria::all();

        return view('cliente.dashboard', compact('tickets', 'categorias'));

    }

    public function create()
    {
        $categorias = Categoria::all();
        return view('cliente.tickets.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'categoria_id' => 'nullable|exists:categorias,id',
        ]);

        Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'categoria_id' => $request->categoria_id,
            'user_id' => auth()->id(),
            'status' => 'open',
        ]);

        return redirect()->route('cliente.dashboard')->with('success', 'Ticket criado com sucesso!');
    }

    public function show($id)
    {
        $ticket = Ticket::with([
            'respostas' => function ($query) {
                $query->orderBy('created_at', 'asc');
            },
            'respostas.user'
        ])
        ->where('id', $id)
        ->where('user_id', auth()->id())
        ->firstOrFail();

        return view('cliente.tickets.show', compact('ticket'));
    }


    public function responder(Request $request, $id)
    {
        $ticket = Ticket::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $request->validate([
            'content' => 'required|string|max:2000',
            'attachment' => 'nullable|file|max:5120', // Validação do anexo, máximo de 5MB
        ]);

        $attachmentPath = null;
        
        // Cria a resposta
        $resposta = Resposta::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'content' => $request->content,
            'attachment_path' => $attachmentPath,
        ]);

        // Verifica se um anexo foi enviado
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
            
            // Adiciona o caminho do anexo à resposta (assumindo que a tabela respostas tem uma coluna 'attachment')
            $resposta->attachment = $attachmentPath;
            $resposta->save();
        }

        return redirect()->route('cliente.ticket.show', $ticket->id)->with('success', 'Resposta enviada!');
    }


    public function respostasJson($id)
    {
        $ticket = Ticket::with(['respostas.user'])->findOrFail($id);
        return response()->json($ticket->respostas);
    }

}

