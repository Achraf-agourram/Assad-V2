<?php
    $paysCAN = ['Maroc','Sénégal','Égypte','Algérie','Tunisie','Nigeria','Ghana','Cameroun','Côte d’Ivoire','Mali','Burkina Faso','Afrique du Sud','RD Congo','Zambie','Guinée','Guinée équatoriale','Cap-Vert','Angola','Mozambique','Namibie','Tanzanie','Ouganda','Bénin','Gabon'];

    function head($title, $script){
        echo "
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>{$title}</title>
            <script src='https://cdn.tailwindcss.com'></script>
            <script src='{$script}' defer></script>
            <style>
                .lion-card:hover {
                    transform: scale(1.03);
                    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                }
                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(-10px); }
                    to { opacity: 1; transform: translateY(0); }
                }
            </style>
        </head>
        ";
    }
    function navbar($links, $page){
        $linksDict = ['home' => ['text-orange-600 font-semibold', 'text-gray-600', 'text-gray-600'], 'animals' => ['text-gray-600', 'text-orange-600 font-semibold', 'text-gray-600'], 'visits' => ['text-gray-600', 'text-gray-600', 'text-orange-600 font-semibold']];
        echo "
        <header class='bg-white shadow-md top-0 z-50'>
        <nav class='mx-auto px-6 py-3 flex justify-between items-center'>
            <div class='text-2xl font-bold text-orange-500'>
                🦁 ASSAD - Zoo Virtuel
            </div>
            <div class='space-x-4 hidden md:flex'>
                <a href='{$links[0]}' class='{$linksDict[$page][0]} hover:text-orange-700 transition duration-300'>Accueil</a>
                <a href='{$links[1]}' class='{$linksDict[$page][1]} hover:text-orange-700 transition duration-300'>Animaux</a>
                
        ";

        if(isset($_SESSION['loggedAccount'])){
            echo "
                <a href='{$links[2]}' class='{$linksDict[$page][2]} hover:text-orange-700 transition duration-300'>Visites</a>
            </div>
            <div class='flex items-center space-x-2'>
            ";
            $connectedUser = extract_rows(request("SELECT * FROM utilisateurs WHERE id = ?;", "i", [$_SESSION['loggedAccount']]));
            if($connectedUser[0]['role'] == 'admin'){
                echo "
                    <a href='{$links[3]}' name='admin' class='bg-transparent border border-orange-700 text-orange-700 px-4 py-2 rounded-lg hover:bg-orange-500 hover:text-white transition duration-300'>
                        Admin
                    </a>
                ";
            }
            echo "
                <form method='POST' class='h-6'>
                    <button name='logout' class='bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-800 transition duration-300'>
                        Deconnexion
                    </button>
                </form>
                ";
        }else{
            echo "
                </div>
                <div class='flex items-center space-x-2'>
                    <button id='btn-login' class='bg-transparent border border-orange-700 text-orange-700 px-4 py-2 rounded-lg hover:bg-orange-50 transition duration-300'>
                        Connexion
                    </button>
                    <button id='btn-register' class='bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-800 transition duration-300'>
                        Inscription
                    </button>
            ";
        }

        echo "
                </div>
                </nav>
            </header>
        ";
    }
    
    if(isset($_POST['login'])){
        $user = extract_rows(request("SELECT * FROM utilisateurs WHERE email = ? AND motpasse_hash = ?;", "ss", [$_POST['loginEmail'], $_POST['loginPassword']]));
        $_SESSION['loggedAccount'] = $user[0]['id'];
        if($user){
            echo '<div id="notification" class="bg-green-500" style="position: absolute;top: 0;left: 40%;color: white;padding: 15px;border-radius: 5px;animation: fadeIn 0.4s ease;z-index: 100;">Connected successfully</div>';
        }else{
            echo '<div id="notification" style="position: absolute;top: 0;left: 40%;color: white;padding: 15px;border-radius: 5px;animation: fadeIn 0.4s ease;background-color: #f44336;z-index: 100;">❌ Cet utilisateur n existe pas!</div>';
        }
    }
    if(isset($_POST['register'])){
        $data = [$_POST['registerName'], $_POST['registerEmail'], $_POST['registerPassword'], $_POST['registerCountry'], $_POST['registerRole']];
        request("INSERT INTO utilisateurs (nom, email, motpasse_hash, pays, role) VALUES (?, ?, ?, ?, ?);", "sssss", $data);
        echo '<div id="notification" class="bg-green-500" style="position: absolute;top: 0;left: 40%;color: white;padding: 15px;border-radius: 5px;animation: fadeIn 0.4s ease;z-index: 100;">Account registred successfully</div>';
    }
    if(isset($_POST['logout'])){
        $_SESSION['loggedAccount'] = null;
    }    
?>

<div id="auth-modal" class="fixed inset-0 bg-gray-600 bg-opacity-75 hidden items-center justify-center z-50">
    <div class="bg-white p-8 rounded-lg shadow-2xl w-full max-w-md">
        <h3 id="modal-title" class="text-2xl font-bold text-gray-800 mb-4">Connexion</h3>
        <form class="hidden" id="login-form" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" name="loginEmail" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:ring-orange-500 focus:border-orange-500" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Mot de passe</label>
                <input type="password" name="loginPassword" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:ring-orange-500 focus:border-orange-500" required>
            </div>
            
            <div class="flex items-center justify-between">
                <button type="submit" name="login" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300">
                    Valider
                </button>
                <button type="button" onclick="closeModal()" class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800">
                    Annuler
                </button>
            </div>
            <p class="toggle-auth text-center text-sm mt-4 cursor-pointer text-blue-500 hover:text-blue-700">Pas encore de compte ? S'inscrire</p>
        </form>

        <form class="hidden" id="register-form" method="POST">
            <div class="mb-2">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nom</label>
                <input type="text" name="registerName" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:ring-orange-500 focus:border-orange-500" required>
            </div>
            <div class="mb-2">
                <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" name="registerEmail" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:ring-orange-500 focus:border-orange-500" required>
            </div>
            <div class="mb-2">
                <label class="block text-gray-700 text-sm font-bold mb-2">Mot de passe</label>
                <input type="password" name="registerPassword" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:ring-orange-500 focus:border-orange-500" required>
            </div>
            <div class="mb-2">
                <label class="block text-gray-700 text-sm font-bold mb-2">Pays</label>
                <select name="registerCountry" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:ring-orange-500 focus:border-orange-500" required>
                    <?php
                        foreach($paysCAN as $pays) echo "<option value='$pays'>$pays</option>";
                    ?>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Je suis</label>
                <select name="registerRole" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:ring-orange-500 focus:border-orange-500">
                    <option value="visiteur">Visiteur</option>
                    <option value="guide">Guide (Soumis à approbation)</option>
                </select>
            </div>
            
            <div class="flex items-center justify-between">
                <button type="submit" name="register" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300">
                    Valider
                </button>
                <button type="button" onclick="closeModal()" class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800">
                    Annuler
                </button>
            </div>
            <p class="toggle-auth text-center text-sm mt-4 cursor-pointer text-blue-500 hover:text-blue-700">Pas encore de compte ? S'inscrire</p>
        </form>
    </div>
</div>