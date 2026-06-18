<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        :root {
            --primary: #4f46e5;
            --accent-navy: #1e3a8a;
            --background: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --radius: 16px;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: system-ui, -apple-system, sans-serif; }
        body { background-color: var(--background); color: var(--text-main); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .container { background-color: var(--card-bg); border-radius: var(--radius); box-shadow: 0 15px 35px rgba(0,0,0,0.08); padding: 40px; width: 100%; max-width: 400px; }
        h1 { font-size: 24px; font-weight: 700; margin-bottom: 8px; }
        p { color: var(--text-muted); font-size: 14px; margin-bottom: 24px; }
        .form-group { margin-bottom: 18px; }
        .form-group label { font-size: 13px; font-weight: 600; display: block; margin-bottom: 6px; }
        .form-group input { width: 100%; padding: 12px 14px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; outline: none; }
        .form-group input:focus { border-color: var(--accent-navy); box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1); }
        .btn { width: 100%; border-radius: 8px; border: none; background-color: var(--accent-navy); color: #ffffff; font-size: 15px; font-weight: 600; padding: 12px; cursor: pointer; }
        .btn:hover { background-color: #172554; }
        .back-link { display: block; text-align: center; margin-top: 16px; color: var(--accent-navy); text-decoration: none; font-size: 14px; }
        .alert { padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; }
        .alert-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .status-icon { text-align: center; font-size: 48px; margin-bottom: 16px; }
        .status-badge { display: inline-block; padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; margin-bottom: 16px; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-approved { background: #dcfce7; color: #166534; }
        .badge-rejected { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="container">
        @if($request->status === 'pending')
            <div class="status-icon">⏳</div>
            <h1>Menunggu Persetujuan</h1>
            <div style="text-align:center">
                <span class="status-badge badge-pending">Menunggu</span>
            </div>
            <p>Permintaan reset password Anda untuk <strong>{{ $request->user->email }}</strong> sedang menunggu persetujuan dari admin.</p>

            <div style="background:#fef3c7;border:1px solid #fcd34d;border-radius:8px;padding:12px;margin-bottom:16px;font-size:13px;color:#92400e;">
                ⚠️ <strong>Penting:</strong> Simpan tautan ini sekarang! Tautan ini tidak akan ditampilkan kembali setelah Anda meninggalkan halaman ini.
            </div>

            <div style="display:flex;gap:8px;margin-bottom:16px;">
                <input type="text" id="resetLink" value="{{ route('password-recovery.show', $token) }}" readonly style="flex:1;padding:10px 12px;border:1px solid var(--border);border-radius:8px;font-size:13px;background:#f8fafc;">
                <button onclick="copyLink()" id="copyBtn" style="padding:10px 16px;border:none;border-radius:8px;background:var(--accent-navy);color:#fff;font-size:13px;font-weight:600;cursor:pointer;white-space:nowrap;">Salin</button>
            </div>

            <a href="{{ route('password-recovery.show', $token) }}" class="btn" style="text-align:center;text-decoration:none;display:block;margin-top:8px;">Segarkan Halaman</a>
            <a href="{{ route('login') }}" class="back-link">Kembali ke Login</a>

            <script>
                function copyLink() {
                    var input = document.getElementById('resetLink');
                    input.select();
                    input.setSelectionRange(0, 99999);
                    navigator.clipboard.writeText(input.value).then(function() {
                        var btn = document.getElementById('copyBtn');
                        btn.textContent = 'Tersalin!';
                        btn.style.background = '#16a34a';
                        setTimeout(function() {
                            btn.textContent = 'Salin';
                            btn.style.background = '';
                        }, 2000);
                    });
                }
            </script>

        @elseif($request->status === 'approved')
            <div class="status-icon">✅</div>
            <h1>Reset Password</h1>
            <div style="text-align:center">
                <span class="status-badge badge-approved">Disetujui</span>
            </div>
            <p>Masukkan password baru Anda untuk akun: <strong>{{ $request->user->email }}</strong></p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password-recovery.update', $token) }}">
                @csrf
                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <input type="password" id="password" name="password" placeholder="Min. 4 karakter" required>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password" required>
                </div>
                <button class="btn" type="submit">Simpan Password Baru</button>
            </form>

        @else
            <div class="status-icon">❌</div>
            <h1>Permintaan Ditolak</h1>
            <div style="text-align:center">
                <span class="status-badge badge-rejected">Ditolak</span>
            </div>
            <p>Permintaan reset password Anda untuk <strong>{{ $request->user->email }}</strong> telah ditolak oleh admin.</p>
            <p>Silakan hubungi administrator untuk informasi lebih lanjut.</p>
            <a href="{{ route('login') }}" class="back-link">Kembali ke Login</a>
        @endif
    </div>
</body>
</html>
