<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Modern Navy</title>
  <style>
    :root {
      --primary: #4f46e5;
      --primary-hover: #4338ca;
      --accent-navy: #1e3a8a; /* Warna Navy Utama */
      --background: #f8fafc;
      --card-bg: #ffffff;
      --text-main: #0f172a;
      --text-muted: #64748b;
      --border: #e2e8f0;
      --radius: 16px;
      --transition: all 0.3s ease;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: system-ui, -apple-system, sans-serif;
    }

    body {
      background-color: var(--background);
      color: var(--text-main);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    /* Container Utama dengan Split Layout */
    .container {
      background-color: var(--card-bg);
      border-radius: var(--radius);
      box-shadow: 0 15px 35px rgba(0,0,0,0.08);
      position: relative;
      overflow: hidden;
      width: 768px;
      max-width: 100%;
      min-height: 480px;
      display: flex;
    }

    /* Sisi Kiri: Form Login */
    .login-section {
      width: 50%;
      padding: 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    /* Sisi Kanan: Panel Dekorasi Navy */
    .navy-panel {
      width: 50%;
      background: var(--accent-navy);
      color: #ffffff;
      padding: 40px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      position: relative;
    }

    /* Efek Dekorasi Lingkaran Halus di Panel Navy */
    .navy-panel::before {
      content: '';
      position: absolute;
      top: -50px;
      right: -50px;
      width: 200px;
      height: 200px;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 50%;
    }

    /* Tipografi */
    h1 {
      font-size: 26px;
      font-weight: 700;
      margin-bottom: 8px;
    }

    .navy-panel h1 {
      color: #ffffff;
    }

    p {
      color: var(--text-muted);
      font-size: 14px;
      margin-bottom: 24px;
    }

    .navy-panel p {
      color: rgba(255, 255, 255, 0.8);
      line-height: 1.5;
    }

    /* Form Input */
    .form-group {
      margin-bottom: 18px;
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .form-group label {
      font-size: 13px;
      font-weight: 600;
    }

    .form-group input {
      width: 100%;
      padding: 12px 14px;
      border: 1px solid var(--border);
      border-radius: 8px;
      font-size: 14px;
      outline: none;
      transition: var(--transition);
    }

    .form-group input:focus {
      border-color: var(--accent-navy);
      box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    /* Opsi Tambahan Form */
    .form-options {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 24px;
      font-size: 13px;
    }

    .remember-me {
      display: flex;
      align-items: center;
      gap: 6px;
      color: var(--text-muted);
      cursor: pointer;
    }

    .forgot-link {
      color: var(--accent-navy);
      text-decoration: none;
      font-weight: 500;
    }

    .forgot-link:hover {
      text-decoration: underline;
    }

    /* Tombol Utama */
    .btn {
      width: 100%;
      border-radius: 8px;
      border: none;
      background-color: var(--accent-navy);
      color: #ffffff;
      font-size: 15px;
      font-weight: 600;
      padding: 12px;
      cursor: pointer;
      transition: var(--transition);
    }

    .btn:hover {
      background-color: #172554; /* Navy lebih tua saat di-hover */
      transform: translateY(-1px);
    }

    .btn:active {
      transform: translateY(0);
    }

    /* Responsif untuk Smartphone */
    @media (max-width: 768px) {
      .container {
        width: 100%;
        max-width: 400px;
        flex-direction: column;
        min-height: auto;
      }
      .login-section {
        width: 100%;
        padding: 32px 24px;
      }
      .navy-panel {
        display: none; /* Menyembunyikan panel dekorasi di layar hp agar ringkas */
      }
    }
  </style>
</head>
<body>

  <div class="container">
    
    <div class="login-section">
      <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <h1>Selamat Datang</h1>
        <p>Silakan masukkan akun Anda untuk melanjutkan</p>
        
        @if ($errors->any())
          <div style="background-color: #fee2e2; border: 1px solid #fecaca; border-radius: 8px; padding: 12px; margin-bottom: 18px; font-size: 13px; color: #dc2626;">
            @foreach ($errors->all() as $error)
              <div>{{ $error }}</div>
            @endforeach
          </div>
        @endif
        
        <div class="form-group">
          <label for="email">Alamat Email</label>
          <input type="email" id="email" name="email" placeholder="nama@domain.com" value="{{ old('email') }}" required>
        </div>
        
        <div class="form-group">
          <label for="password">Kata Sandi</label>
          <input type="password" id="password" name="password" placeholder="••••••••" required>
        </div>
        
        <div class="form-options">
          <label class="remember-me">
            <input type="checkbox" name="remember"> Ingat saya
          </label>
          <a href="#" class="forgot-link">Lupa kata sandi?</a>
        </div>
        
        <button class="btn" type="submit">Masuk Sekarang</button>
      </form>
    </div>

    <div class="navy-panel">
      <h1>Portal Enterprise</h1>
      <p>Akses dasbor aman Anda. Kelola data, pantau performa, dan tingkatkan produktivitas dalam satu platform terintegrasi.</p>
    </div>

  </div>

</body>
</html>

