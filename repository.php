<?php


namespace EWallet\Repository;


$wallets      = [];
$transactions = [];


function get_all_wallets(): array
{
    global $wallets;
    return $wallets;
}

function find_wallet_by_phone(string $telephone): ?array
{
    global $wallets;
    $result = array_filter($wallets, fn($w) => $w['phone'] === $phone);
    return reset($result) ?: null;
}

function code_existe(string $code): bool
{
    global $wallets;
    $codes = array_column($wallets, 'code');
    return in_array($code, $codes);
}

function add_wallet(string $telephone, string $nom, float $solde, string $code): void
{
    global $wallets;
    $wallets[] = [
        'telephone' => $telephone,
        'nom'   => $nom,
        'solde' => $solde,
        'code'  => $code,
    ];
}

function update_solde(string $telephone, float $nouveau_solde): void
{
    global $wallets;
    $wallets = array_map(function ($w) use ($telephone, $nouveau_solde) {
        if ($w['telephone'] === $telephone) {
            $w['solde'] = $nouveau_solde;
        }
        return $w;
    }, $wallets);
}

// ─── Transactions ─────────────────────────────────────────────────────────────

function add_transaction(
    string $telephone,
    string $type,
    float  $montant,
    float  $frais,
    float  $solde_apres
): void {
    global $transactions;
    $transactions[] = [
        'id'          => count($transactions) + 1,
        'telephone'       => $telephone,
        'type'        => $type,
        'montant'     => $montant,
        'frais'       => $frais,
        'solde_apres' => $solde_apres,
        'date'        => date('d/m/Y H:i:s'),
    ];
}

function get_all_transactions(): array
{
    global $transactions;
    return $transactions;
}

function get_transactions_by_telephone(string $telephone): array
{
    global $transactions;
    return array_values(
        array_filter($transactions, fn($t) => $t['phone'] === $phone)
    );
}