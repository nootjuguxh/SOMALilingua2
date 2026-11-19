<?php require_once __DIR__ . '/functions.php'; require_admin(); $page_title=$page_title??'Administration'; $active_page=$active_page??''; $flashMessage=get_flash();
// Auto-detect active page from filename
$currentFile = basename($_SERVER['SCRIPT_FILENAME'] ?? '', '.php');
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($page_title) ?> | SomaliLingua Admin</title>
<link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
<link rel="stylesheet" href="<?= url('assets/css/admin.css') ?>">
<script>window.SOMALILINGUA_BASE = <?= json_encode(BASE_URL) ?>;</script>
<script defer src="<?= url('assets/js/main.js') ?>"></script>
</head>
<body class="admin-body">
<header class="site-header admin-header">
    <div class="container nav-wrap">
        <a class="brand" href="<?= url('admin/index.php') ?>">
            <img src="<?= url('assets/images/logo.svg') ?>" width="38" alt="">
            <span>Somali<span class="brand-accent">Lingua</span><small>Administration</small></span>
        </a>
        <button class="menu-toggle" onclick="document.querySelector('.admin-nav').classList.toggle('is-open')" aria-label="Toggle menu">
            <span></span><span></span><span></span>
        </button>
        <nav class="main-nav admin-nav">
            <a href="<?= url('admin/index.php') ?>" class="nav-item <?= $currentFile==='index' ? 'active' : '' ?>">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1" stroke="currentColor" stroke-width="2"/><rect x="14" y="3" width="7" height="7" rx="1" stroke="currentColor" stroke-width="2"/><rect x="3" y="14" width="7" height="7" rx="1" stroke="currentColor" stroke-width="2"/><rect x="14" y="14" width="7" height="7" rx="1" stroke="currentColor" stroke-width="2"/></svg>
                Overview
            </a>
            <a href="<?= url('admin/lessons.php') ?>" class="nav-item <?= $currentFile==='lessons' ? 'active' : '' ?>">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" stroke="currentColor" stroke-width="2"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" stroke="currentColor" stroke-width="2"/></svg>
                Lessons
            </a>
            <a href="<?= url('admin/alphabet.php') ?>" class="nav-item <?= $currentFile==='alphabet' ? 'active' : '' ?>">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24"><path d="M4 7V4h16v3M9 20h6M12 4v16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Alphabet
            </a>
            <a href="<?= url('admin/vocabulary.php') ?>" class="nav-item <?= $currentFile==='vocabulary' ? 'active' : '' ?>">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24"><path d="M12 20V10M18 20V4M6 20v-4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                Vocabulary
            </a>
            <a href="<?= url('admin/questions.php') ?>" class="nav-item <?= $currentFile==='questions' ? 'active' : '' ?>">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24"><path d="M9 11l3 3L22 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                Quizzes
            </a>
            <a href="<?= url('admin/users.php') ?>" class="nav-item <?= $currentFile==='users' ? 'active' : '' ?>">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="2"/></svg>
                Users
            </a>
            <span class="nav-divider"></span>
            <a href="<?= url() ?>" class="nav-item nav-item-subtle">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><polyline points="15,3 21,3 21,9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><line x1="10" y1="14" x2="21" y2="3" stroke="currentColor" stroke-width="2"/></svg>
                View site
            </a>
            <a href="<?= url('logout.php') ?>" class="nav-item nav-item-subtle">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><polyline points="16,17 21,12 16,7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><line x1="21" y1="12" x2="9" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                Sign out
            </a>
        </nav>
    </div>
</header>
<main class="container admin-main">
<?php if($flashMessage): ?>
<div class="alert alert-<?= e($flashMessage['type']) ?>"><?= e($flashMessage['message']) ?></div>
<?php endif; ?>
