# Araecy Framework - WK Voetbal Finale Inschrijfsysteem

Beroepsexamen K1 project. Student: Noah Wijnman. Opdrachtgever: Stadion de Kuip.

## Stack
- PHP 8.x, MySQL, Twig, FastRoute, PDO, Composer PSR-4 autoloading
- Local dev: `php -S localhost:8000 -t public`
- DB: `EX_DB_102953` - one table: `accounts`
- Admin login: username `admin` / password `#1Geheim` (hardcoded, no DB entry)
This project uses:
- Twig templates
- vanilla CSS
- no Tailwind
- no React runtime

Design exports may contain JSX or Tailwind classes.
These are references only and must be converted into semantic Twig + CSS architecture.
Convert this JSX/Tailwind component into:
- semantic Twig markup
- component-based CSS
- reusable design tokens

Avoid utility-class duplication.

STRICT FORMATTING RULES
- Absolutely forbidden: em dash character (-)

## Phase status

| # | Description | Status |
|---|-------------|--------|
| 1 | DB table (`schema.sql`) | ✅ |
| 2 | Routes (`routes/web.php`) | ✅ |
| 3 | `Account` model | ✅ |
| 4 | `RegistrationController` | ✅ |
| 5 | `AuthController` | ✅ |
| 6 | `FanController` | ✅ |
| 7 | `AdminController` | ✅ |
| 8 | Twig views - layout + all 5 views | ✅ |
| 9 | Test moment - routes, forms, DB | ✅ |
| 10 | Error handling, validation, session security | ✅ |
| 11 | CSS via CDN (Bootstrap 5) | ✅ |

## Routes

| Method | URI | Controller::method |
|--------|-----|--------------------|
| GET | `/` | `HomeController::index` |
| GET | `/test` | `HomeController::test` |
| GET | `/register` | `RegistrationController::create` |
| POST | `/register` | `RegistrationController::store` |
| GET | `/login` | `AuthController::create` |
| POST | `/login` | `AuthController::store` |
| POST | `/logout` | `AuthController::destroy` |
| GET | `/profile` | `FanController::show` |
| POST | `/profile/update` | `FanController::update` |
| POST | `/profile/delete` | `FanController::destroy` |
| GET | `/admin` | `AdminController::index` |
| POST | `/admin/accounts/{id}/delete` | `AdminController::destroy` |
| POST | `/admin/accounts/{id}/approve-ticket` | `AdminController::approveTicket` |

## View data (what each controller passes to Twig)

| View | Variables |
|------|-----------|
| `home.html.twig` | `beschikbaar` (int), `totaal` (int, always 1000) |
| `register.html.twig` | `errors` (string[], optional), `old` (assoc array of POST values, optional) |
| `login.html.twig` | `error` (string, optional), `flash_registration` (email string, optional) |
| `profile.html.twig` | `account` (Account), `errors` (string[], optional), `success` (string, optional) |
| `admin.html.twig` | `accounts` (Account[]), `total` (int) |
| `test.html.twig` | - |

## Account object - Twig-accessible properties

`account.id`, `account.naam`, `account.email`, `account.adres`, `account.woonplaats`,
`account.telefoonnummer`, `account.geboortedatum`, `account.geslacht`, `account.hasTicket`

## CSS / view layout (completed phase 8 + 11)
- `views/layout.html.twig` - shared base: Bootstrap 5.3.3 CDN, dark navbar w/ orange brand, `{% block styles %}`, `{% block content %}`, footer
- All 5 views extend layout; per-view CSS in `public/css/` (`main.css`, `home.css`, `register.css`, `login.css`, `profile.css`, `admin.css`)
- `AbstractController::render()` always merges `is_logged_in` + `is_admin` from `$_SESSION` so the navbar adapts automatically

## Phase 10 additions
- `RegistrationController::create/store` - redirects already-logged-in users to `/profile` or `/admin`
- `AuthController::create/store` - same redirects; `session_regenerate_id(true)` called on every successful login

## View summary
- `home.html.twig` - dark hero, availability progress bar, rules card
- `register.html.twig` - two-column: Bootstrap form (left) + ticket info card (right)
- `login.html.twig` - centred card, flash registration alert, error alert
- `profile.html.twig` - ticket status badge, edit form, separate delete form (no nesting)
- `admin.html.twig` - responsive Bootstrap table, approve-ticket + delete per row
- `test.html.twig` - test harness for all routes, accessible at `/test`
