# E-WALLET — SPRINT 2 (PARTIE B)
### Professionnalisation, Architecture & PHP Moderne
*Sonatel Academy — Orange Digital Center*

---

## Sommaire

- Fonctions Anonymes, Closures & Arrow Functions
- Fonctions de Tableaux Natives (`array_*`)
- Gestionnaire de Dépendances Composer
- Écosystème PHP & Namespaces

---

---

## DIAPOSITIVE 1 : Titre de la Présentation

### 🖥️ Visuel (Sur l'écran)

```
┌─────────────────────────────────────────────────────┐
│         E-WALLET — SPRINT 2 (PARTIE B)              │
│   Professionnalisation, Architecture & PHP Moderne  │
│                                                     │
│  • Fonctions Anonymes, Closures & Arrow Functions   │
│  • Fonctions de Tableaux Natives (array_*)          │
│  • Gestionnaire de Dépendances Composer             │
│  • Écosystème PHP & Namespaces                      │
└─────────────────────────────────────────────────────┘
```

---

## DIAPOSITIVE 2 : Fonctions Anonymes, Closures & Arrow Functions

### 🖥️ Visuel (Sur l'écran)

- **Fonction Anonyme** : Fonction sans nom, utile comme argument de rappel (callback).
- **Closure (Fermeture)** : Fonction anonyme capable de capturer des variables du scope parent avec le mot-clé `use`.
- **Arrow Function (PHP 7.4+)** : Syntaxe plus courte ( `fn() => expr` ) avec capture automatique par valeur du scope parent.

```php
// Exemple 1 : Closure avec 'use' (utilisé dans repository.php)
$found = array_filter($wallets, function(array $w) use ($telephone): bool {
    return $w["telephone"] === $telephone;
});

// Exemple 2 : Arrow function (syntaxe compacte équivalente)
$found = array_filter($wallets, fn(array $w) => $w["telephone"] === $telephone);

// Exemple 3 : Passage d'une fonction anonyme en argument
$saluer = function(string $nom): string {
    return "Bonjour, " . $nom . " !";
};
echo $saluer("Bamba");  // Bonjour, Bamba !
```



---

## DIAPOSITIVE 3 : Fonctions de Tableaux Natives (array_*)

### 🖥️ Visuel (Sur l'écran)

- **`array_filter()`** : Filtre un tableau selon un prédicat. Retourne les éléments pour lesquels le callback retourne `true`.
- **`array_map()`** : Applique une fonction à chaque élément et retourne un nouveau tableau transformé.
- **`array_reduce()`** : Réduit un tableau à une valeur unique (somme, concaténation, etc.) grâce à un accumulateur.
- **`array_column()`** : Extrait une colonne d'un tableau multidimensionnel. Utilisé pour vérifier l'unicité.
- **`in_array()`** : Vérifie si une valeur existe dans un tableau. Autorisé en Partie B.

```php
// Trouver un wallet par téléphone
$result = array_filter($wallets, fn($w) => $w['phone'] === $phone);
$wallet = reset($result) ?: null;

// Vérifier l'unicité du code secret
$codes  = array_column($wallets, 'code');
$existe = in_array($code, $codes);

// Mettre à jour le solde
$wallets = array_map(function($w) use ($phone, $solde) {
    if ($w['phone'] === $phone) $w['solde'] = $solde;
    return $w;
}, $wallets);

// Calculer le total des transactions
$total = array_reduce($transactions, fn($acc, $t) => $acc + $t['montant'], 0);
```



---

## DIAPOSITIVE 4 : Gestionnaire de Dépendances Composer

### 🖥️ Visuel (Sur l'écran)

- **Composer** : Outil CLI qui gère automatiquement les bibliothèques PHP dont un projet dépend.
- **`composer.json`** : Fichier de configuration déclarant les dépendances et les règles d'autoloading PSR-4.
- **`composer.lock`** : Verrouille les versions exactes installées pour garantir un environnement identique.
- **`vendor/autoload.php`** : Fichier généré par Composer qui charge automatiquement toutes les classes.

```json
// composer.json — exemple pour le projet E-Wallet
{
    "name": "bamba/ewallet",
    "autoload": {
        "psr-4": {
            "EWallet\\": "src/"
        }
    },
    "require": {
        "php": ">=7.4"
    }
}
```

```bash
# Commandes essentielles
$ composer init                    # Initialise composer.json
$ composer require vendor/package  # Installe un package
$ composer install                 # Installe depuis composer.lock
$ composer dump-autoload           # Régénère l'autoloader
```



---

## DIAPOSITIVE 5 : Écosystème PHP — Namespaces & Packagist.org

### 🖥️ Visuel (Sur l'écran)

**Namespaces PHP**

- **Namespace** : Espace de nommage qui organise et cloisonne les fonctions/classes pour éviter les conflits de noms.
- **`use`** : Importe une fonction ou classe d'un namespace dans le fichier courant.

```php
// Déclarer un namespace
namespace EWallet\Services;

// Importer depuis un autre namespace
use function EWallet\Repository\find_wallet_by_phone;
use function EWallet\Validator\phone_existe;

// Architecture du projet
// EWallet\Repository  → repository.php  (accès données)
// EWallet\Validator   → validator.php   (règles de gestion)
// EWallet\Services    → services.php    (logique métier)
// EWallet\Controller  → controller.php  (saisies & affichage)
```

**Packagist.org**

- **Packagist.org** : Registre central des packages PHP. Source officielle utilisée par Composer.
- Plus de **350 000 packages** disponibles publiquement.
- Packages populaires : `symfony/console`, `vlucas/phpdotenv`, `ramsey/uuid`, `guzzlehttp/guzzle`

```bash
$ composer require symfony/console
$ composer require vlucas/phpdotenv
```



---

## DIAPOSITIVE 6 : Conclusion & Récapitulatif

### 🖥️ Visuel (Sur l'écran)

- ✅ Fonctions anonymes, closures & arrow functions — callbacks expressifs
- ✅ `array_filter` / `array_map` / `array_reduce` — remplacement des boucles manuelles
- ✅ Namespaces PSR-4 — architecture modulaire et cloisonnée
- ✅ Composer — gestion des dépendances + autoloading automatique
- ✅ Packagist.org — écosystème de 350 000+ packages PHP
- ✅ Git SemVer `v1.0.0` → `v2.0.0` + Conventional Commits + Branch Strategy



---

*Bamba — Sonatel Academy — Orange Digital Center*