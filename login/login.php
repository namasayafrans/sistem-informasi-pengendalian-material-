<?php include 'auth.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Login | BataraSura Mulia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6">
   <div class="w-full max-w-md animate-fade-in">
    <div class="bg-white rounded-[2.5rem] shadow-2xl p-10 border border-slate-100 relative">
        
        <a href="../index.php" class="absolute top-8 right-8 text-slate-300 hover:text-slate-900 transition-colors">
            <i class="fas fa-times text-xl"></i>
        </a>

        <div class="text-center mb-10">
            <h2 class="text-3xl font-extrabold text-blue-700 ">Batara Sura Mulia </h2>
            <p class="text-slate-500 mt-2">Masuk untuk mengelola material</p>
        </div>

        <?php if(isset($error)): ?>
            <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm flex items-center gap-3">
                <i class="fas fa-exclamation-circle"></i> <?= $error ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="space-y-6">
            <div class="space-y-2">
                <label class="text-xs font-bold uppercase text-slate-400 ml-1">Email</label>
                <input type="email" name="email" required class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:border-blue-500 outline-none transition" placeholder="user@batarasura.co.id">
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
</div>
</body>
</html>