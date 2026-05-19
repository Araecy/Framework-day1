# Verbetervoorstellen & Overwegingen
### Beroepsexamen K1 — Voorbereide analyse

**Student:** Noah Wijnman
**Datum:** 2026-05-18
**Doel:** Dit document beschrijft bekende verbeterpunten in de huidige codebase, mogelijke
applicaties die gebouwd kunnen worden op basis van de bestaande generieke CRUD-basis, en
overwegingen voor het behalen van een "Goed"-beoordeling.

---

## 1. Bekende verbeterpunten in de huidige codebase

Dit zijn concrete problemen of ontbrekende onderdelen die al aanwezig zijn vóór de casus bekend is.
Ze moeten worden opgelost tijdens de realisatiefase (Opdracht 2).

### 1.1 `BookController::store()` — debug-code verwijderen voor oplevering

**Bestand:** [app/Controllers/BookController.php](app/Controllers/BookController.php) — regel 26

**Notitie:** De uitgecommentarieerde `dd()`-aanroep en de actieve `dd($book)` zijn aanwezig
als tijdelijke debug-helpers tijdens de voorbereiding. Deze worden verwijderd vóór oplevering.
Na het verwijderen moet `$book->save()` worden aangeroepen en wordt geredirect naar de overzichtspagina.

**Eindvorm `store()` na opruimen:**

```php
public function store(): Response
{
    $book = new Book();
    $book->setType($this->request->getPostParams('type'));
    $book->setTitle($this->request->getPostParams('title'));
    $book->setContent($this->request->getPostParams('content'));

    $book->save();

    header('Location: /books');
    exit;
}
```

**Tijdsindicatie:** 5 minuten

---

### 1.2 Ontbrekende CRUD-operaties — Read (list), Update, Delete

**Probleem:** Op dit moment zijn alleen Create (formulier + store) en een rudimentaire Show
geïmplementeerd. Voor een complete CRUD-applicatie ontbreken:

| Operatie | Status | Wat ontbreekt |
|----------|--------|--------------|
| **C**reate | Deels — `store()` slaat niet op (zie 1.1) | `save()` aanroepen, redirect |
| **R**ead (lijst) | Ontbreekt | `index()`-methode, `Book::all()`, lijsttemplate |
| **R**ead (detail) | Template bestaat maar is leeg | Template vullen met data, `Book::find($id)` |
| **U**pdate | Volledig ontbreekt | `edit()`, `update()`, `Book::update()`, formuliertemplate |
| **D**elete | Volledig ontbreekt | `destroy()`, `Book::delete()`, bevestigingslogica |

**Oplossingen per operatie:**

**Read (lijst) — `Book::all()`:**
```php
public static function all(): array
{
    $connection = Connection::getConnection();
    $stmt = $connection->pdo->query("SELECT * FROM records ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
```

**Read (detail) — `Book::find($id)`:**
```php
public static function find(int $id): ?array
{
    $connection = Connection::getConnection();
    $stmt = $connection->pdo->prepare("SELECT * FROM records WHERE id = :id LIMIT 1");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}
```

**Update — `Book::update()`:**
```php
public function update(): void
{
    $connection = Connection::getConnection();
    $stmt = $connection->pdo->prepare("
        UPDATE records SET type = :type, title = :title, body = :body WHERE id = :id
    ");
    $stmt->bindValue(':type', $this->getType());
    $stmt->bindValue(':title', $this->getTitle());
    $stmt->bindValue(':body', $this->getContent());
    $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);
    $stmt->execute();
}
```

**Delete — `Book::delete($id)`:**
```php
public static function delete(int $id): void
{
    $connection = Connection::getConnection();
    $stmt = $connection->pdo->prepare("DELETE FROM records WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}
```

**Tijdsindicatie:** 1,5 uur totaal voor alle vier operaties

---

### 1.3 Ontbrekende invoervalidatie

**Probleem:** Er is geen validatie van POST-invoer. Als een veld leeg wordt verzonden,
wordt een lege string opgeslagen — of PHP gooit een fout bij `getPostParams()` als
de sleutel niet bestaat.

