<?php
session_start();

// Generate math CAPTCHA if not set
if (!isset($_SESSION['captcha_a']) || !isset($_SESSION['captcha_b'])) {
    $_SESSION['captcha_a'] = rand(1, 99);
    $_SESSION['captcha_b'] = rand(1, 99);
}

$error = "";

// Hashed password for 'admin123'
$hashedPassword = '$2y$10$rrq1JRYfHuCtevEriXfOjuE8udJmMxoRkJFUzS8/mPEpoURKG5z0W';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? "";
    $password = $_POST['password'] ?? "";
    $captcha_answer = $_POST['captcha_answer'] ?? "";

    $correct_answer = $_SESSION['captcha_a'] + $_SESSION['captcha_b'];

    if ((int)$captcha_answer !== $correct_answer) {
        $error = "Incorrect answer to the math question.";
    } elseif ($username === 'admin' && password_verify($password, $hashedPassword)) {
        $_SESSION['admin'] = true;
        unset($_SESSION['captcha_a'], $_SESSION['captcha_b']);
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }

    // Refresh captcha after login attempt
    $_SESSION['captcha_a'] = rand(1, 50);
    $_SESSION['captcha_b'] = rand(1, 20);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Lapasan Profiling DB - Sign In</title>
<style>
:root{
  --primary:#4cafef;
  --primary-dark:#1f6ed4;
  --text:#2c3e50;
  --muted:#7f8c8d;
  --bg:#f5f7fa;
  --card-bg:#ffffff;
  --danger:#e74c3c;
  --radius:12px;
}

*{box-sizing:border-box; margin:0; padding:0; font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;}
body{background:var(--bg); color:var(--text); min-height:100vh; display:flex; align-items:center; justify-content:center; padding:10px;}

