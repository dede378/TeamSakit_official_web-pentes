<?php
declare(strict_types=1);
require __DIR__ . '/../app/bootstrap.php';

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$isLab = ($path === '/labs' || str_starts_with($path, '/labs/'));
if ($isLab) {
    require_lab_access();
    require __DIR__ . '/../app/lab_router.php';
    exit;
}

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
<meta name="theme-color" content="#080d16">
<meta name="description" content="Belajar keamanan siber, memahami risiko digital, dan bertumbuh bersama komunitas TeamSakit.">
<title><?= $article ? h((string)$article['title']) . ' — TeamSakit' : 'TeamSakit — Cybersecurity & Community' ?></title>
<link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<header class="site-header">
  <a class="brand" href="/" aria-label="TeamSakit beranda"><span class="brand-mark" aria-hidden="true">T<span>.</span></span><span>TeamSakit<span class="brand-dot">.</span></span></a>
  <nav aria-label="Navigasi utama">
    <a class="nav-active" href="/">Beranda</a>
    <a href="/?page=cerita">Cerita Kami</a>
    <a href="/?page=panduan">Panduan</a>
    <a href="/?page=kontak">Kontak</a>
  </nav>
  <a class="nav-cta" href="#eksplorasi"><span class="status-dot"></span> Jelajahi Lab <span aria-hidden="true">↗</span></a>
</header>
<main>
<?php if ($article): ?>
<section class="article-hero">
  <p class="eyebrow"><span class="eyebrow-line"></span> CERITA TEAMSAKIT / KNOWLEDGE BASE</p>
  <h1><?= h((string)$article['title']) ?></h1>
  <p class="article-summary"><?= h((string)$article['summary']) ?></p>
  <div class="article-meta"><span class="status-dot"></span> Wawasan komunitas <span class="meta-divider">/</span> TeamSakit</div>
</section>
<section class="section article-section"><article class="article-content"><p><?= nl2br(h((string)$article['body'])) ?></p><a class="text-link" href="/">← Kembali ke beranda</a></article></section>
<?php elseif (preg_match('#^/article/#', $path)): ?>
<section class="hero"><p class="eyebrow"><span class="eyebrow-line"></span> ERROR 404</p><h1>Halaman tidak ditemukan<span class="accent">.</span></h1><p>Konten yang kamu cari mungkin sudah dipindahkan atau belum tersedia.</p><a class="button button-primary" href="/">Kembali ke beranda <span aria-hidden="true">↗</span></a></section>
<?php else: ?>
<section class="hero">
  <div class="hero-copy">
    <p class="eyebrow"><span class="eyebrow-line"></span> CYBERSECURITY <span class="eyebrow-separator">•</span> KNOWLEDGE <span class="eyebrow-separator">•</span> COMMUNITY</p>
    <h1>Jelajahi dunia digital<span class="accent">.</span><br>Pahami risikonya<span class="accent">.</span><br><span class="heading-muted">Bangun pertahanan.</span></h1>
    <p class="hero-description">Tempat belajar keamanan siber, memahami ancaman digital, dan mengasah kemampuan melalui pengetahuan serta latihan yang bertanggung jawab.</p>
    <div class="hero-actions"><a class="button button-primary" href="#artikel">Mulai eksplorasi <span aria-hidden="true">↗</span></a><a class="button button-secondary" href="#eksplorasi"><span class="play-icon" aria-hidden="true">▷</span> Lihat area belajar</a></div>
    <div class="hero-proof"><div class="proof-icon"><span>✓</span></div><p><strong>Belajar dengan aman</strong><br><span>Pengetahuan untuk membangun, bukan merusak.</span></p></div>
  </div>
  <div class="hero-visual" aria-label="Ilustrasi keamanan jaringan digital" role="img">
    <div class="visual-grid"></div><div class="orbit orbit-one"></div><div class="orbit orbit-two"></div><div class="orbit orbit-three"></div>
    <div class="node node-one"></div><div class="node node-two"></div><div class="node node-three"></div><div class="node node-four"></div>
    <div class="shield-wrap"><div class="shield-glow"></div><svg class="shield-svg" viewBox="0 0 180 205" aria-hidden="true"><defs><linearGradient id="shieldGradient" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="#d9ff9b"/><stop offset="50%" stop-color="#9dff3d"/><stop offset="100%" stop-color="#20d8b4"/></linearGradient></defs><path d="M90 8 L157 34 V91 C157 137 129 170 90 193 C51 170 23 137 23 91 V34 Z" fill="#10251f" stroke="url(#shieldGradient)" stroke-width="3"/><path d="M90 23 L143 43 V91 C143 127 122 153 90 174 C58 153 37 127 37 91 V43 Z" fill="none" stroke="#70e9a2" stroke-opacity=".3" stroke-width="1.5"/><rect x="65" y="87" width="50" height="40" rx="8" fill="url(#shieldGradient)"/><path d="M75 87 V73 A15 15 0 0 1 105 73 V87" fill="none" stroke="#baff70" stroke-width="7" stroke-linecap="round"/><circle cx="90" cy="105" r="5" fill="#0a1715"/><path d="M90 109 V117" stroke="#0a1715" stroke-width="4" stroke-linecap="round"/></svg></div>
    <div class="terminal-card"><div class="terminal-top"><span class="terminal-lights"><i></i><i></i><i></i></span><span>security_status.sh</span><span class="terminal-lock">⌑</span></div><div class="terminal-body"><p><span class="term-prompt">›</span> system.check()</p><p><span class="term-ok">●</span> awareness <b>ACTIVE</b></p><p><span class="term-ok">●</span> learning_mode <b>ENABLED</b></p><p><span class="term-ok">●</span> defense_level <b>BUILDING</b></p><div class="terminal-progress"><span></span></div><small>STATUS: READY TO LEARN<span class="cursor">_</span></small></div></div>
    <div class="visual-label label-top"><span class="status-dot"></span> DIGITAL DEFENSE</div><div class="visual-label label-bottom">01 / THINK · LEARN · SECURE</div>
  </div>