**Oplossing — minimale validatie in controller:**
```php
public function store(): Response
{
    $type    = trim($this->request->getPostParams('type') ?? '');
    $title   = trim($this->request->getPostParams('title') ?? '');
    $content = trim($this->request->getPostParams('content') ?? '');

    if (empty($title) || empty($content)) {
        return $this->render('create-book.html.twig', ['error' => 'Vul alle velden in.']);
    }

    $book = new Book();
    $book->setType($type);
    $book->setTitle($title);
    $book->setContent($content);
    $book->save();

    header('Location: /books');
    exit;
}
```

**Tijdsindicatie:** 30 minuten

---

### 1.4 Geen foutafhandeling voor onbekende routes of ontbrekende records

**Probleem:** Als een gebruiker een niet-bestaand ID opvraagt (`/books/9999`) of een
niet-gedefinieerde route bezoekt, geeft de Kernel een PHP-fout (array destructuring mislukt
op een lege `$routeInfo`).

**Oplossing:** Foutafhandeling toevoegen in `Kernel::handle()`:
```php
[$status, $handler, $vars] = $routeInfo;

if ($status === FastRoute\Dispatcher::NOT_FOUND) {
    return new Response('Pagina niet gevonden.', 404);
}
if ($status === FastRoute\Dispatcher::METHOD_NOT_ALLOWED) {
    return new Response('Methode niet toegestaan.', 405);
}
```

**Tijdsindicatie:** 20 minuten

---

### 1.5 Database-verbinding bevat geen wachtwoord

**Bestand:** [database/config.php](database/config.php)

**Huidige configuratie:**
```php
"connectionString" => "mysql:host=127.0.0.1;dbname=cruduniversal;user=root;",
```

**Probleem:** Het wachtwoord ontbreekt in de connection string. PDO verwacht het wachtwoord
als derde argument in de constructor, niet in de DSN-string. In de `Connection`-klasse
wordt de volledige DSN als enige argument doorgegeven — er is geen ondersteuning voor
aparte user/password parameters.

**Actie op examendag:** De schoolserver via Plesk heeft een eigen databasegebruiker met wachtwoord.
De `Connection`-klasse moet worden uitgebreid om user en password als losse parameters te accepteren,
of de config moet worden aangepast aan de Plesk-credentials. Dit is een verplichte stap vóór
deployment naar de schoolserver.

```php
// Uitbreiding Connection::create() voor Plesk-omgeving:
public static function create(string $dsn, string $user = '', string $password = ''): static
{
    if (null === static::$instance) {
        static::$instance = new static($dsn, $user, $password);
    }
    return static::$instance;
}

private function __construct(string $dsn, string $user, string $password)
{
    $this->pdo = new PDO($dsn, $user, $password);
}
```

**Tijdsindicatie:** 15 minuten

---

### 1.6 Typo in `Connection.php`

**Bestand:** [src/Database/Connection.php](src/Database/Connection.php) — regel 7

**Probleem:** `private static $instence = null;` — `instence` is een schrijffout van `instance`.

**Oplossing:** Hernoem naar `$instance` (pas alle verwijzingen aan in hetzelfde bestand).

**Tijdsindicatie:** 5 minuten

---

### 1.7 Geen basisopmaak / styling

**Probleem:** De views hebben geen CSS. De enige view met HTML-structuur is `create-book.html.twig`.
`home.html.twig` bevat alleen `<h1>Hello world!!!</h1>`.

**Aanbeveling:** Maak een Twig base-template `base.html.twig` met een CDN-link naar een
minimaal CSS-framework. Alle andere templates extenden dit.

```twig
{# views/base.html.twig #}
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{% block title %}Applicatie{% endblock %}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
</head>
<body>
    <main class="container">
        {% block content %}{% endblock %}
    </main>
</body>
</html>
```

**Tijdsindicatie:** 30 minuten voor basisopmaak

---

## 2. Mogelijke applicaties op basis van het `records`-schema

Het bestaande schema (`id`, `type`, `title`, `body`, `created_at`) is generiek genoeg
om als basis te dienen voor meerdere applicatietypes. Hieronder staan de meest waarschijnlijke
scenario's voor de casus, met overwegingen per type.

### 2.1 Blog / Nieuwsplatform

**Geschiktheid:** Uitstekend — het schema past perfect.
- `type` → categorie of berichttype (nieuws, blog, aankondiging)
- `title` → berichttitel
- `body` → berichtinhoud
- `created_at` → publicatiedatum

