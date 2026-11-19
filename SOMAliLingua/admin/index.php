<?php
require_once __DIR__.'/../includes/functions.php';
require_admin();

// ── Core stats ──────────────────────────────────────────────────────
$stats = [
    'students'  => (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='student'")->fetchColumn(),
    'admins'    => (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn(),
    'lessons'   => (int)$pdo->query('SELECT COUNT(*) FROM lessons')->fetchColumn(),
    'published' => (int)$pdo->query("SELECT COUNT(*) FROM lessons WHERE is_published=1")->fetchColumn(),
    'words'     => (int)$pdo->query('SELECT COUNT(*) FROM vocabulary')->fetchColumn(),
    'letters'   => (int)$pdo->query('SELECT COUNT(*) FROM alphabet')->fetchColumn(),
    'questions' => (int)$pdo->query('SELECT COUNT(*) FROM quiz_questions')->fetchColumn(),
    'attempts'  => (int)$pdo->query('SELECT COUNT(*) FROM quiz_attempts')->fetchColumn(),
];

// Average quiz score
$avgScore = $pdo->query('SELECT ROUND(AVG(percentage)) FROM quiz_attempts')->fetchColumn();
$avgScore = $avgScore !== null ? (int)$avgScore : null;

// Registrations this week
$weeklyNew = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetchColumn();

// Registrations this month
$monthlyNew = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetchColumn();

// Categories count
$categories = (int)$pdo->query('SELECT COUNT(DISTINCT category) FROM vocabulary')->fetchColumn();

// Recent registrations
$recent = $pdo->query('SELECT name,email,role,created_at FROM users ORDER BY created_at DESC LIMIT 8')->fetchAll();

// Recent quiz attempts
$recentQuizzes = $pdo->query('
    SELECT u.name, l.title AS lesson_title, qa.score, qa.total_questions, qa.percentage, qa.created_at
    FROM quiz_attempts qa
    JOIN users u ON u.id = qa.user_id
    JOIN lessons l ON l.id = qa.lesson_id
    ORDER BY qa.created_at DESC
    LIMIT 6
')->fetchAll();

// Top performing students (by average quiz score)
$topStudents = $pdo->query('
    SELECT u.name, u.email, COUNT(qa.id) AS attempts, ROUND(AVG(qa.percentage)) AS avg_score
    FROM quiz_attempts qa
    JOIN users u ON u.id = qa.user_id
    GROUP BY u.id
    ORDER BY avg_score DESC
    LIMIT 5
')->fetchAll();

// Content completion: lessons with audio vs without
$lessonsWithQuestions = (int)$pdo->query('SELECT COUNT(DISTINCT lesson_id) FROM quiz_questions')->fetchColumn();

$page_title = 'Admin overview';
require __DIR__.'/../includes/admin_header.php';
?>

<!-- ── Welcome Banner ─────────────────────────────────────────────── -->
<div class="admin-welcome">
    <div class="welcome-text">
        <span class="eyebrow"><span class="eyebrow-dot"></span> ADMINISTRATION DASHBOARD</span>
        <h1>Welcome back, <?= e(current_user()['name'] ?? 'Admin') ?></h1>
        <p>Here's what's happening on SomaliLingua today.</p>
    </div>
    <div class="welcome-date">
        <span class="date-icon">📅</span>
        <div>
            <strong><?= date('l') ?></strong>
            <span><?= date('F j, Y') ?></span>
        </div>
    </div>
</div>

<!-- ── Primary Stats ──────────────────────────────────────────────── -->
<div class="admin-stats-grid">
    <div class="stat-card stat-students">
        <div class="stat-icon">
            <svg width="22" height="22" fill="none" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="2"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        </div>
        <div class="stat-info">
            <span>Total Students</span>
            <strong><?= $stats['students'] ?></strong>
            <small class="stat-change stat-up">+<?= $weeklyNew ?> this week</small>
        </div>
    </div>

    <div class="stat-card stat-lessons">
        <div class="stat-icon">
            <svg width="22" height="22" fill="none" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" stroke="currentColor" stroke-width="2"/></svg>
        </div>
        <div class="stat-info">
            <span>Lessons</span>
            <strong><?= $stats['lessons'] ?></strong>
            <small><?= $stats['published'] ?> published</small>
        </div>
    </div>

    <div class="stat-card stat-vocab">
        <div class="stat-icon">
            <svg width="22" height="22" fill="none" viewBox="0 0 24 24"><path d="M12 20V10M18 20V4M6 20v-4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        </div>
        <div class="stat-info">
            <span>Vocabulary</span>
            <strong><?= $stats['words'] ?></strong>
            <small><?= $categories ?> categories</small>
        </div>
    </div>

    <div class="stat-card stat-quiz">
        <div class="stat-icon">
            <svg width="22" height="22" fill="none" viewBox="0 0 24 24"><path d="M9 11l3 3L22 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        </div>
        <div class="stat-info">
            <span>Quiz Attempts</span>
            <strong><?= $stats['attempts'] ?></strong>
            <small>Avg score: <?= $avgScore !== null ? $avgScore.'%' : '—' ?></small>
        </div>
    </div>
</div>

<!-- ── Secondary Stats Row ────────────────────────────────────────── -->
<div class="admin-secondary-stats">
    <div class="sec-stat">
        <span class="sec-stat-label">Alphabet Letters</span>
        <span class="sec-stat-value"><?= $stats['letters'] ?></span>
    </div>
    <div class="sec-stat-divider"></div>
    <div class="sec-stat">
        <span class="sec-stat-label">Quiz Questions</span>
        <span class="sec-stat-value"><?= $stats['questions'] ?></span>
    </div>
    <div class="sec-stat-divider"></div>
    <div class="sec-stat">
        <span class="sec-stat-label">Admins</span>
        <span class="sec-stat-value"><?= $stats['admins'] ?></span>
    </div>
    <div class="sec-stat-divider"></div>
    <div class="sec-stat">
        <span class="sec-stat-label">New This Month</span>
        <span class="sec-stat-value"><?= $monthlyNew ?></span>
    </div>
    <div class="sec-stat-divider"></div>
    <div class="sec-stat">
        <span class="sec-stat-label">Lessons w/ Quizzes</span>
        <span class="sec-stat-value"><?= $lessonsWithQuestions ?>/<?= $stats['lessons'] ?></span>
    </div>
</div>

<!-- ── Main Panels ────────────────────────────────────────────────── -->
<div class="admin-panels-grid">

    <!-- Quick Actions -->
    <section class="admin-panel panel-actions">
        <div class="panel-heading">
            <h2>
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Quick Actions
            </h2>
        </div>
        <div class="admin-action-list">
            <a href="<?= url('admin/lessons.php') ?>" class="action-link">
                <span class="action-icon icon-blue">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" stroke="currentColor" stroke-width="2"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" stroke="currentColor" stroke-width="2"/></svg>
                </span>
                <div>
                    <strong>Lessons</strong>
                    <small>Create and edit lesson content</small>
                </div>
                <span class="action-badge"><?= $stats['lessons'] ?></span>
                <span class="action-arrow">→</span>
            </a>
            <a href="<?= url('admin/alphabet.php') ?>" class="action-link">
                <span class="action-icon icon-gold">Aa</span>
                <div>
                    <strong>Alphabet & Audio</strong>
                    <small>Manage letter examples and recordings</small>
                </div>
                <span class="action-badge"><?= $stats['letters'] ?></span>
                <span class="action-arrow">→</span>
            </a>
            <a href="<?= url('admin/vocabulary.php') ?>" class="action-link">
                <span class="action-icon icon-green">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><path d="M12 20V10M18 20V4M6 20v-4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                </span>
                <div>
                    <strong>Vocabulary</strong>
                    <small>Manage dictionary entries and audio</small>
                </div>
                <span class="action-badge"><?= $stats['words'] ?></span>
                <span class="action-arrow">→</span>
            </a>
            <a href="<?= url('admin/questions.php') ?>" class="action-link">
                <span class="action-icon icon-violet">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><path d="M9 11l3 3L22 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                </span>
                <div>
                    <strong>Quiz Questions</strong>
                    <small>Build lesson knowledge checks</small>
                </div>
                <span class="action-badge"><?= $stats['questions'] ?></span>
                <span class="action-arrow">→</span>
            </a>
            <a href="<?= url('admin/users.php') ?>" class="action-link">
                <span class="action-icon icon-blue">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="2"/></svg>
                </span>
                <div>
                    <strong>Registered Users</strong>
                    <small>Review learner accounts and permissions</small>
                </div>
                <span class="action-badge"><?= $stats['students'] + $stats['admins'] ?></span>
                <span class="action-arrow">→</span>
            </a>
        </div>
    </section>

    <!-- Recent Registrations -->
    <section class="admin-panel panel-recent">
        <div class="panel-heading">
            <h2>
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="2"/><line x1="19" y1="8" x2="19" y2="14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><line x1="22" y1="11" x2="16" y2="11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                Recent Registrations
            </h2>
            <a href="<?= url('admin/users.php') ?>" class="panel-link">View all →</a>
        </div>
        <?php if($recent): ?>
        <div class="user-list">
            <?php foreach($recent as $r): ?>
            <div class="user-row">
                <span class="user-avatar" style="background:<?= $r['role']==='admin' ? '#fbf3e6' : '#edf3f8' ?>;color:<?= $r['role']==='admin' ? '#b98334' : '#2f6598' ?>">
                    <?= strtoupper(mb_substr($r['name'], 0, 1)) ?>
                </span>
                <div class="user-meta">
                    <strong><?= e($r['name']) ?></strong>
                    <small><?= e($r['email']) ?></small>
                </div>
                <div class="user-extra">
                    <span class="role-badge role-<?= e($r['role']) ?>"><?= e(ucfirst($r['role'])) ?></span>
                    <small class="time-ago"><?= e(date('M j, Y', strtotime($r['created_at']))) ?></small>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-panel">
            <svg width="40" height="40" fill="none" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" stroke="#c5ced8" stroke-width="1.5"/><circle cx="9" cy="7" r="4" stroke="#c5ced8" stroke-width="1.5"/></svg>
            <p>No registrations yet.</p>
        </div>
        <?php endif; ?>
    </section>

    <!-- Recent Quiz Activity -->
    <section class="admin-panel panel-quizzes">
        <div class="panel-heading">
            <h2>
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="2"/><polyline points="14,2 14,8 20,8" stroke="currentColor" stroke-width="2"/><line x1="16" y1="13" x2="8" y2="13" stroke="currentColor" stroke-width="2"/><line x1="16" y1="17" x2="8" y2="17" stroke="currentColor" stroke-width="2"/></svg>
                Recent Quiz Activity
            </h2>
        </div>
        <?php if($recentQuizzes): ?>
        <div class="quiz-activity-list">
            <?php foreach($recentQuizzes as $qa): ?>
            <div class="quiz-activity-row">
                <span class="user-avatar user-avatar-sm" style="background:#edf3f8;color:#2f6598">
                    <?= strtoupper(mb_substr($qa['name'], 0, 1)) ?>
                </span>
                <div class="quiz-activity-info">
                    <strong><?= e($qa['name']) ?></strong>
                    <small><?= e($qa['lesson_title']) ?></small>
                </div>
                <div class="quiz-score-badge <?= $qa['percentage'] >= 70 ? 'score-good' : ($qa['percentage'] >= 50 ? 'score-ok' : 'score-low') ?>">
                    <?= (int)$qa['percentage'] ?>%
                </div>
                <small class="time-ago"><?= e(date('M j', strtotime($qa['created_at']))) ?></small>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-panel">
            <svg width="40" height="40" fill="none" viewBox="0 0 24 24"><path d="M9 11l3 3L22 4" stroke="#c5ced8" stroke-width="1.5"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" stroke="#c5ced8" stroke-width="1.5"/></svg>
            <p>No quiz attempts yet.</p>
        </div>
        <?php endif; ?>
    </section>

    <!-- Top Students -->
    <section class="admin-panel panel-top-students">
        <div class="panel-heading">
            <h2>
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Top Students
            </h2>
        </div>
        <?php if($topStudents): ?>
        <div class="top-students-list">
            <?php foreach($topStudents as $i => $ts): ?>
            <div class="top-student-row">
                <span class="rank-badge rank-<?= $i + 1 ?>"><?= $i + 1 ?></span>
                <div class="top-student-info">
                    <strong><?= e($ts['name']) ?></strong>
                    <small><?= (int)$ts['attempts'] ?> quiz<?= (int)$ts['attempts'] !== 1 ? 'zes' : '' ?> taken</small>
                </div>
                <div class="avg-score-pill <?= $ts['avg_score'] >= 70 ? 'score-good' : ($ts['avg_score'] >= 50 ? 'score-ok' : 'score-low') ?>">
                    <?= (int)$ts['avg_score'] ?>% avg
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-panel">
            <svg width="40" height="40" fill="none" viewBox="0 0 24 24"><polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26" stroke="#c5ced8" stroke-width="1.5"/></svg>
            <p>No quiz data yet.</p>
        </div>
        <?php endif; ?>
    </section>

</div>

<?php require __DIR__.'/../includes/admin_footer.php'; ?>
