<?php
session_start();
include("../database.php");
include("../admin/admin.php");

if(isset($_SESSION['loggedAccount'])){
    if(!checkAccess('statut_compte', 1)) return 0;
    include("header.php");
    actions();
    show_visits_page();
}else show_unavailable_page();

function actions(){
    global $availableVisits, $reservedVisits, $reservedOutDatedVisits, $outdatedVisits;

    if(isset($_POST['addComment'])){
        request("INSERT INTO commentaires (note, texte, id_visite, id_utilisateur) VALUES (?, ?, ?, ?);", "isii", [$_POST['note'], $_POST['commentaire'], $_POST['addComment'], $_SESSION['loggedAccount']]);
        echo '<div id="notification" class="bg-green-500" style="position: absolute;top: 0;left: 40%;color: white;padding: 15px;border-radius: 5px;animation: fadeIn 0.4s ease;z-index: 100;">Commentaire ajouté</div>';
    }
    if(isset($_POST['showComment'])){
        $comments = extract_rows(request("SELECT note, nom, texte, date_commentaire FROM commentaires JOIN utilisateurs ON id_utilisateur = utilisateurs.id WHERE id_visite = ?;", "i", [$_POST['showComment']]));
        show_comments($comments);
    }
    if(isset($_POST['reservation'])){
        request("INSERT INTO reservations (nbpersonnes, id_visite, id_utilisateur) VALUES (?, ?, ?);", "iii", [$_POST['nbpersonne'], $_POST['reservation'], $_SESSION['loggedAccount']]);
        echo '<div id="notification" class="bg-green-500" style="position: absolute;top: 0;left: 40%;color: white;padding: 15px;border-radius: 5px;animation: fadeIn 0.4s ease;z-index: 100;">Reservation confirmé</div>';
    }

    if(isset($_GET['search'])){
        $visitName = "%{$_GET['visitToSearch']}%";

        $availableVisits = extract_rows(request("SELECT v.*, utilisateurs.nom FROM visitesguidees v JOIN utilisateurs ON id_guide = utilisateurs.id WHERE NOT EXISTS (SELECT 1 FROM reservations r WHERE r.id_visite = v.id AND r.id_utilisateur = ?) AND dateheure >= NOW() AND titre LIKE ?;", "is", [$_SESSION['loggedAccount'], $visitName]));
        $reservedVisits = extract_rows(request("SELECT v.*, utilisateurs.nom FROM visitesguidees v JOIN utilisateurs ON id_guide = utilisateurs.id WHERE EXISTS (SELECT 1 FROM reservations r WHERE r.id_visite = v.id AND r.id_utilisateur = ?) AND dateheure >= NOW() AND titre LIKE ?;", "is", [$_SESSION['loggedAccount'], $visitName]));
        $reservedOutDatedVisits = extract_rows(request("SELECT v.*, utilisateurs.nom FROM visitesguidees v JOIN utilisateurs ON id_guide = utilisateurs.id WHERE EXISTS (SELECT 1 FROM reservations r WHERE r.id_visite = v.id AND r.id_utilisateur = ?) AND dateheure <= NOW() AND titre LIKE ?;", "is", [$_SESSION['loggedAccount'], $visitName]));
        $outdatedVisits = extract_rows(request("SELECT v.*, utilisateurs.nom FROM visitesguidees v JOIN utilisateurs ON id_guide = utilisateurs.id WHERE NOT EXISTS (SELECT 1 FROM reservations r WHERE r.id_visite = v.id AND r.id_utilisateur = ?) AND dateheure <= NOW() AND titre LIKE ?;", "is", [$_SESSION['loggedAccount'], $visitName]));
    }else{
        $availableVisits = extract_rows(request("SELECT v.*, utilisateurs.nom FROM visitesguidees v JOIN utilisateurs ON id_guide = utilisateurs.id WHERE NOT EXISTS (SELECT 1 FROM reservations r WHERE r.id_visite = v.id AND r.id_utilisateur = ?) AND dateheure >= NOW();", "i", [$_SESSION['loggedAccount']]));
        $reservedVisits = extract_rows(request("SELECT v.*, utilisateurs.nom FROM visitesguidees v JOIN utilisateurs ON id_guide = utilisateurs.id WHERE EXISTS (SELECT 1 FROM reservations r WHERE r.id_visite = v.id AND r.id_utilisateur = ?) AND dateheure >= NOW();", "i", [$_SESSION['loggedAccount']]));
        $reservedOutDatedVisits = extract_rows(request("SELECT v.*, utilisateurs.nom FROM visitesguidees v JOIN utilisateurs ON id_guide = utilisateurs.id WHERE EXISTS (SELECT 1 FROM reservations r WHERE r.id_visite = v.id AND r.id_utilisateur = ?) AND dateheure <= NOW();", "i", [$_SESSION['loggedAccount']]));
        $outdatedVisits = extract_rows(request("SELECT v.*, utilisateurs.nom FROM visitesguidees v JOIN utilisateurs ON id_guide = utilisateurs.id WHERE NOT EXISTS (SELECT 1 FROM reservations r WHERE r.id_visite = v.id AND r.id_utilisateur = ?) AND dateheure <= NOW();", "i", [$_SESSION['loggedAccount']]));
    }
}

