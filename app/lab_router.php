<?php
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';

function lab_page(string $title, string $body): never {
    header('Content-Type: text/html; charset=UTF-8');
    echo '<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>'
        . h($title) . ' — TeamSakit Lab</title><link rel="stylesheet" href="/assets/style.css"></head><body><main><section class="hero"><p class="eyebrow">TEAMSAKIT PRACTICE LAB</p><h1>'
        . h($title) . '</h1><p>Mode aplikasi: <strong>' . h(env_value('APP_MODE', 'fixed')) . '</strong></p><p><a href="/labs/">← Daftar skenario</a> · <a href="/">Beranda</a></p></section><section class="section"><div class="card">'
        . $body . '</div></section></main></body></html>';
    exit;
}

function lab_mode(): string {
    return env_value('APP_MODE', 'fixed') === 'vulnerable' ? 'vulnerable' : 'fixed';
}

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/labs/', PHP_URL_PATH) ?: '/labs/';
if ($path === '/labs/' || $path === '/labs') {
    $items = [
        'L01' => 'Reflected XSS — /labs/L01?q=hello',
        'L02' => 'SQL injection concepts — /labs/L02?id=1',
        'L03' => 'Redirect validation — /labs/L03?next=welcome',
        'L04' => 'Synthetic exposed file — /labs/L04?file=training-notes.txt',
    ];
    $html = '<p>Empat skenario awal memakai data dummy. Mode vulnerable hanya untuk lab lokal terisolasi.</p><ul>';
    foreach ($items as $id => $label) $html .= '<li><a href="/labs/' . h($id) . '">' . h($label) . '</a></li>';
    $html .= '</ul><p><strong>Mode saat ini:</strong> ' . h(lab_mode()) . '</p><p>Ubah APP_MODE hanya pada lingkungan latihan lokal, lalu restart container.</p>';
    lab_page('Skenario latihan', $html);
}

$mode = lab_mode();
switch ($path) {
    case '/labs/L01':
        $q = (string)($_GET['q'] ?? 'hello');
        // Intentional unsafe output exists only in the isolated vulnerable profile.
        $shown = $mode === 'vulnerable' ? $q : h($q);
        lab_page('L01 — Reflected output', '<p>Nilai pencarian: ' . $shown . '</p><form><label for="q">Coba nilai pencarian</label><input id="q" name="q" value="' . h($q) . '"><button>Uji</button></form><p>Fixed mode melakukan HTML escaping pada konteks teks.</p>');
    case '/labs/L02':
        $id = (string)($_GET['id'] ?? '1');
        try {
            if ($mode === 'vulnerable') {
                // Deliberately injectable query, limited to seeded training articles.
                $sql = "SELECT id,title,summary FROM articles WHERE id = " . $id;
                $rows = db()->query($sql)->fetchAll();
            } else {
                $stmt = db()->prepare('SELECT id,title,summary FROM articles WHERE id = ?');
                $stmt->execute([$id]);
                $rows = $stmt->fetchAll();
            }
            $html = '<p>Parameter id: <code>' . h($id) . '</code></p>';
            if (!$rows) $html .= '<p>Tidak ada artikel yang cocok.</p>';
            foreach ($rows as $row) $html .= '<article><h3>' . h((string)$row['title']) . '</h3><p>' . h((string)$row['summary']) . '</p><small>Record #' . h((string)$row['id']) . '</small></article>';
            $html .= '<form><label for="id">ID artikel</label><input id="id" name="id" value="' . h($id) . '"><button>Periksa</button></form><p>Gunakan hanya record dummy pada lab lokal ini.</p>';
            lab_page('L02 — Query artikel', $html);
        } catch (Throwable $e) {
            http_response_code(400);
            lab_page('L02 — Query artikel', '<p>Permintaan tidak valid. Detail database tidak ditampilkan.</p>');
        }
    case '/labs/L03':
        $next = (string)($_GET['next'] ?? 'welcome');
        $destinations = ['welcome' => '/labs/', 'articles' => '/#artikel'];
        if ($mode === 'vulnerable') {
            // Safe simulation: only a fixed internal destination is ever emitted.
            $target = $next === 'articles' ? '/#artikel' : ($next === 'welcome' ? '/labs/' : '/labs/?notice=invalid-destination');
        } else {
            $target = $destinations[$next] ?? '/labs/';
        }
        $html = '<p>Tujuan yang diminta: <code>' . h($next) . '</code></p><p>Simulasi ini tidak mengalihkan ke host eksternal.</p><p><a href="' . h($target) . '">Lanjut ke tujuan yang dipilih</a></p><form><label for="next">Nama tujuan (welcome/articles)</label><input id="next" name="next" value="' . h($next) . '"><button>Periksa</button></form>';
        lab_page('L03 — Validasi redirect', $html);
    case '/labs/L04':
        $file = (string)($_GET['file'] ?? 'training-notes.txt');
        $fixtures = [
            'training-notes.txt' => 'CATATAN LATIHAN: akun, token, dan rahasia berikut ini semuanya fiktif. Tidak ada kredensial nyata.',
            'public-info.txt' => 'Informasi publik simulasi: TeamSakit practice environment.',
        ];
        if ($mode === 'vulnerable') {
            $content = $fixtures[$file] ?? null;
        } else {
            $content = $file === 'public-info.txt' ? $fixtures[$file] : null;
        }
        $html = '<p>Nama fixture: <code>' . h($file) . '</code></p>';
        $html .= $content === null ? '<p>File tidak tersedia.</p>' : '<pre>' . h($content) . '</pre>';
        $html .= '<form><label for="file">Nama file fixture</label><input id="file" name="file" value="' . h($file) . '"><button>Buka fixture</button></form><p>File hanya berasal dari daftar fixture dalam kode; filesystem host tidak dibaca.</p>';
        lab_page('L04 — File fixture', $html);
    default:
        http_response_code(404);
        lab_page('Skenario tidak ditemukan', '<p>Level ini belum tersedia.</p>');
}
