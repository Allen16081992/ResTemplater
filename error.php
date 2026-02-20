<?php 
  session_start(); 
  $code = $_SESSION['error'] ?? 500;
  unset($_SESSION['error']);

  switch ($code) {
    case 405:
      $msg = 'Invalid request method.';
      break;
    case 403:
      $msg = 'Access denied.';
      break;
    default:
      $msg = 'It looks like the page you were looking for got affected by free will.<br> Quick! Take a broomstick and go before they see you!';
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>PaperWitch – Error</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Unna:wght@400;700&family=Inter:wght@400;600;800&display=swap">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">
  <style>
    :root {
      --accent: #8b5cf6;
      --accent-2: #4f46e5;
      --bg: #0a0a0f;
      --muted: #a7a7b3;
    }
    html, body {
      margin: 0;
      height: 100%;
      color: #e8e8ef;
      font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
    }
    main {
      width: min(800px, 94vw);
      background: rgba(15,17,24,.85);
      border: 1px solid rgba(255,255,255,.08);
      border-radius: 24px;
      box-shadow: 0 10px 36px rgba(0,0,0,.45), inset 0 1px 0 rgba(255,255,255,.06);
      backdrop-filter: blur(8px);
      overflow: hidden;
    }
    .error-gif {
      width: 100%;
      height: 260px;
      background: #11131a;
      background-image: url('assets/images/landing_witch.gif');
      background-size: cover;
      background-position: center;
    }
    .content-area {
      padding: clamp(24px, 4vw, 48px);
    }
    h1 {
      font-family: Unna, Georgia, serif;
      font-size: clamp(2rem, 4vw, 3rem);
      line-height: 1.1;
    }
    p.subtitle {
      color: var(--muted);
      margin-top: .75rem;
      margin-bottom: 1.75rem;
    }
    .btn-home {
      background: linear-gradient(135deg, var(--accent), var(--accent-2));
      color: #fff;
      border: none;
      border-radius: 12px;
      padding: .8rem 1.5rem;
      font-weight: 600;
      box-shadow: 0 10px 26px rgba(139,92,246,.35);
      transition: transform .15s ease, filter .2s ease;
    }
    .btn-home:hover {
      filter: brightness(1.07);
      transform: translateY(-1px);
      box-shadow: 0 14px 32px rgba(139,92,246,.45);
    }
    footer {
      margin-top: 2rem;
      color: var(--muted);
      font-size: .9rem;
    }
  </style>
</head>
<body>
  <main>
    <div class="error-gif"></div>
    <div class="content-area">
      <h1>Ohh No!</h1>
      <p class="subtitle"><?= $msg ?></p>
      <?php if (!isset($_SESSION['session_data']['user_id'])) { ?>
        <a href="index.php" class="btn-home">Return Home</a>
      <?php } else { ?>
        <a href="client.php" class="btn-home">Return Home</a> 
      <?php } ?>
      <footer>© 2025 PaperWitch</footer>
    </div>
  </main>
</body>
</html>