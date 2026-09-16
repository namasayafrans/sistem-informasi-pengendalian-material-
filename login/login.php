<?php include 'auth.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | BataraSura Mulia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6">
   <div class="w-full max-w-md animate-fade-in">

    <?php if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 3): ?>
        
        <!-- TAMPILAN KUNCI DI LUAR KOLOM LOGIN (FORM HILANG TOTAL) -->
        <div class="flex flex-col items-center justify-center text-center space-y-6">
            <!-- Ikon Kunci di Luar Box -->
            <div class="w-28 h-28 bg-red-600 text-white rounded-full flex items-center justify-center text-5xl shadow-2xl shadow-red-300 animate-bounce">
                <i class="fas fa-lock"></i>
            </div>

            <!-- Keterangan Pesan Khusus -->
            <div class="space-y-2">
                <h3 class="text-2xl font-black text-slate-800 tracking-wide uppercase">
                    Akses Ditutup!
                </h3>
                <p class="text-lg font-bold text-red-600 animate-pulse">
                    swiper jgn mencuri swiper jgn mencuri
                </p>
            </div>
        </div>

    <?php else: ?>

        <!-- KARTU FORM LOGIN (HANYA MUNCUL JIKA SALAH < 3 KALI) -->
        <div class="bg-white rounded-[2.5rem] shadow-2xl p-10 border border-slate-100 relative">
            
            <a href="../index.php" class="absolute top-8 right-8 text-slate-300 hover:text-slate-900 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </a>

            <div class="text-center mb-8">
                <h2 class="text-3xl font-extrabold text-blue-700">Batara Sura Mulia</h2>
                <p class="text-slate-500 mt-2">Masuk untuk mengelola material</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm flex items-center gap-3 border border-red-100">
                    <i class="fas fa-exclamation-circle text-lg flex-shrink-0"></i>
                    <span><?= $error ?></span>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase text-slate-400 ml-1">Email</label>
                    <input type="text" name="email" required class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-blue-500 outline-none transition" placeholder="user@batarasura.co.id">
                </div>
                
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase text-slate-400 ml-1">Password</label>
                    <input type="password" name="password" required class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-blue-500 outline-none transition" placeholder="••••••••">
                </div>

                <button type="submit" name="login" class="w-full bg-blue-600 text-white py-4 rounded-2xl font-bold text-lg hover:bg-blue-700 transition-all shadow-lg shadow-blue-200">
                    Masuk ke Sistem
                </button>
            </form>
        </div>

    <?php endif; ?>

</div>
</body>
</html>