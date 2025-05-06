<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Resposta;
use App\Models\User;
use App\Notifications\TicketActionNotification;
use Illuminate\Support\Facades\Notification;
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
        
        //$tickets = $query->latest()->get();
        $tickets = $query->latest()->paginate(10);
        $categorias = \App\Models\Categoria::all();

        // Contar as respostas não lidas
        foreach ($tickets as $ticket) {
            $ticket->unread_responses_count = $ticket->respostas()->where('is_read', false)->count();
        }
        
        
        return view('admin.tickets.index', compact('tickets', 'categorias'));
        
    }

    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);
        
        // Marcar o ticket como lido
        $ticket->read = true;
        $ticket->save();

        $ticket = Ticket::with(['respostas' => function ($query) {
            $query->orderBy('created_at', 'asc');
        }, 'respostas.user'])->findOrFail($id);

         // Marcar todas as respostas como lidas ao exibir o ticket
         foreach ($ticket->respostas as $resposta) {
            // Verifica se a resposta não foi lida e se não é do usuário atual
            if (!$resposta->is_read && $resposta->user_id !== auth()->id()) {
                $resposta->update(['is_read' => true]);  // Atualiza o status para "lido"
            }
        }
        

        return view('admin.tickets.show', compact('ticket'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'categoria_id' => 'nullable|exists:categorias,id',
        ]);

        $ticket = Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'categoria_id' => $request->categoria_id,
            'user_id' => auth()->id(),
            'status' => 'open',
        ]);

        // Enviar notificação para administradores
        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new TicketActionNotification($ticket, 'created'));

        return redirect()->route('cliente.dashboard')->with('success', 'Ticket criado com sucesso!');
    }

    public function responder(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:5000',
            'attachment' => 'nullable|file|max:5120', // Validação do anexo, máximo de 5MB
        ]);

        $attachmentPath = null;
        $ticket = Ticket::findOrFail($id);

        $resposta = Resposta::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'content' => $request->content,
            'attachment_path' => $attachmentPath,
            'is_read' => 0, // Inicialmente a resposta é não lida
        ]);

        // Verifica se um anexo foi enviado
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
            
            // Adiciona o caminho do anexo à resposta (assumindo que a tabela respostas tem uma coluna 'attachment')
            $resposta->attachment = $attachmentPath;
            $resposta->save();
        }

        // Enviar notificação para administradores
        $clientes = User::where('role', 'client')->get();
        Notification::send($clientes, new TicketActionNotification($ticket, 'responded'));

        // Notificação quando a resposta for adicionada
        session()->flash('success', 'Resposta enviada com sucesso!');

        

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

        session()->flash('status_update', 'Status do ticket atualizado com sucesso!');

        return redirect()->route('admin.tickets.show', $ticket->id)->with('success', 'Status atualizado!');
    }

    public function respostasJson($id)
    {
        $ticket = Ticket::with(['respostas.user'])->findOrFail($id);
        return response()->json($ticket->respostas);
    }

    public function markRespostaAsRead(Request $request, $id)
    {
        $resposta = Resposta::findOrFail($id);

        // Certifique-se de que a resposta não seja do próprio usuário
        if ($resposta->user_id !== auth()->id()) {
            $resposta->update(['is_read' => true]);
        }

        return response()->json(['success' => true]);
    }



}