</section>
<section id="eksplorasi" class="feature-strip">
  <a class="feature-item" href="#artikel"><span class="feature-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21zM4 5.5v13A2.5 2.5 0 0 1 6.5 16H20M8 7h8M8 10h6"/></svg></span><span><strong>Wawasan & cerita</strong><small>Ide dan pengalaman komunitas</small></span><span class="feature-arrow">↗</span></a>
  <a class="feature-item" href="#artikel"><span class="feature-icon cyan"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3 20 6v5c0 5-3.4 8-8 10-4.6-2-8-5-8-10V6zM9 12l2 2 4-4"/></svg></span><span><strong>Panduan keamanan</strong><small>Mulai dari konsep dasar</small></span><span class="feature-arrow">↗</span></a>
  <a class="feature-item" href="#lab-info"><span class="feature-icon purple"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m8 7-5 5 5 5M16 7l5 5-5 5M14 4l-4 16"/></svg></span><span><strong>Cyber lab</strong><small>Latihan di lingkungan terkontrol</small></span><span class="feature-arrow">↗</span></a>
</section>
<section id="artikel" class="section">
  <div class="section-heading"><div><p class="eyebrow"><span class="eyebrow-line"></span> DARI KOMUNITAS</p><h2>Pengetahuan adalah<br>pertahanan pertama<span class="accent">.</span></h2></div><p class="section-intro">Jelajahi cerita, ide, dan panduan praktis untuk membangun kebiasaan digital yang lebih aman.</p></div>
  <div class="cards">
  <?php foreach ($articles as $index => $item): ?><article class="card">
    <a class="card-art art-<?= (int)$index % 3 ?>" href="/article/<?= rawurlencode((string)$item['slug']) ?>" aria-label="Baca <?= h((string)$item['title']) ?>"><span class="art-kicker">TEAMSAKIT / INSIGHT</span><span class="art-symbol" aria-hidden="true"><?= $index % 3 === 0 ? '⌘' : ($index % 3 === 1 ? '◈' : '⟡') ?></span><span class="art-index">0<?= (int)$index + 1 ?> — ARTICLE</span></a>
    <div class="card-content"><p class="card-category"><span class="status-dot"></span> CERITA TEAMSAKIT</p><h3><?= h((string)$item['title']) ?></h3><p class="card-summary"><?= h((string)$item['summary']) ?></p><a class="text-link" href="/article/<?= rawurlencode((string)$item['slug']) ?>">Baca cerita <span aria-hidden="true">→</span></a></div>
  </article><?php endforeach; ?>
  <?php if (!$articles): ?><article class="card card-empty"><div class="empty-symbol">⌘</div><h3>Konten sedang disiapkan</h3><p>Pastikan database MySQL sudah aktif dan berisi artikel, lalu muat ulang halaman ini.</p></article><?php endif; ?>
  </div>
</section>
<section id="lab-info" class="lab-banner"><div class="lab-banner-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m8 7-5 5 5 5M16 7l5 5-5 5M14 4l-4 16"/></svg></div><div class="lab-banner-copy"><p class="eyebrow">PRACTICE RESPONSIBLY</p><h2>Rasa ingin tahu. <span class="accent">Pertahanan yang kuat.</span></h2><p>Bangun pemahaman lewat skenario edukasi dan latihan hanya di lingkungan yang kamu miliki atau punya izin untuk menguji.</p></div><div class="lab-banner-tag"><span class="status-dot"></span> LAB ACCESS CONTROLLED</div></section>
<?php endif; ?>
<?php if ($isLab): ?><section class="section lab"><p class="eyebrow">PRACTICE AREA</p><h2>Lab keamanan web</h2><p>Ini adalah daftar rencana skenario. Setiap level harus diimplementasikan dan diuji secara lokal sebelum dipakai.</p><div class="scenario-grid"><?php foreach ($scenarios as $id=>$title): ?><div class="scenario"><strong><?= h($id) ?></strong><span><?= h($title) ?></span></div><?php endforeach; ?></div></section><?php endif; ?>
</main>
<footer><div class="footer-main"><a class="brand" href="/"><span class="brand-mark" aria-hidden="true">T<span>.</span></span><span>TeamSakit<span class="brand-dot">.</span></span></a><p>Belajar bersama. Berpikir kritis. Tetap aman di dunia digital.</p><a class="back-top" href="#" aria-label="Kembali ke atas">↑</a></div><div class="footer-bottom"><span>© <?= date('Y') ?> TeamSakit Community</span><span><span class="status-dot"></span> BUILT FOR RESPONSIBLE LEARNING</span></div></footer>
</body></html>
