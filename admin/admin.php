<?php
    function leftsidemenu(){
        echo '
                <aside class="w-64 bg-gray-900 text-white hidden md:block">
                    <div class="p-6">
                        <span class="text-2xl font-bold text-orange-500">🦁 ASSAD Admin</span>
                    </div>
                    <nav class="mt-6">
                        <a href="#" class="block py-3 px-6 bg-gray-800 border-l-4 border-orange-500">👤 Utilisateurs</a>
                        <a href="#" class="block py-3 px-6 hover:bg-gray-800 transition">🦁 Animaux (CRUD)</a>
                        <a href="#" class="block py-3 px-6 hover:bg-gray-800 transition">🌿 Habitats (CRUD)</a>
                        <a href="#" class="block py-3 px-6 hover:bg-gray-800 transition">📈 Statistiques</a>
                    </nav>
                </aside>
        ';
    }
    function title($page){
        echo '
            <header class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">'. $page .'</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">Connecté en tant que: <strong>Admin</strong></span>
                    <button name="logout" class="text-red-500 hover:underline text-sm font-semibold">Déconnexion</button>
                </div>
            </header>
        ';
    }
    function show_unavailable_page(){
        echo '
                <!DOCTYPE html>
                <html lang="fr">

                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Page non trouvé</title>
                    <script src="https://cdn.tailwindcss.com"></script>
                </head>
                <body class="bg-gray-100 font-sans">
                    <main class="flex-grow flex items-center justify-center px-6 py-12">
                        <div class="text-center max-w-2xl">
                            
                            <div class="relative mb-8 flex justify-center">
                                <h1 class="text-[150px] md:text-[200px] font-black text-orange-200 leading-none select-none">
                                    404
                                </h1>
                                <div class="absolute inset-0 flex items-center justify-center floating">
                                    <span class="text-8xl">🦁</span>
                                </div>
                            </div>

                            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800 mb-4">
                                Oups ! Vous vous êtes égaré dans la savane...
                            </h2>
                            <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                                Même le Lion de l Atlas ne trouve pas cette trace. Il semble que la page que vous cherchez n existe pas ou a été déplacée vers un autre chemin.
                            </p>

                            <div class="flex flex-col sm:flex-row justify-center gap-4">
                                <a href="../index.php" class="bg-orange-600 text-white font-bold px-8 py-4 rounded-full hover:bg-orange-700 transition shadow-lg flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    Retourner à l accueil
                                </a>
                                <button onclick="window.history.back()" class="bg-white text-orange-600 border-2 border-orange-600 font-bold px-8 py-4 rounded-full hover:bg-orange-50 transition shadow-sm">
                                    Page précédente
                                </button>
                            </div>
                        </div>
                    </main>
                </body>
                </html>
        ';
    }

    function checkAccess($key, $value){
        if(isset($_SESSION['loggedAccount'])){
            $connectedUser = extract_rows(request("SELECT * FROM utilisateurs WHERE id = ?;", "i", [$_SESSION['loggedAccount']]))[0];
            if($connectedUser[$key] == $value) return true;
        }
        show_unavailable_page();
    }
?>