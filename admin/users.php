<?php
session_start();
include("../database.php");
include("admin.php");


function show_users_page(){
    $statutDict = [0 => 'Désactivé', 1 => 'Activé'];
    $approuveDict = ['guide' => [0 => 'EN ATTENTE', 1 => 'N/A'], 'visiteur' => [0 => 'N/A', 1 => 'N/A']];
    
    $usersTotal = extract_rows(request("SELECT COUNT(*) as total FROM `utilisateurs` WHERE role != 'admin';", null, null))[0]['total'];
    $guideAttenteTotal = extract_rows(request("SELECT COUNT(*) as attente FROM `utilisateurs` WHERE role = 'guide' AND statut_compte AND !role_approuve;", null, null))[0]['attente'];
    $actifAccountsTotal = extract_rows(request("SELECT COUNT(*) as actifAccounts FROM `utilisateurs` WHERE statut_compte AND role != 'admin';", null, null))[0]['actifAccounts'];

    $guidesAttenteList = extract_rows(request("SELECT id, nom, email, role, statut_compte AS statut, role_approuve AS approuve FROM `utilisateurs` WHERE role='guide' AND statut_compte AND !role_approuve;", null, null));
    $actifAccountsList = extract_rows(request("SELECT id, nom, email, role, statut_compte AS statut, role_approuve AS approuve FROM `utilisateurs` WHERE statut_compte AND (role != 'guide' OR (role = 'guide' AND role_approuve)) AND role != 'admin';", null, null));
    $inactifAccountsList = extract_rows(request("SELECT id, nom, email, role, statut_compte AS statut, role_approuve AS approuve FROM `utilisateurs` WHERE !statut_compte;", null, null));

    echo '
        <!DOCTYPE html>
        <html lang="fr">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Admin</title>
            <script src="https://cdn.tailwindcss.com"></script>
        </head>
        <body class="bg-gray-100 font-sans">
                <div class="flex min-h-screen">
        ';

    leftsidemenu();
    echo '<form method="POST" class="flex-1 p-8">';
    title("Gestion des utilisateurs");

    echo '
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                            <p class="text-sm text-gray-500 font-medium uppercase">Total Utilisateurs</p>
                            <p class="text-3xl font-bold text-gray-900">'. $usersTotal . '</p>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                            <p class="text-sm text-orange-500 font-medium uppercase">Guides en attente</p>
                            <p class="text-3xl font-bold text-gray-900">'. $guideAttenteTotal . '</p>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                            <p class="text-sm text-green-500 font-medium uppercase">Comptes Actifs</p>
                            <p class="text-3xl font-bold text-gray-900">'. $actifAccountsTotal . '</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
                        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                            <h2 class="text-xl font-semibold text-gray-800">Liste des comptes</h2>
                            <input type="text" placeholder="Rechercher un email ou nom..." class="p-2 border border-gray-300 rounded-lg w-full md:w-64 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-bold">
                                    <tr>
                                        <th class="px-6 py-4">Nom / Email</th>
                                        <th class="px-6 py-4">Rôle</th>
                                        <th class="px-6 py-4">Statut Compte</th>
                                        <th class="px-6 py-4">Validation Rôle</th>
                                        <th class="px-6 py-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
        ';

    foreach($guidesAttenteList as $guide){
        echo"
            <tr class='hover:bg-gray-50 transition'>
                <td class='px-6 py-4'>
                    <div class='font-bold text-gray-900'>{$guide['nom']}</div>
                    <div class='text-sm text-gray-500'>{$guide['email']}</div>
                </td>
                <td class='px-6 py-4'>
                    <span class='px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold'>{$guide['role']}</span>
                </td>
                <td class='px-6 py-4'>
                    <span class='flex items-center text-green-600 text-sm'>
                        <span class='h-2 w-2 bg-green-600 rounded-full mr-2'></span> {$statutDict[$guide['statut']]}
                    </span>
                </td>
                <td class='px-6 py-4'>
                    <span class='px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-semibold'>{$approuveDict[$guide['role']][$guide['approuve']]}</span>
                </td>
                <td class='px-6 py-4'>
                    <div class='flex space-x-2'>
                        <button value='{$guide['id']}' name='approuve' class='bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs font-bold transition'>Approuver</button>
                        <button value='{$guide['id']}' name='desactive' class='bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1 rounded text-xs font-bold transition'>Désactiver</button>
                    </div>
                </td>
            </tr>
        ";
    }
    foreach($actifAccountsList as $account){
        echo"
            <tr class='hover:bg-gray-50 transition'>
                <td class='px-6 py-4'>
                    <div class='font-bold text-gray-900'>{$account['nom']}</div>
                    <div class='text-sm text-gray-500'>{$account['email']}</div>
                </td>
                <td class='px-6 py-4'>
                    <span class='px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold'>{$account['role']}</span>
                </td>
                <td class='px-6 py-4'>
                    <span class='flex items-center text-green-600 text-sm'>
                        <span class='h-2 w-2 bg-green-600 rounded-full mr-2'></span> {$statutDict[$account['statut']]}
                    </span>
                </td>
                <td class='px-6 py-4 text-gray-400 text-xs italic'>{$approuveDict[$account['role']][$account['approuve']]}</td>
                <td class='px-6 py-4'>
                    <button value='{$account['id']}' name='desactive' class='text-red-500 hover:text-red-700 text-sm font-semibold'>Désactiver le compte</button>
                </td>
            </tr>
        ";
    }
    foreach($inactifAccountsList as $account){
        echo"
            <tr class='hover:bg-gray-50 transition bg-red-50'>
                <td class='px-6 py-4'>
                    <div class='font-bold text-gray-900'>{$account['nom']}</div>
                    <div class='text-sm text-gray-500'>{$account['email']}</div>
                </td>
                <td class='px-6 py-4'>
                    <span class='px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold'>{$account['role']}</span>
                </td>
                <td class='px-6 py-4'>
                    <span class='flex items-center text-red-600 text-sm'>
                        <span class='h-2 w-2 bg-red-600 rounded-full mr-2'></span> {$statutDict[$account['statut']]}
                    </span>
                </td>
                <td class='px-6 py-4 text-gray-400 text-xs italic'>N/A</td>
                <td class='px-6 py-4'>
                    <button value='{$account['id']}' name='reactive' class='bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs font-bold transition'>Réactiver</button>
                </td>
            </tr>
        ";
    }
    echo '
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        <script>
            setTimeout(() => {
                try {
                    notification.style.display = "none";
                } catch { }
            }, 2000);
        </script>
        </body>
        </html>
    ';
}
function actions(){
    if(isset($_POST['approuve'])){
        request("UPDATE `utilisateurs` SET role_approuve = 1 WHERE id = ?;", "i", [$_POST['approuve']]);
        echo '<div id="notification" class="bg-green-500" style="position: absolute;top: 0;left: 45%;color: white;padding: 15px;border-radius: 5px;animation: fadeIn 0.4s ease;z-index: 100;">Ce guide a été approuvé</div>';
    }
    if(isset($_POST['desactive'])){
        request("UPDATE `utilisateurs` SET statut_compte = 0 WHERE id = ?;", "i", [$_POST['desactive']]);
        echo '<div id="notification" class="bg-green-500" style="position: absolute;top: 0;left: 45%;color: white;padding: 15px;border-radius: 5px;animation: fadeIn 0.4s ease;z-index: 100;">Ce compte a été désactivé</div>';
    }
    if(isset($_POST['reactive'])){
        request("UPDATE `utilisateurs` SET statut_compte = 1 WHERE id = ?;", "i", [$_POST['reactive']]);
        echo '<div id="notification" class="bg-green-500" style="position: absolute;top: 0;left: 45%;color: white;padding: 15px;border-radius: 5px;animation: fadeIn 0.4s ease;z-index: 100;">Ce compte a été réactivé</div>';
    }    
}

if(isset($_POST['logout'])){
    $_SESSION['loggedAccount'] = null;
    header("Location: ../index.php");
    exit(); 
}

if(checkAccess('role', 'admin')){
    actions();
    show_users_page();
}

?>