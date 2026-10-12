# cv03: Router a čisté adresy

Teória a ukážky: <https://beata.stehlikova.website.tuke.sk/it/04.html>

**Odovzdávaš (do nasledujúceho cvičenia):** v tomto repository `.htaccess`, `composer.json`, `index.php` s route pre každú svoju tabuľku, `src/Router.php`, `src/helpers.php`, `views/` so všetkými pohľadmi, svoje `src/Model/` a `README.md` s tabuľkou route; vo svojom webovom priestore na sigma.tuke.sk v `public_html/it/cv03` aplikáciu, ktorá odpovedá na všetky adresy z tabuľky route a pre neexistujúcu adresu vráti stavový kód 404.

**Hodnotenie:** 1 bod. Tento týždeň je zároveň prvý míľnik: obhajoba týždňov 2–4 podľa zverejnených položiek na stránkach 02, 03 a 04.

**Čo je v router.zip** ([router.zip](https://beata.stehlikova.website.tuke.sk/it/router.zip), 10 súborov): `.htaccess` (3 nové riadky: prepisovanie ciest do `index.php`), `composer.json` (blok `"files"` so `src/helpers.php`), `index.php` (front controller: prefix, relatívna adresa, zoznam route, `dispatch()`), `src/Router.php` (trieda Router), `src/helpers.php` (funkcie `e()`, `url()`, `view()`), `views/layout.php`, `views/home.php`, `views/404.php`, `views/debug.php`, `views/kontrola.php`.

**Úloha 4.1: Repository cv03 z cv02 a router.zip.** Prijmi zadanie *cv03* (link je vyššie) a repository naklonuj do `~/web/it/cv03` podľa návodu [04 Odovzdávanie](https://beata.stehlikova.website.tuke.sk/navody/04-odovzdavanie.html), časti 2 a 3 (posledné slovo príkazu je `cv03`). Potom v termináli:

```bash
cd ~/web/it
cp cv02/config.php cv02/config.example.php cv02/.gitignore cv03/
cp -r cv02/sql cv02/src cv03/
cd cv03
curl -O https://beata.stehlikova.website.tuke.sk/it/router.zip
unzip -o router.zip && rm router.zip
composer install
```

`unzip -o` prepíše `.htaccess`, `composer.json` a `index.php` novou verziou a pridá `src/Router.php`, `src/helpers.php` a `views/`. Potom pravý klik na priečinok `cv03` → **Upload Folder**. Kontrola: `https://sigma.tuke.sk/student/meno.priezvisko/it/cv03/` ukáže úvodnú stránku s menu, `…/cv03/debug` ukáže tabuľku s piatimi hodnotami, `…/cv03/nic` ukáže stránku 404. Commit a push: `cv03: kostra s routerom`.

**Úloha 4.2: Kontrolná stránka.** Vo `views/kontrola.php` doplň do poľa `$models` jeden riadok `new \App\Model\TvojModel(),` pre každú svoju triedu (rovnaké riadky ako v `index.php` z cv02). Ulož. Kontrola: `…/cv03/kontrola` zobrazí tabuľky a modely ako v cv02, v ráme s menu.

**Úloha 4.3: Zoznamy.** Vytvor `views/list.php` podľa Ukážky 4. V `index.php` pridaj pred riadok `echo $router->dispatch(…)` jednu route pre každý svoj číselník a jednu pre hlavnú entitu; relatívna adresa je `/nazov-tabulky`, vzor je v Ukážke 3. Do menu vo `views/layout.php` pridaj odkaz cez `url()` pre každú z nich. Kontrola: každá adresa zobrazí tabuľku záznamov; pri hlavnej entite je aj stĺpec z číselníka (JOIN z cv02).

**Úloha 4.4: Detail.** Vytvor `views/show.php` (Ukážka 4 alebo prompt z Ukážky 6). Pridaj route `/hlavna-entita/{id}` podľa Ukážky 3 a v route zoznamu hlavnej entity pošli pohľadu `'show' => '/hlavna-entita'`. Kontrola: klik na `id` v zozname otvorí detail; `…/hlavna-entita/999` ukáže stránku 404 a v karte Network stavový kód 404.

**Úloha 4.5: Vlastná route.** Pridaj route `/o-aplikacii` s pohľadom `views/about.php`: názov tvojej témy, zoznam tvojich tabuliek a odkaz na GitHub repository. Pridaj odkaz do menu. Kontrola: adresa funguje, odkaz v menu vedie na ňu.

**Úloha 4.6: Odovzdanie.** Commit a push podľa návodu [04 Odovzdávanie](https://beata.stehlikova.website.tuke.sk/navody/04-odovzdavanie.html), časť 5. Na GitHub skontroluj, že je tam `.htaccess`, `composer.json` s blokom `"files"`, `index.php`, `src/Router.php`, `src/helpers.php`, `views/` so všetkými pohľadmi a tvoje `src/Model/`. `config.php` a `vendor/` tam nie sú.

**Úloha 4.7: Kontrola na sigma.tuke.sk.** Pravý klik na `cv03`, **Upload Folder**. Preklikaj menu: úvod, každý číselník, hlavná entita, jeden detail, kontrola, o aplikácii. Potom over tri adresy: `…/cv03/nic` ukáže stránku 404, `…/cv03/src/Router.php` ukáže 404, `…/cv03/config.php` ukáže 403. Do `README.md` v `cv03` doplň tabuľku všetkých svojich route: relatívna adresa a čo zobrazí.

**Prompt na pohľad (Ukážka 6):**

Do AI vlož `src/helpers.php`, `views/layout.php`, `views/list.php` a tento text:

```text
Napíš PHP pohľad views/show.php pre moju aplikáciu. Pravidlá:
- Pohľad dostane premenné $row (asociatívne pole jedného záznamu z getById()) a $back (relatívna adresa zoznamu).
- Každú hodnotu vypisuj cez funkciu e(), každý odkaz cez funkciu url(). Obe sú v priloženom helpers.php.
- Žiadne <html>, <head>, <body>: to robí priložený layout.php.
- Stĺpce vypíš ako <dl> s <dt> názov stĺpca a <dd> hodnota.
- Na konci odkaz „Späť na zoznam“ na adresu $back.
- Bez CSS, bez JavaScriptu, bez ďalších funkcií.
```

V odpovedi skontroluj:

| Čo | Správne |
|---|---|
| výpis hodnôt | každý `<?= … ?>` s hodnotou z `$row` má `e()` |
| odkazy | každý `href` má `url()` |
| rám stránky | v súbore nie je `<html>`, `<head>`, `<body>` ani `<h1>` |
| premenné | len `$row` a `$back`; iné by pohľad nedostal |

**Tabuľka route (doplň v úlohe 4.7):**

| Relatívna adresa | Čo zobrazí |
|---|---|
| `/` | úvod |
| `/kontrola` | kontrolná stránka |
| `/debug` | hodnoty z požiadavky, prefix, relatívna adresa |
| … | … |

---

# cv03: Router and clean URLs (English)

Theory and examples: <https://beata.stehlikova.website.tuke.sk/it/04.html> (switch to EN at the top)

**You submit (by the next lab):** in this repository `.htaccess`, `composer.json`, `index.php` with a route for each of your tables, `src/Router.php`, `src/helpers.php`, `views/` with all views, your `src/Model/` and `README.md` with the route table; in your web space on sigma.tuke.sk in `public_html/it/cv03` an application that answers all addresses from the route table and returns status code 404 for a non-existent address.

**Grading:** 1 point. This week is also the first milestone: defence of weeks 2–4 using the published items on pages 02, 03 and 04.

**What router.zip contains** ([router.zip](https://beata.stehlikova.website.tuke.sk/it/router.zip), 10 files): `.htaccess` (3 new lines: rewriting paths to `index.php`), `composer.json` (`"files"` block with `src/helpers.php`), `index.php` (front controller: prefix, relative address, list of routes, `dispatch()`), `src/Router.php` (the Router class), `src/helpers.php` (functions `e()`, `url()`, `view()`), `views/layout.php`, `views/home.php`, `views/404.php`, `views/debug.php`, `views/kontrola.php`.

**Task 4.1: Repository cv03 from cv02 and router.zip.** Accept the assignment *cv03* (link above) and clone the repository into `~/web/it/cv03` following the guide [04 Odovzdávanie](https://beata.stehlikova.website.tuke.sk/navody/04-odovzdavanie.html), parts 2 and 3 (the last word of the command is `cv03`). Then in the terminal:

```bash
cd ~/web/it
cp cv02/config.php cv02/config.example.php cv02/.gitignore cv03/
cp -r cv02/sql cv02/src cv03/
cd cv03
curl -O https://beata.stehlikova.website.tuke.sk/it/router.zip
unzip -o router.zip && rm router.zip
composer install
```

`unzip -o` overwrites `.htaccess`, `composer.json` and `index.php` with the new version and adds `src/Router.php`, `src/helpers.php` and `views/`. Then right-click the folder `cv03` → **Upload Folder**. Check: `https://sigma.tuke.sk/student/meno.priezvisko/it/cv03/` shows the home page with the menu, `…/cv03/debug` shows the table with five values, `…/cv03/nic` shows the 404 page. Commit and push: `cv03: kostra s routerom`.

**Task 4.2: Check page.** In `views/kontrola.php` add one line `new \App\Model\YourModel(),` to the `$models` array for each of your classes (the same lines as in `index.php` from cv02). Save. Check: `…/cv03/kontrola` shows the tables and models as in cv02, in the frame with the menu.

**Task 4.3: Lists.** Create `views/list.php` following Example 4. In `index.php`, before the line `echo $router->dispatch(…)`, add one route for each of your lookup tables and one for the main entity; the relative address is `/table-name`, the pattern is in Example 3. Add a link through `url()` for each of them to the menu in `views/layout.php`. Check: every address shows the table of records; the main entity also has the column from the lookup table (JOIN from cv02).

**Task 4.4: Detail.** Create `views/show.php` (Example 4 or the prompt from Example 6). Add the route `/main-entity/{id}` following Example 3 and in the list route of the main entity send the view `'show' => '/main-entity'`. Check: clicking an `id` in the list opens the detail; `…/main-entity/999` shows the 404 page and the Network tab shows status code 404.

**Task 4.5: Own route.** Add the route `/o-aplikacii` with the view `views/about.php`: the name of your topic, the list of your tables and a link to the GitHub repository. Add a link to the menu. Check: the address works, the menu link leads to it.

**Task 4.6: Submission.** Commit and push following the guide [04 Odovzdávanie](https://beata.stehlikova.website.tuke.sk/navody/04-odovzdavanie.html), part 5. On GitHub check that `.htaccess`, `composer.json` with the `"files"` block, `index.php`, `src/Router.php`, `src/helpers.php`, `views/` with all views and your `src/Model/` are there. `config.php` and `vendor/` are not.

**Task 4.7: Check on sigma.tuke.sk.** Right-click `cv03`, **Upload Folder**. Click through the menu: home, every lookup table, the main entity, one detail, check page, about. Then verify three addresses: `…/cv03/nic` shows the 404 page, `…/cv03/src/Router.php` shows 404, `…/cv03/config.php` shows 403. Add a table of all your routes to `README.md` in `cv03`: relative address and what it shows.

**Prompt for a view (Example 6):**

Give the AI `src/helpers.php`, `views/layout.php`, `views/list.php` and this text:

```text
Write the PHP view views/show.php for my application. Rules:
- The view receives the variables $row (associative array of one record from getById()) and $back (relative address of the list).
- Print every value through the function e(), every link through the function url(). Both are in the attached helpers.php.
- No <html>, <head>, <body>: the attached layout.php does that.
- Print the columns as a <dl> with <dt> column name and <dd> value.
- At the end a link "Späť na zoznam" to the address $back.
- No CSS, no JavaScript, no further functions.
```

In the answer check:

| What | Correct |
|---|---|
| printing values | every `<?= … ?>` with a value from `$row` has `e()` |
| links | every `href` has `url()` |
| page frame | the file has no `<html>`, `<head>`, `<body>` or `<h1>` |
| variables | only `$row` and `$back`; the view would not receive others |

**Route table (fill in in task 4.7):**

| Relative address | What it shows |
|---|---|
| `/` | home |
| `/kontrola` | check page |
| `/debug` | request values, prefix, relative address |
| … | … |
