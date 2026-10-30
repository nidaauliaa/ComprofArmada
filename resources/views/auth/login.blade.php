<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>Admin Login – New Armada</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root {
  --blue-dark: #12274d;
  --blue-primary: #0c4a7a;
  --blue-sky: #0a7bb8;
  --blue-light: #e0f2fe;
  --bg-wavy: #bae6fd;
  --red-accent: #ff5733;
  --white: #ffffff;
  --ink-primary: #0f172a;
  --ink-muted: #64748b;
  --line-color: #e2e8f0;
}

*, *::before, *::after { box-sizing: border-box; }

body {
  margin: 0;
  font-family: 'Barlow', system-ui, sans-serif;
  color: var(--ink-primary);
  min-height: 100vh;
  display: grid;
  place-items: center;
  padding: 24px;
  background-color: #f0f9ff;
  /* Latar belakang dengan elemen gelombang (wavy shapes) seperti gambar referensi */
  background-image:
    radial-gradient(circle at 10% 20%, rgba(186, 230, 253, 0.8) 0%, transparent 40%),
    radial-gradient(circle at 90% 80%, rgba(186, 230, 253, 0.9) 0%, transparent 50%),
    radial-gradient(circle at 50% 100%, rgba(125, 211, 252, 0.4) 0%, transparent 60%);
  position: relative;
  overflow-x: hidden;
}

/* Kartu Utama Split-Screen */
.login-card {
  display: grid;
  grid-template-columns: 1fr 1.2fr;
  width: min(1040px, 100%);
  min-height: 560px;
  background: var(--white);
  border-radius: 28px;
  box-shadow: 0 25px 50px -12px rgba(12, 74, 122, 0.18);
  overflow: hidden;
  position: relative;
  z-index: 10;
  border: 1px solid rgba(255, 255, 255, 0.8);
}

