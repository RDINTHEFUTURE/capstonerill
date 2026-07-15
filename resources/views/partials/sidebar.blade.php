<div class="sidebar-wrapper active">
    <div class="sidebar-header position-relative">
        <div class="d-flex justify-content-between align-items-center">
            <div class="logo">
                <a href="{{ route('home') }}" style="font-weight: bold; color: var(--bs-body-color);">
                    <span class="sidebar-link" style="color: inherit;">Manajemen E-Faktur Penjualan</span>
                </a>
            </div>
            <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--system-uicons" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                    <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2" opacity=".3"></path>
                        <g transform="translate(-210 -1)">
                            <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                            <circle cx="220.5" cy="11.5" r="4"></circle>
                            <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path>
                        </g>
                    </g>
                </svg>
                <div class="form-check form-switch fs-6">
                    <input class="form-check-input me-0" type="checkbox" id="toggle-dark" style="cursor: pointer">
                    <label class="form-check-label"></label>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--mdi" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                    <path fill="currentColor" d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z"></path>
                </svg>
            </div>
            <div class="sidebar-toggler x">
                <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
            </div>
        </div>
    </div>
    <div class="sidebar-menu">
        <ul class="menu">
            {{-- Dashboard --}}
            <li class="sidebar-item {{ request()->routeIs('home') ? 'active' : '' }}">
                <a href="{{ route('home') }}" class="sidebar-link">
                    <i class="bi bi-house-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            {{-- Profile --}}
            <li class="sidebar-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <a href="{{ route('profile.show') }}" class="sidebar-link">
                    <i class="bi bi-person-fill"></i>
                    <span>Profil</span>
                </a>
            </li>

            {{-- Invoices --}}
            <li class="sidebar-item has-sub {{ request()->routeIs('invoices.*') ? 'active open' : '' }}">
                <a href="#" class="sidebar-link" data-bs-toggle="collapse" data-bs-target="#submenu-invoices">
                    <i class="bi bi-grid-fill"></i>
                    <span>Invoices</span>
                    <i class="bi bi-chevron-down float-end"></i>
                </a>
                <ul id="submenu-invoices" class="collapse {{ request()->routeIs('invoices.*') ? 'show' : '' }}">
                    <li class="sidebar-item">
                        <a href="{{ route('invoices.index') }}" class="sidebar-link {{ request()->routeIs('invoices.index') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Daftar Invoice</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('invoices.create') }}" class="sidebar-link {{ request()->routeIs('invoices.create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle"></i>
                            <span>Buat Invoice</span>
                        </a>
                    </li>
                    @if(auth()->check() && auth()->user()->roleLevel() >= 2)
                    <li class="sidebar-item">
                        <a href="{{ route('invoices.index', ['approval_status' => 'pending_review']) }}" class="sidebar-link">
                            <i class="bi bi-clipboard-check"></i>
                            <span>Review Queue</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>

            {{-- Accounting --}}
            <li class="sidebar-item has-sub {{ request()->routeIs('chart-of-accounts.*') || request()->routeIs('ledger.*') || request()->routeIs('reports.import*') ? 'active open' : '' }}">
                <a href="#" class="sidebar-link" data-bs-toggle="collapse" data-bs-target="#submenu-accounting">
                    <i class="bi bi-calculator-fill"></i>
                    <span>Accounting</span>
                    <i class="bi bi-chevron-down float-end"></i>
                </a>
                <ul id="submenu-accounting" class="collapse {{ request()->routeIs('chart-of-accounts.*') || request()->routeIs('ledger.*') || request()->routeIs('reports.import*') ? 'show' : '' }}">
                    <li class="sidebar-item">
                        <a href="{{ route('chart-of-accounts.index') }}" class="sidebar-link {{ request()->routeIs('chart-of-accounts.*') ? 'active' : '' }}">
                            <i class="bi bi-list-columns-reverse"></i>
                            <span>Chart of Accounts</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('ledger.index') }}" class="sidebar-link {{ request()->routeIs('ledger.*') ? 'active' : '' }}">
                            <i class="bi bi-journal-bookmark-fill"></i>
                            <span>General Ledger</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('reports.import-form') }}" class="sidebar-link {{ request()->routeIs('reports.import*') ? 'active' : '' }}">
                            <i class="bi bi-upload"></i>
                            <span>Import / Export</span>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Reports --}}
            <li class="sidebar-item {{ request()->routeIs('reports.sales') ? 'active' : '' }}">
                <a href="{{ route('reports.sales') }}" class="sidebar-link">
                    <i class="bi bi-bar-chart-fill"></i>
                    <span>Sales Report</span>
                </a>
            </li>

            {{-- Users --}}
            <li class="sidebar-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <a href="{{ route('users.index') }}" class="sidebar-link">
                    <i class="bi bi-people-fill"></i>
                    <span>Users</span>
                </a>
            </li>

            {{-- Login Logs (all users) --}}
            <li class="sidebar-item {{ request()->routeIs('activity.login-logs') ? 'active' : '' }}">
                <a href="{{ route('activity.login-logs') }}" class="sidebar-link">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Login Logs</span>
                </a>
            </li>

            {{-- Administration (Admin/Manager only) --}}
            @if(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isManager()))
            <li class="sidebar-item has-sub {{ request()->routeIs('activity.index') || request()->routeIs('admin.password-resets*') ? 'active open' : '' }}">
                <a href="#" class="sidebar-link" data-bs-toggle="collapse" data-bs-target="#submenu-admin">
                    <i class="bi bi-shield-lock-fill"></i>
                    <span>Administration</span>
                    <i class="bi bi-chevron-down float-end"></i>
                </a>
                <ul id="submenu-admin" class="collapse {{ request()->routeIs('activity.index') || request()->routeIs('admin.password-resets*') ? 'show' : '' }}">
                    <li class="sidebar-item">
                        <a href="{{ route('activity.index') }}" class="sidebar-link {{ request()->routeIs('activity.index') ? 'active' : '' }}">
                            <i class="bi bi-clock-history"></i>
                            <span>Activity Log</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('admin.password-resets.index') }}" class="sidebar-link {{ request()->routeIs('admin.password-resets*') ? 'active' : '' }}">
                            <i class="bi bi-key-fill"></i>
                            <span>Password Resets</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif

            {{-- Settings (Admin only) --}}
            @if(auth()->check() && auth()->user()->isAdmin())
            <li class="sidebar-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <a href="{{ route('settings.company') }}" class="sidebar-link">
                    <i class="bi bi-gear-fill"></i>
                    <span>Settings</span>
                </a>
            </li>
            @endif

            {{-- Logout --}}
            <li class="sidebar-item">
                <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: inline; width: 100%;">
                    @csrf
                    <button type="submit" class="sidebar-link logout-action" style="border: none; background: none; width: 100%; text-align: left; cursor: pointer; padding: 0.5rem 1rem; display: flex; align-items: center; gap: 0.75rem;">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <style>
        .sidebar-menu {
            max-height: calc(100vh - 180px);
            overflow-y: auto;
            overflow-x: hidden;
        }
        .sidebar-menu::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-menu::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 4px;
        }
        .sidebar-menu .menu .has-sub > .sidebar-link .bi-chevron-down {
            transition: transform 0.2s ease;
        }
        .sidebar-menu .menu .has-sub.active.open > .sidebar-link .bi-chevron-down {
            transform: rotate(180deg);
        }
        .sidebar-menu .menu ul.collapse .sidebar-link {
            padding-left: 2.5rem;
            font-size: 0.875rem;
        }
        .sidebar-menu .menu ul.collapse .sidebar-link.active {
            color: var(--bs-primary);
            font-weight: 600;
        }
        .sidebar-link {
            transition: all 0.2s ease-out;
        }
        .sidebar-link:hover {
            background: rgba(99, 102, 241, 0.08);
            color: var(--bs-primary);
        }
        .sidebar-item.active > .sidebar-link {
            border-left: 3px solid var(--bs-primary);
            padding-left: calc(1rem - 3px);
        }
        #logoutModal.modal { position: fixed; inset: 0; z-index: 1050; display: flex; align-items: center; justify-content: center; }
        #logoutModal .modal-dialog { margin: 0; }
        #logoutModal[style*="display:none"] { display: none !important; }
        #logoutModal.show { display:flex !important; }
        #logoutModal::before { content: ''; position: absolute; inset: 0; background: rgba(15,23,42,0.6); pointer-events: none; }
        #logoutModal .modal-content { position: relative; z-index: 2; }
        #logoutModal.show .modal-content {
            animation: scaleIn 0.2s ease-out;
        }
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</div>

