<?php
require_once __DIR__ . '/../includes/functions.php';

function lesson_content_html(string $content): string
{
    $html = '';
    $paragraph = [];
    $list = [];
    $flushParagraph = static function () use (&$html, &$paragraph): void {
        if ($paragraph) {
            $html .= '<p>' . e(implode(' ', $paragraph)) . '</p>';
            $paragraph = [];
        }
    };
    $flushList = static function () use (&$html, &$list): void {
        if ($list) {
            $html .= '<ul class="lesson-phrase-list">';
            foreach ($list as $item) {
                $parts = explode(' — ', $item, 2);
                if (count($parts) === 2) {
                    $html .= '<li><strong>' . e($parts[0]) . '</strong><span>' . e($parts[1]) . '</span></li>';
                } else {
                    $html .= '<li>' . e($item) . '</li>';
                }
            }
            $html .= '</ul>';
            $list = [];
        }
    };

    foreach (preg_split('/\r\n|\r|\n/', trim($content)) as $line) {
        $line = trim($line);
        if ($line === '') {
            $flushParagraph();
            $flushList();
        } elseif (preg_match('/^##\s+(.+)$/', $line, $matches)) {
            $flushParagraph();
            $flushList();
            $html .= '<h2>' . e($matches[1]) . '</h2>';
        } elseif (str_starts_with($line, '- ')) {
            $flushParagraph();
            $list[] = substr($line, 2);
        } else {
            $flushList();
            $paragraph[] = $line;
        }
    }
    $flushParagraph();
    $flushList();
    return $html;
}

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM lessons WHERE id=? AND is_published=1');
$stmt->execute([$id]);
$lesson = $stmt->fetch();
if (!$lesson) {
    http_response_code(404);
    $page_title = 'Lesson not found';
    require __DIR__ . '/../includes/header.php';
    echo '<section class="container content-section"><div class="empty-state">This lesson is unavailable.</div></section>';
    require __DIR__ . '/../includes/footer.php';
    exit;
}

$user = current_user();
$done = $user ? is_completed($pdo, (int) $user['id'], 'lesson', $id) : false;
$allLessons = $pdo->query('SELECT id,title FROM lessons WHERE is_published=1 ORDER BY sort_order,id')->fetchAll();
$lessonPosition = 1;
foreach ($allLessons as $index => $item) {
    if ((int) $item['id'] === $id) {
        $lessonPosition = $index + 1;
        break;
    }
}
$previousLesson = $allLessons[$lessonPosition - 2] ?? null;
$nextLesson = $allLessons[$lessonPosition] ?? null;
$page_title = $lesson['title'];
$active_page = 'lessons';
require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero page-hero-small lesson-hero">
  <div class="container">
    <a class="back-link" href="<?= url('pages/lessons.php') ?>">← All lessons</a>
    <div class="hero-tags">
      <span class="level-chip"><?= e(level_label($lesson['level'])) ?></span>
      <span class="small-muted"><?= (int) $lesson['estimated_minutes'] ?> min</span>
    </div>
    <h1><?= e($lesson['title']) ?></h1>
    <p><?= e($lesson['description']) ?></p>
    <div class="lesson-step">
      <span>Lesson <?= $lessonPosition ?> of <?= count($allLessons) ?></span>
      <progress value="<?= $lessonPosition ?>" max="<?= max(1, count($allLessons)) ?>" aria-label="Lesson <?= $lessonPosition ?> of <?= count($allLessons) ?>"></progress>
      <span><?= $done ? 'Completed' : 'Keep going' ?></span>
    </div>
  </div>
</section>
<section class="container content-section lesson-reading">
  <article class="reading-card">
    <div class="reading-content"><?= lesson_content_html($lesson['content']) ?></div>
    <div class="lesson-actions">
      <?php if ($user): ?>
        <button class="btn <?= $done ? 'btn-outline' : 'btn-primary' ?>" type="button" data-mark-progress data-item-type="lesson" data-item-id="<?= $id ?>" data-csrf="<?= e(csrf_token()) ?>" <?= $done ? 'disabled' : '' ?>><?= $done ? '✓ Lesson completed' : 'Mark lesson complete' ?></button>
      <?php else: ?>
        <a class="btn btn-primary" href="<?= url('login.php') ?>">Sign in to save progress</a>
      <?php endif; ?>
      <a class="btn btn-outline" href="<?= url('pages/quiz.php?lesson_id=' . $id) ?>">Take lesson quiz <span aria-hidden="true">→</span></a>
    </div>
    <nav class="lesson-sequence" aria-label="Lesson navigation">
      <?php if ($previousLesson): ?>
        <a href="<?= url('pages/lesson.php?id=' . (int) $previousLesson['id']) ?>"><span>Previous lesson</span><strong>← <?= e($previousLesson['title']) ?></strong></a>
      <?php else: ?>
        <span></span>
      <?php endif; ?>
      <?php if ($nextLesson): ?>
        <a class="lesson-sequence-next" href="<?= url('pages/lesson.php?id=' . (int) $nextLesson['id']) ?>"><span>Up next</span><strong><?= e($nextLesson['title']) ?> →</strong></a>
      <?php endif; ?>
    </nav>
  </article>
  <aside class="reading-aside">
    <span class="eyebrow">A SIMPLE STUDY ROUTINE</span>
    <h3>Learn it. Say it. Use it.</h3>
    <ol class="study-steps">
      <li><span>1</span><div><strong>Read</strong><small>Notice the Somali phrase and its meaning.</small></div></li>
      <li><span>2</span><div><strong>Repeat</strong><small>Say each example aloud at your own pace.</small></div></li>
      <li><span>3</span><div><strong>Recall</strong><small>Try the quiz without looking back.</small></div></li>
    </ol>
    <a href="<?= url('pages/dictionary.php') ?>">Look up a word in the dictionary →</a>
  </aside>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
