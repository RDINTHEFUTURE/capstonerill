<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi</title>
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
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .alert-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Lupa Kata Sandi</h1>
        <p>Masukkan email Anda untuk mengajukan reset password. Permintaan akan diverifikasi oleh admin.</p>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('forgot-password.submit') }}">
            @csrf
            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" id="email" name="email" placeholder="nama@domain.com" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label for="reason">Alasan Reset Password</label>
                <textarea id="reason" name="reason" rows="3" placeholder="Jelaskan alasan Anda meminta reset password..." required style="width:100%; padding:12px 14px; border:1px solid var(--border); border-radius:8px; font-size:14px; outline:none; resize:vertical;">{{ old('reason') }}</textarea>
            </div>
            <button class="btn" type="submit">Kirim Permintaan</button>
        </form>

        <a href="{{ route('login') }}" class="back-link">Kembali ke Login</a>
    </div>
</body>
</html>
