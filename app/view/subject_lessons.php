<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- These paths work because index.php is in public/ and
         the view files are in app/view/ — same as subjects.php -->
    <link rel="stylesheet" href="../css_folder/lessons.css">
    <link rel="stylesheet" href="../css_folder/components.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>

    <div class="container-fluid p-0">
        <?php include("../components/offcanvas.php"); ?>
        <?php include("../components/sidebar.php"); ?>

        <div class="rightbar">
            <?php
            // $viewFile and $subject are already set by StudentsController::subject_lessons()
            // All lesson data variables are also already set:
            // $module, $lessons, $lessonId, $lesson, $images, $videos,
            // $flashcards, $activities, $quizzes, $activityData, $quizData,
            // $lessonCompletion, $completedCount, $totalLessons, $moduleId, $studentId
            
            if (isset($viewFile) && $viewFile && file_exists($viewFile)) {
                include $viewFile;
            } else {
                echo "<h3>Lesson not found</h3>";
            }

            // Load the subject's JS file
            $jsMap = [
                "philosophy" => "../Grade_12/philosophy/philosophy.js",
                "ucsp" => "../Grade_12/ucsp/ucsp.js",
                "css" => "../Grade_12/css/css.js",
                "pe" => "../Grade_12/pe/pe.js",
                "3i" => "../Grade_12/3i/3i.js",
                "entrep" => "../Grade_12/entrep/entrep.js",
                "work_immersion" => "../Grade_12/work_immersion/work_immersion.js",
                "media_info_literature" => "../Grade_11/media_info_literature/media_info_literature.js",
                "p_e" => "../Grade_11/p_e/pe.js",
                "css_11" => "../Grade_11/css_11/css.js",
                "reading_writing" => "../Grade_11/reading_writing/reading_writing.js",
                "pagbasa_pagsusuri" => "../Grade_11/pagbasa_pagsusuri/pagbasa_pagsusuri.js",
                "practical_research" => "../Grade_11/practical_research/practical_research.js",
                "physical_science" => "../Grade_11/physical_science/physical_science.js",
                "statistics_probability" => "../Grade_11/statistics_probability/statistics_probability.js",
            ];

            if (isset($subject) && isset($jsMap[$subject])) {
                echo '<script src="' . htmlspecialchars($jsMap[$subject]) . '"></script>';
            }
            ?>
        </div>
    </div>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>

    <script>
        const lessonParams = new URLSearchParams(window.location.search);
        const lessonSubject = lessonParams.get("subject") || "philosophy";
        const lessonModule = lessonParams.get("module");
    </script>

</body>

</html>