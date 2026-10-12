<?php
// Autograder cv03: 10 kontrol × 10 bodov. Spúšťa sa v GitHub Actions (tests/classroom.yml) alebo lokálne: php tests/test.php
declare(strict_types=1);

$root = dirname(__DIR__);
$prefix = '/student/test.user/it/cv03';
$port = 8765;
$score = 0;
$results = [];

function check(string $name, bool $ok, string $hint = ''): void
{
    global $score, $results;
    $score += $ok ? 10 : 0;
    $results[] = sprintf('%s %s%s', $ok ? '✔' : '✘', $name, $ok || $hint === '' ? '' : "  → $hint");
}

function get(string $path): array
{
    global $prefix, $port;
    $ctx = stream_context_create(['http' => ['ignore_errors' => true, 'timeout' => 10]]);
    $body = @file_get_contents("http://127.0.0.1:$port$prefix$path", false, $ctx);
    $status = 0;
    foreach ($http_response_header ?? [] as $h) {
        if (preg_match('#^HTTP/\S+ (\d{3})#', $h, $m)) {
            $status = (int) $m[1];
        }
    }
    return [$status, (string) $body];
}

// --- statické kontroly súborov -------------------------------------------------
$ht = @file_get_contents("$root/.htaccess") ?: '';
check('.htaccess: RewriteEngine On, RewriteCond !-f, RewriteRule index.php',
    preg_match('/^\s*RewriteEngine\s+On/mi', $ht) && preg_match('/RewriteCond\s+%\{REQUEST_FILENAME\}\s+!-f/i', $ht) && preg_match('/RewriteRule\s+\S+\s+index\.php/i', $ht),
    'použi .htaccess z router.zip');

$cj = json_decode(@file_get_contents("$root/composer.json") ?: '', true) ?? [];
check('composer.json: autoload.files obsahuje src/helpers.php',
    in_array('src/helpers.php', $cj['autoload']['files'] ?? [], true),
    'blok "files": ["src/helpers.php"] v časti autoload');

$rt = @file_get_contents("$root/src/Router.php") ?: '';
check('src/Router.php: trieda App\\Router s metódami get() a dispatch()',
    preg_match('/namespace\s+App;/', $rt) && preg_match('/class\s+Router/', $rt) && str_contains($rt, 'function get(') && str_contains($rt, 'function dispatch('),
    'súbor src/Router.php z router.zip');

$hp = @file_get_contents("$root/src/helpers.php") ?: '';
check('src/helpers.php: funkcie e(), url(), view()',
    preg_match('/function\s+e\s*\(/', $hp) && preg_match('/function\s+url\s*\(/', $hp) && preg_match('/function\s+view\s*\(/', $hp),
    'súbor src/helpers.php z router.zip');

$ix = @file_get_contents("$root/index.php") ?: '';
preg_match_all('/->get\(\s*[\'"]([^\'"]+)[\'"]/', $ix, $mm);
$routes = $mm[1];
$custom = array_values(array_diff($routes, ['/', '/kontrola', '/debug']));
$idRoutes = array_values(array_filter($routes, fn($r) => str_contains($r, '{')));
check('index.php: aspoň 3 vlastné route a aspoň jedna s {id}',
    count($custom) >= 3 && count($idRoutes) >= 1,
    'route pre každý číselník, hlavnú entitu a detail /nazov/{id} (úlohy 4.3, 4.4)');

$bad = [];
foreach (glob("$root/views/*.php") ?: [] as $v) {
    if (preg_match('/href=["\']\/(?!\/)/', file_get_contents($v))) {
        $bad[] = basename($v);
    }
}
check('views/: odkazy cez url(), žiadny href="/..." s napísanou cestou',
    is_dir("$root/views") && !$bad,
    'v súboroch ' . implode(', ', $bad) . ' použi href="<?= url(\'/...\') ?>"');

// --- HTTP kontroly cez php -S ----------------------------------------------------
$cmd = sprintf('php -S 127.0.0.1:%d %s > /dev/null 2>&1 & echo $!', $port, escapeshellarg("$root/tests/router.php"));
$pid = (int) trim((string) shell_exec($cmd));
usleep(700000);

[$s, $b] = get('/');
check('GET / → 200 a stránka s menu (<nav)', $s === 200 && str_contains($b, '<nav'), "stavový kód $s");

[$s, $b] = get('/debug?x=1');
check('GET /debug → 200, tabuľka so SCRIPT_NAME a relatívnou adresou /debug',
    $s === 200 && str_contains($b, 'SCRIPT_NAME') && preg_match('#<td>/debug</td>#', $b),
    "stavový kód $s");

[$s, $b] = get('/toto-neexistuje-' . random_int(1000, 9999));
check('GET neexistujúca adresa → 404 a stránka v ráme layoutu', $s === 404 && str_contains($b, '<nav'), "stavový kód $s");

$ok = false; $why = '';
if ($idRoutes) {
    $r = str_replace(['{id}'], ['999999'], preg_replace('/\{[a-z_]+\}/', '999999', $idRoutes[0]));
    [$s, $b] = get($r);
    $ok = $s === 404;
    $why = "$r → stavový kód $s";
    if ($ok) {
        [$s2, $b2] = get('/kontrola');
        $ok = $s2 === 200 && preg_match('/Modely \((\d+)\)/u', $b2, $m) && (int) $m[1] >= 2;
        $why = $ok ? '' : "/kontrola → stavový kód $s2, alebo menej než 2 modely v \$models";
    }
}
check('detail s neexistujúcim id → 404; /kontrola → 200 s aspoň 2 modelmi', $ok, $why ?: 'chýba route s {id}');

if ($pid) {
    exec("kill $pid 2>/dev/null");
}

echo implode("\n", $results), "\n\n", "Body: $score / 100\n";
exit($score === 100 ? 0 : 1);
