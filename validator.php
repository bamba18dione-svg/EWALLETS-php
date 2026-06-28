<?php

function saisir($label)
{
    print($label . " : ");
    return trim(fgets(STDIN));
}

function nomValide($nom)
{
    $nom = trim((string)$nom);
    if ($nom === "") {
        return "invalide";
    }

    return "ok";
}

function afficherErreur($message)
{
    print("[ERREUR] " . $message . "\n");
}


function validerPrefixes($telephone)
{
    $prefixes = ["77", "78", "76", "75", "70"];

    for ($i = 0; $i < count($prefixes); $i++) {

        if ($telephone[0] . $telephone[1] === $prefixes[$i]) {
            return true;
        }
    }

    return false;
}

function telephoneValide($telephone, $longueur = 9)
{
    if (strlen($telephone) != $longueur) {
        return "invalide";
    }

    if (!validerPrefixes($telephone)) {
        return "invalide";
    }

    return "ok";
}

function codeValide($code, $longueur = 4)
{
    if (strlen($code) == $longueur) {
        return $code;
    }

    return "invalide";
}

function soldeValide($solde)
{
    if ($solde >= 0) {
        return $solde;
    }

    return "invalide";
} 
function validerTelephoneUnique($telephone)
{
    if (rechercherWallet('telephone', $telephone) !== "introuvable") {
        return "Téléphone déjà utilisé.";
    }
    return "ok";
}
function validerCodeUnique($code)
{
    if (rechercherWallet('code', $code) !== "introuvable") {
        return "Code déjà utilisé.";
    }
    return "ok";
}


function validerCreationWallet($nom, $telephone, $code, $solde)
{
    $validations = [
        nomValide($nom),
        telephoneValide($telephone),
        validerPrefixes($telephone),
        validerTelephoneUnique($telephone),
        codeValide($code),
        validerCodeUnique($code),
        soldeValide($solde),
    ];
 
    for ($i = 0; $i < count($validations); $i++) {
        if ($validations[$i] !== "ok") {
            return $validations[$i];
        }
    }
    return "ok";
}

 
function afficherSucces($message)
{
    print("[OK] " . $message . "\n");
}

function montantValide($montant)
{
    if ($montant > 0) {
        return $montant;
    }
    return "invalide";
}

function validerMontant($montant)
{
    if (montantValide($montant) === "invalide") {
        return "Montant invalide : doit être strictement positif.";
    }
    return "ok";
}
 
function rechercherWallet($champ, $valeur)
{
    global $wallets;
 
    for ($i = 0; $i < count($wallets); $i++) {
        if ($wallets[$i][$champ] === $valeur) {
            return $i;
        }
    }
    return "introuvable";
}


function validerWalletExiste($telephone)
{
    if (rechercherWallet('telephone', $telephone) === "introuvable") {
        return "Wallet introuvable.";
    }
    return "ok";
}

function validerDepot($telephone, $montant)
{
    $validations = [
        validerWalletExiste($telephone),
        validerMontant($montant),
    ];
 
    for ($i = 0; $i < count($validations); $i++) {
        if ($validations[$i] !== "ok") {
            return $validations[$i];
        }
    }
    return "ok";
}