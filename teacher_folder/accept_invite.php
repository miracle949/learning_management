<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accept Invitation — iLearn</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --green:      #00C950;
            --green-dark: #009e3e;
            --red:        #dc2626;
            --blue:       #1d4ed8;
            --bg:         #f0f4f8;
            --card:       #ffffff;
            --text:       #111827;
            --muted:      #6b7280;
            --border:     #e4e7eb;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        /* Subtle animated background blobs */
        body::before, body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: .18;
            pointer-events: none;
            z-index: 0;
            animation: float 8s ease-in-out infinite alternate;
        }

        body::before {
            width: 500px; height: 500px;
            background: var(--green);
            top: -120px; left: -120px;
        }

        body::after {
            width: 400px; height: 400px;
            background: #3b82f6;
            bottom: -100px; right: -100px;
            animation-delay: -4s;
        }

        @keyframes float {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(30px, 20px) scale(1.05); }
        }

        .page-wrap {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 460px;
            animation: cardIn .45s cubic-bezier(.34,1.56,.64,1) both;
        }

        @keyframes cardIn {
            from { transform: translateY(40px) scale(.96); opacity: 0; }
            to   { transform: translateY(0)    scale(1);   opacity: 1; }
        }

        .card {
            background: var(--card);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,.10), 0 4px 16px rgba(0,0,0,.06);
            overflow: hidden;
        }

        /* ── STATUS HEADER ── */
        .status-header {
            padding: 36px 32px 28px;
            text-align: center;
            position: relative;
        }

        .status-header.success { background: linear-gradient(135deg, #00C950 0%, #009e3e 100%); }
        .status-header.already { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }
        .status-header.error   { background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%); }

        .status-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: rgba(255,255,255,.2);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 34px;
            margin-bottom: 14px;
            animation: iconPop .5s .2s cubic-bezier(.34,1.56,.64,1) both;
        }

        @keyframes iconPop {
            from { transform: scale(0) rotate(-20deg); opacity: 0; }
            to   { transform: scale(1) rotate(0);      opacity: 1; }
        }

        .status-header h2 {
            font-size: 22px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 6px;
            letter-spacing: -.3px;
        }

        .status-header p {
            font-size: 13px;
            color: rgba(255,255,255,.8);
            line-height: 1.5;
        }

        /* ── CARD BODY ── */
        .card-body {
            padding: 28px 32px;
        }

        /* Class info card */
        .class-info-box {
            border-radius: 14px;
            padding: 18px 20px;
            margin-bottom: 24px;
            border: 1px solid;
        }

        .class-info-box.green  { background: #f0fdf4; border-color: #bbf7d0; }
        .class-info-box.blue   { background: #eff6ff; border-color: #bfdbfe; }
        .class-info-box.red    { background: #fef2f2; border-color: #fecaca; }

        .class-info-box .subject-name {
            font-size: 17px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .class-info-box.green .subject-name { color: #15803d; }
        .class-info-box.blue  .subject-name { color: #1e40af; }
        .class-info-box.red   .subject-name { color: #991b1b; }

        .class-meta-row {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: var(--muted);
            margin-top: 5px;
        }

        .class-meta-row strong { color: #374151; }

        .class-meta-row .dot {
            width: 3px; height: 3px;
            border-radius: 50%;
            background: #d1d5db;
            flex-shrink: 0;
        }

        /* Message text */
        .card-body .message-text {
            font-size: 13px;
            color: var(--muted);
            line-height: 1.65;
            margin-bottom: 20px;
            text-align: center;
        }

        /* ── FOOTER ── */
        .card-footer {
            padding: 0 32px 28px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 30px;
            border-radius: 50px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all .15s;
            letter-spacing: .1px;
        }

        .btn-primary {
            background: var(--green);
            color: #fff;
            box-shadow: 0 4px 16px rgba(0,201,80,.35);
        }

        .btn-primary:hover {
            background: var(--green-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,201,80,.4);
            color: #fff;
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
            transform: translateY(-1px);
        }

        /* ── BRAND STRIP ── */
        .brand-strip {
            text-align: center;
            padding: 14px 32px 18px;
            border-top: 1px solid var(--border);
        }

        .brand-strip span {
            font-size: 11px;
            font-weight: 600;
            color: #9ca3af;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .brand-logo {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 16px;
            font-weight: 800;
            color: var(--green);
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="page-wrap">
    <div class="card">

        <?php if ($result['success']): ?>

            <?php if (!empty($result['already'])): ?>
                <!-- ALREADY ENROLLED -->
                <div class="status-header already">
                    <div class="status-icon">ℹ️</div>
                    <h2>Already Enrolled</h2>
                    <p>You're already a member of this class.</p>
                </div>
                <div class="card-body">
                    <p class="message-text">
                        <?= htmlspecialchars($result['message']) ?>
                    </p>
                    <div class="class-info-box blue">
                        <?php if (!empty($result['subject_name'])): ?>
                            <div class="subject-name"><?= htmlspecialchars($result['subject_name']) ?></div>
                        <?php endif; ?>
                        <?php if (!empty($result['section_name'])): ?>
                            <div class="class-meta-row">
                                <span>Section: <strong><?= htmlspecialchars($result['section_name']) ?></strong></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($result['teacher_name'])): ?>
                            <div class="class-meta-row">
                                <span>Teacher: <strong><?= htmlspecialchars($result['teacher_name']) ?></strong></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            <?php else: ?>
                <!-- SUCCESS -->
                <div class="status-header success">
                    <div class="status-icon">✅</div>
                    <h2>Enrollment Confirmed!</h2>
                    <p>You've been successfully enrolled in the class.</p>
                </div>
                <div class="card-body">
                    <div class="class-info-box green">
                        <div class="subject-name"><?= htmlspecialchars($result['subject_name'] ?? '') ?></div>
                        <?php if (!empty($result['section_name'])): ?>
                            <div class="class-meta-row">
                                <span>Section: <strong><?= htmlspecialchars($result['section_name']) ?></strong></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($result['teacher_name'])): ?>
                            <div class="class-meta-row">
                                <span>Teacher: <strong><?= htmlspecialchars($result['teacher_name']) ?></strong></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <p class="message-text">
                        Welcome! You now have full access to this class —
                        log in to iLearn to start exploring your materials.
                    </p>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <!-- ERROR -->
            <div class="status-header error">
                <div class="status-icon">❌</div>
                <h2>Invitation Invalid</h2>
                <p>This invitation could not be processed.</p>
            </div>
            <div class="card-body">
                <div class="class-info-box red">
                    <div class="subject-name" style="font-size:14px;font-weight:600;">
                        <?= htmlspecialchars($result['message']) ?>
                    </div>
                </div>
                <p class="message-text">
                    This link may have expired or already been used.
                    Please contact your teacher to request a new invitation.
                </p>
            </div>
        <?php endif; ?>

        <div class="card-footer">
            <a href="/learning_management/public/?url=login" class="btn btn-primary">
                🎓 Go to iLearn
            </a>
        </div>

        <div class="brand-strip">
            <span>Powered by</span><br>
            <a href="/learning_management/public/?url=login" class="brand-logo" style="margin-top:4px;display:inline-flex;">
                📚 iLearn LMS
            </a>
        </div>

    </div>
</div>

</body>
</html>