<style>
    /* ── BANNER ── */
    .container-fluid .rightbar .module-title {
        width: 100%;
        border-radius: 10px;
        border: 1px solid #E2E8E5;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .container-fluid .rightbar .module-title .module-picture {
        background-image: url('../images/philosophy_picture.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        width: 100%;
        height: 180px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .container-fluid .rightbar .module-title .module-body {
        background-color: #ffffff;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
        padding: 1.5rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 2rem;
    }

    .container-fluid .rightbar .module-title .module-body h1 {
        font-size: 20px;
        text-transform: uppercase;
        font-family: "Titan", sans-serif;
        color: var(--green-dark);
        margin: 0 0 0.4rem;
    }

    .container-fluid .rightbar .module-title .module-body p {
        font-size: 14px;
        color: #555;
        line-height: 26px;
        margin: 0;
        max-width: 500px;
    }

    .module-browse-btn {
        text-decoration: none;
        background-color: var(--green);
        color: #ffffff;
        padding: 10px 24px;
        border-radius: 28px;
        font-size: 13.5px;
        font-weight: 700;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }

    .module-browse-btn:hover {
        opacity: .88;
        color: #fff;
    }

    /* ── MAIN LAYOUT ── */
    .modules-main {
        display: flex;
        gap: 2rem;
        align-items: flex-start;
        margin-top: 2rem;
    }

    /* ── LEARNING CATALOG (left) ── */
    .learning-catalog {
        width: 220px;
        min-width: 200px;
        flex-shrink: 0;
    }

    .learning-catalog h4 {
        font-size: 17px;
        font-weight: 800;
        color: #1a1a1a;
        margin: 0 0 1rem;
    }

    .learning-catalog .catalog-section-title {
        font-size: 13px;
        font-weight: 700;
        color: #555;
        margin: 0 0 .5rem;
    }

    .catalog-list {
        list-style: none;
        padding: 0;
        margin: 0 0 1rem;
    }

    .catalog-list li {
        font-size: 13.5px;
        color: #333;
        padding: 5px 0;
        border-bottom: 1px solid #f0f0f0;
        cursor: pointer;
        transition: color .15s;
    }

    .catalog-list li:hover {
        color: var(--green);
    }

    /* .catalog-list li.active {
        color: var(--green);
        font-weight: 700;
    } */

    .see-more-btn {
        background: none;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        padding: 6px 16px;
        font-size: 12.5px;
        font-weight: 700;
        color: #555;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: border-color .18s, color .18s;
        margin-top: .3rem;
    }

    .see-more-btn:hover {
        border-color: var(--green);
        color: var(--green);
    }

    /* ── MODULE CARDS GRID (right) ── */
    .learning-module {
        flex: 1;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.2rem;
    }

    .module-feed-card {
        background: #ffffff;
        border: 1px solid #E2E8E5;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        cursor: pointer;
        transition: box-shadow 0.2s, border-color 0.2s;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
    }

    .module-feed-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.10);
        border-color: var(--green);
        color: inherit;
        text-decoration: none;
    }

    .module-feed-card .card-img {
        width: 100%;
        height: 130px;
        background-image: url('../images/philosophy_picture.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-color: #e8f5ee;
    }

    .module-feed-card .card-body {
        padding: 1.2rem 1.4rem 1rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .module-feed-card .card-body h3 {
        font-size: 15px;
        /* font-weight: 800; */
        color: var(--green-dark);
        font-family: "Titan", sans-serif;
        margin: 0 0 .5rem;
        line-height: 1.4;
    }

    .module-feed-card .card-body p {
        font-size: 13px;
        color: #555;
        margin: 0 0 1.2rem;
        line-height: 1.5;
        flex: 1;
    }

    .start-now-btn {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 7px;
        background-color: var(--green);
        color: #ffffff;
        padding: 9px 20px;
        width: 100%;
        border-radius: 28px;
        font-size: 14px;
        font-weight: 600;
        /* font-weight: 700; */
        text-decoration: none;
        /* align-self: flex-start; */
        transition: opacity .18s;
        /* text-align: center; */
    }

    .start-now-btn:hover {
        opacity: .88;
        color: #fff;
    }

    .modules-empty {
        text-align: center;
        padding: 60px 20px;
        color: #aaa;
        grid-column: span 2;
    }

    .modules-empty i {
        font-size: 48px;
        display: block;
        margin-bottom: 14px;
    }
</style>

<?php
// ── VARIABLES FROM HomeController::modules() ──────────────
// DO NOT re-instantiate Students or re-query the DB here.
// These variables are already set by the controller:
//
//   $subject      = 'philosophy'  (slug from URL)
//   $subjectInfo  = subject row from DB
//   $modules      = all interactive_modules for this subject
//   $lessonCounts = lesson count per module id
?>

<!-- ── BANNER ── -->
<div class="module-title">
    <div class="module-picture"></div>
    <div class="module-body">
        <div>
            <h1><?= htmlspecialchars($subjectInfo['subject_name'] ?? 'Philosophy') ?></h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                tempor incididunt ut labore et</p>
        </div>
        <a href="/learning_management/public/?url=subjects&subject=<?= htmlspecialchars($subject) ?>"
            class="module-browse-btn">
            Browse <i class="fa fa-arrow-right"></i>
        </a>
    </div>
</div>

<!-- ── MAIN LAYOUT ── -->
<div class="modules-main">

    <!-- Learning Catalog (left) -->
    <div class="learning-catalog">
        <h4>Learning Catalog</h4>
        <p class="catalog-section-title">Modules</p>
        <ul class="catalog-list">
            <?php foreach ($modules as $i => $mod): ?>
                <li class="<?= $i === 0 ? 'active' : '' ?> <?= $i >= 5 ? 'catalog-extra' : '' ?>"
                    style="<?= $i >= 5 ? 'display:none;' : '' ?>">
                    <?= htmlspecialchars($mod['title']) ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php if (count($modules) > 5): ?>
            <button class="see-more-btn" onclick="toggleCatalog(this)">
                See more <i class="fa fa-chevron-down"></i>
            </button>
        <?php endif; ?>
    </div>

    <!-- Module Cards Grid (right) -->
    <div class="learning-module">
        <?php if (empty($modules)): ?>
            <div class="modules-empty">
                <i class="fa fa-book-open"></i>
                <p>No interactive modules available yet.</p>
            </div>
        <?php else: ?>
            <?php foreach ($modules as $mod):
                $detailUrl = "/learning_management/public/?url=subject_lessons&subject={$subject}&id={$mod['id']}";
                $count = $lessonCounts[$mod['id']] ?? 0;
                ?>
                <a href="<?= $detailUrl ?>" class="module-feed-card">
                    <div class="card-img"></div>
                    <div class="card-body">
                        <h3><?= htmlspecialchars($mod['title']) ?></h3>
                        <p><?= htmlspecialchars(
                            !empty($mod['description'])
                            ? mb_strimwidth($mod['description'], 0, 100, '...')
                            : 'Lorem ipsum dolor sit amet consectetur adipiscing elit, sed do eiusmod temp or incididunt ut labore et'
                        ) ?>
                        </p>
                        <span class="start-now-btn">
                            Start now <i class="fa fa-arrow-right"></i>
                        </span>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>

<script>
    function toggleCatalog(btn) {
        const extras = document.querySelectorAll('.catalog-extra');
        const isHidden = extras[0].style.display === 'none';
        extras.forEach(el => el.style.display = isHidden ? 'block' : 'none');
        btn.innerHTML = isHidden
            ? 'See less <i class="fa fa-chevron-up"></i>'
            : 'See more <i class="fa fa-chevron-down"></i>';
    }
</script>