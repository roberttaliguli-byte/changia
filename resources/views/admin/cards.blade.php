@extends('layouts.admin')

@section('title', 'Maombi ya Kadi')
@section('page_title', 'Maombi ya Kadi')
@section('page_subtitle', 'Usimamizi wa maombi ya kadi kutoka kwa waratibu wa matukio')

@push('admin-styles')
<style>
    .filter-card {
        background: white;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    
    .cards-table {
        background: white;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .cards-table th {
        background: #F8FAFC;
        padding: 12px 15px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748B;
    }
    
    .cards-table td {
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
    
    .badge-pending { background: #FEF3C7; color: #D97706; }
    .badge-approved { background: #D1FAE5; color: #059669; }
    .badge-rejected { background: #FEE2E2; color: #DC2626; }
    .badge-completed { background: #DBEAFE; color: #2563EB; }
    
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
    
    .modal-lg-custom {
        max-width: 800px;
    }
    
    .card-preview {
        background: #F9FAFB;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
    }
    
    .card-preview h6 {
        font-size: 0.8rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: #FF6F00;
    }
    
    .detail-row {
        display: flex;
        margin-bottom: 8px;
        font-size: 0.75rem;
    }
    
    .detail-label {
        width: 130px;
        font-weight: 600;
        color: #6B7280;
    }
    
    .detail-value {
        flex: 1;
        color: #1F2937;
    }
    
    .design-preview {
        max-width: 100%;
        border-radius: 8px;
        margin-top: 10px;
    }
    
    @media (max-width: 768px) {
        .cards-table th,
        .cards-table td {
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
        
        .detail-label {
            width: 100px;
            font-size: 0.7rem;
        }
    }
</style>
@endpush

@section('admin-content')
<div class="container-fluid px-2 px-md-3">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h5 class="mb-0">Maombi ya Kadi</h5>
            <small class="text-muted">Jumla: {{ number_format($cards->total()) }} maombi</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.cards.download', request()->query()) }}" class="btn btn-sm btn-success">
                <i class="fas fa-download me-1"></i> Pakua CSV
            </a>
        </div>
    </div>
    
    <!-- Filter Card -->
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.cards') }}" class="row g-2">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control form-control-sm" 
                       placeholder="Tafuta kwa jina au mratibu..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="card_type" class="form-select form-select-sm">
                    <option value="">Aina Yote</option>
                    <option value="invitation" {{ request('card_type') == 'invitation' ? 'selected' : '' }}>Mwaliko</option>
                    <option value="contribution" {{ request('card_type') == 'contribution' ? 'selected' : '' }}>Mchango</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="admin_status" class="form-select form-select-sm">
                    <option value="">Hali Yote</option>
                    <option value="pending" {{ request('admin_status') == 'pending' ? 'selected' : '' }}>Inasubiri</option>
                    <option value="approved" {{ request('admin_status') == 'approved' ? 'selected' : '' }}>Imeidhinishwa</option>
                    <option value="rejected" {{ request('admin_status') == 'rejected' ? 'selected' : '' }}>Imekataliwa</option>
                    <option value="completed" {{ request('admin_status') == 'completed' ? 'selected' : '' }}>Imekamilika</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-sm btn-primary w-100">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.cards') }}" class="btn btn-sm btn-secondary w-100">
                    <i class="fas fa-undo me-1"></i> Reset
                </a>
            </div>
        </form>
    </div>
    
    <!-- Cards Table -->
    <div class="cards-table">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Jina la Kadi</th>
                        <th>Mwombaji</th>
                        <th>Aina</th>
                        <th>Tarehe ya Tukio</th>
                        <th>Hali</th>
                        <th>Tarehe ya Ombi</th>
                        <th>Vitendo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cards as $index => $card)
                    <tr>
                        <td>{{ $cards->firstItem() + $index }}</td>
                        <td class="fw-semibold">{{ \Str::limit($card->title, 25) }}</td>
                        <td>
                            <strong>{{ $card->user->name ?? '-' }}</strong><br>
                            <small class="text-muted">{{ $card->user->phone ?? '-' }}</small>
                        </td>
                        <td>
                            @if($card->card_type == 'invitation')
                                <span class="badge-status" style="background:#FFF3E0; color:#FF6F00;">📨 Mwaliko</span>
                            @else
                                <span class="badge-status" style="background:#D1FAE5; color:#10B981;">🤝 Mchango</span>
                            @endif
                        </td>
                        <td>{{ $card->event_date ? $card->event_date->format('d/m/Y') : '-' }}</td>
                        <td>
                            <span class="badge-status badge-{{ $card->admin_status }}">
                                @if($card->admin_status == 'pending') Inasubiri
                                @elseif($card->admin_status == 'approved') Imeidhinishwa
                                @elseif($card->admin_status == 'rejected') Imekataliwa
                                @else Imekamilika @endif
                            </span>
                        </td>
                        <td>{{ $card->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-info btn-action" 
                                    onclick="viewCardDetails({{ $card->id }})" 
                                    data-bs-toggle="modal" data-bs-target="#viewCardModal">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-primary btn-action" 
                                    onclick="processCard({{ $card->id }})" 
                                    data-bs-toggle="modal" data-bs-target="#processCardModal">
                                <i class="fas fa-cog"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger btn-action" 
                                    onclick="confirmDelete({{ $card->id }}, '{{ $card->title }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-id-card fa-2x text-muted mb-2 d-block"></i>
                            Hakuna maombi ya kadi yaliyopatikana
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="pagination-wrapper">
            {{ $cards->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- View Card Details Modal -->
<div class="modal fade" id="viewCardModal" tabindex="-1">
    <div class="modal-dialog modal-lg-custom">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #FF6F00, #FF9800);">
                <h5 class="modal-title text-white">
                    <i class="fas fa-id-card me-2"></i> Maelezo ya Kadi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="cardDetailsContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2">Inapakia...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Funga</button>
            </div>
        </div>
    </div>
</div>

<!-- Process Card Modal -->
<div class="modal fade" id="processCardModal" tabindex="-1">
    <div class="modal-dialog modal-lg-custom">
        <div class="modal-content">
            <form method="POST" action="" id="processCardForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header" style="background: linear-gradient(135deg, #FF6F00, #FF9800);">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-cog me-2"></i> Shughulikia Ombi la Kadi
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="card_id" id="process_card_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Hali <span class="text-danger">*</span></label>
                        <select name="admin_status" id="process_status" class="form-select" required onchange="toggleProcessFields()">
                            <option value="approved">Idhinisha</option>
                            <option value="rejected">Kataa</option>
                            <option value="completed">Kamilisha (Kadi imetengenezwa)</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Maelezo / Ujumbe kwa Mwombaji</label>
                        <textarea name="admin_notes" id="process_notes" class="form-control" rows="3" 
                                  placeholder="Weka maelezo au sababu ya kukataa..."></textarea>
                    </div>
                    
                    <div id="design_fields" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">Gharama ya Kubuni (TSh)</label>
                            <input type="number" name="design_cost" id="design_cost" class="form-control" 
                                   placeholder="Weka gharama ya kubuni kadi">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Pakia Kadi Iliyobuniwa</label>
                            <input type="file" name="design_file" id="design_file" class="form-control" accept="image/*,.pdf">
                            <small class="text-muted">Pakia kadi iliyobuniwa (PNG, JPG, au PDF)</small>
                        </div>
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
<form id="deleteCardForm" method="POST" action="">
    @csrf
    @method('DELETE')
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function toggleProcessFields() {
    const status = document.getElementById('process_status').value;
    const designFields = document.getElementById('design_fields');
    
    if (status === 'completed') {
        designFields.style.display = 'block';
    } else {
        designFields.style.display = 'none';
    }
}

function viewCardDetails(id) {
    fetch(`/admin/cards/${id}/details`)
        .then(response => response.json())
        .then(data => {
            let html = `
                <div class="card-preview">
                    <h6><i class="fas fa-info-circle"></i> Taarifa za Kadi</h6>
                    <div class="detail-row">
                        <div class="detail-label">Jina la Kadi:</div>
                        <div class="detail-value">${data.title}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Aina ya Kadi:</div>
                        <div class="detail-value">${data.card_type === 'invitation' ? '📨 Mwaliko' : '🤝 Ombi la Mchango'}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Tarehe ya Tukio:</div>
                        <div class="detail-value">${data.event_date}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Saa ya Tukio:</div>
                        <div class="detail-value">${data.event_time || '-'}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Mahali:</div>
                        <div class="detail-value">${data.location}</div>
                    </div>
                    ${data.description ? `
                    <div class="detail-row">
                        <div class="detail-label">Maelezo:</div>
                        <div class="detail-value">${data.description}</div>
                    </div>` : ''}
                </div>
                
                <div class="card-preview">
                    <h6><i class="fas fa-user"></i> Maelezo ya Mwombaji</h6>
                    <div class="detail-row">
                        <div class="detail-label">Jina:</div>
                        <div class="detail-value">${data.user.name}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Simu:</div>
                        <div class="detail-value">${data.user.phone}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Barua Pepe:</div>
                        <div class="detail-value">${data.user.email || '-'}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Tarehe ya Ombi:</div>
                        <div class="detail-value">${data.created_at}</div>
                    </div>
                </div>`;
            
            if (data.card_type === 'invitation' && (data.groom_name || data.bride_name)) {
                html += `
                <div class="card-preview">
                    <h6><i class="fas fa-heart"></i> Maelezo ya Harusi</h6>
                    <div class="detail-row">
                        <div class="detail-label">Bwana Harusi:</div>
                        <div class="detail-value">${data.groom_name || '-'}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Bi Harusi:</div>
                        <div class="detail-value">${data.bride_name || '-'}</div>
                    </div>
                </div>`;
            }
            
            if (data.honoree_name) {
                html += `
                <div class="card-preview">
                    <h6><i class="fas fa-star"></i> Maelezo ya Tukio</h6>
                    <div class="detail-row">
                        <div class="detail-label">Jina la Tukio:</div>
                        <div class="detail-value">${data.honoree_name}</div>
                    </div>
                </div>`;
            }
            
            if (data.suggested_amount) {
                html += `
                <div class="card-preview">
                    <h6><i class="fas fa-money-bill"></i> Maelezo ya Mchango</h6>
                    <div class="detail-row">
                        <div class="detail-label">Kiasho cha Mchango:</div>
                        <div class="detail-value">TSh ${Number(data.suggested_amount).toLocaleString()}</div>
                    </div>
                </div>`;
            }
            
            html += `
                <div class="card-preview">
                    <h6><i class="fas fa-phone"></i> Mawasiliano</h6>
                    <div class="detail-row">
                        <div class="detail-label">Namba ya Simu:</div>
                        <div class="detail-value">${data.contact_phone}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Barua Pepe:</div>
                        <div class="detail-value">${data.contact_email || '-'}</div>
                    </div>
                </div>
                
                <div class="card-preview">
                    <h6><i class="fas fa-chart-line"></i> Takwimu</h6>
                    <div class="detail-row">
                        <div class="detail-label">Idadi ya Kutazamwa:</div>
                        <div class="detail-value">${data.views || 0}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Idadi ya Kushirikiwa:</div>
                        <div class="detail-value">${data.shares || 0}</div>
                    </div>
                </div>`;
            
            if (data.admin_notes) {
                html += `
                <div class="card-preview">
                    <h6><i class="fas fa-comment"></i> Ujumbe wa Msimamizi</h6>
                    <div class="detail-row">
                        <div class="detail-value">${data.admin_notes}</div>
                    </div>
                </div>`;
            }
            
            if (data.design_file_path) {
                html += `
                <div class="card-preview">
                    <h6><i class="fas fa-image"></i> Kadi Iliyobuniwa</h6>
                    <img src="/storage/${data.design_file_path}" class="design-preview" alt="Designed Card">
                </div>`;
            }
            
            document.getElementById('cardDetailsContent').innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('cardDetailsContent').innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-2"></i>
                    <p>Imeshindwa kupata maelezo ya kadi</p>
                </div>`;
        });
}

function processCard(id) {
    document.getElementById('process_card_id').value = id;
    document.getElementById('processCardForm').action = `/admin/cards/${id}/process`;
    document.getElementById('design_fields').style.display = 'none';
}

function confirmDelete(id, name) {
    Swal.fire({
        title: 'Una uhakika?',
        text: `Unataka kufuta ombi la kadi "${name}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ndio, Futa',
        cancelButtonText: 'Ghairi'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('deleteCardForm');
            form.action = `/admin/cards/${id}/delete`;
            form.submit();
        }
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleProcessFields();
});
</script>
@endsection