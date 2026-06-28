<?php

function ajouterWallet($wallet)
{
    global $wallets;

    $wallets[] = $wallet;
}

 
function mettreAJourSolde($telephone, $montant)
{
    global $wallets;
 
    $index = rechercherWallet('telephone', $telephone);
    $wallets[$index]['solde'] += $montant;
}
 
function ajouterTransaction($transaction)
{
    global $transactions;
 
    $transactions[] = $transaction;
}

