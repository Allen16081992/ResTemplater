<?php 
  header('Cache-Control: private, no-cache, must-revalidate'); // per-user, don’t cache
  header('Pragma: no-cache');   // legacy HTTP/1.0
  header('Expires: 0');         // expire immediately
  
  // Essential PHP files
  require_once "./config/session_manager.conf.php"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="PaperWitch export page for resume.">
  <!-- Favicon -->
  <?php include_once "./views/head_favicon.html" ?>
  <!-- CSS -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Unna:wght@400;700&family=Inter:wght@400;600;800&display=swap">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">
  <link rel="stylesheet" href="assets/css/paperwitch.css">
  <title>PaperWitch — Export Your Resume</title>

  <style>
    :root {
      --accent: #8b5cf6;
      --accent-2: #4f46e5;
      --font-title: Unna, Georgia, serif;
    }

    .pw-export-page {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
      color: #e8e8ef;
    }

    .pw-export-shell {
      width: min(1080px, 100%);
      margin-inline: auto;
      background: rgba(15,17,24,.9);
      border-radius: 24px;
      border: 1px solid rgba(255,255,255,.08);
      box-shadow:
        0 18px 60px rgba(0,0,0,.65),
        inset 0 1px 0 rgba(255,255,255,.06);
      padding: clamp(1.75rem, 3vw, 2.5rem);
      backdrop-filter: blur(10px);
    }

    .pw-export-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .pw-export-title-block h1 {
      font-family: var(--font-title);
      font-size: clamp(1.9rem, 3vw, 2.4rem);
      margin: 0 0 .25rem;
    }

    .pw-export-title-block p {
      margin: 0;
      color: var(--muted);
      max-width: 40rem;
    }

    .pw-export-meta {
      text-align: right;
      font-size: .9rem;
      color: var(--muted);
    }

    .pw-export-meta span {
      display: inline-flex;
      align-items: center;
      gap: .3rem;
      padding: .25rem .55rem;
      border-radius: 999px;
      background: rgba(22,163,74,.08);
      border: 1px solid rgba(22,163,74,.35);
      color: #bbf7d0;
      font-weight: 600;
    }

    .pw-export-meta small {
      display: block;
      margin-top: .35rem;
    }

    .pw-export-layout {
      display: grid;
      grid-template-columns: minmax(0, 2.1fr) minmax(0, 1.35fr);
      gap: 1.75rem;
    }

    @media (max-width: 835px) {
      .pw-export-layout {
        grid-template-columns: minmax(0, 1fr);
      }
      .pw-export-header {
        flex-direction: column;
        align-items: flex-start;
        text-align: left;
      }
      .pw-export-meta {
        text-align: left;
      }
    }

    /* Option grid */
    .pw-export-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
      gap: 1.25rem;
    }

    .pw-export-card {
      position: relative;
      padding: 1.2rem 1.1rem 1.15rem;
      border-radius: 18px;
      background: radial-gradient(circle at top left, rgba(139,92,246,.16), rgba(15,17,24,1) 55%);
      border: 1px solid rgba(255,255,255,.09);
      box-shadow: 0 12px 32px rgba(0,0,0,.55);
      display: flex;
      flex-direction: column;
      gap: .6rem;
    }

    .pw-export-card--primary {
      border-color: rgba(139,92,246,.85);
      box-shadow:
        0 0 0 1px rgba(139,92,246,.35),
        0 16px 42px rgba(139,92,246,.45);
    }

    .pw-export-pill {
      position: absolute;
      top: .8rem;
      right: .8rem;
      font-size: .7rem;
      text-transform: uppercase;
      letter-spacing: .08em;
      padding: .25rem .55rem;
      border-radius: 999px;
      background: rgba(24,24,27,.9);
      border: 1px solid rgba(244,244,255,.2);
      color: #e5e5ff;
    }

    .pw-export-icon {
      width: 34px;
      height: 34px;
      border-radius: 12px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: rgba(17,24,39,.9);
      box-shadow: 0 8px 24px rgba(0,0,0,.5);
      font-size: 1.2rem;
      margin-bottom: .25rem;
    }

    .pw-export-card h2 {
      margin: 0;
      font-size: 1.05rem;
    }

    .pw-export-format {
      font-size: .8rem;
      text-transform: uppercase;
      letter-spacing: .12em;
      color: var(--muted);
    }

    .pw-export-desc {
      margin: .2rem 0 .4rem;
      font-size: .9rem;
      color: #d4d4e6;
    }

    .pw-export-list {
      list-style: none;
      padding: 0;
      margin: 0 0 .6rem;
      font-size: .82rem;
      color: var(--muted);
    }

    .pw-export-list li {
      display: flex;
      align-items: center;
      gap: .4rem;
      margin-bottom: .3rem;
    }

    .pw-export-list li::before {
      content: "•";
      color: #a5b4fc;
      font-size: 1rem;
    }

    .pw-export-actions {
      margin-top: auto;
      display: flex;
      align-items: center;
      gap: .6rem;
    }

    .pw-btn {
      border-radius: 999px;
      border: none;
      font-family: inherit;
      font-weight: 600;
      cursor: pointer;
      padding: .55rem 1.1rem;
      font-size: .88rem;
      display: inline-flex;
      align-items: center;
      gap: .4rem;
      transition: transform .12s ease, box-shadow .15s ease, filter .2s ease, background .2s ease;
      text-decoration: none;
    }

    .pw-btn-primary {
      background: linear-gradient(135deg, var(--accent), var(--accent-2));
      color: #fff;
      box-shadow: 0 10px 28px rgba(129,140,248,.55);
    }

    .pw-btn-primary:hover {
      transform: translateY(-1px);
      filter: brightness(1.05);
      box-shadow: 0 14px 34px rgba(129,140,248,.7);
    }

    .pw-btn-ghost {
      background: rgba(15,23,42,.8);
      color: #e4e4ff;
      border: 1px solid rgba(148,163,184,.4);
    }

    .pw-btn-ghost:hover {
      background: rgba(15,23,42,1);
      box-shadow: 0 8px 22px rgba(15,23,42,.8);
    }

    .pw-btn span.icon {
      font-size: 1.05rem;
      line-height: 1;
    }

    /* Right-side info panel */
    .pw-export-side {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .pw-export-preview {
      padding: 1rem 1.1rem;
      border-radius: 18px;
      background: radial-gradient(circle at top, rgba(15,23,42,.9), rgba(15,23,42,.98));
      border: 1px solid rgba(148,163,184,.35);
      box-shadow: 0 10px 30px rgba(0,0,0,.6);
      font-size: .9rem;
    }

    .pw-export-preview-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: .4rem;
    }

    .pw-export-preview-header strong {
      font-size: .95rem;
    }

    .pw-export-preview-file {
      font-size: .82rem;
      color: var(--muted);
    }

    .pw-export-preview-body {
      margin-top: .4rem;
      padding: .7rem;
      border-radius: 12px;
      background: rgba(15,23,42,1);
      border: 1px solid rgba(30,64,175,.6);
      font-family: system-ui, -apple-system, "SF Mono", ui-monospace, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
      font-size: .8rem;
      line-height: 1.4;
      color: #e5e7eb;
      max-height: 210px;
      overflow: auto;
    }

    .pw-export-note {
      font-size: .8rem;
      color: var(--muted);
      padding: .75rem .9rem;
      border-radius: 14px;
      background: rgba(24,24,27,.9);
      border: 1px solid rgba(63,63,70,.8);
    }

    .pw-export-note strong {
      color: #e5e5ff;
    }

    @media (max-width: 640px) {
      .pw-export-shell {
        padding: 1.25rem 1rem 1.5rem;
        border-radius: 18px;
      }
      .pw-export-header {
        margin-bottom: 1.4rem;
      }
      .pw-export-grid {
        grid-template-columns: minmax(0, 1fr);
      }
    }
    @media (max-width: 350px) {
      .pw-export-page {
        padding: 0;
      }
    }
  </style>
