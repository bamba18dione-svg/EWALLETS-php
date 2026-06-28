<?php


namespace EWallet\Services;

use function EWallet\Repository\find_wallet_by_phone;
use function EWallet\Repository\add_wallet;
use function EWallet\Repository\update_solde;
use function EWallet\Repository\add_transaction;
use function EWallet\Repository\get_all_transactions;
use function EWallet\Repository\get_transactions_by_phone;
use function EWallet\Validator\champ_obligatoire;
use function EWallet\Validator\solde_initial;
use function EWallet\Validator\phone_unique;
use function EWallet\Validator\code_unique;
use function EWallet\Validator\phone_existe;
use function EWallet\Validator\montant_positif;
use function EWallet\Validator\solde_suffisant;

// ─── Service : Créer Wallet ───────────────────────────────────────────────────

function creer_wallet(
    string $phone,
    string $nom,
    string $solde_str,
    string $code
): array {

    $validations = [
        champ_obligatoire($phone,     'Numéro de téléphone'),
        champ_obligatoire($nom,       'Nom du client'),
        champ_obligatoire($code,      'Code secret'),
        solde_initial($solde_str),
        phone_unique($phone),
        code_unique($code),
    ];

    $erreurs = array_filter($validations, fn($v) => $v !== null);

    if (!empty($erreurs)) {
        return ['succes' => false, 'erreur' => reset($erreurs)];
    }

    add_wallet(trim($phone), trim($nom), (float)$solde_str, trim($code));

    return [
        'succes'  => true,
        'message' => "Wallet créé avec succès pour " . trim($nom)
                   . " (Tél : " . trim($phone) . ")."
                   . " Solde initial : " . (float)$solde_str . " CFA.",
    ];
}

// ─── Service : Faire un Dépôt ─────────────────────────────────────────────────

function faire_depot(string $phone, string $montant_str): array
{
    $erreurs = array_filter([
        phone_existe($phone),
        montant_positif($montant_str),
    ], fn($v) => $v !== null);

    if (!empty($erreurs)) {
        return ['succes' => false, 'erreur' => reset($erreurs)];
    }

    $montant       = (float)$montant_str;
    $wallet        = find_wallet_by_phone($phone);
    $nouveau_solde = $wallet['solde'] + $montant;

    update_solde($phone, $nouveau_solde);
    add_transaction($phone, 'DEPOT', $montant, 0.0, $nouveau_solde);

    return [
        'succes'  => true,
        'message' => "Dépôt de " . $montant . " CFA effectué."
                   . " Nouveau solde : " . $nouveau_solde . " CFA.",
    ];
}

// ─── Service : Faire un Retrait ───────────────────────────────────────────────

/**
 * RG 3.2 : Calcul des frais par paliers, plafonné à 5 000 CFA.
 */
function calculer_frais(float $montant): float
{
    if ($montant <= 10000) {
        return 200.0;
    } elseif ($montant <= 100000) {
        return 500.0;
    } else {
        return min($montant * 0.01, 5000.0);
    }
}

function faire_retrait(string $phone, string $montant_str): array
{
    $erreurs = array_filter([
        phone_existe($phone),
        montant_positif($montant_str),
    ], fn($v) => $v !== null);

    if (!empty($erreurs)) {
        return ['succes' => false, 'erreur' => reset($erreurs)];
    }

    $montant = (float)$montant_str;
    $frais   = calculer_frais($montant);

    $erreur = solde_suffisant($phone, $montant, $frais);
    if ($erreur !== null) {
        return ['succes' => false, 'erreur' => $erreur];
    }

    $wallet        = find_wallet_by_phone($phone);
    $nouveau_solde = $wallet['solde'] - $montant - $frais;

    update_solde($phone, $nouveau_solde);
    add_transaction($phone, 'RETRAIT', $montant, $frais, $nouveau_solde);

    return [
        'succes'  => true,
        'message' => "Retrait de " . $montant . " CFA effectué."
                   . " Frais : " . $frais . " CFA."
                   . " Nouveau solde : " . $nouveau_solde . " CFA.",
    ];
}

// ─── Service : Lister les Transactions ────────────────────────────────────────

function lister_transactions(string $phone = ''): array
{
    if ($phone === '') {
        return get_all_transactions();
    }

    $erreur = phone_existe($phone);
    if ($erreur !== null) {
        return ['succes' => false, 'erreur' => $erreur];
    }

    return get_transactions_by_phone($phone);
}