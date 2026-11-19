<?php
require_once __DIR__ . '/includes/functions.php';
if(is_logged_in()) redirect('pages/dashboard.php');
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  verify_csrf(); $email=trim(strtolower($_POST['email']??'')); $password=$_POST['password']??'';
  $stmt=$pdo->prepare('SELECT id,name,email,password_hash,role FROM users WHERE email=? LIMIT 1'); $stmt->execute([$email]); $user=$stmt->fetch();
  if($user && password_verify($password,$user['password_hash'])){session_regenerate_id(true); unset($user['password_hash']); $_SESSION['user']=$user; flash('success','Welcome back, '.$user['name'].'!'); redirect('pages/dashboard.php');}
  $error='The email address or password is incorrect.';
}
$page_title='Sign in'; require __DIR__.'/includes/header.php';
?>
<section class="auth-section"><div class="auth-aside"><div class="auth-aside-content"><span class="eyebrow eyebrow-light">SOO DHAWOOW</span><h1>Keep building<br>your <em>language.</em></h1><p>Pick up where you left off. Your lessons, practice and progress are waiting.</p><div class="auth-quote">“Af aqoon leh waa dad aqoon leh.”<small>Learning a language opens a world.</small></div></div></div><div class="auth-form-wrap"><form class="auth-form" method="post"><span class="eyebrow">WELCOME BACK</span><h2>Sign in to SomaliLingua</h2><p class="muted">Enter your details to continue learning.</p><?php if($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?><?= csrf_field() ?><label>Email address<input type="email" name="email" autocomplete="email" required value="<?= e($_POST['email']??'') ?>"></label><label>Password<input type="password" name="password" autocomplete="current-password" required></label><button class="btn btn-primary btn-full" type="submit">Sign in <span>→</span></button><p class="auth-switch">New to SomaliLingua? <a href="<?= url('register.php') ?>">Create an account</a></p></form></div></section>
<?php require __DIR__.'/includes/footer.php'; ?>
