<style>
    /* ── MODULE PICTURE ── */
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
        padding: 1.8rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 2rem;
    }

    .container-fluid .rightbar .module-title .module-body h1 {
        font-size: 22px;
        text-transform: uppercase;
        font-family: "Titan", sans-serif;
        color: var(--green-dark);
        margin: 0 0 0.7rem;
    }

    .container-fluid .rightbar .module-title .module-body p {
        font-size: 14px;
        color: #1A1A1A;
        line-height: 26px;
        margin: 0;
    }

    /* ── MODULE CARDS ── */
    .modules-feed {
        display: flex;
        flex-direction: column;
        gap: 1rem;
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
        display: block;
    }

    .module-feed-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.10);
        border-color: var(--green);
        color: inherit;
        text-decoration: none;
    }

    .module-feed-card .card-top {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1.2rem 1.5rem 1rem;
    }

    .module-feed-card .card-icon {
        width: 46px;
        height: 46px;
        min-width: 46px;
        border-radius: 50%;
        background: #e8f5ee;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--green);
        font-size: 18px;
    }

    .module-feed-card .card-icon.assignment {
        background: #fff7ed;
        color: #d97706;
    }

    .module-feed-card .card-icon.announcement {
        background: #f0ebff;
        color: #7c3aed;
    }

    .module-feed-card .card-info {
        flex: 1;
    }

    .module-feed-card .card-info .card-label {
        font-size: 12px;
        color: #aaa;
        font-weight: 600;
        display: block;
        margin-bottom: 4px;
    }

    .module-feed-card .card-info h3 {
        font-size: 14.5px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0 0 4px;
        line-height: 1.4;
    }

    .module-feed-card .card-info p {
        font-size: 13px;
        color: #555;
        margin: 0;
        line-height: 1.4;
    }

    .module-feed-card .card-footer {
        padding: 0.5rem 1.5rem 0.8rem;
        border-top: 1px solid #f0f0f0;
    }

    .module-feed-card .card-footer p {
        font-size: 12.5px;
        color: #aaa;
        margin: 0;
    }

    .modules-empty {
        text-align: center;
        padding: 60px 20px;
        color: #aaa;
    }

    .modules-empty i {
        font-size: 48px;
        display: block;
        margin-bottom: 14px;
    }
</style>

<!-- ── SUBJECT BANNER ── -->
<div class="module-title">
    <div class="module-picture"></div>
    <div class="module-body">
        <div>
            <h1>Understanding Culture Society and Politics</h1>
            <p>Browse all modules, assignments, and announcements for this subject.</p>
        </div>
        <a href="/learning_management/public/?url=module_all"
            style="text-decoration:none; color:var(--green); font-size:13px; font-weight:700; white-space:nowrap;">
            <i class="fa fa-arrow-left"></i> Back to modules
        </a>
    </div>
</div>

<!-- ── FEED ── -->
<div class="modules-feed">

    <?php
    // $feedItems is set in modules.php before this file loads
    // $subject  is the slug from the URL e.g. 'philosophy'
    
    $urlMap = [
        'module' => 'module_view',
        'assignment' => 'assignment_view',
        'announcement' => 'announcement_view',
    ];
    $labelMap = [
        'module' => 'New Material',
        'assignment' => 'New Assignment',
        'announcement' => 'Announcement',
    ];
    $iconMap = [
        'module' => ['class' => 'fa-layer-group', 'bg' => ''],
        'assignment' => ['class' => 'fa-file-alt', 'bg' => 'assignment'],
        'announcement' => ['class' => 'fa-bullhorn', 'bg' => 'announcement'],
    ];

    if (!empty($feedItems)):
        foreach ($feedItems as $item):
            $pageUrl = "/learning_management/public/?url={$urlMap[$item['type']]}&subject={$subject}&id={$item['id']}";
            $label = $labelMap[$item['type']];
            $icon = $iconMap[$item['type']];
            $date = date('M j', strtotime($item['date']));
            $subtext = mb_strimwidth(strip_tags($item['subtext']), 0, 120, '...');
            ?>

            <a href="<?= $pageUrl ?>" class="module-feed-card">
                <div class="card-top">
                    <div class="card-icon <?= $icon['bg'] ?>">
                        <i class="fa <?= $icon['class'] ?>"></i>
                    </div>
                    <div class="card-info">
                        <span class="card-label"><?= htmlspecialchars($label) ?></span>
                        <h3><?= htmlspecialchars($item['heading']) ?></h3>
                        <p><?= htmlspecialchars($subtext) ?></p>
                    </div>
                </div>
                <div class="card-footer">
                    <p>Date Received: <?= $date ?></p>
                </div>
            </a>

            <?php
        endforeach;
    else:
        ?>
        <div class="modules-empty">
            <i class="fa fa-folder-open"></i>
            <p>No modules posted yet for this subject.</p>
        </div>
    <?php endif; ?>

</div>