function show_comments($comments){
    echo "
        <div id='view-comments-modal' class='fixed inset-0 flex bg-gray-900 bg-opacity-80 items-center justify-center z-[60] overflow-y-auto'>
            <div class='bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden transform transition-all m-4'>
                
                <div class='bg-orange-500 p-6 text-white flex justify-between items-center'>
                    <h3 class='text-2xl font-bold' id='view-comments-title'>Commentaires</h3>
                    <button onclick='closeModal(&quot;view-comments-modal&quot;)' class='text-white hover:rotate-90 transition duration-300'>
                        <svg xmlns='http://www.w3.org/2000/svg' class='h-8 w-8' fill='none' viewBox='0 0 24 24' stroke='currentColor'>
                            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12' />
                        </svg>
                    </button>
                </div>

                <div class='p-6 max-h-[60vh] overflow-y-auto bg-gray-50' id='comments-container'>
                    
                    <div class='space-y-4'>
        ";
    if($comments){
        foreach($comments as $cmnt){
            $rate = "";
            $shortName = substr($cmnt['nom'], 0, 2);
            for($i=0; $i<$cmnt['note']; $i++) $rate .= "<span>⭐</span>";

            echo "
                <div class='bg-white p-4 rounded-2xl shadow-sm border border-orange-100'>
                    <div class='flex justify-between items-start mb-2'>
                        <div class='flex items-center'>
                            <div class='h-10 w-10 rounded-full bg-orange-200 flex items-center justify-center text-orange-700 font-bold mr-3'>
                                {$shortName}
                            </div>
                            <div>
                                <h4 class='font-bold text-gray-800'>{$cmnt['nom']}</h4>
                                <p class='text-[10px] text-gray-400 uppercase'>{$cmnt['date_commentaire']}</p>
                            </div>
                        </div>
                        <div class='flex text-orange-400'>
                            {$rate}
                        </div>
                    </div>
                    <p class='text-gray-600 text-sm leading-relaxed'>
                        {$cmnt['texte']}
                    </p>
                </div>
            ";
        }
    }else{
        echo "<h1 class='text-gray-600 flex justify-center'>Il n'ya aucun commentaire pour le moment</h1>";
    }

    echo "
                    </div>

                    <div id='no-comments-message' class='hidden text-center py-10'>
                        <span class='text-5xl block mb-4'>🏜️</span>
                        <p class='text-gray-500 italic'>Aucun commentaire pour le moment. Soyez le premier à partager votre avis !</p>
                    </div>

                </div>

                <div class='p-4 bg-white border-t flex justify-center'>
                    <button onclick='closeModal(&quot;view-comments-modal&quot;)' class='bg-gray-100 text-gray-600 px-6 py-2 rounded-xl font-bold hover:bg-gray-200 transition'>
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    ";
}

