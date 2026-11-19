<?php
require_once __DIR__ . '/../includes/functions.php';

$level = trim($_GET['level'] ?? '');
$validLevels = ['beginner', 'elementary', 'intermediate'];
$levelIsValid = in_array($level, $validLevels, true);
$sql = 'SELECT id,title,level,description,estimated_minutes FROM lessons WHERE is_published=1';
$params = [];
if ($levelIsValid) {
    $sql .= ' AND level=?';
    $params[] = $level;
}
$sql .= ' ORDER BY sort_order,id';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$lessons = $stmt->fetchAll();

$user = current_user();
$completedIds = [];
$totalLessons = (int) $pdo->query('SELECT COUNT(*) FROM lessons WHERE is_published=1')->fetchColumn();
if ($user) {
    $stmt = $pdo->prepare(
        "SELECT up.item_id FROM user_progress up
         INNER JOIN lessons l ON l.id=up.item_id
         WHERE up.user_id=? AND up.item_type='lesson' AND l.is_published=1"
    );
    $stmt->execute([(int) $user['id']]);
    $completedIds = array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
}
$completedCount = count($completedIds);
$progressPercent = $totalLessons > 0 ? (int) round($completedCount / $totalLessons * 100) : 0;
$nextLessonId = null;
foreach ($lessons as $lesson) {
    if (!in_array((int) $lesson['id'], $completedIds, true)) {
        $nextLessonId = (int) $lesson['id'];
        break;
    }
}

$page_title = 'Somali lessons';
$active_page = 'lessons';
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero lessons-hero">
  <div class="container">
    <span class="eyebrow">YOUR SOMALI LEARNING PATH</span>
    <h1>Make progress, <em>one lesson at a time.</em></h1>
    <p>Build useful Somali step by step: learn a few phrases, practise them in context, then check what you remember.</p>
    <div class="page-hero-meta">
      <span><?= $totalLessons ?> guided lessons</span>
      <span>Beginner to intermediate</span>
      <?php if ($user): ?><span><?= $completedCount ?> completed</span><?php endif; ?>
    </div>
  </div>
</section>
<section class="container content-section lessons-section">
  <div class="filter-row">
    <div>
      <span class="eyebrow">LEARNING PATH</span>
      <h2><?= count($lessons) ?> <?= $levelIsValid ? e(level_label($level)) . ' ' : '' ?>lessons</h2>
    </div>
    <nav class="filter-pills" aria-label="Filter lessons by level">
      <a class="<?= !$levelIsValid ? 'selected' : '' ?>" href="<?= url('pages/lessons.php') ?>" <?= !$levelIsValid ? 'aria-current="page"' : '' ?>>All levels</a>
      <?php foreach ($validLevels as $validLevel): ?>
        <a class="<?= $level === $validLevel ? 'selected' : '' ?>" href="<?= url('pages/lessons.php?level=' . $validLevel) ?>" <?= $level === $validLevel ? 'aria-current="page"' : '' ?>><?= e(level_label($validLevel)) ?></a>
      <?php endforeach; ?>
    </nav>
  </div>

  <?php if ($user && $totalLessons > 0): ?>
    <div class="learning-progress">
      <div class="learning-progress-copy">
        <strong>Your progress</strong>
        <span><?= $completedCount ?> of <?= $totalLessons ?> lessons complete</span>
      </div>
      <progress value="<?= $completedCount ?>" max="<?= $totalLessons ?>" aria-label="Course progress: <?= $progressPercent ?> percent"><?= $progressPercent ?>%</progress>
      <span class="learning-progress-percent"><?= $progressPercent ?>%</span>
    </div>
  <?php else: ?>
    <p class="learning-path-note">Choose a lesson to get started. Your next step is always easy to find.</p>
  <?php endif; ?>

  <?php if ($lessons): ?>
    <div class="lesson-card-grid lesson-grid-wide">
      <?php foreach ($lessons as $i => $lesson):
          $lessonId = (int) $lesson['id'];
          $isComplete = in_array($lessonId, $completedIds, true);
          $isNext = $user && $nextLessonId === $lessonId;
      ?>
        <article class="lesson-card<?= $isComplete ? ' is-complete' : '' ?><?= $isNext ? ' is-next' : '' ?>">
          <div class="lesson-card-top">
            <span class="lesson-number"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
            <span class="level-chip"><?= e(level_label($lesson['level'])) ?></span>
          </div>
          <div class="lesson-card-status">
            <?php if ($isComplete): ?>
              <span class="lesson-status-complete">✓ Completed</span>
            <?php elseif ($isNext): ?>
              <span class="lesson-status-next">Your next lesson</span>
            <?php elseif (!$user && $i === 0): ?>
              <span class="lesson-status-next">Start here</span>
            <?php else: ?>
              <span>Ready when you are</span>
            <?php endif; ?>
          </div>
          <h3><?= e($lesson['title']) ?></h3>
          <p><?= e($lesson['description']) ?></p>
          <div class="lesson-card-bottom">
            <span><?= (int) $lesson['estimated_minutes'] ?> min</span>
            <a href="<?= url('pages/lesson.php?id=' . $lessonId) ?>"><?= $isComplete ? 'Review lesson' : ($isNext ? 'Continue learning' : 'Start lesson') ?> <span aria-hidden="true">→</span></a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="empty-state">No lessons found for this level yet. Choose another level to keep learning.</div>
  <?php endif; ?>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
