@extends('layouts.admin')

@section('title', 'Matukio')
@section('page_title', 'Matukio')
@section('page_subtitle', 'Matukio yote yaliyosajiliwa mfumoni')

@push('admin-styles')
<style>
    .filter-card {
        background: white;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    
    .event-table {
        background: white;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .event-table th {
        background: #F8FAFC;
        padding: 12px 15px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748B;
    }
    
    .event-table td {
        padding: 12px 15px;
        font-size: 0.8rem;
        border-bottom: 1px solid #E2E8F0;
        vertical-align: middle;
    }
    
    .badge-status {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-block;
    }
    
    .badge-active { background: #D1FAE5; color: #059669; }
    .badge-completed { background: #DBEAFE; color: #2563EB; }
    .badge-cancelled { background: #FEE2E2; color: #DC2626; }
    
    .btn-action {
        padding: 4px 8px;
        font-size: 0.7rem;
        margin: 0 2px;
    }
    
    .pagination-wrapper {
        padding: 15px;
        background: white;
        border-top: 1px solid #E2E8F0;
    }
    
    @media (max-width: 768px) {
        .event-table th,
        .event-table td {
            padding: 8px 10px;
            font-size: 0.7rem;
        }
        
        .badge-status {
            padding: 2px 6px;
            font-size: 0.6rem;
        }
        
        .btn-action {
            padding: 2px 6px;
            font-size: 0.6rem;
        }
    }
</style>
@endpush

@section('admin-content')
<div class="container-fluid px-2 px-md-3">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h5 class="mb-0">Matukio Yote</h5>
            <small class="text-muted">Jumla: {{ number_format($events->total()) }} matukio</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.events.download', request()->query()) }}" class="btn btn-sm btn-success">
                <i class="fas fa-download me-1"></i> Pakua CSV
            </a>
        </div>
    </div>
    
    <!-- Filter Card -->
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.events') }}" class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" 
                       placeholder="Tafuta kwa jina la tukio..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Hali Yote</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Inaendelea</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Imekamilika</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Imefutwa</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-sm btn-primary w-100">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.events') }}" class="btn btn-sm btn-secondary w-100">
                    <i class="fas fa-undo me-1"></i> Reset
                </a>
            </div>
        </form>
    </div>
    
    <!-- Events Table -->
    <div class="event-table">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Jina la Tukio</th>
                        <th>Aliyesajili</th>
                        <th>Namba ya Simu</th>
                        <th>Tarehe ya Tukio</th>
                        <th>Tarehe ya Usajili</th>
                        <th>Hali</th>
                        <th>Kitendo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $index => $event)
                    <tr>
                        <td>{{ $events->firstItem() + $index }}</td>
                        <td class="fw-semibold">{{ \Str::limit($event->event_name, 30) }}</td>
                        <td>
                            <strong>{{ $event->user->name ?? '-' }}</strong><br>
                            <small class="text-muted">{{ ucfirst($event->user->role_display ?? '') }}</small>
                        </td>
                        <td>{{ $event->user->phone ?? '-' }}</td>
                        <td>{{ $event->event_date ? $event->event_date->format('d/m/Y') : '-' }}</td>
                        <td>{{ $event->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <span class="badge-status badge-{{ $event->status }}">
                                @if($event->status == 'active') Inaendelea
                                @elseif($event->status == 'completed') Imekamilika
                                @else Imefutwa @endif
                            </span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger btn-action" 
                                    onclick="confirmDelete({{ $event->id }}, '{{ $event->event_name }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-calendar-alt fa-2x text-muted mb-2 d-block"></i>
                            Hakuna matukio yaliyopatikana
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="pagination-wrapper">
            {{ $events->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Delete Confirmation Form -->
<form id="deleteEventForm" method="POST" action="">
    @csrf
    @method('DELETE')
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmDelete(id, name) {
    Swal.fire({
        title: 'Una uhakika?',
        text: `Unataka kufuta tukio "${name}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ndio, Futa',
        cancelButtonText: 'Ghairi'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('deleteEventForm');
            form.action = `/admin/events/${id}/delete`;
            form.submit();
        }
    });
}

// Auto-hide alerts after 3 seconds
setTimeout(function() {
    let alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        alert.style.transition = 'opacity 0.5s ease';
        alert.style.opacity = '0';
        setTimeout(function() {
            alert.remove();
        }, 500);
    });
}, 3000);
</script>
@endsection