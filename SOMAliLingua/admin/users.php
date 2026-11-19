<?php
require_once __DIR__.'/../includes/functions.php';
require_admin();

if($_SERVER['REQUEST_METHOD']==='POST'){
    verify_csrf();
    $action=$_POST['action']??'';
    $id=(int)($_POST['id']??0);
    if($id===(int)current_user()['id']){
        flash('warning','You cannot change or delete your own administrator account here.');
        redirect('admin/users.php');
    }
    if($action==='role'){
        $role=($_POST['role']??'student')==='admin'?'admin':'student';
        $s=$pdo->prepare('UPDATE users SET role=? WHERE id=?');
        $s->execute([$role,$id]);
        flash('success','User role updated.');
        redirect('admin/users.php');
    }
    if($action==='delete'){
        $s=$pdo->prepare('DELETE FROM users WHERE id=?');
        $s->execute([$id]);
        flash('success','User account deleted.');
        redirect('admin/users.php');
    }
}

// ── Stats ───────────────────────────────────────────────────────────
$totalUsers   = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$totalStudents= (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='student'")->fetchColumn();
$totalAdmins  = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn();
$newThisWeek  = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetchColumn();
$newThisMonth = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetchColumn();

// ── Filter / search ─────────────────────────────────────────────────
$roleFilter = $_GET['role'] ?? 'all';
$search     = trim($_GET['q'] ?? '');

$sql = 'SELECT u.id, u.name, u.email, u.role, u.created_at,
        (SELECT COUNT(*) FROM user_progress p WHERE p.user_id=u.id) AS progress_count,
        (SELECT COUNT(*) FROM quiz_attempts qa WHERE qa.user_id=u.id) AS quiz_count,
        (SELECT ROUND(AVG(qa2.percentage)) FROM quiz_attempts qa2 WHERE qa2.user_id=u.id) AS avg_score
        FROM users u WHERE 1=1';
$params = [];

if($roleFilter === 'student'){ $sql .= " AND u.role='student'"; }
elseif($roleFilter === 'admin'){ $sql .= " AND u.role='admin'"; }

if($search !== ''){
    $sql .= ' AND (u.name LIKE ? OR u.email LIKE ?)';
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
}

$sql .= ' ORDER BY u.created_at DESC LIMIT 500';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$users = $stmt->fetchAll();

$page_title='Manage users';
require __DIR__.'/../includes/admin_header.php';
?>

<!-- ── Page Header ────────────────────────────────────────────────── -->
<div class="admin-heading">
    <div>
        <span class="eyebrow"><span class="eyebrow-dot"></span> USER MANAGEMENT</span>
        <h1>Manage Users</h1>
        <p>Review registered accounts, monitor activity, and manage access.</p>
    </div>
</div>

<!-- ── User Stats ─────────────────────────────────────────────────── -->
<div class="user-stats-row">
    <div class="user-stat-card">
        <div class="user-stat-icon" style="background:#edf3f8;color:#2f6598">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="2"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        </div>
        <div>
            <span>Total Users</span>
            <strong><?= $totalUsers ?></strong>
        </div>
    </div>
    <div class="user-stat-card">
        <div class="user-stat-icon" style="background:#e8f2ee;color:#3b796c">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2"/></svg>
        </div>
        <div>
            <span>Students</span>
            <strong><?= $totalStudents ?></strong>
        </div>
    </div>
    <div class="user-stat-card">
        <div class="user-stat-icon" style="background:#fbf3e6;color:#b98334">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M12 15V3m0 12l-4-4m4 4l4-4M2 17l.621 2.485A2 2 0 0 0 4.561 21h14.878a2 2 0 0 0 1.94-1.515L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </div>
        <div>
            <span>Admins</span>
            <strong><?= $totalAdmins ?></strong>
        </div>
    </div>
    <div class="user-stat-card">
        <div class="user-stat-icon" style="background:#f0edf8;color:#7965a1">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/><polyline points="12,6 12,12 16,14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        </div>
        <div>
            <span>New This Week</span>
            <strong><?= $newThisWeek ?></strong>
        </div>
    </div>
    <div class="user-stat-card">
        <div class="user-stat-icon" style="background:#edf3f8;color:#2f6598">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="2"/><line x1="16" y1="2" x2="16" y2="6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><line x1="8" y1="2" x2="8" y2="6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><line x1="3" y1="10" x2="21" y2="10" stroke="currentColor" stroke-width="2"/></svg>
        </div>
        <div>
            <span>New This Month</span>
            <strong><?= $newThisMonth ?></strong>
        </div>
    </div>
</div>

<!-- ── Filter Bar ─────────────────────────────────────────────────── -->
<section class="admin-panel users-panel">
    <div class="users-toolbar">
        <div class="users-filter-tabs">
            <a href="?role=all<?= $search ? '&q='.urlencode($search) : '' ?>" class="filter-tab <?= $roleFilter==='all' ? 'active' : '' ?>">
                All <span class="tab-count"><?= $totalUsers ?></span>
            </a>
            <a href="?role=student<?= $search ? '&q='.urlencode($search) : '' ?>" class="filter-tab <?= $roleFilter==='student' ? 'active' : '' ?>">
                Students <span class="tab-count"><?= $totalStudents ?></span>
            </a>
            <a href="?role=admin<?= $search ? '&q='.urlencode($search) : '' ?>" class="filter-tab <?= $roleFilter==='admin' ? 'active' : '' ?>">
                Admins <span class="tab-count"><?= $totalAdmins ?></span>
            </a>
        </div>
        <form class="users-search" method="get">
            <input type="hidden" name="role" value="<?= e($roleFilter) ?>">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8" stroke="#8b98a6" stroke-width="2"/><path d="M21 21l-4.35-4.35" stroke="#8b98a6" stroke-width="2" stroke-linecap="round"/></svg>
            <input type="text" name="q" value="<?= e($search) ?>" placeholder="Search by name or email…">
        </form>
    </div>

    <!-- ── Users Table ────────────────────────────────────────────── -->
    <div class="admin-table-wrap">
        <table class="admin-table users-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Progress</th>
                    <th>Quizzes</th>
                    <th>Avg Score</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if(empty($users)): ?>
                <tr><td colspan="7" class="empty-table-cell">No users found matching your criteria.</td></tr>
            <?php else: ?>
                <?php foreach($users as $u): ?>
                <tr>
                    <td>
                        <div class="user-cell">
                            <span class="user-avatar user-avatar-sm" style="background:<?= $u['role']==='admin' ? '#fbf3e6' : '#edf3f8' ?>;color:<?= $u['role']==='admin' ? '#b98334' : '#2f6598' ?>">
                                <?= strtoupper(mb_substr($u['name'], 0, 1)) ?>
                            </span>
                            <div>
                                <strong><?= e($u['name']) ?></strong>
                                <small><?= e($u['email']) ?></small>
                            </div>
                        </div>
                    </td>
                    <td><span class="role-badge role-<?= e($u['role']) ?>"><?= e(ucfirst($u['role'])) ?></span></td>
                    <td>
                        <span class="progress-count"><?= (int)$u['progress_count'] ?> items</span>
                    </td>
                    <td><?= (int)$u['quiz_count'] ?></td>
                    <td>
                        <?php if($u['avg_score'] !== null): ?>
                        <span class="quiz-score-inline <?= $u['avg_score'] >= 70 ? 'score-good' : ($u['avg_score'] >= 50 ? 'score-ok' : 'score-low') ?>"><?= (int)$u['avg_score'] ?>%</span>
                        <?php else: ?>
                        <span class="no-data">—</span>
                        <?php endif; ?>
                    </td>
                    <td><span class="date-cell"><?= e(date('M j, Y', strtotime($u['created_at']))) ?></span></td>
                    <td>
                        <?php if((int)$u['id'] !== (int)current_user()['id']): ?>
                        <div class="action-buttons">
                            <form method="post" class="inline-form">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                                <input type="hidden" name="action" value="role">
                                <select name="role" aria-label="Role for <?= e($u['name']) ?>" class="role-select">
                                    <option value="student" <?= $u['role']==='student'?'selected':'' ?>>Student</option>
                                    <option value="admin" <?= $u['role']==='admin'?'selected':'' ?>>Admin</option>
                                </select>
                                <button class="btn-action btn-save" type="submit" title="Save role">
                                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            </form>
                            <form method="post" class="inline-form" onsubmit="return confirm('Permanently delete this account and all progress?')">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                                <button class="btn-action btn-delete" name="action" value="delete" title="Delete user">
                                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24"><polyline points="3,6 5,6 21,6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                </button>
                            </form>
                        </div>
                        <?php else: ?>
                        <span class="current-badge">You</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="table-footer">
        Showing <?= count($users) ?> user<?= count($users) !== 1 ? 's' : '' ?> (maximum 500)
    </div>
</section>

<?php require __DIR__.'/../includes/admin_footer.php'; ?>
