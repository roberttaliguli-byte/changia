@extends('layouts.admin')

@section('title', 'Watumiaji')
@section('page_title', 'Watumiaji')
@section('page_subtitle', 'Usimamizi wa watumiaji wote wa mfumo')

@push('admin-styles')
<style>
    .filter-card {
        background: white;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    
    .user-table {
        background: white;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .user-table th {
        background: #F8FAFC;
        padding: 12px 15px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748B;
    }
    
    .user-table td {
        padding: 12px 15px;
        font-size: 0.8rem;
        border-bottom: 1px solid #E2E8F0;
        vertical-align: middle;
    }
    
    .badge-role {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-block;
    }
    
    .badge-role-admin { background: #FEE2E2; color: #DC2626; }
    .badge-role-accountant { background: #DBEAFE; color: #2563EB; }
    .badge-role-event_user { background: #D1FAE5; color: #059669; }
    .badge-role-user { background: #F3F4F6; color: #6B7280; }
    
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
    
    .event-badge {
        background: #E0F2FE;
        color: #0369A1;
        padding: 2px 6px;
        border-radius: 12px;
        font-size: 0.6rem;
        font-weight: 600;
        display: inline-block;
        margin: 2px;
    }
    
    .modal-lg-custom {
        max-width: 600px;
    }
    
    @media (max-width: 768px) {
        .user-table th,
        .user-table td {
            padding: 8px 10px;
            font-size: 0.7rem;
        }
        
        .badge-role {
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
    <!-- Header with Add Button -->
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h5 class="mb-0">Watumiaji Wote</h5>
            <small class="text-muted">Jumla: {{ number_format($users->total()) }} watumiaji</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.download', request()->query()) }}" class="btn btn-sm btn-success">
                <i class="fas fa-download me-1"></i> Pakua CSV
            </a>
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fas fa-plus me-1"></i> Mtumiaji Mpya
            </button>
        </div>
    </div>
    
    <!-- Filter Card -->
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.users') }}" class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" 
                       placeholder="Tafuta kwa jina, email au simu..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select form-select-sm">
                    <option value="">Jukumu Lote</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Msimamizi</option>
                    <option value="accountant" {{ request('role') == 'accountant' ? 'selected' : '' }}>Mhasibu</option>
                    <option value="event_user" {{ request('role') == 'event_user' ? 'selected' : '' }}>Mratibu wa Tukio</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Mtumiaji</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-sm btn-primary w-100">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.users') }}" class="btn btn-sm btn-secondary w-100">
                    <i class="fas fa-undo me-1"></i> Reset
                </a>
            </div>
        </form>
    </div>
    
    <!-- Users Table -->
    <div class="user-table">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Jina Kamili</th>
                        <th>Simu</th>
                        <th>Barua Pepe</th>
                        <th>Jukumu</th>
                        <th>Matukio</th>
                        <th>Tarehe</th>
                        <th>Vitendo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                    <tr>
                        <td>{{ $users->firstItem() + $index }}</td>
                        <td class="fw-semibold">{{ $user->name }}</td>
                        <td>{{ $user->phone ?? '-' }}</td>
                        <td>{{ $user->email ?? '-' }}</td>
                        <td>
                            <span class="badge-role badge-role-{{ $user->role }}">
                                {{ $roles[$user->role] ?? $user->role }}
                            </span>
                        </td>
                        <td>
                            @if($user->role == 'event_user')
                                @php $eventCount = $user->ownedEvents()->count(); @endphp
                                <span class="event-badge">
                                    <i class="fas fa-calendar-alt me-1"></i> {{ $eventCount }} matukio
                                </span>
                            @elseif($user->role == 'accountant')
                                @php $eventCount = $user->events()->count(); @endphp
                                <span class="event-badge">
                                    <i class="fas fa-chart-line me-1"></i> {{ $eventCount }} matukio
                                </span>
                            @else
                                <span class="event-badge">-</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-info btn-action" 
                                    onclick="editUser({{ $user->id }})" 
                                    data-bs-toggle="modal" data-bs-target="#editUserModal">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger btn-action" 
                                    onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-users fa-2x text-muted mb-2 d-block"></i>
                            Hakuna watumiaji waliopatikana
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="pagination-wrapper">
            {{ $users->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg-custom">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.users.store') }}" id="addUserForm">
                @csrf
                <div class="modal-header" style="background: linear-gradient(135deg, #FF6F00, #FF9800);">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-user-plus me-2"></i> Ongeza Mtumiaji Mpya
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Jina Kamili <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Namba ya Simu <span class="text-danger">*</span></label>
                        <input type="tel" name="phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Barua Pepe</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jukumu <span class="text-danger">*</span></label>
                        <select name="role" class="form-select" required>
                            <option value="">Chagua Jukumu</option>
                            <option value="admin">Msimamizi</option>
                            <option value="accountant">Mhasibu</option>
                            <option value="event_user">Mratibu wa Tukio</option>
                            <option value="user">Mtumiaji</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nywila <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thibitisha Nywila <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ghairi</button>
                    <button type="submit" class="btn" style="background: #FF6F00; color: white;">Hifadhi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg-custom">
        <div class="modal-content">
            <form method="POST" action="" id="editUserForm">
                @csrf
                @method('PUT')
                <div class="modal-header" style="background: linear-gradient(135deg, #FF6F00, #FF9800);">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-edit me-2"></i> Badilisha Mtumiaji
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="edit_user_id">
                    <div class="mb-3">
                        <label class="form-label">Jina Kamili <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Namba ya Simu <span class="text-danger">*</span></label>
                        <input type="tel" name="phone" id="edit_phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Barua Pepe</label>
                        <input type="email" name="email" id="edit_email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jukumu <span class="text-danger">*</span></label>
                        <select name="role" id="edit_role" class="form-select" required>
                            <option value="admin">Msimamizi</option>
                            <option value="accountant">Mhasibu</option>
                            <option value="event_user">Mratibu wa Tukio</option>
                            <option value="user">Mtumiaji</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nywila Mpya (Acha ikiwa hutaki kubadilisha)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thibitisha Nywila Mpya</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ghairi</button>
                    <button type="submit" class="btn" style="background: #FF6F00; color: white;">Hifadhi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Form -->
<form id="deleteUserForm" method="POST" action="">
    @csrf
    @method('DELETE')
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function editUser(id) {
    // Fetch user data via AJAX
    fetch(`/admin/users/${id}/edit-ajax`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            document.getElementById('edit_user_id').value = data.id;
            document.getElementById('edit_name').value = data.name;
            document.getElementById('edit_phone').value = data.phone;
            document.getElementById('edit_email').value = data.email || '';
            document.getElementById('edit_role').value = data.role;
            document.getElementById('editUserForm').action = `/admin/users/${id}/update`;
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Haiwezi kupata data ya mtumiaji', 'error');
        });
}

function confirmDelete(id, name) {
    Swal.fire({
        title: 'Una uhakika?',
        text: `Unataka kumfuta ${name}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ndio, Futa',
        cancelButtonText: 'Ghairi'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('deleteUserForm');
            form.action = `/admin/users/${id}/delete`;
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