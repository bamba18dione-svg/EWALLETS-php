<?php


require_once __DIR__ . '/repository.php';
require_once __DIR__ . '/validator.php';
require_once __DIR__ . '/services.php';
require_once __DIR__ . '/controller.php';

use function EWallet\Controller\dispatcher;

// ─── Affichage du menu ────────────────────────────────────────────────────────

function afficher_menu(): void
{
    echo "\n";
    echo "  ** Menu Distributeur **\n";
    echo "  1 - Créer Wallet\n";
    echo "  2 - Faire Dépôt\n";
    echo "  3 - Faire Retrait\n";
    echo "  4 - Lister les Transactions\n";
    echo "  0 - Quitter\n";
    echo "\n";
    echo "  Votre choix : ";
}


do {
    afficher_menu();
    $choix = trim(fgets(STDIN));

    if ($choix !== '0') {
        dispatcher($choix);
    }

} while ($choix !== '0');

echo "\n  Au revoir !\n\n";