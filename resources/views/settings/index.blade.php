@extends('layouts.app')

@section('title', 'Mipangilio')
@section('page_title', 'Mipangilio ya Mfumo')

@push('styles')
<style>
    /* ── layout ── */
    .settings-wrap { display: flex; gap: 20px; align-items: flex-start; }

    .settings-sidebar {
        width: 200px;
        flex-shrink: 0;
        background: #fff;
        border: 1px solid var(--gray-200);
        border-radius: 12px;
        overflow: hidden;
        position: sticky;
        top: calc(var(--topbar-h, 64px) + 16px);
    }

    .stab {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        font-size: .88rem;
        font-weight: 500;
        color: var(--gray-700);
        cursor: pointer;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        border-left: 3px solid transparent;
        transition: background .15s, color .15s;
    }

    .stab i { width: 16px; color: var(--gray-600); flex-shrink: 0; }
    .stab:hover { background: var(--gray-100); color: var(--primary); }
    .stab.active { background: rgba(255,111,0,.07); color: var(--primary); border-left-color: var(--primary); }
    .stab.active i { color: var(--primary); }

    .settings-main { flex: 1; min-width: 0; }

    .spanel { display: none; }
    .spanel.active { display: block; }

    /* ── card ── */
    .scard {
        background: #fff;
        border: 1px solid var(--gray-200);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 16px;
    }

    .scard-head {
        padding: 16px 20px;
        border-bottom: 1px solid var(--gray-200);
    }

    .scard-head h6 { font-size: .95rem; font-weight: 700; margin: 0 0 2px; }
    .scard-head p  { font-size: .8rem; color: var(--gray-600); margin: 0; }
    .scard-body { padding: 20px; }

    /* ── form elements ── */
    .field-label {
        display: block;
        font-size: .82rem;
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 6px;
    }

    .field-hint { font-size: .75rem; color: var(--gray-600); margin-top: 4px; }

    .form-control, .form-select {
        border: 1px solid var(--gray-200);
        border-radius: 8px;
        font-size: .88rem;
        padding: 9px 12px;
        color: var(--gray-700);
        width: 100%;
        transition: border-color .15s, box-shadow .15s;
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(255,111,0,.1);
    }

    /* ── theme cards ── */
    .theme-grid { display: flex; gap: 10px; flex-wrap: wrap; }

    .theme-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        padding: 14px 18px;
        border: 1.5px solid var(--gray-200);
        border-radius: 10px;
        cursor: pointer;
        transition: all .15s;
        min-width: 90px;
        user-select: none;
    }

    .theme-card i { font-size: 1.5rem; }
    .theme-card span { font-size: .78rem; font-weight: 600; }
    .theme-card.light-card { background: #fff; color: #334155; }
    .theme-card.dark-card  { background: #1e293b; color: #f1f5f9; border-color: #334155; }
    .theme-card.auto-card  { background: linear-gradient(135deg, #fff 50%, #1e293b 50%); color: var(--primary); }
    .theme-card.selected   { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(255,111,0,.15); }

    /* ── layout cards ── */
    .layout-grid { display: flex; gap: 10px; flex-wrap: wrap; }

    .layout-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        padding: 12px 16px;
        border: 1.5px solid var(--gray-200);
        border-radius: 10px;
        cursor: pointer;
        transition: all .15s;
        min-width: 90px;
        background: #fff;
        user-select: none;
    }

    .layout-card i { font-size: 1.2rem; color: var(--gray-600); }
    .layout-card span { font-size: .78rem; font-weight: 600; color: var(--gray-700); }
    .layout-card:hover { border-color: var(--primary); }
    .layout-card.selected { border-color: var(--primary); background: rgba(255,111,0,.06); box-shadow: 0 0 0 3px rgba(255,111,0,.12); }
    .layout-card.selected i, .layout-card.selected span { color: var(--primary); }

    /* ── toggle row ── */
    .toggle-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 14px 16px;
        background: var(--gray-100);
        border-radius: 10px;
        margin-bottom: 10px;
    }

    .toggle-row:last-child { margin-bottom: 0; }

    .toggle-info strong { font-size: .88rem; display: block; }
    .toggle-info small  { font-size: .75rem; color: var(--gray-600); }

    .form-switch .form-check-input { width: 40px; height: 22px; cursor: pointer; }
    .form-check-input:checked { background-color: var(--primary); border-color: var(--primary); }

    /* ── save btn ── */
    .btn-save {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 22px;
        background: var(--primary);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: .88rem;
        font-weight: 600;
        cursor: pointer;
        transition: background .15s, transform .1s;
    }

    .btn-save:hover { background: var(--primary-dark); }
    .btn-save:active { transform: scale(.98); }

    .btn-reset {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 22px;
        background: #fff;
        color: var(--gray-700);
        border: 1px solid var(--gray-300);
        border-radius: 8px;
        font-size: .88rem;
        font-weight: 500;
        cursor: pointer;
        transition: background .15s;
    }

    .btn-reset:hover { background: var(--gray-100); }

    .form-actions { display: flex; justify-content: flex-end; padding-top: 8px; }

    /* ── section divider ── */
    .sdivider { height: 1px; background: var(--gray-200); margin: 20px 0; }

    /* ── dark mode (applied to <html>) ── */
    html.dark-mode {
        --gray-100: #1e293b;
        --gray-200: #334155;
        --gray-300: #475569;
        --gray-600: #94a3b8;
        --gray-700: #e2e8f0;
    }

    html.dark-mode body { background: #0f172a; color: #e2e8f0; }
    html.dark-mode .scard,
    html.dark-mode .settings-sidebar,
    html.dark-mode .theme-card.light-card,
    html.dark-mode .layout-card { background: #1e293b; }
    html.dark-mode .toggle-row { background: #0f172a; }

    /* ── mobile ── */
    @media (max-width: 768px) {
        .settings-wrap { flex-direction: column; }
        .settings-sidebar { width: 100%; position: static; display: flex; overflow-x: auto; border-radius: 10px; }
        .stab { border-left: none; border-bottom: 3px solid transparent; white-space: nowrap; padding: 10px 14px; }
        .stab.active { border-bottom-color: var(--primary); border-left: none; }
        .scard-body { padding: 16px; }
    }
</style>
@endpush

@section('content')
<div class="settings-wrap">

    {{-- ── sidebar tabs ── --}}
    <div class="settings-sidebar">
        <button class="stab active" onclick="switchTab('appearance', this)">
            <i class="fas fa-palette"></i> Mwonekano
        </button>
        <button class="stab" onclick="switchTab('language', this)">
            <i class="fas fa-language"></i> Lugha na Eneo
        </button>
        <button class="stab" onclick="switchTab('notifications', this)">
            <i class="fas fa-bell"></i> Arifa
        </button>
        <button class="stab" onclick="switchTab('preferences', this)">
            <i class="fas fa-sliders-h"></i> Mapendeleo
        </button>
    </div>

    {{-- ── panels ── --}}
    <div class="settings-main">

        {{-- ── APPEARANCE ── --}}
        <div class="spanel active" id="panel-appearance">
            <form method="POST" action="{{ route('settings.update') }}" id="appearanceForm">
                @csrf @method('PUT')

                <div class="scard">
                    <div class="scard-head">
                        <h6>Mandhari (Theme)</h6>
                        <p>Badilisha rangi ya mfumo</p>
                    </div>
                    <div class="scard-body">
                        <input type="hidden" name="theme" id="themeInput" value="{{ $settings['theme'] ?? 'light' }}">
                        <div class="theme-grid">
                            <div class="theme-card light-card {{ ($settings['theme'] ?? 'light') == 'light' ? 'selected' : '' }}"
                                 data-theme="light" onclick="selectTheme('light')">
                                <i class="fas fa-sun"></i><span>Mwanga</span>
                            </div>
                            <div class="theme-card dark-card {{ ($settings['theme'] ?? '') == 'dark' ? 'selected' : '' }}"
                                 data-theme="dark" onclick="selectTheme('dark')">
                                <i class="fas fa-moon"></i><span>Giza</span>
                            </div>
                            <div class="theme-card auto-card {{ ($settings['theme'] ?? '') == 'auto' ? 'selected' : '' }}"
                                 data-theme="auto" onclick="selectTheme('auto')">
                                <i class="fas fa-circle-half-stroke"></i><span>Otomatiki</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="scard">
                    <div class="scard-head">
                        <h6>Mwonekano wa Kompakt</h6>
                        <p>Onyesha maelezo machache kwenye orodha</p>
                    </div>
                    <div class="scard-body">
                        <div class="toggle-row">
                            <div class="toggle-info">
                                <strong>Kompakt</strong>
                                <small>Punguza nafasi kati ya vipengee</small>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="compact_view" id="compactView"
                                       {{ ($settings['compact_view'] ?? false) ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-save"><i class="fas fa-save"></i> Hifadhi</button>
                </div>
            </form>
        </div>

        {{-- ── LANGUAGE ── --}}
        <div class="spanel" id="panel-language">
            <form method="POST" action="{{ route('settings.update') }}" id="languageForm">
                @csrf @method('PUT')

                <div class="scard">
                    <div class="scard-head">
                        <h6>Lugha na Eneo</h6>
                        <p>Weka mapendeleo ya lugha na eneo lako</p>
                    </div>
                    <div class="scard-body">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="field-label">Lugha</label>
                                <select name="language" id="languageSelect" class="form-select"
                                        onchange="applyLanguage(this.value)">
                                    <option value="sw" {{ ($settings['language'] ?? 'sw') == 'sw' ? 'selected' : '' }}>Kiswahili</option>
                                    <option value="en" {{ ($settings['language'] ?? '') == 'en' ? 'selected' : '' }}>English</option>
                                </select>
                                <div class="field-hint">Mabadiliko yatatumika baada ya kuhifadhi</div>
                            </div>

                            <div class="col-sm-6">
                                <label class="field-label">Sarafu</label>
                                <select name="currency" class="form-select">
                                    <option value="TZS" {{ ($settings['currency'] ?? 'TZS') == 'TZS' ? 'selected' : '' }}>Tanzania Shilingi (TZS)</option>
                                    <option value="USD" {{ ($settings['currency'] ?? '') == 'USD' ? 'selected' : '' }}>US Dollar (USD)</option>
                                    <option value="EUR" {{ ($settings['currency'] ?? '') == 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
                                    <option value="GBP" {{ ($settings['currency'] ?? '') == 'GBP' ? 'selected' : '' }}>British Pound (GBP)</option>
                                </select>
                            </div>

                            <div class="col-sm-6">
                                <label class="field-label">Timezone</label>
                                <select name="timezone" class="form-select">
                                    <option value="Africa/Dar_es_Salaam" {{ ($settings['timezone'] ?? 'Africa/Dar_es_Salaam') == 'Africa/Dar_es_Salaam' ? 'selected' : '' }}>Dar es Salaam (UTC+3)</option>
                                    <option value="Africa/Nairobi"       {{ ($settings['timezone'] ?? '') == 'Africa/Nairobi'       ? 'selected' : '' }}>Nairobi (UTC+3)</option>
                                    <option value="Africa/Kampala"       {{ ($settings['timezone'] ?? '') == 'Africa/Kampala'       ? 'selected' : '' }}>Kampala (UTC+3)</option>
                                    <option value="Africa/Johannesburg"  {{ ($settings['timezone'] ?? '') == 'Africa/Johannesburg'  ? 'selected' : '' }}>Johannesburg (UTC+2)</option>
                                </select>
                            </div>

                            <div class="col-sm-3">
                                <label class="field-label">Mfumo wa Tarehe</label>
                                <select name="date_format" class="form-select">
                                    <option value="d/m/Y" {{ ($settings['date_format'] ?? 'd/m/Y') == 'd/m/Y' ? 'selected' : '' }}>31/12/2024</option>
                                    <option value="m/d/Y" {{ ($settings['date_format'] ?? '') == 'm/d/Y' ? 'selected' : '' }}>12/31/2024</option>
                                    <option value="Y-m-d" {{ ($settings['date_format'] ?? '') == 'Y-m-d' ? 'selected' : '' }}>2024-12-31</option>
                                </select>
                            </div>

                            <div class="col-sm-3">
                                <label class="field-label">Mfumo wa Saa</label>
                                <select name="time_format" class="form-select">
                                    <option value="24" {{ ($settings['time_format'] ?? '24') == '24' ? 'selected' : '' }}>24-saa (14:30)</option>
                                    <option value="12" {{ ($settings['time_format'] ?? '') == '12' ? 'selected' : '' }}>12-saa (2:30 PM)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-save"><i class="fas fa-save"></i> Hifadhi</button>
                </div>
            </form>
        </div>

        {{-- ── NOTIFICATIONS ── --}}
        <div class="spanel" id="panel-notifications">
            <form method="POST" action="{{ route('settings.update') }}" id="notificationsForm">
                @csrf @method('PUT')

                <div class="scard">
                    <div class="scard-head">
                        <h6>Arifa za Barua Pepe</h6>
                        <p>Weka barua pepe na uchague arifa za kupokea</p>
                    </div>
                    <div class="scard-body">
                        <div class="toggle-row">
                            <div class="toggle-info">
                                <strong>Arifa za Matukio</strong>
                                <small>Pokea arifa kuhusu matukio yako</small>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="email_alerts" id="emailAlerts"
                                       {{ ($settings['email_alerts'] ?? true) ? 'checked' : '' }}>
                            </div>
                        </div>

                        <div class="sdivider"></div>

                        <label class="field-label" for="notifEmail">Barua Pepe ya Arifa</label>
                        <input type="email" name="notification_email" id="notifEmail"
                               class="form-control"
                               value="{{ $settings['notification_email'] ?? ($user->email ?? '') }}">
                        <div class="field-hint">Barua pepe itakayotumika kupokea arifa</div>
                    </div>
                </div>

                <div class="scard">
                    <div class="scard-head">
                        <h6>Arifa za SMS na Mfumo</h6>
                    </div>
                    <div class="scard-body">
                        <div class="toggle-row">
                            <div class="toggle-info">
                                <strong>Arifa za SMS</strong>
                                <small>Pokea arifa kwa namba yako ya simu</small>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="sms_alerts" id="smsAlerts"
                                       {{ ($settings['sms_alerts'] ?? false) ? 'checked' : '' }}>
                            </div>
                        </div>
                        <div class="toggle-row">
                            <div class="toggle-info">
                                <strong>Arifa za Mfumo</strong>
                                <small>Pokea arifa ndani ya mfumo</small>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="push_notifications" id="pushNotifications"
                                       {{ ($settings['push_notifications'] ?? true) ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-save"><i class="fas fa-save"></i> Hifadhi</button>
                </div>
            </form>
        </div>

        {{-- ── PREFERENCES ── --}}
        <div class="spanel" id="panel-preferences">
            <form method="POST" action="{{ route('settings.update') }}" id="preferencesForm">
                @csrf @method('PUT')

                <div class="scard">
                    <div class="scard-head">
                        <h6>Vipengee kwa Ukurasa</h6>
                        <p>Idadi ya rekodi kuonyeshwa kwenye orodha</p>
                    </div>
                    <div class="scard-body">
                        <select name="items_per_page" class="form-select" style="max-width:180px;">
                            @foreach([10,15,25,50,100] as $n)
                                <option value="{{ $n }}" {{ ($settings['items_per_page'] ?? 15) == $n ? 'selected' : '' }}>{{ $n }} kwa ukurasa</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="scard">
                    <div class="scard-head">
                        <h6>Mpangilio wa Dashboard</h6>
                    </div>
                    <div class="scard-body">
                        <input type="hidden" name="dashboard_layout" id="layoutInput" value="{{ $settings['dashboard_layout'] ?? 'default' }}">
                        <div class="layout-grid">
                            <div class="layout-card {{ ($settings['dashboard_layout'] ?? 'default') == 'default' ? 'selected' : '' }}"
                                 data-layout="default" onclick="selectLayout('default')">
                                <i class="fas fa-th-large"></i><span>Chaguo-msingi</span>
                            </div>
                            <div class="layout-card {{ ($settings['dashboard_layout'] ?? '') == 'compact' ? 'selected' : '' }}"
                                 data-layout="compact" onclick="selectLayout('compact')">
                                <i class="fas fa-th"></i><span>Kompakt</span>
                            </div>
                            <div class="layout-card {{ ($settings['dashboard_layout'] ?? '') == 'detailed' ? 'selected' : '' }}"
                                 data-layout="detailed" onclick="selectLayout('detailed')">
                                <i class="fas fa-list"></i><span>Maelezo</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions" style="gap:10px;">
                    <button type="button" class="btn-reset" onclick="confirmReset()">
                        <i class="fas fa-undo"></i> Rejesha Msingi
                    </button>
                    <button type="submit" class="btn-save"><i class="fas fa-save"></i> Hifadhi</button>
                </div>
            </form>

            <form method="POST" action="{{ route('settings.reset') }}" id="resetForm" style="display:none;">
                @csrf
            </form>
        </div>

    </div>{{-- /settings-main --}}
</div>
@endsection

@push('scripts')
<script>
    /* ── tab switching ── */
    function switchTab(id, btn) {
        document.querySelectorAll('.stab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.spanel').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('panel-' + id).classList.add('active');
        localStorage.setItem('settingsTab', id);
    }

    /* ── theme ── */
    function selectTheme(val) {
        document.getElementById('themeInput').value = val;
        document.querySelectorAll('.theme-card').forEach(c => c.classList.remove('selected'));
        document.querySelector(`.theme-card[data-theme="${val}"]`).classList.add('selected');
        applyTheme(val);
        localStorage.setItem('theme', val);
    }

    function applyTheme(val) {
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const dark = val === 'dark' || (val === 'auto' && prefersDark);
        document.documentElement.classList.toggle('dark-mode', dark);
    }

    /* ── layout ── */
    function selectLayout(val) {
        document.getElementById('layoutInput').value = val;
        document.querySelectorAll('.layout-card').forEach(c => c.classList.remove('selected'));
        document.querySelector(`.layout-card[data-layout="${val}"]`).classList.add('selected');
    }

    /* ── language preview ── */
    function applyLanguage(lang) {
        // Immediately swap visible UI labels so user sees instant feedback.
        // Full server-side switch happens on form submit (Laravel locale).
        const labels = {
            sw: {
                saveBtn: 'Hifadhi',
                themeLabel: 'Mandhari (Theme)',
                langLabel: 'Lugha na Eneo',
            },
            en: {
                saveBtn: 'Save',
                themeLabel: 'Theme',
                langLabel: 'Language & Region',
            }
        };
        const t = labels[lang] || labels.sw;
        document.querySelectorAll('.btn-save').forEach(b => {
            b.innerHTML = `<i class="fas fa-save"></i> ${t.saveBtn}`;
        });
        localStorage.setItem('language', lang);
    }

    /* ── reset ── */
    function confirmReset() {
        Swal.fire({
            title: 'Una uhakika?',
            text: 'Mipangilio yote itarejeshwa kwenye chaguo-msingi!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#FF6F00',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ndio, Rejesha',
            cancelButtonText: 'Ghairi'
        }).then(r => { if (r.isConfirmed) document.getElementById('resetForm').submit(); });
    }

    /* ── show success toast on redirect ── */
    @if(session('success'))
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({ toast: true, position: 'top-end', icon: 'success',
                title: '{{ session('success') }}', showConfirmButton: false, timer: 2500 });
        });
    @endif

    /* ── restore state on load ── */
    document.addEventListener('DOMContentLoaded', () => {
        // restore active tab
        const savedTab = localStorage.getItem('settingsTab');
        if (savedTab) {
            const btn = document.querySelector(`.stab[onclick*="${savedTab}"]`);
            if (btn) switchTab(savedTab, btn);
        }

        // apply saved theme immediately (before server round-trip)
        const savedTheme = localStorage.getItem('theme') || '{{ $settings["theme"] ?? "light" }}';
        applyTheme(savedTheme);

        // sync OS dark-mode changes when theme is auto
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            if (localStorage.getItem('theme') === 'auto') applyTheme('auto');
        });
    });
</script>
@endpush