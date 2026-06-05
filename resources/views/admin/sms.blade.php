@extends('layouts.admin')

@section('title', 'Matumizi ya SMS')
@section('page_title', 'Matumizi ya SMS')
@section('page_subtitle', 'Usimamizi wa matumizi ya SMS kwa watumiaji')

@push('admin-styles')
<style>
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        padding: 12px;
        color: white;
        margin-bottom: 15px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .stats-card-warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .stats-card-success {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .stats-card-info {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }
    
    .sms-counter {
        font-size: 1.5rem;
        font-weight: bold;
        margin: 5px 0;
    }
    
    .stats-card small {
        font-size: 0.7rem;
    }
    
    .stats-card i {
        font-size: 1.5rem;
    }
    
    .progress-sms {
        height: 6px;
        border-radius: 10px;
        background: rgba(255,255,255,0.3);
    }
    
    .progress-sms-bar {
        background: white;
        border-radius: 10px;
        transition: width 0.3s ease;
    }
    
    .user-card {
        background: white;
        border-radius: 8px;
        padding: 10px;
        margin-bottom: 8px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        transition: all 0.2s;
        cursor: pointer;
        border: 1px solid transparent;
    }
    
    .user-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .user-card.active {
        border-color: #FF6F00;
        background: #FFF8F0;
    }
    
    .user-card h6 {
        font-size: 0.85rem;
        margin-bottom: 0;
    }
    
    .user-card small {
        font-size: 0.65rem;
    }
    
    .sms-usage-table {
        background: white;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .sms-usage-table th {
        background: #F8FAFC;
        padding: 8px 10px;
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748B;
    }
    
    .sms-usage-table td {
        padding: 8px 10px;
        font-size: 0.75rem;
        border-bottom: 1px solid #E2E8F0;
    }
    
    .badge-sms {
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.65rem;
        font-weight: 600;
        display: inline-block;
    }
    
    .badge-sms-invitation { background: #DBEAFE; color: #2563EB; }
    .badge-sms-contribution_request { background: #D1FAE5; color: #059669; }
    .badge-sms-reminder { background: #FEE2E2; color: #DC2626; }
    
    .quota-alert {
        background: #FEF3C7;
        border-left: 3px solid #F59E0B;
        padding: 5px 8px;
        border-radius: 4px;
        margin-top: 8px;
        font-size: 0.65rem;
    }
    
    .admin-nav-section {
        margin-bottom: 15px;
    }
    
    .admin-nav-title {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748B;
        margin-bottom: 8px;
        letter-spacing: 0.5px;
    }
    
    .admin-nav-item {
        margin-bottom: 3px;
    }
    
    .admin-nav-item.active {
        background: #FFF8F0;
        border-radius: 6px;
    }
    
    .admin-nav-link {
        display: flex;
        align-items: center;
        padding: 6px 10px;
        color: #334155;
        text-decoration: none;
        border-radius: 6px;
        transition: all 0.2s;
        font-size: 0.8rem;
    }
    
    .admin-nav-link:hover {
        background: #F1F5F9;
    }
    
    .admin-nav-item.active .admin-nav-link {
        background: #FF6F00;
        color: white;
    }
    
    .admin-nav-link i {
        width: 20px;
        font-size: 0.85rem;
    }
    
    .nav-label {
        margin-left: 8px;
        font-size: 0.75rem;
    }
    
    .card-header {
        padding: 8px 12px;
    }
    
    .card-header h6 {
        font-size: 0.85rem;
    }
    
    .btn-sm {
        padding: 3px 8px;
        font-size: 0.7rem;
    }
    
    .badge {
        font-size: 0.65rem;
        padding: 2px 6px;
    }
    
    .table .btn-icon {
        width: 24px;
        height: 24px;
        font-size: 0.7rem;
    }
    
    @media (max-width: 768px) {
        .sms-counter {
            font-size: 1.2rem;
        }
        
        .stats-card {
            padding: 8px;
        }
        
        .user-card {
            padding: 8px;
        }
        
        .sms-usage-table th,
        .sms-usage-table td {
            padding: 6px 8px;
            font-size: 0.65rem;
        }
        
        .stats-card i {
            font-size: 1.2rem;
        }
    }
</style>
@endpush

@section('admin-content')
<div class="container-fluid px-2 px-md-3">
    
    <!-- Communication Navigation Section -->
    <div class="admin-nav-section">
        <div class="admin-nav-title">COMMUNICATION</div>
        <div class="admin-nav-item {{ request()->routeIs('admin.sms*') ? 'active' : '' }}">
            <a href="{{ route('admin.sms') }}" class="admin-nav-link">
                <i class="fas fa-envelope"></i>
                <span class="nav-label">Matumizi ya SMS</span>
            </a>
        </div>
    </div>
    
    <!-- Overall Stats -->
    <div class="row mb-3">
        <div class="col-md-3 col-6 mb-2">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small>TOTAL SMS</small>
                        <div class="sms-counter">{{ number_format($totalSmsSent ?? 0) }}</div>
                        <small>Mwezi huu</small>
                    </div>
                    <i class="fas fa-envelope opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-2">
            <div class="stats-card stats-card-success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small>GHARAMA</small>
                        <div class="sms-counter">TSh {{ number_format($totalCost ?? 0, 0) }}</div>
                        <small>Mwezi huu</small>
                    </div>
                    <i class="fas fa-money-bill-wave opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-2">
            <div class="stats-card stats-card-info">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small>WATUMIAJI</small>
                        <div class="sms-counter">{{ number_format($activeUsers ?? 0) }}</div>
                        <small>Wanayotumia</small>
                    </div>
                    <i class="fas fa-users opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-2">
            <div class="stats-card stats-card-warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small>MATUKIO</small>
                        <div class="sms-counter">{{ number_format($eventsWithSms ?? 0) }}</div>
                        <small>Yaliyotumia SMS</small>
                    </div>
                    <i class="fas fa-calendar-alt opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="row">
        <!-- Left Column - Users List -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header" style="background: #FF6F00; color: white;">
                    <h6 class="mb-0">
                        <i class="fas fa-users me-2"></i> Watumiaji na Matumizi ya SMS
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($usersWithSms ?? [] as $userData)
                            @php 
                                $usagePercent = ($userData->user->sms_used_this_month / max($userData->user->sms_quota, 1)) * 100;
                                $isQuotaLow = $usagePercent >= 90;
                            @endphp
                            <div class="user-card {{ $selectedUserId == $userData->user->id ? 'active' : '' }}"
                                 onclick="selectUser({{ $userData->user->id }})">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <div>
                                        <h6 class="mb-0">{{ $userData->user->name }}</h6>
                                        <small class="text-muted">
                                            <i class="fas fa-tag me-1"></i>
                                            {{ $roles[$userData->user->role] ?? $userData->user->role }}
                                        </small>
                                    </div>
                                    <span class="badge bg-secondary">
                                        {{ $userData->total_sms }} SMS
                                    </span>
                                </div>
                                
                                <!-- SMS Counter Bar -->
                                <div class="mb-1">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span>Matumizi</span>
                                        <span>{{ number_format($userData->user->sms_used_this_month) }} / {{ number_format($userData->user->sms_quota) }}</span>
                                    </div>
                                    <div class="progress-sms">
                                        <div class="progress-sms-bar" style="width: {{ min($usagePercent, 100) }}%; height: 100%; background: {{ $isQuotaLow ? '#F59E0B' : 'white' }};"></div>
                                    </div>
                                </div>
                                
                                <!-- Additional Info -->
                                <div class="row small">
                                    <div class="col-6">
                                        <span class="text-muted">Salio:</span><br>
                                        <strong class="{{ $userData->user->sms_balance < 1000 ? 'text-danger' : '' }}">
                                            TSh {{ number_format($userData->user->sms_balance, 0) }}
                                        </strong>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted">Ujumbe:</span><br>
                                        <strong>{{ number_format($userData->sms_count) }}</strong>
                                    </div>
                                </div>
                                
                                @if($isQuotaLow)
                                    <div class="quota-alert">
                                        <small><i class="fas fa-exclamation-triangle"></i> Quota {{ number_format($usagePercent, 0) }}% imetumika!</small>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-3">
                                <i class="fas fa-users fa-2x text-muted mb-2 d-block"></i>
                                <small>Hakuna watumiaji waliopatikana</small>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column - SMS History -->
        <div class="col-md-8 mb-3">
            <div class="card">
                <div class="card-header" style="background: #FF6F00; color: white;">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h6 class="mb-0">
                            <i class="fas fa-history me-2"></i> Historia ya SMS
                            <span class="small">- {{ $selectedUser ? $selectedUser->name : 'Hajachaguliwa' }}</span>
                        </h6>
                        @if($selectedUser && auth()->user()->role === 'admin')
                            <button type="button" class="btn btn-sm btn-light" onclick="adjustQuota({{ $selectedUser->id }})">
                                <i class="fas fa-edit"></i> Badilisha Quota
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="sms-usage-table">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Tarehe</th>
                                        <th>Tukio</th>
                                        <th>Aina</th>
                                        <th>Nambari</th>
                                        <th>SMS</th>
                                        <th>Gharama</th>
                                        <th>Hali</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($selectedUser && isset($selectedUserSms) && $selectedUserSms->count() > 0)
                                        @foreach($selectedUserSms as $sms)
                                            <tr>
                                                <td>{{ $sms->created_at->format('d/m/Y H:i') }}</td>
                                                <td>{{ $sms->event->event_name ?? '-' }}</td>
                                                <td>
                                                    <span class="badge-sms badge-sms-{{ $sms->message_type }}">
                                                        {{ $messageTypes[$sms->message_type] ?? $sms->message_type }}
                                                    </span>
                                                </td>
                                                <td>{{ $sms->recipient_phone }}</td>
                                                <td>{{ $sms->sms_count }}</td>
                                                <td>TSh {{ number_format($sms->cost, 0) }}</td>
                                                <td>
                                                    @if($sms->status == 'sent')
                                                        <span class="text-success"><i class="fas fa-check-circle"></i> Imetumwa</span>
                                                    @else
                                                        <span class="text-danger"><i class="fas fa-times-circle"></i> Imeshindwa</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @elseif($selectedUser)
                                        <tr>
                                            <td colspan="7" class="text-center py-3">
                                                <i class="fas fa-envelope fa-2x text-muted mb-2 d-block"></i>
                                                <small>Hakuna SMS zilizotumwa bado</small>
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center py-3">
                                                <i class="fas fa-user-circle fa-2x text-muted mb-2 d-block"></i>
                                                <small>Chagua mtumiaji kuona historia ya SMS</small>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Adjust Quota Modal -->
<div class="modal fade" id="quotaModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #FF6F00, #FF9800); padding: 10px 15px;">
                <h6 class="modal-title text-white">
                    <i class="fas fa-edit me-2"></i> Badilisha Quota
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="quotaForm" method="POST">
                @csrf
                <div class="modal-body" style="padding: 15px;">
                    <input type="hidden" name="user_id" id="quota_user_id">
                    <div class="mb-2">
                        <label class="form-label small">Mtumiaji</label>
                        <input type="text" id="quota_user_name" class="form-control form-control-sm" readonly>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Quota ya SMS kwa Mwezi</label>
                        <input type="number" name="sms_quota" id="quota_amount" class="form-control form-control-sm" required min="0">
                        <small class="text-muted" style="font-size: 0.6rem;">Idadi ya SMS kwa mwezi</small>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Salio la Kifedha (TSh)</label>
                        <input type="number" name="sms_balance" id="quota_balance" class="form-control form-control-sm" step="0.01" min="0">
                        <small class="text-muted" style="font-size: 0.6rem;">Salio la pesa</small>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 10px 15px;">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Ghairi</button>
                    <button type="submit" class="btn btn-sm" style="background: #FF6F00; color: white;">Hifadhi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function selectUser(userId) {
    window.location.href = "{{ route('admin.sms') }}?user_id=" + userId;
}

function adjustQuota(userId) {
    fetch(`/admin/users/${userId}/edit-ajax`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('quota_user_id').value = data.id;
            document.getElementById('quota_user_name').value = data.name;
            document.getElementById('quota_amount').value = data.sms_quota || 500;
            document.getElementById('quota_balance').value = data.sms_balance || 0;
            document.getElementById('quotaForm').action = `/admin/sms/quota/${userId}`;
            
            new bootstrap.Modal(document.getElementById('quotaModal')).show();
        })
        .catch(error => {
            Swal.fire('Error', 'Haiwezi kupata data ya mtumiaji', 'error');
        });
}

document.getElementById('quotaForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Imefanikiwa', data.message, 'success').then(() => {
                location.reload();
            });
        } else {
            Swal.fire('Error', data.message || 'Hitilafu imetokea', 'error');
        }
    })
    .catch(() => {
        Swal.fire('Error', 'Hitilafu ya mtandao', 'error');
    });
});
</script>

<style>
.highlight {
    animation: pulse 0.5s ease;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; transform: scale(1.02); }
    100% { opacity: 1; }
}
</style>
@endsection