/* SISI KIRI: FORM LOGIN */
.form-side {
  padding: 48px 44px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.brand-logo {
  font: 800 24px/1 'Barlow Condensed', sans-serif;
  letter-spacing: .04em;
  display: flex;
  align-items: center;
  gap: 10px;
  color: var(--blue-dark);
  margin-bottom: 36px;
}

.brand-icon {
  width: 14px;
  height: 20px;
  background: var(--red-accent);
  transform: skewX(-18deg);
  border-radius: 2px;
}

.form-side h1 {
  font: 800 clamp(28px, 3vw, 36px)/1.1 'Barlow Condensed', sans-serif;
  color: var(--blue-dark);
  text-transform: uppercase;
  margin: 0 0 8px;
}

.form-side h1 span {
  color: var(--red-accent);
}

.subtitle {
  margin: 0 0 28px;
  color: var(--ink-muted);
  font-size: 15px;
  line-height: 1.5;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  font-size: 13px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .06em;
  color: var(--ink-muted);
  margin-bottom: 8px;
}

.input-box {
  position: relative;
  display: flex;
  align-items: center;
}

.input-box input {
  width: 100%;
  padding: 13px 16px;
  border: 1.5px solid var(--line-color);
  border-radius: 12px;
  font: 500 15px/1 'Barlow', sans-serif;
  color: var(--ink-primary);
  background: #f8fafc;
  outline: none;
  transition: all .25s ease;
}

.input-box input:focus {
  border-color: var(--blue-sky);
  background: var(--white);
  box-shadow: 0 0 0 4px rgba(10, 123, 184, 0.12);
}

.remember-flex {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 24px;
  font-size: 14px;
}

.remember-flex label {
  display: flex;
  align-items: center;
  gap: 8px;
  color: var(--ink-muted);
  cursor: pointer;
  margin: 0;
  text-transform: none;
  font-weight: 500;
  letter-spacing: normal;
}

.remember-flex input[type="checkbox"] {
  width: 16px;
  height: 16px;
  accent-color: var(--blue-sky);
  cursor: pointer;
}

.btn-submit {
  width: 100%;
  background: linear-gradient(135deg, var(--blue-dark), var(--blue-primary));
  color: var(--white);
  border: none;
  border-radius: 12px;
  padding: 14px 24px;
  font: 700 16px/1 'Barlow Condensed', sans-serif;
  text-transform: uppercase;
  letter-spacing: .08em;
  cursor: pointer;
  transition: all .3s ease;
  box-shadow: 0 8px 20px rgba(12, 74, 122, 0.25);
}

.btn-submit:hover {
  background: linear-gradient(135deg, var(--blue-primary), var(--blue-sky));
  transform: translateY(-2px);
  box-shadow: 0 12px 24px rgba(10, 123, 184, 0.35);
}

.error-msg {
  background: #fef2f2;
  border: 1px solid #fecaca;
  color: #dc2626;
  padding: 10px 14px;
  border-radius: 10px;
  font-size: 14px;
  margin-bottom: 20px;
}

/* SISI KANAN: BANNER ILUSTRASI BUS */
.illustration-side {
  background: linear-gradient(160deg, #e0f2fe 0%, #bae6fd 100%);
  position: relative;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding: 40px;
  overflow: hidden;
}

/* Gelombang Latar Dalam Banner Kanan */
.illustration-side::before {
  content: "";
  position: absolute;
  width: 140%;
  height: 140%;
  background: rgba(255, 255, 255, 0.4);
  border-radius: 40%;
  top: -20%;
  right: -30%;
  pointer-events: none;
}

.illustration-side::after {
  content: "";
  position: absolute;
  width: 300px;
  height: 300px;
  background: rgba(10, 123, 184, 0.1);
  border-radius: 50%;
  bottom: -50px;
  left: -50px;
  pointer-events: none;
}

.bus-container {
  position: relative;
  z-index: 2;
  width: 100%;
  max-width: 440px;
  text-align: center;
}

.bus-img {
  width: 100%;
  height: auto;
  object-fit: contain;
  filter: drop-shadow(0 20px 24px rgba(12, 74, 122, 0.25));
  transition: transform .5s ease;
}

.bus-container:hover .bus-img {
  transform: translateY(-6px) scale(1.02);
}

.illus-text {
  position: relative;
  z-index: 2;
  margin-top: 24px;
  text-align: center;
}

.illus-text h3 {
  font: 800 24px/1.2 'Barlow Condensed', sans-serif;
  color: var(--blue-dark);
  text-transform: uppercase;
  margin: 0 0 6px;
}

.illus-text p {
  margin: 0;
  font-size: 14px;
  color: var(--blue-primary);
  opacity: .85;
}

/* Responsif untuk Tablet & HP */
@media (max-width: 860px) {
  .login-card { grid-template-columns: 1fr; }
  .illustration-side { display: none; }
  .form-side { padding: 40px 28px; }
}
</style>
</head>
<body>

<div class="login-card">
  <!-- SISI KIRI: Form Login Admin -->
  <div class="form-side">
    <div class="brand-logo">
      <span class="brand-icon"></span> NEW ARMADA
    </div>

    <h1>Selamat Datang <span>Admin</span></h1>
    <p class="subtitle">Silakan masuk untuk mengelola konten website &amp; katalog bus New Armada.</p>

    <!-- Notifikasi Error dari Laravel Breeze -->
    @if ($errors->any())
        <div class="error-msg">
            Email atau password yang kamu masukkan salah.
        </div>
    @endif

    <!-- Form Terhubung Langsung ke Laravel Breeze Auth -->
    <form method="POST" action="{{ route('login') }}">
      @csrf

      <div class="form-group">
        <label for="email">Email Admin</label>
        <div class="input-box">
          <input id="email" type="email" name="email" value="{{ old('email', 'marketing@newarmada.co.id') }}" required autofocus autocomplete="username" placeholder="masukkan email...">
        </div>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <div class="input-box">
          <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="masukkan password...">
        </div>
      </div>

      <div class="remember-flex">
        <label for="remember_me">
          <input id="remember_me" type="checkbox" name="remember">
          <span>Ingat saya</span>
        </label>
      </div>

      <button type="submit" class="btn-submit">Masuk ke Dashboard</button>
    </form>
  </div>

  <!-- SISI KANAN: Gambar Bus & Background Gelombang (Wavy) -->
  <div class="illustration-side">
    <div class="bus-container">
      <!-- Menggunakan aset gambar bus PNG transparan dari project-mu -->
      {{-- <img class="bus-img" src="{{ asset('images/BIGBUS-DOUBLEDECKER.png') }}" alt="New Armada Big Bus" onerror="this.src='images/BIGBUS-DOUBLEDECKER.png'"> --}}
    </div>
    <div class="illus-text">
      <h3>Karoseri Bus Indonesia</h3>
      <p>Together We Are Strong – PT Mekar Armada Jaya</p>
    </div>
  </div>
</div>

</body>
</html>
