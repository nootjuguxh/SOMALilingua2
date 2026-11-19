<?php
require_once __DIR__.'/../includes/functions.php';
require_admin();
$error='';
$edit=null;
$editId=(int)($_GET['edit']??0);
if($editId){$s=$pdo->prepare('SELECT * FROM quiz_questions WHERE id=?');$s->execute([$editId]);$edit=$s->fetch();}
if($_SERVER['REQUEST_METHOD']==='POST'){
    verify_csrf();
    try{
        $action=$_POST['action']??'save';
        $id=(int)($_POST['id']??0);
        if($action==='delete'){
            $s=$pdo->prepare('DELETE FROM quiz_questions WHERE id=?');$s->execute([$id]);
            flash('success','Quiz question deleted.');redirect('admin/questions.php');
        }
        $lesson=(int)($_POST['lesson_id']??0);
        $question=trim($_POST['question']??'');
        $a=trim($_POST['option_a']??'');$b=trim($_POST['option_b']??'');
        $c=trim($_POST['option_c']??'');$d=trim($_POST['option_d']??'');
        $correct=$_POST['correct_option']??'a';$explanation=trim($_POST['explanation']??'');
        if(!$lesson||$question===''||in_array('',[$a,$b,$c,$d],true)) throw new RuntimeException('Select a lesson and complete the question and all four answers.');
        if(!in_array($correct,['a','b','c','d'],true))$correct='a';
        if($id){
            $s=$pdo->prepare('UPDATE quiz_questions SET lesson_id=?,question=?,option_a=?,option_b=?,option_c=?,option_d=?,correct_option=?,explanation=? WHERE id=?');
            $s->execute([$lesson,$question,$a,$b,$c,$d,$correct,$explanation,$id]);
        }else{
            $s=$pdo->prepare('INSERT INTO quiz_questions(lesson_id,question,option_a,option_b,option_c,option_d,correct_option,explanation) VALUES(?,?,?,?,?,?,?,?)');
            $s->execute([$lesson,$question,$a,$b,$c,$d,$correct,$explanation]);
        }
        flash('success','Quiz question saved.');redirect('admin/questions.php');
    }catch(Throwable $e){$error=$e->getMessage();}
}
$lessons=$pdo->query('SELECT id,title FROM lessons ORDER BY sort_order,id')->fetchAll();
$questions=$pdo->query('SELECT q.id,q.question,l.title lesson_title FROM quiz_questions q JOIN lessons l ON l.id=q.lesson_id ORDER BY q.id DESC')->fetchAll();
$page_title='Manage quiz questions';require __DIR__.'/../includes/admin_header.php';
$v=$edit??$_POST;
?>
<div class="admin-heading"><div><span class="eyebrow">ASSESSMENT</span><h1>Manage quiz questions</h1><p>Create, edit and remove multiple-choice questions assigned to lessons.</p></div></div>
<div class="admin-two-col">
<section class="admin-panel">
<div class="panel-heading"><h2><?= $edit?'Edit question':'Add a question' ?></h2><?php if($edit): ?><a href="<?= url('admin/questions.php') ?>">Cancel</a><?php endif; ?></div>
<?php if($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="admin-form">
<?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)($v['id']??0) ?>">
<label>Lesson<select name="lesson_id" required><option value="">Choose a lesson</option><?php foreach($lessons as $l): ?><option value="<?= (int)$l['id'] ?>" <?= (int)($v['lesson_id']??0)===(int)$l['id']?'selected':'' ?>><?= e($l['title']) ?></option><?php endforeach; ?></select></label>
<label>Question<textarea name="question" rows="3" required><?= e($v['question']??'') ?></textarea></label>
<div class="form-row"><label>Option A<input name="option_a" required value="<?= e($v['option_a']??'') ?>"></label><label>Option B<input name="option_b" required value="<?= e($v['option_b']??'') ?>"></label></div>
<div class="form-row"><label>Option C<input name="option_c" required value="<?= e($v['option_c']??'') ?>"></label><label>Option D<input name="option_d" required value="<?= e($v['option_d']??'') ?>"></label></div>
<label>Correct answer<select name="correct_option"><?php foreach(['a','b','c','d'] as $letter): ?><option value="<?= $letter ?>" <?= ($v['correct_option']??'a')===$letter?'selected':'' ?>>Option <?= strtoupper($letter) ?></option><?php endforeach; ?></select></label>
<label>Explanation (optional)<textarea name="explanation" rows="2"><?= e($v['explanation']??'') ?></textarea></label>
<button class="btn btn-primary" type="submit" name="action" value="save">Save question</button>
</form>
</section>
<section class="admin-panel"><div class="panel-heading"><h2>Existing questions</h2><span><?= count($questions) ?> total</span></div>
<div class="admin-table-wrap"><table class="admin-table"><thead><tr><th>Question</th><th>Lesson</th><th>Action</th></tr></thead><tbody>
<?php foreach($questions as $q): ?><tr><td><?= e($q['question']) ?></td><td><?= e($q['lesson_title']) ?></td><td><a href="?edit=<?= (int)$q['id'] ?>">Edit</a><form method="post" class="inline-form" onsubmit="return confirm('Delete this question?')"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$q['id'] ?>"><button class="link-danger" name="action" value="delete">Delete</button></form></td></tr><?php endforeach; ?>
</tbody></table></div></section>
</div>
<?php require __DIR__.'/../includes/admin_footer.php'; ?>
