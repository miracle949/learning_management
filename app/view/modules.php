<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modules</title>
    <link rel="stylesheet" href="../css_folder/components.css">
    <link rel="stylesheet" href="../css_folder/subjects.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>
    <div class="container-fluid p-0">
        <?php include("../components/offcanvas.php"); ?>
        <?php include("../components/navbar.php"); ?>
        <?php include("../components/sidebar.php"); ?>

        <div class="rightbar">
            <?php
            // $subject is set in HomeController::modules()
            // Same pattern as your subjects.php $subjectMap
            
            $moduleMap = [
                // Grade 12
                'philosophy' => '../Grade_12/philosophy/philosophy_module.php',
                'ucsp' => '../Grade_12/ucsp/ucsp_module.php',
                'css' => '../Grade_12/css/css_module.php',
                'pe' => '../Grade_12/pe/pe_modules.php',
                '3i' => '../Grade_12/3i/3i_modules.php',
                'entrep' => '../Grade_12/entrep/entrep_modules.php',
                'work_immersion' => '../Grade_12/work_immersion/work_immersion_modules.php',

                // Grade 11
                'media_info_literature' => '../Grade_11/media_info_literature/media_info_literature_modules.php',
                'p_e' => '../Grade_11/p_e/p_e_modules.php',
                'css_11' => '../Grade_11/css_11/css_11_modules.php',
                'reading_writing' => '../Grade_11/reading_writing/reading_writing_modules.php',
                'pagbasa_pagsusuri' => '../Grade_11/pagbasa_pagsusuri/pagbasa_pagsusuri_modules.php',
                'practical_research' => '../Grade_11/practical_research/practical_research_modules.php',
                'physical_science' => '../Grade_11/physical_science/physical_science_modules.php',
                'statistics_probability' => '../Grade_11/statistics_probability/statistics_probability_modules.php',
            ];

            if ($subject && isset($moduleMap[$subject])) {
                include $moduleMap[$subject];
            } else {
                echo '<p style="color:#aaa; padding:2rem; text-align:center;">Select a subject.</p>';
            }
            ?>
        </div>
    </div>
    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>

</html>