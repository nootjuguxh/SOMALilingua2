<?php
require_once __DIR__.'/../includes/functions.php';require_login();$result=$_SESSION['last_quiz_result']??null;unset($_SESSION['last_quiz_result']);if(!$result)redirect('pages/quizzes.php');$page_title='Quiz result';require __DIR__.'/../includes/header.php';
?>
<section class="container content-section result-section"><div class="result-card"><span class="eyebrow">QUIZ COMPLETE</span><div class="result-score"><?= (int)$result['percentage'] ?><small>%</small></div><h1><?= (int)$result['correct'] ?> out of <?= (int)$result['total'] ?> correct</h1><p>You completed <strong><?= e($result['lesson']) ?></strong>. Keep practising to strengthen what you know.</p><div class="result-actions"><a class="btn btn-primary" href="<?= url('pages/quizzes.php') ?>">Try another quiz</a><a class="btn btn-outline" href="<?= url('pages/dashboard.php') ?>">View my dashboard</a></div></div></section>
<?php require __DIR__.'/../includes/footer.php'; ?>
