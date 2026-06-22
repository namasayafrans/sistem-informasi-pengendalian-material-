<?php
session_start();

// Jika tombol "Ya, Keluar" ditekan
if (isset($_POST['confirm_logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Efek blur pada latar belakang saat modal terbuka */
        dialog::backdrop {
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(4px);
        }
    </style>
</head>
<body class="bg-gray-200">

    <!-- Dialog Modal Native -->
    <dialog id="logoutDialog" class="rounded-lg shadow-2xl p-0 w-80 overflow-hidden border-none">
        <div class="p-5 bg-white">
            <h3 class="text-lg font-bold text-gray-800">Logout</h3>
            <p class="text-gray-600 mt-2 text-sm">Apakah anda yakin ingin keluar?</p>
            
            <div class="mt-6 flex justify-end space-x-3">
                <!-- Tombol Batal -->
                <button onclick="window.history.back()" class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                    Batal
                </button>
                
                <!-- Form Logout -->
                <form method="POST">
                    <button type="submit" name="confirm_logout" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700">
                        Ya, Keluar
                    </button>
                </form>
            </div>
        </div>
    </dialog>

    <script>
        const dialog = document.getElementById('logoutDialog');
        // Langsung tampilkan modal saat halaman diakses
        dialog.showModal();
        
        // Mencegah user menutup modal dengan menekan tombol 'Esc' (opsional)
        dialog.addEventListener('cancel', (event) => {
            event.preventDefault();
        });
    </script>

</body>
</html>