**Wat extra nodig is:**
- Paginering op de overzichtspagina (LIMIT/OFFSET in SQL)
- Zoekfunctionaliteit (WHERE title LIKE :q)
- Eventueel auteursveld (extra kolom of aparte tabel)

**Tijdsindicatie extra:** 1–2 uur bovenop de basis CRUD

---

### 2.2 Takenlijst / To-Do Applicatie

**Geschiktheid:** Goed — minimale aanpassingen nodig.
- `type` → prioriteit of categorie (hoog, middel, laag)
- `title` → taaknaam
- `body` → taakomschrijving
- Toevoeging nodig: `status` kolom (open/afgerond) of `due_date`

**Wat extra nodig is:**
- Extra kolom in het schema: `ALTER TABLE records ADD status VARCHAR(20) DEFAULT 'open';`
- Filteropties op de overzichtspagina (toon alleen open taken)
- Markeer-als-afgerond functionaliteit

**Tijdsindicatie extra:** 1 uur bovenop de basis CRUD

---

### 2.3 Kennisbank / FAQ-systeem

**Geschiktheid:** Goed.
- `type` → categorie van de vraag
- `title` → de vraag
- `body` → het antwoord

**Wat extra nodig is:**
- Zoekfunctionaliteit
- Groepering op categorie in de lijstweergave

**Tijdsindicatie extra:** 1 uur bovenop de basis CRUD

---

### 2.4 Evenementenbeheer

**Geschiktheid:** Redelijk — extra velden nodig.
- `type` → type evenement
- `title` → naam evenement
- `body` → beschrijving
- Toevoeging nodig: `event_date DATETIME`, `location VARCHAR(255)`

**Tijdsindicatie extra:** 1,5–2 uur

---

## 3. Aanbevelingen voor een "Goed"-beoordeling

Om in aanmerking te komen voor een "Goed" (G) mogen maximaal 1 indicator onvoldoende zijn
over alle drie opdrachten. De volgende punten verhogen de kwaliteit bovenop "Voldoende":

| Categorie | Aanbeveling | Impact |
|-----------|------------|--------|
| **Ontwerp** | Voeg een ERD (Entity Relationship Diagram) toe aan het ontwerp naast de wireframes | Toont volledigheid van het technisch ontwerp |
| **Code-kwaliteit** | Gebruik PHP DocBlocks bij elke publieke methode — `@param`, `@return` | Voldoet aan code-conventies en verhoogt leesbaarheid |
| **Beveiliging** | Voeg CSRF-token toe aan formulieren (sessie-gebaseerd) | Toont inzicht in webbeveiliging beyond de basis |
| **Testdiepte** | Test ook edge cases: maximale invoerlengte, speciale tekens, lege database | Toont grondige testaanpak |
| **Verbetervoorstel** | Geef concrete tijdsindicaties per verbetering in uren (niet "later" of "enige tijd") | Voldoet volledig aan criterium 5 van Opdracht 3 |
| **Documentatie** | Voeg screenshots van de werkende applicatie toe aan het verslag | Maakt het bewijs van oplevering concreet |
| **Planning** | Noteer bij de planning ook de werkelijk bestede tijd per fase achteraf | Toont reflectie op het eigen werkproces |

---

## 4. Samenvatting — Prioriteitenlijst voor examendag

Op volgorde van belang voor het behalen van een voldoende:

1. `Connection::create()` uitbreiden met user/password parameters — **kritiek voor Plesk-deployment**
2. `database/config.php` bijwerken met de Plesk-databasecredentials (host, dbname, user, password) — **kritiek**
3. Debug-code (`dd()`) verwijderen en `$book->save()` activeren in `store()` — **vóór oplevering**
4. `Book::all()` implementeren en lijstpagina maken — **kritiek voor UC-01**
5. `Book::find($id)` implementeren en detailpagina vullen — **hoog**
6. Foutafhandeling in `Kernel::handle()` voor 404 — **hoog**
7. Invoervalidatie toevoegen in `store()` — **hoog**
8. Update-functionaliteit toevoegen — **middel**
9. Delete-functionaliteit toevoegen — **middel**
10. Basisopmaak via Twig base-template + CDN CSS — **middel**
11. Typo in `Connection.php` (`$instence` → `$instance`) — **laag**
12. Testplan en testrapport invullen op dag 3 — **vereist voor Opdracht 3**

---

*Document gegenereerd: 2026-05-18*
