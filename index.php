<?php

require_once __DIR__ . '/services.php';
require_once __DIR__ . '/repository.php';
require_once __DIR__ . '/validator.php';
require_once __DIR__ . '/controller.php';

$wallets = []; 

do {
    echo "\n===== MENU =====\n";
    echo "1 - Créer Wallet\n";
    echo "2 - Faire Dépôt\n";
    echo "3 - Faire Retrait\n";
    echo "4 - Lister Transactions\n";
    echo "0 - Quitter\n";

    $choix = (int) readline("Choisissez une option : ");

    switch ($choix) {

        case 1:
            routerAction("create", $wallets);
            break;

        case 2:
            routerAction("depot", $wallets);
            break;

        case 3:
            routerAction("retrait", $wallets);
            break;

        case 4:
            routerAction("liste", $wallets);
            break;

        case 0:
            echo "Quitter\n";
            break;

        default:
            echo "Choix invalide\n";
    }

} while ($choix != 0);