function show_visits_page(){
    global $availableVisits, $reservedVisits, $reservedOutDatedVisits, $outdatedVisits;
    echo '
    <!DOCTYPE html>
    <html lang="fr">';
    head("visites", "Scripts/visits.js");
    echo '<body class="bg-gray-100 font-sans">';
    navbar(['../index.php', 'animals.php', 'visits.php', '../admin/users.php'], 'visits');
    echo '
        <main class="mx-auto px-6 py-12">
            <h1 class="text-4xl font-extrabold text-gray-800 mb-2">Parcours Éducatifs et Visites Virtuelles</h1>
            <p class="text-xl text-gray-600 mb-4">Réservez votre place pour une exploration guidée et interactive de la
                faune africaine.</p>

            <form class="mb-4 bg-white p-6 rounded-xl shadow-lg" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <input type="text" name="visitToSearch" placeholder="Rechercher par titre du visite..."
                        class="md:col-span-2 p-3 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500">

                    <button name="search" class="bg-orange-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-orange-600 transition duration-300 w-full md:w-auto">
                        Rehercher
                    </button>
                </div>
            </form>
            <section id="tour-list" class="space-y-6">
                <div class="flex justify-between">
                    <h2 class="text-3xl font-bold text-gray-800">Prochaines Visites</h2>
                    <!--button class="bg-orange-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-orange-500 transition duration-300">
                        Ajouter une nouvelle visite
                    </button-->
                </div>
    ';

    foreach($availableVisits as $visit){
        $Steps = extract_rows(request("SELECT titreetape FROM `etapesvisite` WHERE id_visite = ? ORDER BY ordreetape ASC;", "i", [$visit['id']]));
        $stepsString = "";
        foreach($Steps as $step) $stepsString .= $step['titreetape'] . " ->";
        $stepsString = substr($stepsString, 0, -2);
        echo "
            <div class='bg-white p-6 rounded-lg shadow-md flex flex-col lg:flex-row justify-between items-start lg:items-center transition duration-300'>
                <div class='lg:w-3/5 mb-4 lg:mb-0'>
                    <h3 class='text-2xl font-bold text-orange-500 mb-1'>{$visit['titre']}</h3>
                    <p class='text-sm text-gray-700 mb-2'>Guide: {$visit['nom']} | Langue: {$visit['langue']}</p>
                    <div class='flex items-center space-x-4 text-gray-700 text-sm font-medium'>
                        <span>📅 {$visit['dateheure']}</span>
                        <span>⏱️ {$visit['duree']}</span>
                        <span>👥 Capacité: {$visit['capacite_max']}</span>
                    </div>
                    <div class='mt-3'>
                        <span class='font-semibold text-gray-800'>Parcours:</span>
                        <span class='text-sm text-gray-600'>{$stepsString}.</span>
                    </div>
                </div>

                <div class='lg:w-1/5 flex flex-col items-start lg:items-end'>
                    <span class='text-3xl font-extrabold text-orange-500'>{$visit['prix']}€</span>
                    <button class='mt-2 bg-orange-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-orange-500 transition duration-300' onclick='showBookingModal({$visit['id']}, {$visit['prix']})'>
                        Réserver
                    </button>
                </div>
            </div>
        ";
    }

    foreach($reservedVisits as $visit){
        $Steps = extract_rows(request("SELECT titreetape FROM `etapesvisite` WHERE id_visite = ? ORDER BY ordreetape ASC;", "i", [$visit['id']]));
        $stepsString = "";
        foreach($Steps as $step) $stepsString .= $step['titreetape'] . " ->";
        $stepsString = substr($stepsString, 0, -2);

        echo "
            <div class='bg-white p-6 rounded-lg shadow-md flex flex-col lg:flex-row justify-between items-start lg:items-center transition duration-300'>
                <div class='lg:w-3/5 mb-4 lg:mb-0'>
                    <h3 class='text-2xl font-bold text-orange-500 mb-1'>{$visit['titre']}</h3>
                    <p class='text-sm text-gray-700 mb-2'>Guide: {$visit['nom']} | Langue: {$visit['langue']}</p>
                    <div class='flex items-center space-x-4 text-gray-700 text-sm font-medium'>
                        <span>📅 {$visit['dateheure']}</span>
                        <span>⏱️ {$visit['duree']}</span>
                        <span>👥 Capacité: {$visit['capacite_max']}</span>
                    </div>
                    <div class='mt-3'>
                        <span class='font-semibold text-gray-800'>Parcours:</span>
                        <span class='text-sm text-gray-700'>{$stepsString}.</span>
                    </div>
                </div>

                <div class='lg:w-1/5 flex flex-col items-start lg:items-end'>
                    <span class='text-3xl font-extrabold text-orange-500'>{$visit['prix']}€</span>
                    <div class='mt-2 bg-orange-500 text-white px-6 py-2 rounded-lg font-semibold'>
                        Réservée déja
                    </div>
                </div>
            </div>
        ";
    }

    foreach($reservedOutDatedVisits as $visit){
        $Steps = extract_rows(request("SELECT titreetape FROM `etapesvisite` WHERE id_visite = ? ORDER BY ordreetape ASC;", "i", [$visit['id']]));
        $stepsString = "";
        foreach($Steps as $step) $stepsString .= $step['titreetape'] . " ->";
        $stepsString = substr($stepsString, 0, -2);

        $commentsInfo = extract_rows(request("SELECT COUNT(*) AS commentstotal, SUM(note) AS notesTotal FROM commentaires WHERE id_visite = ?;", "i", [$visit['id']]))[0];
        $rate = $commentsInfo['notesTotal'] ? "Note: ". $commentsInfo['notesTotal']/$commentsInfo['commentstotal']. "/5" : "";
        echo "
            <div class='bg-gray-200 p-6 rounded-lg shadow-inner flex flex-col lg:flex-row justify-between items-start lg:items-center opacity-70'>

                <div class='lg:w-3/5 mb-4 lg:mb-0'>
                    <h3 class='text-2xl font-bold text-gray-700 mb-1'>{$visit['titre']}</h3>
                    <p class='text-sm text-gray-500 mb-2'>Guide: {$visit['nom']} | Langue: {$visit['langue']}</p>
                    <div class='flex items-center space-x-4 text-gray-700 text-sm font-medium'>
                        <span>✅ Terminée le: {$visit['dateheure']}</span>
                        <span>⏱️ {$visit['duree']}</span>
                    </div>
                    <div class='mt-3'>
                        <span class='font-semibold text-gray-800'>Parcours:</span>
                        <span class='text-sm text-gray-600'>{$stepsString}.</span>
                    </div>
                </div>

                <div class='lg:w-1/5 flex flex-col items-start lg:items-end'>
                    <div name class='text-2xl font-bold text-orange-600'>
                        {$rate}
                    </div>
                    <form method='POST'>
                        <button value='{$visit['id']}' name='showComment' class='text-xs text-gray-500 hover:text-orange-500 transition duration-300'>
                            Voir les {$commentsInfo['commentstotal']} commentaires
                        </button>
                    </form>
                    <button name='addComment' class='bg-orange-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-orange-700 transition duration-300'
                        onclick='showCommentModal({$visit['id']}, " . json_encode($visit['titre']) . ")'>
                        💬 Laisser un commentaire
                    </button>
                </div>
            </div>
        ";
    }

    foreach($outdatedVisits as $visit){
        $Steps = extract_rows(request("SELECT titreetape FROM `etapesvisite` WHERE id_visite = ? ORDER BY ordreetape ASC;", "i", [$visit['id']]));
        $stepsString = "";
        foreach($Steps as $step) $stepsString .= $step['titreetape'] . " ->";
        $stepsString = substr($stepsString, 0, -2);

        $commentsInfo = extract_rows(request("SELECT COUNT(*) AS commentstotal, SUM(note) AS notesTotal FROM commentaires WHERE id_visite = ?;", "i", [$visit['id']]))[0];
        $rate = $commentsInfo['notesTotal'] ? "Note: ". $commentsInfo['notesTotal']/$commentsInfo['commentstotal']. "/5" : "";

        echo "
            <div class='bg-gray-200 p-6 rounded-lg shadow-inner flex flex-col lg:flex-row justify-between items-start lg:items-center opacity-70'>

                <div class='lg:w-3/5 mb-4 lg:mb-0'>
                    <h3 class='text-2xl font-bold text-gray-700 mb-1'>{$visit['titre']}</h3>
                    <p class='text-sm text-gray-500 mb-2'>Guide: {$visit['nom']} | Langue: {$visit['langue']}</p>
                    <div class='flex items-center space-x-4 text-gray-700 text-sm font-medium'>
                        <span>✅ Terminée le: {$visit['dateheure']}</span>
                        <span>⏱️ {$visit['duree']}</span>
                    </div>
                    <div class='mt-3'>
                        <span class='font-semibold text-gray-800'>Parcours:</span>
                        <span class='text-sm text-gray-600'>{$stepsString}.</span>
                    </div>
                </div>

                <form method='POST' class='lg:w-1/5 flex flex-col items-start lg:items-end'>
                    <div class='text-2xl font-bold text-orange-600 mb-1'>
                        {$rate}
                    </div>
                    <button value='{$visit['id']}' name='showComment' class='mt-1 text-xs text-gray-500 hover:text-orange-500 transition duration-300'>
                        Voir les {$commentsInfo['commentstotal']} commentaires
                    </button>
                </form>
            </div>
        ";
    }

    echo '
            </section>

        </main>

        <div id="booking-modal" class="fixed inset-0 bg-gray-600 bg-opacity-75 hidden items-center justify-center z-50">
            <div class="bg-white p-8 rounded-lg shadow-2xl w-full max-w-lg">
                <h3 class="text-2xl font-bold text-orange-700 mb-4">Réservation : <span id="booking-tour-title"></span></h3>
                <form id="form-booking" method="POST">
                    <div class="mb-4">
                        <p class="block text-gray-700 text-sm font-bold mb-2">Nombre de
                            personnes</p>
                        <input type="number" id="nb_personnes" name="nbpersonne" min="1" max="5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:ring-orange-500 focus:border-orange-500"
                            required>
                    </div>

                    <div class="text-xl font-bold mb-6">
                        Total à payer: <span id="total-price" class="text-orange-500">10€</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <button id="reservationBtn" name="reservation" type="submit" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                            Confirmer la Réservation
                        </button>
                        <button type="button" onclick="closeModal(&quot;booking-modal&quot;)"
                            class="text-sm text-gray-500 hover:text-gray-800">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div id="comment-modal" class="fixed flex inset-0 bg-gray-600 bg-opacity-75 items-center hidden justify-center z-50">
            <div class="bg-white p-8 rounded-lg shadow-2xl w-full max-w-lg">
                <h3 class="text-2xl font-bold text-orange-700 mb-4">Évaluer : <span id="comment-tour-title"></span></h3>
                <form method="POST">
                    <div class="mb-4">
                        <p class="block text-gray-700 text-sm font-bold mb-2">Note sur 5 (⭐)</p>
                        <select name="note" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:ring-orange-500 focus:border-orange-500" required>
                            <option value="5">5 - Excellent</option>
                            <option value="4">4 - Très bien</option>
                            <option value="3">3 - Moyen</option>
                            <option value="2">2 - Mauvais</option>
                            <option value="1">1 - Très mauvais</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <p class="block text-gray-700 text-sm font-bold mb-2">Votre Commentaire</p>
                        <textarea name="commentaire" rows="4" placeholder="Partagez votre expérience..."
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:ring-orange-500 focus:border-orange-500"
                            required></textarea>
                    </div>

                    <div class="flex items-center justify-between">
                        <button id="commentBtn" name="addComment" type="submit" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                            Soumettre
                        </button>
                        <button type="button" onclick="closeModal(&quot;comment-modal&quot;)" class="text-sm text-gray-500 hover:text-gray-800">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div id="create-tour-modal" class="fixed inset-0 bg-gray-600 bg-opacity-75 items-center justify-center hidden z-50">
            <div class="bg-white p-8 rounded-lg shadow-2xl w-full max-w-xl">
                <h3 class="text-2xl font-bold text-orange-700 mb-4" id="tour-modal-title">Créer une Nouvelle Visite Guidée</h3>
                
                <form id="form-create-tour">
                    <div class="mb-4">
                        <p fo" class="block text-gray-700 text-sm font-bold mb-2">Titre de la Visite</p>
                        <input type="text" id="tour-titre" placeholder="Ex: Safari dans la Savane Kenyane" maxlength="100"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:ring-orange-500 focus:border-orange-500" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <p fo" class="block text-gray-700 text-sm font-bold mb-2">Date et Heure de Début</p>
                            <input type="datetime-local" id="tour-dateheure"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:ring-orange-500 focus:border-orange-500" required>
                        </div>
                        
                        <div>
                            <p fo" class="block text-gray-700 text-sm font-bold mb-2">Langue de la Visite</p>
                            <select id="tour-langue"
                                    class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:ring-orange-500 focus:border-orange-500" required>
                                <option value="fr">Français</option>
                                <option value="en">Anglais</option>
                                <option value="ar">Arabe</option>
                                <option value="es">Espagnol</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <p fo" class="block text-gray-700 text-sm font-bold mb-2">Capacité Max</p>
                            <input type="number" id="tour-capacite" min="5" max="100" value="30"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:ring-orange-500 focus:border-orange-500" required>
                        </div>
                        
                        <div>
                            <p fo" class="block text-gray-700 text-sm font-bold mb-2">Durée (en minutes)</p>
                            <input type="number" id="tour-duree" min="30" max="180" value="90"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:ring-orange-500 focus:border-orange-500" required>
                        </div>

                        <div>
                            <p fo" class="block text-gray-700 text-sm font-bold mb-2">Prix (€ ou MAD)</p>
                            <input type="number" id="tour-prix" min="0" step="0.01" value="10.00"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:ring-orange-500 focus:border-orange-500" required>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                            Créer la Visite
                        </button>
                        <button type="button" onclick="closeModal("create-tour-modal")" class="text-sm text-gray-500 hover:text-gray-800">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </body>
    </html>
    ';
}

?>
