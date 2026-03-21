<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Module View</title>
    <link rel="stylesheet" href="../css_folder/components.css">
    <link rel="stylesheet" href="../css_folder/subjects.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
    <style>
        .mv-back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 700;
            color: var(--green);
            text-decoration: none;
            margin-bottom: 1.2rem;
        }

        .mv-back-link:hover {
            text-decoration: underline;
        }

        .mv-header-icon {
            width: 52px;
            height: 52px;
            min-width: 52px;
            border-radius: 50%;
            background: #e8f5ee;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--green);
            font-size: 22px;
        }

        .mv-header-info small {
            font-size: 12px;
            color: #aaa;
            font-weight: 600;
            display: block;
            margin-bottom: 4px;
        }

        .mv-header-info h2 {
            font-size: 20px;
            font-weight: 800;
            color: #1a1a1a;
            margin: 0 0 4px;
        }

        .mv-header-info p {
            font-size: 13.5px;
            color: #555;
            margin: 0 0 2px;
        }

        .mv-date {
            font-size: 12px;
            color: #aaa;
            margin-top: 4px;
            display: block;
        }

        .mv-attachments-card h5 {
            font-size: 14px;
            font-weight: 800;
            color: #333;
            margin-bottom: 1.2rem;
        }


        .mv-att-card:hover .mv-att-icon {
            opacity: .85;
            transform: translateY(-2px);
        }

        .mv-att-icon {
            width: 140px;
            height: 110px;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 32px;
            transition: opacity .18s, transform .18s;
        }

        .mv-att-icon.pdf {
            background: #b0b0b0;
            color: #fff;
        }

        .mv-att-icon.image {
            background: #e8f5ee;
            color: var(--green);
        }

        .mv-att-icon.video {
            background: #1a1a2e;
            color: #fff;
        }

        .mv-att-icon.other {
            background: #f5f6fa;
            color: #555;
        }

        .mv-att-icon span {
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .mv-att-label {
            font-size: 12px;
            color: #555;
            text-align: center;
        }

        .mv-att-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .mv-att-badge.pdf {
            background: #fee2e2;
            color: #dc2626;
        }

        .mv-att-badge.image {
            background: #dcfce7;
            color: #16a34a;
        }

        .mv-att-badge.video {
            background: #dbeafe;
            color: #2563eb;
        }

        .mv-viewer-wrap {
            display: none;
            background: #fff;
            border: 1px solid #E2E8E5;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
            margin-bottom: 1.4rem;
        }

        .mv-viewer-wrap.open {
            display: block;
            animation: fadeIn .2s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(6px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .mv-viewer-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .8rem 1.2rem;
            background: #f8f9fa;
            border-bottom: 1px solid #e5e7eb;
        }

        .mv-viewer-toolbar span {
            font-size: 13px;
            font-weight: 700;
            color: #333;
        }

        .mv-viewer-toolbar button {
            background: none;
            border: none;
            color: #888;
            font-size: 18px;
            cursor: pointer;
            padding: 0;
        }

        .mv-not-found {
            text-align: center;
            padding: 60px 20px;
            color: #aaa;
        }

        .mv-not-found i {
            font-size: 48px;
            display: block;
            margin-bottom: 14px;
        }

        .mv-viewer-iframe {
            width: 100%;
            height: 600px;
            border: none;
            display: block;
        }
    </style>
</head>

<body>
    <div class="container-fluid p-0">
        <?php include("../components/offcanvas.php"); ?>
        <?php include("../components/navbar.php"); ?>
        <?php include("../components/sidebar.php"); ?>

        <div class="rightbar">
            <div class="mv-page">
                <?php
                // ── READ FROM URL ──────────────────────────────────────────────
                // URL: ?url=module_view&subject=ucsp&id=1
                $moduleId = isset($_GET['id']) ? (int) trim($_GET['id']) : 0;
                $subjectSlug = isset($_GET['subject']) ? trim($_GET['subject']) : '';

                // ── STATIC DATA FOR ALL SUBJECTS ──────────────────────────────
                // KEY  = subject slug (must match your $subjectMap in subjects.php)
                // Each subject has a 'modules' array indexed by module id.
                // Add as many modules and attachments as you need per subject.
                //
                // HOW TO ADD A NEW MODULE:
                //   1. Find the subject key e.g. 'ucsp'
                //   2. Add a new number inside 'modules' e.g. 2 => [ ... ]
                //   3. Add its attachments array
                //
                // HOW TO ADD A NEW SUBJECT:
                //   Just copy any subject block and change the key + values.
                //
                // WHEN DB IS READY: delete $subjectData and uncomment the
                // PDO query at the bottom of this comment block.
                // ──────────────────────────────────────────────────────────────
                $subjectData = [

                    // ── GRADE 12 ──────────────────────────────────────────────
                    'philosophy' => [
                        'name' => 'Introduction to Philosophy of Human Person',
                        'modules' => [
                            1 => [
                                'title' => 'Module 1 Week 1 - 3',
                                'topic' => 'Introduction to Philosophy',
                                'date' => 'Mar 18',
                                'attachments' => [
                                    ['file_name' => 'Module 1: Week 1-2', 'file_path' => '/learning_management/uploads/philosophy/mod1/week1-2.pdf', 'file_type' => 'pdf'],
                                    ['file_name' => 'Module 1: Week 3', 'file_path' => '/learning_management/uploads/philosophy/mod1/week3.pdf', 'file_type' => 'pdf'],
                                ],
                            ],
                            2 => [
                                'title' => 'Module 2 Week 4 - 6',
                                'topic' => 'The Human Person',
                                'date' => 'Mar 25',
                                'attachments' => [
                                    ['file_name' => 'Module 2: Week 4-5', 'file_path' => '/learning_management/uploads/philosophy/mod2/week4-5.pdf', 'file_type' => 'pdf'],
                                    ['file_name' => 'Module 2: Week 6', 'file_path' => '/learning_management/uploads/philosophy/mod2/week6.pdf', 'file_type' => 'pdf'],
                                ],
                            ],
                        ],
                    ],

                    'ucsp' => [
                        'name' => 'Understanding Culture, Society and Politics',
                        'modules' => [
                            1 => [
                                'title' => 'Module 1 Week 1 - 3',
                                'topic' => 'Introduction to Culture',
                                'date' => 'Mar 18',
                                'attachments' => [
                                    ['file_name' => 'UCSP Module 1: Week 1-2', 'file_path' => '/learning_management/uploads/ucsp/mod1/week1-2.pdf', 'file_type' => 'pdf'],
                                    ['file_name' => 'UCSP Module 1: Week 3', 'file_path' => '/learning_management/uploads/ucsp/mod1/week3.pdf', 'file_type' => 'pdf'],
                                ],
                            ],
                        ],
                    ],

                    'css' => [
                        'name' => 'Computer Systems Servicing',
                        'modules' => [
                            1 => [
                                'title' => 'Module 1 Week 1 - 3',
                                'topic' => 'Computer Hardware',
                                'date' => 'Mar 18',
                                'attachments' => [
                                    ['file_name' => 'CSS Module 1: Week 1-2', 'file_path' => '/learning_management/uploads/css/mod1/week1-2.pdf', 'file_type' => 'pdf'],
                                    ['file_name' => 'CSS Module 1: Week 3', 'file_path' => '/learning_management/uploads/css/mod1/week3.pdf', 'file_type' => 'pdf'],
                                ],
                            ],
                        ],
                    ],

                    'pe' => [
                        'name' => 'Physical Education',
                        'modules' => [
                            1 => [
                                'title' => 'Module 1 Week 1 - 3',
                                'topic' => 'Physical Fitness',
                                'date' => 'Mar 18',
                                'attachments' => [
                                    ['file_name' => 'PE Module 1', 'file_path' => '/learning_management/uploads/pe/mod1/week1.pdf', 'file_type' => 'pdf'],
                                ],
                            ],
                        ],
                    ],

                    '3i' => [
                        'name' => 'Inquiries, Investigations and Immersion',
                        'modules' => [
                            1 => [
                                'title' => 'Module 1 Week 1 - 3',
                                'topic' => 'Research Methods',
                                'date' => 'Mar 18',
                                'attachments' => [
                                    ['file_name' => '3i Module 1', 'file_path' => '/learning_management/uploads/3i/mod1/week1.pdf', 'file_type' => 'pdf'],
                                ],
                            ],
                        ],
                    ],

                    'entrep' => [
                        'name' => 'Entrepreneurship',
                        'modules' => [
                            1 => [
                                'title' => 'Module 1 Week 1 - 3',
                                'topic' => 'Introduction to Entrepreneurship',
                                'date' => 'Mar 18',
                                'attachments' => [
                                    ['file_name' => 'Entrep Module 1', 'file_path' => '/learning_management/uploads/entrep/mod1/week1.pdf', 'file_type' => 'pdf'],
                                ],
                            ],
                        ],
                    ],

                    'work_immersion' => [
                        'name' => 'Work Immersion',
                        'modules' => [
                            1 => [
                                'title' => 'Module 1 Week 1 - 3',
                                'topic' => 'Workplace Orientation',
                                'date' => 'Mar 18',
                                'attachments' => [
                                    ['file_name' => 'WI Module 1', 'file_path' => '/learning_management/uploads/work_immersion/mod1/week1.pdf', 'file_type' => 'pdf'],
                                ],
                            ],
                        ],
                    ],

                    // ── GRADE 11 ──────────────────────────────────────────────
                    'media_info_literature' => [
                        'name' => 'Media and Information Literacy',
                        'modules' => [
                            1 => [
                                'title' => 'Module 1 Week 1 - 3',
                                'topic' => 'Introduction to Media',
                                'date' => 'Mar 18',
                                'attachments' => [
                                    ['file_name' => 'MIL Module 1', 'file_path' => '/learning_management/uploads/media_info_literature/mod1/week1.pdf', 'file_type' => 'pdf'],
                                ],
                            ],
                        ],
                    ],

                    'p_e' => [
                        'name' => 'Physical Education (Grade 11)',
                        'modules' => [
                            1 => [
                                'title' => 'Module 1 Week 1 - 3',
                                'topic' => 'Physical Fitness',
                                'date' => 'Mar 18',
                                'attachments' => [
                                    ['file_name' => 'PE Module 1', 'file_path' => '/learning_management/uploads/p_e/mod1/week1.pdf', 'file_type' => 'pdf'],
                                ],
                            ],
                        ],
                    ],

                    'css_11' => [
                        'name' => 'Computer Systems Servicing (Grade 11)',
                        'modules' => [
                            1 => [
                                'title' => 'Module 1 Week 1 - 3',
                                'topic' => 'Computer Hardware Basics',
                                'date' => 'Mar 18',
                                'attachments' => [
                                    ['file_name' => 'CSS11 Module 1', 'file_path' => '/learning_management/uploads/css_11/mod1/week1.pdf', 'file_type' => 'pdf'],
                                ],
                            ],
                        ],
                    ],

                    'reading_writing' => [
                        'name' => 'Reading and Writing',
                        'modules' => [
                            1 => [
                                'title' => 'Module 1 Week 1 - 3',
                                'topic' => 'Academic Reading',
                                'date' => 'Mar 18',
                                'attachments' => [
                                    ['file_name' => 'RW Module 1', 'file_path' => '/learning_management/uploads/reading_writing/mod1/week1.pdf', 'file_type' => 'pdf'],
                                ],
                            ],
                        ],
                    ],

                    'pagbasa_pagsusuri' => [
                        'name' => 'Pagbasa at Pagsusuri ng Iba\'t Ibang Teksto',
                        'modules' => [
                            1 => [
                                'title' => 'Modyul 1 Linggo 1 - 3',
                                'topic' => 'Pagbasa ng mga Teksto',
                                'date' => 'Mar 18',
                                'attachments' => [
                                    ['file_name' => 'Modyul 1', 'file_path' => '/learning_management/uploads/pagbasa_pagsusuri/mod1/week1.pdf', 'file_type' => 'pdf'],
                                ],
                            ],
                        ],
                    ],

                    'practical_research' => [
                        'name' => 'Practical Research',
                        'modules' => [
                            1 => [
                                'title' => 'Module 1 Week 1 - 3',
                                'topic' => 'Introduction to Research',
                                'date' => 'Mar 18',
                                'attachments' => [
                                    ['file_name' => 'PR Module 1', 'file_path' => '/learning_management/uploads/practical_research/mod1/week1.pdf', 'file_type' => 'pdf'],
                                ],
                            ],
                        ],
                    ],

                    'physical_science' => [
                        'name' => 'Physical Science',
                        'modules' => [
                            1 => [
                                'title' => 'Module 1 Week 1 - 3',
                                'topic' => 'Introduction to Physical Science',
                                'date' => 'Mar 18',
                                'attachments' => [
                                    ['file_name' => 'PS Module 1', 'file_path' => '/learning_management/uploads/physical_science/mod1/week1.pdf', 'file_type' => 'pdf'],
                                ],
                            ],
                        ],
                    ],

                    'statistics_probability' => [
                        'name' => 'Statistics and Probability',
                        'modules' => [
                            1 => [
                                'title' => 'Module 1 Week 1 - 3',
                                'topic' => 'Introduction to Statistics',
                                'date' => 'Mar 18',
                                'attachments' => [
                                    ['file_name' => 'StatProb Module 1', 'file_path' => '/learning_management/uploads/statistics_probability/mod1/week1.pdf', 'file_type' => 'pdf'],
                                ],
                            ],
                        ],
                    ],

                ];

                // ── LOOK UP THE MODULE ─────────────────────────────────────────
                $module = null;
                $attachments = [];
                $subjectName = 'Subject';

                if ($subjectSlug && isset($subjectData[$subjectSlug])) {
                    $subjectName = $subjectData[$subjectSlug]['name'];
                    if ($moduleId && isset($subjectData[$subjectSlug]['modules'][$moduleId])) {
                        $module = $subjectData[$subjectSlug]['modules'][$moduleId];
                        $attachments = $module['attachments'] ?? [];
                    }
                }
                ?>

                <?php if (!$module): ?>
                    <a href="/learning_management/public/?url=subjects&subject=<?= htmlspecialchars($subjectSlug) ?>"
                        class="mv-back-link">
                        <i class="fa fa-arrow-left"></i> Back to Subject
                    </a>
                    <div class="mv-not-found">
                        <i class="fa fa-folder-open"></i>
                        <p>Module not found.</p>
                    </div>
                <?php else: ?>

                    <a href="/learning_management/public/?url=subjects&subject=<?= htmlspecialchars($subjectSlug) ?>"
                        class="mv-back-link">
                        <i class="fa fa-arrow-left"></i> Back to <?= htmlspecialchars($subjectName) ?>
                    </a>

                    <div class="mv-header-card">
                        <div class="mv-parent-icon">
                            <div class="mv-header-icon">
                                <i class="fa fa-layer-group"></i>
                            </div>
                            <div class="mv-header-info">
                                <small>New Material</small>
                                <h2>
                                    <?= htmlspecialchars($module['title']) ?>
                                </h2>
                                <p>Topic:
                                    <?= htmlspecialchars($module['topic']) ?>
                                </p>
                                <span class="mv-date">Date Received:
                                    <?= htmlspecialchars($module['date']) ?>
                                </span>
                            </div>
                        </div>

                        <hr>

                        <div class="mv-attachments-card">
                            <h5>Attachments</h5>
                            <div class="mv-attachments-grid">
                                <?php foreach ($attachments as $att):
                                    $type = $att['file_type'] ?? 'other';
                                    $iconMap = ['pdf' => 'fa-file-pdf', 'image' => 'fa-image', 'video' => 'fa-film', 'other' => 'fa-file'];
                                    $icon = $iconMap[$type] ?? 'fa-file';
                                    ?>
                                    <div class="mv-att-card"
                                        onclick="openPdfViewer('<?= htmlspecialchars($att['file_path']) ?>', '<?= htmlspecialchars($att['file_name']) ?>')">
                                        <div class="mv-att-icon <?= $type ?>">
                                            <i class="fa <?= $icon ?>"></i>
                                            <span>
                                                <?= strtoupper($type) ?>
                                            </span>
                                        </div>
                                        <span class="mv-att-label">
                                            <?= htmlspecialchars($att['file_name']) ?>
                                        </span>
                                        <span class="mv-att-badge <?= $type ?>">
                                            <i class="fa <?= $icon ?>"></i>
                                            <?= htmlspecialchars($att['file_name']) ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="mv-viewer-wrap" id="pdfViewerWrap">
                        <div class="mv-viewer-toolbar">
                            <span id="pdfViewerLabel">Loading...</span>
                            <button onclick="closePdfViewer()"><i class="fa fa-times"></i></button>
                        </div>
                        <iframe class="mv-viewer-iframe" id="pdfViewerIframe" src=""></iframe>
                    </div>



                <?php endif; ?>

            </div><!-- end mv-page -->
        </div><!-- end rightbar -->
    </div>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
    <script>
        function openPdfViewer(url, name) {
            const wrap = document.getElementById('pdfViewerWrap');
            document.getElementById('pdfViewerLabel').textContent = name;
            document.getElementById('pdfViewerIframe').src = url;
            wrap.classList.add('open');
            wrap.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
        function closePdfViewer() {
            document.getElementById('pdfViewerWrap').classList.remove('open');
            document.getElementById('pdfViewerIframe').src = '';
        }
    </script>
</body>

</html>