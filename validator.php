<?php


namespace EWallet\Validator;

use function EWallet\Repository\find_wallet_by_phone;
use function EWallet\Repository\code_existe;


function champ_obligatoire(string $valeur, string $label): ?string
{
    if (trim($valeur) === '') {
        return "Le champ '{$label}' est obligatoire.";
    }
    return null;
}

function solde_initial(string $valeur): ?string
{
    if (!is_numeric($valeur)) {
        return "Le solde initial doit être un nombre valide.";
    }
    if ((float)$valeur < 0) {
        return "Le solde initial doit être positif ou nul (>= 0).";
    }
    return null;
}

function phone_unique(string $phone): ?string
{
    if (find_wallet_by_phone($phone) !== null) {
        return "Ce numéro de téléphone est déjà associé à un wallet.";
    }
    return null;
}

function code_unique(string $code): ?string
{
    if (code_existe($code)) {
        return "Ce code secret est déjà utilisé par un autre wallet.";
    }
    return null;
}


function phone_existe(string $phone): ?string
{
    if (find_wallet_by_phone($phone) === null) {
        return "Aucun wallet trouvé pour ce numéro de téléphone.";
    }
    return null;
}

function montant_positif(string $valeur): ?string
{
    if (!is_numeric($valeur) || (float)$valeur <= 0) {
        return "Le montant doit être strictement positif (> 0).";
    }
    return null;
}

function solde_suffisant(string $phone, float $montant, float $frais): ?string
{
    $wallet       = find_wallet_by_phone($phone);
    $total_requis = $montant + $frais;

    if ($wallet['solde'] < $total_requis) {
        return "Solde insuffisant. "
             . "Disponible : " . $wallet['solde'] . " CFA | "
             . "Requis : " . $total_requis . " CFA.";
    }
    return null;
}