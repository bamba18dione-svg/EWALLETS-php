<?php


namespace EWallet\Controller;

use function EWallet\Services\creer_wallet;
use function EWallet\Services\faire_depot;
use function EWallet\Services\faire_retrait;
use function EWallet\Services\lister_transactions;

// ─── Utilitaires console ──────────────────────────────────────────────────────

function afficher_separateur(): void
{
    echo "-----------------------------------------------\n";
}

function afficher_succes(string $message): void
{
    echo "\n  [OK] " . $message . "\n\n";
}

function afficher_erreur(string $message): void
{
    echo "\n  [ERREUR] " . $message . "\n\n";
}

function lire_saisie(string $label): string
{
    echo "  " . $label . " : ";
    return trim(fgets(STDIN));
}


function saisir_wallet(): array
{
    return [
        'phone' => lire_saisie("Numéro de téléphone"),
        'nom'   => lire_saisie("Nom du client"),
        'solde' => lire_saisie("Solde initial (CFA)"),
        'code'  => lire_saisie("Code secret"),
    ];
}

function saisir_depot(): array
{
    return [
        'phone'   => lire_saisie("Numéro de téléphone"),
        'montant' => lire_saisie("Montant à déposer (CFA)"),
    ];
}

function saisir_retrait(): array
{
    return [
        'phone'   => lire_saisie("Numéro de téléphone"),
        'montant' => lire_saisie("Montant à retirer (CFA)"),
    ];
}

// ─── Contrôleurs ──────────────────────────────────────────────────────────────

function ctrl_creer_wallet(): void
{
    afficher_separateur();
    echo "  CREATION D'UN WALLET\n";
    afficher_separateur();

    $donnees = saisir_wallet();
    $result  = creer_wallet(
        $donnees['phone'],
        $donnees['nom'],
        $donnees['solde'],
        $donnees['code']
    );

    $result['succes'] ? afficher_succes($result['message'])
                      : afficher_erreur($result['erreur']);
}

function ctrl_faire_depot(): void
{
    afficher_separateur();
    echo "  DEPOT SUR WALLET\n";
    afficher_separateur();

    $donnees = saisir_depot();
    $result  = faire_depot($donnees['phone'], $donnees['montant']);

    $result['succes'] ? afficher_succes($result['message'])
                      : afficher_erreur($result['erreur']);
}

function ctrl_faire_retrait(): void
{
    afficher_separateur();
    echo "  RETRAIT SUR WALLET\n";
    afficher_separateur();

    $donnees = saisir_retrait();
    $result  = faire_retrait($donnees['phone'], $donnees['montant']);

    $result['succes'] ? afficher_succes($result['message'])
                      : afficher_erreur($result['erreur']);
}

function ctrl_lister_transactions(): void
{
    afficher_separateur();
    echo "  HISTORIQUE DES TRANSACTIONS\n";
    afficher_separateur();

    echo "  [1] Toutes les transactions\n";
    echo "  [2] Transactions d'un wallet spécifique\n";
    $choix = lire_saisie("Votre choix");

    $transactions = match($choix) {
        '1'     => lister_transactions(),
        '2'     => lister_transactions(lire_saisie("Numéro de téléphone")),
        default => null,
    };

    if ($transactions === null) {
        afficher_erreur("Choix invalide.");
        return;
    }

    if (isset($transactions['succes']) && $transactions['succes'] === false) {
        afficher_erreur($transactions['erreur']);
        return;
    }

    echo "\n";

    if (empty($transactions)) {
        echo "  Aucune transaction enregistrée.\n\n";
        return;
    }

    echo "  ID   | Téléphone       | Type    | Montant (CFA) | Frais (CFA) | Solde après  | Date\n";
    echo "  " . str_repeat('-', 90) . "\n";

    array_walk($transactions, function ($t) {
        echo "  " . $t['id']
           . "    | " . $t['phone']
           . " | " . $t['type']
           . "  | " . $t['montant']
           . "           | " . $t['frais']
           . "       | " . $t['solde_apres']
           . "      | " . $t['date'] . "\n";
    });

    echo "\n";
}


function dispatcher(string $choix): void
{
    match($choix) {
        '1'     => ctrl_creer_wallet(),
        '2'     => ctrl_faire_depot(),
        '3'     => ctrl_faire_retrait(),
        '4'     => ctrl_lister_transactions(),
        default => echo "\n  Choix invalide, veuillez réessayer.\n\n",
    };
}