/* Container */
.container{display:flex; width:100%; max-width:900px; border-radius:var(--radius); overflow:hidden; box-shadow:0 6px 20px rgba(0,0,0,0.1); background:#fff; flex-wrap:wrap;}

/* Left Panel */
.left-panel{
  flex:1; background:#2c3e50; color:#ecf0f1; padding:40px 20px; display:flex; flex-direction:column; justify-content:center;
}
.left-panel h1{font-size:2.2rem; margin-bottom:15px; letter-spacing:1px;}
.left-panel p, .left-panel ul{margin-bottom:12px; font-size:1rem;}
.left-panel ul{list-style:disc; margin-left:20px;}
.left-panel ul li{margin-bottom:6px;}

/* Right Panel */
.right-panel{
  flex:1; background:#f9fafc; padding:40px 20px; display:flex; flex-direction:column; justify-content:center;
}
.card{background:var(--card-bg); padding:20px; border-radius:var(--radius); box-shadow:0 4px 15px rgba(0,0,0,0.05);}
.card h1.title{font-size:1.8rem; margin-bottom:10px; color:var(--primary);}
.card .title-underline{width:60px; height:3px; background:var(--primary); margin-bottom:15px;}

.group{margin-bottom:15px;}
.label{display:flex; gap:6px; align-items:center; font-weight:600; margin-bottom:6px;}
.asterisk{color:#e74c3c;}
.input-wrap{position:relative; border-bottom:1.5px solid #dfe3e8;}
.input{width:100%; border:0; outline:0; padding:10px 35px 10px 0; font-size:14px; background:transparent; color:#2c3e50;}
.input::placeholder{color:var(--muted);}
.input-wrap:focus-within{border-color:var(--primary);}
.icon-right{position:absolute; inset-inline-end:0; inset-block:50%; transform:translateY(-50%); width:22px; height:22px; display:grid; place-items:center; color:var(--muted);}
.btn-eye{all:unset; cursor:pointer;}
.btn-eye:hover{color:var(--primary);}

.captcha{display:flex; flex-wrap:wrap; align-items:center; gap:8px; margin:15px 0 20px;}
.cap-input{width:60px; text-align:center; font-weight:600; border:1px solid #dfe3e8; background:#f9fafc; border-radius:8px; padding:8px; font-size:14px; color:#2c3e50;}
.cap-symbol{font-weight:700; font-size:16px; color:#7f8c8d; padding:0 4px;}
.cap-answer{background:#fff;}
.cap-refresh{display:inline-grid; place-items:center; width:34px; height:34px; border:1px solid #dfe3e8; background:#fff; border-radius:8px; cursor:pointer; color:var(--primary);}
.cap-refresh:hover{border-color:var(--primary-dark);}

.btn{width:100%; padding:12px; border:0; border-radius:var(--radius); background:var(--primary); color:#fff; font-weight:600; cursor:pointer; font-size:16px; transition:0.2s;}
.btn:hover{background:var(--primary-dark);}
.forgot{text-align:center; margin-top:10px;}
.forgot a{color:var(--primary); text-decoration:none;}
.forgot a:hover{text-decoration:underline;}
.alert{background:var(--danger); color:#fff; padding:10px 12px; border-radius:8px; margin-bottom:14px; text-align:center;}

/* Responsive */
@media(max-width:992px){
  .container{flex-direction:column;}
  .left-panel, .right-panel{flex:unset; width:100%; padding:25px;}
  .left-panel h1{font-size:2rem;}
  .card h1.title{font-size:1.6rem;}
}
@media(max-width:576px){
  .left-panel h1{font-size:1.6rem;}
  .left-panel p, .left-panel ul{font-size:0.9rem;}
  .card h1.title{font-size:1.4rem;}
  .cap-input{width:50px; padding:6px; font-size:13px;}
}
</style>
</head>
<body>
<div class="container">
  <!-- Left Panel -->
  <div class="left-panel">
    <h1>Lapasan Profiling DB</h1>
    <p>Welcome to the Lapasan Profiling Database. Key features include:</p>
    <ul>
      <li>Efficient voter profile management</li>
      <li>Detailed personal information tracking</li>
      <li>Reports & analytics generation</li>
      <li>Secure login and access control</li>
    </ul>
    <p>Keep your credentials secure and contact the administrator for support.</p>
  </div>

  <!-- Right Panel -->
  <div class="right-panel">
    <main class="card">
      <h1 class="title">Sign In</h1>
      <div class="title-underline" aria-hidden="true"></div>

      <?php if ($error): ?>
        <div class="alert"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="post" autocomplete="off">
        <div class="group">
          <label class="label" for="username"><span class="asterisk">*</span>Username</label>
          <div class="input-wrap">
            <input id="username" name="username" class="input" type="text" placeholder="Enter Username" required />
            <span class="icon-right">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Zm0 2c-5 0-9 2.5-9 5v1h18v-1c0-2.5-4-5-9-5Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </span>
          </div>
        </div>

        <div class="group">
          <label class="label" for="password"><span class="asterisk">*</span>Password</label>
          <div class="input-wrap">
            <input id="password" name="password" class="input" type="password" placeholder="Enter Password" required />
            <span class="icon-right">
              <button type="button" id="togglePwd" class="btn-eye">
                <svg id="eyeIcon" width="22" height="22" viewBox="0 0 24 24" fill="none">
                  <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                  <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.7"/>
                </svg>
              </button>
            </span>
          </div>
        </div>

        <!-- CAPTCHA -->
        <div class="captcha">
          <input id="capA" class="cap-input" type="text" value="<?= $_SESSION['captcha_a'] ?>" readonly />
          <span class="cap-symbol">+</span>
          <input id="capB" class="cap-input" type="text" value="<?= $_SESSION['captcha_b'] ?>" readonly />
          <span class="cap-symbol">=</span>
          <input id="capAns" name="captcha_answer" class="cap-input cap-answer" type="text" inputmode="numeric" required />
          <button type="button" id="refreshCaptcha" class="cap-refresh" title="Refresh captcha">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
              <path d="M20 12a8 8 0 1 1-2.34-5.66M20 4v6h-6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
        </div>

        <button class="btn" type="submit">
          Sign In
        </button>
        <p class="forgot"><a href="#">Forgot Password</a></p>
      </form>
    </main>
  </div>
</div>

<script>
  document.getElementById('togglePwd').addEventListener('click', () => {
    const pwd = document.getElementById('password');
    const eye = document.getElementById('eyeIcon');
    const isHidden = pwd.type === 'password';
    pwd.type = isHidden ? 'text' : 'password';
    eye.innerHTML = isHidden
      ? '<path d="M2 12s3.5-7 10-7c2.6 0 4.8.9 6.5 2.2M22 12s-3.5 7-10 7c-2.6 0-4.8-.9-6.5-2.2M3 3l18 18" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>'
      : '<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.7"/>';
  });

  document.getElementById('refreshCaptcha').addEventListener('click', () => {
    document.getElementById('capA').value = Math.floor(Math.random() * 50) + 1;
    document.getElementById('capB').value = Math.floor(Math.random() * 20) + 1;
    document.getElementById('capAns').value = "";
  });
</script>
</body>
</html>