<div id="logoutModal" class="modal" tabindex="-1" role="dialog" style="display:none;">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:420px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Logout</h5>
                <button type="button" class="close" aria-label="Close" id="logoutModalClose">&times;</button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Apakah Anda yakin ingin keluar dari sesi ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="logoutCancel">Batal</button>
                <button type="button" class="btn btn-danger" id="logoutConfirm">Keluar</button>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        var logoutForm = document.getElementById('logout-form');
        var modal = document.getElementById('logoutModal');
        if (!logoutForm || !modal) return;

        logoutForm.addEventListener('submit', function (e) {
            e.preventDefault();
            modal.classList.add('show');
            modal.style.display = 'flex';
        });

        if (modal._bound) return;
        modal._bound = true;

        var btnClose = document.getElementById('logoutModalClose');
        var btnCancel = document.getElementById('logoutCancel');
        var btnConfirm = document.getElementById('logoutConfirm');

        function hideModal() {
            modal.classList.remove('show');
            modal.style.display = 'none';
        }

        btnClose && btnClose.addEventListener('click', hideModal);
        btnCancel && btnCancel.addEventListener('click', hideModal);

        btnConfirm && btnConfirm.addEventListener('click', function () {
            logoutForm.submit();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.classList.contains('show')) {
                hideModal();
            }
        });
    })();
</script>
