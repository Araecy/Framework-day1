# Beroepsexamen K1 - Realiseert Software
### Projectplan - WK Voetbal Finale Inschrijfsysteem

**Student:** Noah Wijnman
**Datum:** 2026-05-19
**Opleiding:** Software Developer - BOL NIV 4 | CREBO 25604
**Kerntaak:** B1-K1 Realiseert software
**Opdrachtgever:** Stadion de Kuip

---

## Inhoudsopgave

1. [Samenvatting / Debriefing](#1-samenvatting--debriefing)
2. [Planning](#2-planning)
3. [Functioneel Ontwerp](#3-functioneel-ontwerp)
   - 3.1 [Use-case diagram](#31-use-case-diagram)
   - 3.2 [Flowchart](#32-flowchart)
   - 3.3 [Wireframes](#33-wireframes)
4. [Technisch Ontwerp](#4-technisch-ontwerp)
   - 4.1 [Gebruikte technieken](#41-gebruikte-technieken)
   - 4.2 [Architectuur (MVC)](#42-architectuur-mvc)
   - 4.3 [Database ontwerp (ERD)](#43-database-ontwerp-erd)
   - 4.4 [Route-overzicht](#44-route-overzicht)
   - 4.5 [Privacy & Veiligheid](#45-privacy--veiligheid)
   - 4.6 [Back-up plan (Git)](#46-back-up-plan-git)
5. [Oplevering](#5-oplevering)
   - 5.1 [Deadline](#51-deadline)
   - 5.2 [Testplan](#52-testplan)
   - 5.3 [Oplevering aan de klant](#53-oplevering-aan-de-klant)
6. [Inlog informatie](#6-inlog-informatie)

---

## 1. Samenvatting / Debriefing

### Wat wil de klant?

Stadion de Kuip wil fans de mogelijkheid geven de WK voetbal finale live in het stadion te bekijken.
Maximaal **1.000 fans** kunnen een ticket kopen voor toegang tot het stadion op **zondag 19 juli**.

De klant wil een webapplicatie waarmee fans zich kunnen inschrijven voor dit evenement, hun eigen gegevens kunnen beheren en zich kunnen uitschrijven. Een admin heeft inzicht in alle inschrijvingen en kan deze verwijderen indien nodig.

### Voor wie is de applicatie?

- **Fans** van het WK voetbal die zich willen inschrijven voor de finale-avond in het stadion.
- **Stadion de Kuip (admin)** die de inschrijvingen wil beheren en overzien.

### Welke functionaliteiten worden verwacht?

**Homepage (`/`)**
- Informatie over het evenement:
  - Datum en tijdstip waarop het stadion opengaat (zondag 19 juli).
  - Regels voor bezoekers (geen vuurwerk, geen blikjes of flesjes, geen eigen drank, etc.).
- Mogelijkheid om door te klikken naar inschrijven of inloggen.
- Aantal beschikbare plaatsen zichtbaar (max. 1.000).

**Inschrijven (`/register`)**
- Registratieformulier met de volgende velden:
  - Naam, adres en woonplaats.
  - Telefoonnummer.
  - E-mailadres (wordt gebruikt voor bevestiging en inloggen).
  - Geboortedatum.
  - Geslacht.
  - Wachtwoord (voor latere login).
- Na succesvolle inschrijving: bevestigingsmail (gesimuleerd).
- Betaling gesimuleerd via e-mail; na bevestiging wordt het ticket binnen 24 uur verstuurd.
- Inschrijving wordt geblokkeerd zodra 1.000 fans zijn bereikt.

**Inloggen / Uitloggen (`/login`, `/logout`)**
- Fan logt in met e-mailadres en wachtwoord.
- Na inloggen: doorsturen naar fan-pagina.

**Fan-pagina (`/profile`)**
- Fan kan eigen gegevens inzien.
- Fan kan eigen gegevens aanpassen en opslaan.
- Fan kan zich uitschrijven (account verwijderen).

**Admin-pagina (`/admin`)**
- Toegankelijk met een vooraf bepaalde login:
  - Gebruikersnaam: `admin` | Wachtwoord: `#1Geheim`
- Overzicht van alle inschrijvingen.
- Admin kan afzonderlijke inschrijvingen verwijderen.

### Wat is het eindresultaat?

Een werkende PHP-webapplicatie, gehost op de schoolserver (Plesk), waarmee fans zich kunnen inschrijven voor de WK finale-avond in Stadion de Kuip. De applicatie biedt een fan-portaal en een beveiligd admin-dashboard.

---

## 2. Planning

**Totale beschikbare tijd: 13 uur (Opdracht 2 - Realisatie)**

| Fase | Activiteit | Tijd |
|------|-----------|------|
| 1 | Casus analyseren, `accounts`-tabel aanmaken in phpMyAdmin via Plesk | 30 min |
| 2 | Routes definiëren: `/`, `/register`, `/login`, `/logout`, `/profile`, `/admin` | 30 min |
| 3 | `Account`-model: properties, `save()`, `findByEmail()`, `findById()`, `getAll()`, `delete()` | 1 uur |
| 4 | `RegistrationController` + registratieformulier (validatie, wachtwoord-hashing, limietcheck) | 1,5 uur |
| 5 | `AuthController` (inloggen, sessie aanmaken, uitloggen) | 1 uur |
| 6 | `FanController` (profiel bekijken, gegevens aanpassen, uitschrijven) | 1 uur |
| 7 | `AdminController` (alle inschrijvingen tonen, verwijderen) | 1 uur |
| 8 | Twig-views aanmaken: `home`, `register`, `login`, `profile`, `admin` | 1,5 uur |
| 9 | **Eerste testmoment** - routes, formulieren, sessies, database-opslag controleren | 30 min |
| 10 | Foutafhandeling, invoervalidatie, sessiebeveiliging per pagina | 45 min |
| 11 | Basisopmaak via CSS CDN (Bootstrap of Pico CSS) | 45 min |
| 12 | **Tweede testmoment** - alle use cases volledig doorlopen | 30 min |
| 13 | Buffer voor onverwachte problemen | 1,5 uur |
| 14 | Code opschonen, commentaar, code-conventies controleren | 30 min |
| **Totaal** | | **13 uur** |

> Testmomenten zijn bewust ingepland tijdens de realisatie - niet alleen aan het einde.
> De buffer van 1,5 uur wordt ingezet als een eerdere fase uitloopt.

---

## 3. Functioneel Ontwerp

### 3.1 Use-case diagram

```
                         WK Finale Inschrijfsysteem
  ================================================================

  +----------+     evenement info bekijken        +-------------+
  |          |--------------------------------->  |             |
  |          |     inschrijven                    |   Systeem   |
  |   Fan    |--------------------------------->  |             |
  | (gast)   |                                    +-------------+
  +----------+

  +----------+     inloggen                       +-------------+
  |          |--------------------------------->  |             |
  |          |     eigen gegevens bekijken        |   Systeem   |
  |   Fan    |--------------------------------->  |             |
  | (ingelogd|     eigen gegevens aanpassen  -->  |             |
  |          |     uitschrijven              -->  |             |
  +----------+                                    +-------------+

  +----------+     inloggen als admin             +-------------+
  |          |--------------------------------->  |             |
  |  Admin   |     alle inschrijvingen bekijken   |   Systeem   |
  |          |--------------------------------->  |             |
  |          |     inschrijving verwijderen  -->  |             |
  +----------+                                    +-------------+
```

### 3.2 Flowchart

**Registratiestroom:**

```
  [Start] --> Bezoeker opent homepage
                      |
                      v
             Klik op "Inschrijven"
                      |
                      v
          Is het maximumaantal (1000) bereikt?
               /                  \
             Ja                   Nee
              |                    |
              v                    v
    [Melding: vol]      Toon registratieformulier
                                   |
                                   v
                      Fan vult formulier in & verstuurt
                                   |
                                   v
                       Validatie: zijn alle velden geldig?
                            /              \
                          Nee             Ja
                           |               |
                           v               v
                   [Foutmelding      Controleer: e-mail
                    per veld]        al geregistreerd?
                                      /         \
                                    Ja           Nee
                                     |            |
                                     v            v
                              [Foutmelding   Sla account op
                               duplicaat]    (wachtwoord gehasht)
                                                  |
                                                  v
                                      Stuur bevestigingsmail
                                                  |
                                                  v
                                      [Doorsturen naar /login]
```

**Adminstroom:**

```
  [Start] --> Admin opent /admin
                    |
                    v
           Is admin ingelogd (sessie)?
               /           \
             Nee            Ja
              |              |
              v              v
       [Doorsturen    Toon alle inschrijvingen
        naar /login]         |
                             v
                    Admin kiest "Verwijderen"
                             |
                             v
                  Verwijder account uit database
                             |
                             v
                    [Toon bijgewerkte lijst]
```

### 3.3 Wireframes

#### Scherm 1 - Homepage (`/`)

```
+----------------------------------------------------------+
|  STADION DE KUIP                          [Inloggen]     |
+----------------------------------------------------------+
|                                                          |
|   WK VOETBAL FINALE 2026                                 |
|   Zondag 19 juli - Stadion opent om 14:00 uur            |
|                                                          |
|   --------------------------------------------------------|
|   REGELS VOOR BEZOEKERS                                  |
|   • Geen vuurwerk meenemen                               |
|   • Geen blikjes of flesjes (eigen drank)                |
|   • Geen grote tassen (max. A4-formaat)                  |
|   • Houd je toegangsticket gereed bij de ingang          |
|   --------------------------------------------------------|
|                                                          |
|   Beschikbare plaatsen: 847 / 1.000                      |
|                                                          |
|            [ SCHRIJF JE IN ]                             |
|                                                          |
+----------------------------------------------------------+
```

#### Scherm 2 - Inschrijfformulier (`/register`)

```
+----------------------------------------------------------+
|  STADION DE KUIP                          [Inloggen]     |
+----------------------------------------------------------+
|                                                          |
|   INSCHRIJVEN - WK FINALE                                |
|                                                          |
|   Naam           [ ________________________________ ]    |
|   Adres          [ ________________________________ ]    |
|   Woonplaats     [ ________________________________ ]    |
|   Telefoonnummer [ ________________________________ ]    |
|   E-mailadres    [ ________________________________ ]    |
|   Geboortedatum  [ ________________________________ ]    |
|   Geslacht       [ man               v ]                 |
|   Wachtwoord     [ ________________________________ ]    |
|   Herhaal ww     [ ________________________________ ]    |
|                                                          |
|              [ INSCHRIJVEN ]                             |
|                                                          |
|   Al ingeschreven?  [Inloggen]                           |
|                                                          |
+----------------------------------------------------------+
```

#### Scherm 3 - Loginpagina (`/login`)

```
+----------------------------------------------------------+
|  STADION DE KUIP                                         |
+----------------------------------------------------------+
|                                                          |
|   INLOGGEN                                               |
|                                                          |
|   E-mailadres  [ ________________________________ ]      |
|   Wachtwoord   [ ________________________________ ]      |
|                                                          |
|                [ INLOGGEN ]                              |
|                                                          |
|   Nog niet ingeschreven?  [Inschrijven]                  |
|                                                          |
+----------------------------------------------------------+
```

#### Scherm 4 - Fan-pagina / Profiel (`/profile`)

```
+----------------------------------------------------------+
|  STADION DE KUIP                          [Uitloggen]    |
+----------------------------------------------------------+
|                                                          |
|   MIJN INSCHRIJVING                                      |
|   Ticket status: IN BEHANDELING / BEVESTIGD              |
|                                                          |
|   --------------------------------------------------------|
|   MIJN GEGEVENS                                          |
|                                                          |
|   Naam           [ Noah Wijnman _________________ ]      |
|   Adres          [ Voorbeeldstraat 1 _____________]      |
|   Woonplaats     [ Rotterdam ___________________ ]       |
|   Telefoonnummer [ 0612345678 _________________ ]        |
|   E-mailadres    [ noah@example.com ____________ ]       |
|   Geboortedatum  [ 01-01-2000 _________________ ]        |
|   Geslacht       [ man               v ]                 |
|                                                          |
|   [ OPSLAAN ]                     [ UITSCHRIJVEN ]       |
|                                                          |
+----------------------------------------------------------+
```

#### Scherm 5 - Admin-pagina (`/admin`)

```
+----------------------------------------------------------+
|  STADION DE KUIP - ADMIN                  [Uitloggen]    |
+----------------------------------------------------------+
|                                                          |
|   ALLE INSCHRIJVINGEN                                    |
|   Totaal: 153 / 1.000                                    |
|                                                          |
|  +----+------------------+---------------------+-------+ |
|  | #  | Naam             | E-mail              | Del   | |
|  +----+------------------+---------------------+-------+ |
|  |  1 | Jan de Vries     | jan@example.com     | [X]   | |
|  |  2 | Maria Pieterse   | mp@example.com      | [X]   | |
|  |  3 | Kees Bakker      | kees@example.com    | [X]   | |
|  | .. | ...              | ...                 | [X]   | |
|  +----+------------------+---------------------+-------+ |
|                                                          |
+----------------------------------------------------------+
```

**Overzicht van schermen:**

| Scherm | Route | Toegankelijk voor |
|--------|-------|------------------|
| Homepage | `/` | Iedereen |
| Inschrijven | `/register` | Niet-ingelogde bezoekers |
| Inloggen | `/login` | Niet-ingelogde bezoekers |
| Fan-pagina | `/profile` | Ingelogde fans |
| Admin-pagina | `/admin` | Admin (hardcoded sessie) |

**Use cases:**

| Use Case | Beschrijving | HTTP-methode | Actor | Prioriteit |
|----------|-------------|-------------|-------|------------|
| UC-01 | Evenementinformatie bekijken | GET `/` | Bezoeker | Hoog |
| UC-02 | Inschrijven voor het evenement | GET + POST `/register` | Bezoeker | Hoog |
| UC-03 | Inloggen als fan | GET + POST `/login` | Fan | Hoog |
| UC-04 | Uitloggen | POST `/logout` | Fan / Admin | Hoog |
| UC-05 | Eigen gegevens bekijken | GET `/profile` | Fan (ingelogd) | Hoog |
| UC-06 | Eigen gegevens aanpassen | POST `/profile/update` | Fan (ingelogd) | Middel |
| UC-07 | Uitschrijven van evenement | POST `/profile/delete` | Fan (ingelogd) | Middel |
| UC-08 | Alle inschrijvingen bekijken | GET `/admin` | Admin | Hoog |
| UC-09 | Inschrijving verwijderen | POST `/admin/accounts/{id}/delete` | Admin | Hoog |

---

## 4. Technisch Ontwerp

### 4.1 Gebruikte technieken

| Techniek | Toepassing |
|----------|-----------|
| PHP 8.x | Server-side programmeertaal - routing, business logic, database-interactie, sessies |
| MySQL | Relationele database - aangemaakt en beheerd via Plesk op de schoolserver |
| HTML5 | Structuur van de Twig-templates |
| CSS / Bootstrap CDN | Basisopmaak van de gebruikersinterface |
| Twig | Template engine - strikte scheiding van logica en presentatie |
| PDO | Database-abstraction layer - veilige queries via prepared statements |
| FastRoute | URL-routing - koppelt URI-patronen aan controllermethoden |
| PHP Sessions | Authenticatie - sessie aanmaken bij inloggen, vernietigen bij uitloggen |
| `password_hash()` | Wachtwoorden worden gehasht met bcrypt vóór opslag in de database |
| Composer PSR-4 | Autoloading - klassen worden automatisch geladen op basis van namespace |
| MVC | Architectuurpatroon - scheiding van Model, View en Controller |

**Ontwikkelomgeving:**

| Tool | Gebruik |
|------|---------|
| Visual Studio Code | Code-editor |
| Plesk / FileZilla | Deployment - bestanden geüpload naar schoolserver via Plesk of FTP |
| phpMyAdmin (via Plesk) | Database aanmaken en beheren |
| Composer | PHP dependency management |
| Git | Versiebeheer van de broncode |

**Hardware:**

| Hardware | Specificatie |
|----------|-------------|
| Computer | Schoolcomputer (Windows) |
| Browser | Google Chrome / Microsoft Edge |

### 4.2 Architectuur (MVC)

Het project volgt het MVC-patroon binnen een zelfgebouwd PHP micro-framework (*Araecy Framework*).

**Mappenstructuur:**

```
Framework/
├- public/
│   └- index.php                       # Front Controller - enig HTTP-toegangspunt
├- routes/
│   └- web.php                         # Route-definities
├- app/
│   ├- Controllers/
│   │   ├- HomeController.php          # Homepagina (evenementinfo)
│   │   ├- RegistrationController.php  # Inschrijven
│   │   ├- AuthController.php          # Inloggen / uitloggen
│   │   ├- FanController.php           # Fan-pagina (profiel, aanpassen, uitschrijven)
│   │   └- AdminController.php         # Admin-dashboard
│   └- Models/
│       └- Account.php                 # Model voor fans (CRUD, wachtwoord-hashing)
├- src/                                # Framework-kern
│   ├- Http/
│   │   ├- Kernel.php                  # Dispatcht requests via FastRoute
│   │   ├- Request.php                 # Singleton - wraps $_SERVER, $_POST, $_GET
│   │   └- Response.php               # HTTP response
│   ├- Controllers/
│   │   └- AbstractController.php     # Basiscontroller - render() via Twig
│   └- Database/
│       └- Connection.php             # Singleton PDO-verbinding
├- views/
│   ├- home.html.twig                  # Homepage
│   ├- register.html.twig             # Inschrijfformulier
│   ├- login.html.twig                # Loginformulier
│   ├- profile.html.twig              # Fan-pagina
│   └- admin.html.twig                # Admin-dashboard
└- database/
    └- config.php                     # Database-verbindingsconfiguratie (buiten webroot)
```

**Request-flow:**

```
Browser → public/index.php (Front Controller)
        → Request::create() [Singleton]
        → Kernel::handle(Request)
        → FastRoute dispatcher
        → [Controller, method]($vars)
        → Sessiecheck (indien beveiligde route)
        → AbstractController::render(template, data)
        → Twig::render()
        → Response::send()
        → Browser
```

### 4.3 Database ontwerp (ERD)

**Entity Relationship Diagram:**

```
+-------------------------------------------+
|                 accounts                  |
+-------------------------------------------+
| Id (PK)          INT UNSIGNED NOT NULL AI  |
| Naam             VARCHAR(255) NOT NULL     |
| Email            VARCHAR(255) NOT NULL UQ  |
| Adres            VARCHAR(255) NOT NULL     |
| Woonplaats       VARCHAR(255) NOT NULL     |
| Telefoonnummer   VARCHAR(20)  NOT NULL     |
| Geboortedatum    DATE         NOT NULL     |
| Geslacht         ENUM(man,vrouw,anders)    |
| Wachtwoord_hash  VARCHAR(255) NOT NULL     |
| has_Ticket       BOOLEAN      DEFAULT FALSE|
+-------------------------------------------+
```

> Eén tabel. De admin is hardcoded (geen database-entry). Het veld `has_Ticket` geeft aan of het ticket na bevestigde (gesimuleerde) betaling is verstuurd.

**SQL-schema:**

```sql
CREATE TABLE `EX_DB_102953`.`accounts` (
    `Id`              INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `Naam`            VARCHAR(255)     NOT NULL,
    `Email`           VARCHAR(255)     NOT NULL UNIQUE,
    `Adres`           VARCHAR(255)     NOT NULL,
    `Woonplaats`      VARCHAR(255)     NOT NULL,
    `Telefoonnummer`  VARCHAR(20)      NOT NULL,
    `Geboortedatum`   DATE             NOT NULL,
    `Geslacht`        ENUM('man','vrouw','anders') NOT NULL,
    `Wachtwoord_hash` VARCHAR(255)     NOT NULL,
    `has_Ticket`      BOOLEAN          NOT NULL DEFAULT FALSE,
    PRIMARY KEY (`Id`)
);
```

**Database-verbinding:**

| Gegeven | Waarde |
|---------|--------|
| Host | `localhost:3306` |
| Database | `EX_DB_102953` |
| Gebruikersnaam | `Admin` |
| Wachtwoord | `9Sn$*nPmc3uasdZ4` |

### 4.4 Route-overzicht

| Methode | URI | Controller | Actie |
|---------|-----|-----------|-------|
| GET | `/` | HomeController | `index` - evenementinfo + plaatsen teller |
| GET | `/register` | RegistrationController | `create` - registratieformulier |
| POST | `/register` | RegistrationController | `store` - validatie, opslaan, bevestigingsmail |
| GET | `/login` | AuthController | `create` - loginformulier |
| POST | `/login` | AuthController | `store` - sessie aanmaken |
| POST | `/logout` | AuthController | `destroy` - sessie vernietigen |
| GET | `/profile` | FanController | `show` - fan-pagina (sessiecheck) |
| POST | `/profile/update` | FanController | `update` - gegevens opslaan |
| POST | `/profile/delete` | FanController | `destroy` - uitschrijven |
| GET | `/admin` | AdminController | `index` - alle inschrijvingen (admin sessiecheck) |
| POST | `/admin/accounts/{id:\d+}/delete` | AdminController | `destroy` - inschrijving verwijderen |

### 4.5 Privacy & Veiligheid

De applicatie verwerkt persoonsgegevens (naam, adres, e-mail, geboortedatum) en valt daarmee onder de AVG. De volgende maatregelen worden genomen:

| Maatregel | Beschrijving | Toepassing |
|-----------|-------------|-----------|
| **Wachtwoord-hashing** | Wachtwoorden worden nooit in plaintext opgeslagen. `password_hash()` met bcrypt wordt gebruikt bij registratie; `password_verify()` bij inloggen. | `Account::save()` en `AuthController::store()` |
| **SQL-injectie preventie** | Alle queries gebruiken PDO prepared statements. Gebruikersinvoer komt nooit direct in SQL. | Alle modelmethoden via `Connection::get()` |
| **XSS-preventie** | Twig escapet automatisch alle variabelen. De `raw`-filter wordt niet gebruikt voor gebruikersinvoer. | Alle `.html.twig`-templates |
| **Sessiebeveiliging** | Beveiligde routes controleren of een geldige sessie aanwezig is. Zonder sessie: doorsturen naar `/login`. | `FanController`, `AdminController` |
| **Admin-isolatie** | De admin-sessie is gescheiden van fan-sessies. Admin heeft geen account in de database. | `AuthController::store()` |
| **Geen gevoelige data in URL** | Persoonsgegevens worden niet via GET-parameters doorgegeven. | Route-definities in `web.php` |
| **Configuratie buiten webroot** | `database/config.php` staat buiten `public/` en is niet via de browser bereikbaar. | Projectstructuur |
| **Maximumlimiet** | Registratie wordt geweigerd als het aantal accounts 1.000 heeft bereikt. | `RegistrationController::store()` |
| **Invoervalidatie server-side** | Alle formulierinvoer wordt gevalideerd aan de server-side (verplichte velden, e-mailformaat, wachtwoordsterkte). | `RegistrationController`, `FanController` |

### 4.6 Back-up plan (Git)

De broncode wordt bijgehouden in een Git-repository. Tijdens de realisatie wordt na elke voltooide fase een commit geplaatst. Dit zorgt ervoor dat er altijd een werkende versie beschikbaar is om op terug te vallen als er iets misgaat.

**Werkwijze:**

1. Na elke voltooide fase: `git add . && git commit -m "fase X: omschrijving"`
2. De repository staat lokaal en wordt gepusht naar de remote (GitHub of schoolserver) aan het einde van elke dag.
3. Als een implementatie niet werkt, kan worden teruggevallen op de vorige commit via `git checkout`.

---

## 5. Oplevering

### 5.1 Deadline

Het project wordt opgeleverd binnen de beschikbare examenstijd van **3 dagen**:

| Dag | Opdracht | Activiteit |
|-----|---------|-----------|
| Dag 1 | Opdracht 1 - Voorbereiding | Casus analyseren, projectplan schrijven, ontwerp uitwerken |
| Dag 2 | Opdracht 2 - Realisatie | Applicatie bouwen (13 uur) |
| Dag 3 | Opdracht 3 - Testen & Verbetervoorstellen | Testen, testrapport invullen, verbetervoorstellen formuleren |

### 5.2 Testplan

**Wat voor soort tests worden er gedaan?**

| Testtype | Beschrijving |
|---------|-------------|
| Functionele tests | Controleren of alle use cases werken zoals beschreven |
| Validatietests | Controleren of foutieve invoer correct wordt afgewezen |
| Grenswaarde-tests | Testen van de 1.000-plekken-limiet |
| Beveiligingstests | SQL-injectie en XSS-pogingen |
| Conformiteitstests | Controleren of alle casuseisen aanwezig en werkend zijn |

**In welke fase worden de tests gedaan?**

- **Eerste testmoment (tijdens realisatie):** Na fase 8 - routes, formulieren en database-opslag controleren.
- **Tweede testmoment (tijdens realisatie):** Na fase 11 - alle use cases doorlopen.
- **Volledig testmoment (dag 3):** Alle tests uit het testplan systematisch uitvoeren en vastleggen in het testrapport.

**Door wie wordt er getest?**

De tests worden uitgevoerd door de student/ontwikkelaar (Noah Wijnman).

**Hoe wordt dit inzichtelijk voor de klant?**

De testresultaten worden vastgelegd in het **testrapport** (zie sectie hieronder). Dit rapport toont per test: wat er getest is, welke invoer is gebruikt, wat het verwachte resultaat was en of de test geslaagd is.

**Testplan:**

| Test-ID | Testgebied | Testtype | Invoer | Verwacht resultaat |
|---------|-----------|---------|--------|-------------------|
| T-01 | Registratie - geldig | Functioneel | Volledig ingevuld formulier | Account opgeslagen, bevestigingsmail verstuurd |
| T-02 | Registratie - lege velden | Validatie | Leeg formulier verzenden | Foutmelding per veld; niets opgeslagen |
| T-03 | Registratie - duplicaat e-mail | Validatie | Reeds geregistreerd e-mailadres | Foutmelding; geen dubbel account |
| T-04 | Registratie - limiet bereikt | Grenswaarde | 1.001e inschrijving | Inschrijving geblokkeerd; melding getoond |
| T-05 | Inloggen - juiste gegevens | Functioneel | Geldig e-mail + wachtwoord | Sessie aangemaakt; doorgestuurd naar `/profile` |
| T-06 | Inloggen - verkeerd wachtwoord | Validatie | Verkeerd wachtwoord | Foutmelding; geen toegang |
| T-07 | Gegevens aanpassen | Functioneel | Gewijzigde naam of adres | Wijzigingen opgeslagen en zichtbaar |
| T-08 | Uitschrijven | Functioneel | Klik op "Uitschrijven" | Account verwijderd; uitgelogd; teruggestuurd naar homepage |
| T-09 | Admin inloggen | Functioneel | `admin` / `#1Geheimv` | Toegang tot `/admin` |
| T-10 | Admin onjuiste login | Validatie | Foutief wachtwoord voor admin | Foutmelding; geen toegang |
| T-11 | Admin inschrijving verwijderen | Functioneel | Klik op [X] bij een fan | Account verwijderd; niet meer zichtbaar in lijst |
| T-12 | Toegang zonder sessie | Beveiliging | Direct navigeren naar `/profile` | Doorgestuurd naar `/login` |
| T-13 | SQL-injectie poging | Beveiliging | `' OR '1'='1` in invoerveld | Invoer gesaneerd; geen DB-fout of onbedoelde toegang |
| T-14 | XSS-poging | Beveiliging | `<script>alert(1)</script>` in invoerveld | Script niet uitgevoerd; tekst getoond als tekst |
| T-15 | Alle casuseisen aanwezig | Conformiteit | Doorlopen van alle use cases | Alle gevraagde functionaliteiten aanwezig en werkend |

**Testrapport (in te vullen op dag 3):**

| Test-ID | Geteste functionaliteit | Resultaat | Werkt? | Opmerking |
|---------|------------------------|-----------|--------|-----------|
| T-01 | Registratie - geldig | - | - | - |
| T-02 | Registratie - lege velden | - | - | - |
| T-03 | Registratie - duplicaat e-mail | - | - | - |
| T-04 | Registratie - limiet bereikt | - | - | - |
| T-05 | Inloggen - juiste gegevens | - | - | - |
| T-06 | Inloggen - verkeerd wachtwoord | - | - | - |
| T-07 | Gegevens aanpassen | - | - | - |
| T-08 | Uitschrijven | - | - | - |
| T-09 | Admin inloggen | - | - | - |
| T-10 | Admin onjuiste login | - | - | - |
| T-11 | Admin inschrijving verwijderen | - | - | - |
| T-12 | Toegang zonder sessie | - | - | - |
| T-13 | SQL-injectie poging | - | - | - |
| T-14 | XSS-poging | - | - | - |
| T-15 | Alle casuseisen aanwezig | - | - | - |

**Conclusie testrapport:** *(in te vullen na dag 3)*

### Verbetervoorstellen

*(In te vullen op basis van testresultaten - zie ook `IMPROVEMENTS.md` voor voorbereide suggesties.)*

**a. Welke onderdelen moeten verbeterd worden en hoe?**

| Onderdeel | Probleem | Voorgestelde verbetering |
|-----------|---------|------------------------|
| *(in te vullen)* | *(in te vullen)* | *(in te vullen)* |

**b. Welke extra functionaliteit zou toegevoegd kunnen worden en waarom?**

| Extra feature | Motivatie |
|--------------|----------|
| Automatische e-mailbevestiging (bijv. via PHPMailer) | Nu is de bevestigingsmail gesimuleerd; echte verstuur-functionaliteit verhoogt de betrouwbaarheid |
| Betalingsintegratie (bijv. Mollie sandbox) | De betaling is nu gesimuleerd; een echte sandbox-integratie sluit beter aan bij de beschrijving |
| Wachtwoord vergeten / resetflow | Verhoogt gebruiksgemak voor fans die hun wachtwoord zijn vergeten |

**c. Tijdsindicatie voor verbeteringen:**

| Verbetering / Feature | Geschatte tijd |
|----------------------|---------------|
| PHPMailer e-mailintegratie | ±2 uur |
| Mollie betaling sandbox | ±3 uur |
| Wachtwoord-resetflow | ±2 uur |

### 5.3 Oplevering aan de klant

**Hoe krijgt de klant het project?**

De applicatie is beschikbaar via een link naar de schoolserver (Plesk). De klant ontvangt:
- Een URL naar de live-webapplicatie op de schoolserver.
- De inloggegevens voor de admin-pagina.

**Waar staat de broncode?**

De broncode staat in een Git-repository op GitHub. De klant krijgt toegang tot de repository, zodat de broncode ook na het examen beschikbaar blijft.

> Repository: *(URL invullen na aanmaken van de Git-repo)*

**Is de broncode van de klant?**

Ja. De broncode en de bijbehorende database-structuur worden volledig overgedragen aan de klant (Stadion de Kuip) bij oplevering.

**Waar is de database te vinden?**

De database draait op de schoolserver via Plesk:

| Gegeven | Waarde |
|---------|--------|
| Host | `localhost:3306` |
| Database | `EX_DB_102953` |
| Beheer via | phpMyAdmin (Plesk) |

---

## 6. Inlog informatie

**Fan-account (testaccount - aan te maken tijdens de realisatie):**

| Gegeven | Waarde |
|---------|--------|
| E-mail | *(testaccount e-mail invullen)* |
| Wachtwoord | *(testaccount wachtwoord invullen)* |

**Admin-account:**

| Gegeven | Waarde |
|---------|--------|
| Gebruikersnaam | `admin` |
| Wachtwoord | `#1Geheimv` |
| Toegang via | `/login` (kies admin-modus) of direct via `/admin` |

> **Let op:** De admin-inloggegevens zijn hardcoded in de applicatiecode. Er is geen aparte database-entry voor de admin. Wijzig het wachtwoord voor productiegebruik.

---

*Document bijgewerkt: 2026-05-19 - Casus verwerkt: WK Voetbal Finale, Stadion de Kuip.*
