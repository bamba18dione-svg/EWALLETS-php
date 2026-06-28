<?php

function routerAction(string $choix, array &$wallets, array &$transactions): void
{
    
    switch ($choix) {

        // ── OPTION 1 : Créer un Wallet ───────────────────────
        case '1':
           
            echo "Numéro de téléphone : ";
            $telephone = trim(fgets(STDIN));

            echo "Nom du client       : ";
            $nom = trim(fgets(STDIN));

            echo "Solde initial (CFA) : ";
            $solde = trim(fgets(STDIN));

            echo "Code secret         : ";
            $secret = trim(fgets(STDIN));

            
            $resultat = serviceCreerWallet($telephone, $nom, $solde, $secret, $wallets, $transactions);

            
            echo "\n" . $resultat['message'] . "\n";
            break;  


        // ── OPTION 2 : Faire un Dépôt ────────────────────────
        case '2':
          
            echo "Numéro de téléphone : ";
            $telephone = trim(fgets(STDIN));

            echo "Montant à déposer (CFA) : ";
            $montant = trim(fgets(STDIN));

            $resultat = serviceDeposer($telephone, $montant, $wallets, $transactions);

            echo "\n" . $resultat['message'] . "\n";
            break;


        // ── OPTION 3 : Faire un Retrait ──────────────────────
        case '3':
            
            echo "Numéro de téléphone : ";
            $telephone = trim(fgets(STDIN));

            echo "Montant à retirer (CFA) : ";
            $montant = trim(fgets(STDIN));

            $resultat = serviceRetirer($telephone, $montant, $wallets, $transactions);

            echo "\n" . $resultat['message'] . "\n";
            break;

        // ── OPTION 4 : Lister les Transactions ───────────────
        case '4':
           
            echo "Filtrer par numéro de téléphone ?\n";
            echo "(Appuyer sur ENTRÉE pour voir TOUTES les transactions) : ";
            $filtre = trim(fgets(STDIN));
            $telephoneFiltre = ($filtre === '') ? null : $filtre;

            $affichage = serviceListerTransactions($wallets, $transactions, $telephoneFiltre);
            echo $affichage . "\n";
            break;
       
        default:
            echo "❌ Action non reconnue : " . $choix . "\n";
            break;
    }
}