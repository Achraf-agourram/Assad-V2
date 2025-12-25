<?php
session_start();
include("admin/admin.php");
include("database.php");

if(isset($_SESSION['loggedAccount'])) if(!checkAccess('statut_compte', 1)) return 0;
include("pages/header.php");
?>

<!DOCTYPE html>
<html lang="fr">
<?php head("ASSAD-Zoo Virtuel de la CAN 2025", "pages/Scripts/index.js"); ?>

<body class="bg-gray-100 font-sans">
    <?php navbar(['index.php', 'pages/animals.php', 'pages/visits.php', 'admin/users.php'], 'home'); ?>
    <main class="container mx-auto px-6 py-12">
        
        <section id="lion-atlas" class="mb-16 bg-orange-100 p-8 rounded-xl shadow-lg flex flex-col md:flex-row items-center justify-between lion-card transition duration-500">
            <div class="md:w-3/5">
                <h2 class="text-4xl font-extrabold text-orange-700 mb-2">⭐ Asaad : Le Lion de l'Atlas</h2>
                <p class="text-xl text-gray-800 mb-4">Symbole du Maroc et de la CAN 2025 !</p>
                <p class="text-gray-600 mb-6">Découvrez l'histoire, la conservation et les caractéristiques uniques de ce majestueux félin, emblème de notre Zoo Virtuel.</p>
                <button class="bg-orange-600 text-white font-bold px-6 py-3 rounded-full hover:bg-orange-700 transition duration-300 shadow-md" onclick="openAsaadModal()">
                    Explorer la fiche complète →
                </button>
            </div>
            <div class="md:w-2/5 mt-6 md:mt-0 flex justify-center">
                <div class="w-48 h-48 bg-orange-300 rounded-full flex items-center justify-center text-4xl text-white font-bold">

                </div>
            </div>
        </section>

    </main>
    
    <div id="modal-asaad" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-black bg-opacity-75 transition-opacity" onclick="closeAsaadModal()"></div>

        <div class="relative flex min-h-screen items-center justify-center p-4">
            <div class="relative w-full max-w-4xl overflow-hidden rounded-3xl bg-orange-50 shadow-2xl transition-all transform">
                
                <button onclick="closeAsaadModal()" class="absolute right-4 top-4 z-50 flex h-10 w-10 items-center justify-center rounded-full bg-black bg-opacity-50 text-white hover:bg-orange-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="flex flex-col md:flex-row">
                    <div class="relative w-full md:w-2/5 bg-orange-200">
                        <img src="https://images.unsplash.com/photo-1546182990-dffeafbe841d?auto=format&fit=crop&q=80&w=800" alt="Asaad" class="h-64 w-full object-cover md:h-full">
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-orange-900 p-6 text-white">
                            <h2 class="text-3xl font-black uppercase">Asaad</h2>
                            <p class="text-sm font-medium opacity-80">Lion de l'Atlas (Maroc)</p>
                        </div>
                    </div>

                    <div class="w-full md:w-3/5 p-8">
                        <div class="mb-6 flex space-x-2">
                            <span class="rounded-full bg-orange-200 px-3 py-1 text-xs font-bold text-orange-700 uppercase tracking-wider">CAN 2025 Mascotte</span>
                            <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700 uppercase tracking-wider">Espèce Protégée</span>
                        </div>

                        <h3 class="mb-4 text-xl font-bold text-gray-800 border-b-2 border-orange-500 inline-block">Le Roi des Sommets</h3>
                        <p class="mb-6 text-gray-600 leading-relaxed">
                            Emblème de la puissance marocaine, le Lion de l'Atlas se distingue par sa crinière noire légendaire. Adapté au froid des montagnes, il est le symbole de courage pour les supporters de la CAN 2025.
                        </p>

                        <div class="grid grid-cols-2 gap-4 mb-8">
                            <div class="rounded-xl bg-white p-3 shadow-sm border border-orange-100">
                                <p class="text-[10px] uppercase text-gray-400 font-bold">Poids</p>
                                <p class="text-lg font-bold text-orange-600">Jusqu'à 280kg</p>
                            </div>
                            <div class="rounded-xl bg-white p-3 shadow-sm border border-orange-100">
                                <p class="text-[10px] uppercase text-gray-400 font-bold">Habitat</p>
                                <p class="text-lg font-bold text-orange-600">Montagnes Atlas</p>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3">
                            <button class="flex-1 rounded-xl bg-orange-600 py-3 font-bold text-white hover:bg-orange-700 transition shadow-lg">
                                Réserver une visite
                            </button>
                            <button onclick="closeAsaadModal()" class="flex-1 rounded-xl border-2 border-orange-600 py-3 font-bold text-orange-600 hover:bg-orange-50 transition">
                                Fermer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>