</head>
<body>
  <header> 
    <a href="<?= !isset($_SESSION['session_data']['user_id']) ? 'index.php' : 'client.php' ?>" id="logo"><img src="assets/images/witch_logo2.png" alt="Brand logo"></a>
  </header>
  <div class="pw-export-page">
    <div class="pw-export-shell">
      <header class="pw-export-header">
        <nav aria-label="main navigation"><a href="<?= !isset($_SESSION['session_data']['user_id']) ? 'index.php' : 'client.php' ?>">Back</a></nav>
        <div class="pw-export-title-block">
          <h1>Export your resume</h1>
          <p>
            Choose how you want your scroll to leave the cauldron.
            Pick a format that fits the application portal, recruiter, or teacher in front of you.
          </p>
        </div>
        <div class="pw-export-meta">
          <span>
            <span>✨</span>
            <span>Saved</span>
          </span>
          <small>“Neo-Gothic Internship Resume – v3”</small>
        </div>
      </header>

      <div class="pw-export-layout">
        <!-- LEFT: Export options -->
        <section class="pw-export-grid" aria-label="Export options">
          <!-- Standard PDF -->
          <article class="pw-export-card pw-export-card--primary">
            <div class="pw-export-pill">Recommended</div>
            <div class="pw-export-icon" aria-hidden="true">📜</div>
            <span class="pw-export-format">PDF • Print-ready</span>
            <h2>Download as PDF</h2>
            <p class="pw-export-desc">
              The classic enchanted scroll. Perfect for emailing, uploading, and printing without layout breaking.
            </p>
            <ul class="pw-export-list">
              <li>Locked layout & typography</li>
              <li>Great for sending to recruiters</li>
            </ul>
            <div class="pw-export-actions">
              <!-- Hook this up to your export endpoint -->
              <form action="#" method="post">
                <input type="hidden" name="pdf" value="">
                <button class="pw-btn pw-btn-primary" type="button" data-export-format="pdf">
                  <span class="icon">⬇️</span>
                  <span>Download</span>
                </button>
              </form>
              <!-- <button class="pw-btn pw-btn-ghost" type="button">
                <span class="icon">👁️</span>
                <span>Preview</span>
              </button> -->
            </div>
          </article>

          <!-- DOCX / Editable -->
          <article class="pw-export-card">
            <div class="pw-export-icon" aria-hidden="true">📝</div>
            <span class="pw-export-format">DOCX • Editable</span>
            <h2>Download as Word (.docx)</h2>
            <p class="pw-export-desc">
              A flexible version you can tweak in Word, LibreOffice, or Google Docs for last-minute changes.
            </p>
            <ul class="pw-export-list">
              <li>Best for school portals or HR templates</li>
              <li>Lets you adjust text after export</li>
            </ul>
            <div class="pw-export-actions">
              <form action="#" method="post">
                <input type="hidden" name="docx" value="">
                <button class="pw-btn pw-btn-primary" type="button" data-export-format="docx">
                  <span class="icon">⬇️</span>
                  <span>Download</span>
                </button>
              </form>
            </div>
          </article>

          <!-- JPG / Image -->
          <article class="pw-export-card">
            <div class="pw-export-icon" aria-hidden="true">📷</div>
            <span class="pw-export-format">JPG • Image</span>
            <h2>Download as JPEG (.jpg)</h2>
            <p class="pw-export-desc">
              A flat image of your scroll. Useful for portfolios, LinkedIn posts, or sending as a quick visual.
            </p>
            <ul class="pw-export-list">
              <li>Great for visual previews</li>
              <li>Easy to share in chats or socials</li>
            </ul>
            <div class="pw-export-actions">
              <form action="#" method="post">
                <input type="hidden" name="jpg" value="">
                <button class="pw-btn pw-btn-primary" type="button" data-export-format="jpg">
                  <span class="icon">⬇️</span>
                  <span>Download</span>
                </button>
              </form>
            </div>
          </article>

          <!-- Minimal / ATS-friendly -->
          <article class="pw-export-card">
            <div class="pw-export-icon" aria-hidden="true">📝</div>
            <span class="pw-export-format">TXT • ATS-Friendly</span>
            <h2>Rich Text Format (.rtf)</h2>
            <p class="pw-export-desc">
              Stripped of visuals, packed with content. Built to survive strict ATS scanners and ugly portals.
            </p>
            <ul class="pw-export-list">
              <li>Easy copy-paste into online forms</li>
              <li>Maximum parser compatibility</li>
            </ul>
            <div class="pw-export-actions">
              <form action="#" method="post">
                <input type="hidden" name="rtf" value="">
                <button class="pw-btn pw-btn-primary" type="button" data-export-format="rtf">
                  <span class="icon">⬇️</span>
                  <span>Download</span>
                </button>
              </form>
            </div>
          </article>
        </section>

        <!-- RIGHT: Context / preview / notes -->
        <aside class="pw-export-side" aria-label="Export details">
          <section class="pw-export-preview">
            <div class="pw-export-preview-header">
              <strong>Current selection</strong>
              <span class="pw-export-preview-file">crystal-clear-resume.pdf</span>
            </div>
            <div class="pw-export-preview-body">
              {  
                "name": "Allen Pieter",  
                "target_role": "Internship – Junior Dev / IT",  
                "templates": [  
                  "PaperWitch • Obsidian Scroll",  
                  "PaperWitch • Moonlit Intern"  
                ],  
                "highlights": [  
                  "Tailored to 1 specific vacancy",  
                  "Readable in under 30 seconds",  
                  "Keywords tuned for ATS portals"  
                ]  
              }
            </div>
          </section>

          <section class="pw-export-note">
            <strong>Small ritual tip:</strong>
            Even without experience, a recruiter or manager, may notice the effort in your paper.
            Say you made it with Word or LibreOffice, thought about spacing, alignment and structure as well.
            They may think <em>'No experience, but handy with documents. If this person can do that with Confluence pages, I'll make an exception.'</em>
          </section>
        </aside>
      </div>
    </div>
  </div>
</body>
</html>