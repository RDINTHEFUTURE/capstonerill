<div class="page-header">
    <div class="page-title">
        <h3>@yield('page-title', '')</h3>
    </div>
    <div class="page-meta d-flex align-items-center gap-3">
        @auth
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center gap-2 text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                    @if(auth()->user()->avatar)
                        <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="rounded-circle" style="width:36px;height:36px;object-fit:cover;">
                    @else
                        <div class="avatar avatar-sm rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:36px;height:36px;font-size:14px;font-weight:600;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="d-none d-md-block">
                        <span class="fw-semibold">{{ auth()->user()->name }}</span>
                        <small class="text-muted d-block" style="font-size:0.75rem;">{{ auth()->user()->role }}</small>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.show') }}">
                            <i class="bi bi-person me-2"></i>Profil Saya
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <button type="button" class="dropdown-item text-danger header-logout-btn">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </button>
                    </li>
                </ul>
            </div>
        @endauth
    </div>
</div>

<script>
    (function () {
        var headerLogoutBtn = document.querySelector('.header-logout-btn');
        var logoutForm = document.getElementById('logout-form');
        var modal = document.getElementById('logoutModal');
        if (!headerLogoutBtn || !modal) return;

        headerLogoutBtn.addEventListener('click', function (e) {
            e.preventDefault();
            modal.classList.add('show');
            modal.style.display = 'flex';
        });

        if (modal._headerBound) return;
        modal._headerBound = true;

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
            if (logoutForm) logoutForm.submit();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.classList.contains('show')) {
                hideModal();
            }
        });
    })();
</script>
