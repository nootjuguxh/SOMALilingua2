<?php
require_once __DIR__ . '/functions.php';
$page_title = $page_title ?? APP_NAME;
$active_page = $active_page ?? '';
$flashMessage = get_flash();
$user = current_user();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="SomaliLingua — learn Somali through structured lessons, audio, vocabulary and practice.">
  <title><?= e($page_title) ?> | <?= APP_NAME ?></title>
  <link rel="icon" href="<?= url('assets/images/logo.svg') ?>" type="image/svg+xml">
  <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
  <script>window.SOMALILINGUA_BASE = <?= json_encode(BASE_URL) ?>;</script>
  <script defer src="<?= url('assets/js/main.js') ?>"></script>
  <script defer src="<?= url('assets/js/audio.js') ?>"></script>
</head>
<body>
<header class="site-header">
  <div class="container nav-wrap">
    <a class="brand" href="<?= url() ?>"><img src="<?= url('assets/images/logo.svg') ?>" alt="" width="38" height="38"><span>Somali<span class="brand-accent">Lingua</span><small>Learn. Connect. Preserve.</small></span></a>
    <button class="menu-toggle" type="button" aria-label="Toggle navigation" aria-expanded="false" data-menu-toggle><span></span><span></span><span></span></button>
    <nav class="main-nav" data-main-nav>
      <a class="<?= $active_page==='home'?'active':'' ?>" href="<?= url() ?>">Home</a>
      <a class="<?= $active_page==='alphabet'?'active':'' ?>" href="<?= url('pages/alphabet.php') ?>">Alphabet</a>
      <a class="<?= $active_page==='lessons'?'active':'' ?>" href="<?= url('pages/lessons.php') ?>">Lessons</a>
      <a class="<?= $active_page==='dictionary'?'active':'' ?>" href="<?= url('pages/dictionary.php') ?>">Dictionary</a>
      <?php if ($user): ?>
        <a class="<?= $active_page==='dashboard'?'active':'' ?>" href="<?= url('pages/dashboard.php') ?>">My learning</a>
        <?php if (($user['role'] ?? '')==='admin'): ?><a href="<?= url('admin/index.php') ?>">Admin</a><?php endif; ?>
        <a class="nav-user" href="<?= url('pages/profile.php') ?>"><?= e($user['name']) ?></a>
        <a class="nav-cta nav-cta-light" href="<?= url('logout.php') ?>">Sign out</a>
      <?php else: ?>
        <a href="<?= url('login.php') ?>">Sign in</a><a class="nav-cta" href="<?= url('register.php') ?>">Get started</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main>
<?php if ($flashMessage): ?><div class="container flash-wrap"><div class="alert alert-<?= e($flashMessage['type']) ?>"><?= e($flashMessage['message']) ?></div></div><?php endif; ?>
