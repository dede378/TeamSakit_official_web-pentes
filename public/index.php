<?php
declare(strict_types=1);
require __DIR__ . '/../app/bootstrap.php';

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$isLab = str_starts_with($path, '/labs');
if ($isLab) require_lab_access();

$articles = [];
$article = null;
try {
    $pdo = db();
    $articles = $pdo->query('SELECT slug,title,summary FROM articles ORDER BY id DESC')->fetchAll();
    if (preg_match('#^/article/([a-z0-9-]+)$#', $path, $matches)) {
        $stmt = $pdo->prepare('SELECT title,summary,body FROM articles WHERE slug = ?');
        $stmt->execute([$matches[1]]);
        $article = $stmt->fetch() ?: null;
        if (!$article) http_response_code(404);
    }
} catch (Throwable $e) {
    http_response_code(503);
}
$scenarios = [
    'L01' => 'Reflected output and XSS context',
    'L02' => 'SQL query handling',
    'L03' => 'Redirect validation',
    'L04' => 'Sensitive-file discovery',
    'L05' => 'Stored content encoding',
    'L06' => 'Object-level authorization (IDOR)',
    'L07' => 'Path handling',
    'L08' => 'CSRF protections',
    'L09' => 'Input validation and SQL filters',
    'L10' => 'HTML attribute context',
    'L11' => 'Consistent authorization checks',
    'L12' => 'SSRF using a local-only simulator',
    'L13' => 'Template rendering boundaries',
    'L14' => 'Blind SQLi concepts with dummy records',
    'L15' => 'Token and session validation',
    'L16' => 'Multi-step authorization review',
];
?><!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="description" content="Cerita, panduan, dan ide untuk komunitas TeamSakit.">
<title><?= $article ? h((string)$article['title']) . ' — TeamSakit' : 'TeamSakit — Cerita dan Inspirasi' ?></title>
<link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<header class="site-header"><a class="brand" href="/">TeamSakit<span>.</span></a>
<nav><a href="/">Beranda</a><a href="/?page=cerita">Cerita Kami</a><a href="/?page=panduan">Panduan</a><a href="/?page=kontak">Kontak</a></nav></header>
<main>
<?php if ($article): ?>
<section class="hero"><p class="eyebrow">CERITA TEAMSAKIT</p><h1><?= h((string)$article['title']) ?></h1><p><?= h((string)$article['summary']) ?></p></section>
<section class="section"><article class="card"><p><?= nl2br(h((string)$article['body'])) ?></p><a href="/">← Kembali ke beranda</a></article></section>
<?php elseif (preg_match('#^/article/#', $path)): ?>
<section class="hero"><p class="eyebrow">404</p><h1>Cerita tidak ditemukan.</h1><p>Konten yang kamu cari tidak tersedia.</p><a class="primary" href="/">Kembali ke beranda</a></section>
<?php else: ?>
<section class="hero"><p class="eyebrow">CERITA • IDE • KOMUNITAS</p><h1>Temukan inspirasi<br>untuk hal yang berarti.</h1><p>Ruang untuk berbagi cerita, menemukan ide baru, dan tumbuh bersama komunitas.</p><a class="primary" href="#artikel">Jelajahi cerita <span aria-hidden="true">↗</span></a></section>
<section id="artikel" class="section"><div class="section-heading"><div><p class="eyebrow">DARI KOMUNITAS</p><h2>Cerita terbaru</h2></div><span class="muted">Pilihan editor</span></div>
<div class="cards"><?php foreach ($articles as $item): ?><article class="card"><div class="card-art" aria-hidden="true"></div><p class="eyebrow">CERITA TEAMSAKIT</p><h3><?= h((string)$item['title']) ?></h3><p><?= h((string)$item['summary']) ?></p><a href="/article/<?= rawurlencode((string)$item['slug']) ?>">Baca cerita →</a></article><?php endforeach; ?>
<?php if (!$articles): ?><article class="card"><h3>Konten sedang disiapkan</h3><p>Pastikan database telah siap, lalu muat ulang halaman ini.</p></article><?php endif; ?></div></section>
<?php endif; ?>
<?php if ($isLab): ?><section class="section lab"><p class="eyebrow">PRACTICE AREA</p><h2>Lab keamanan web</h2><p>Ini adalah daftar rencana skenario. Setiap level harus diimplementasikan dan diuji secara lokal sebelum dipakai.</p><div class="scenario-grid"><?php foreach ($scenarios as $id=>$title): ?><div class="scenario"><strong><?= h($id) ?></strong><span><?= h($title) ?></span></div><?php endforeach; ?></div></section><?php endif; ?>
</main><footer><a class="brand" href="/">TeamSakit<span>.</span></a><p>Ruang cerita dan ide untuk komunitas.</p><small>© <?= date('Y') ?> TeamSakit</small></footer>
</body></html>
