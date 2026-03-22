<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Introduction to Philosophy of Human Person</title>
    <link rel="stylesheet" href="../css_folder/subjects.css">
    <link rel="stylesheet" href="../css_folder/components.css">

    <!-- bootstrap link -->
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>

    <div class="container-fluid p-0">

        <?php include("../components/offcanvas.php"); ?>

        <?php include("../components/navbar.php"); ?>

        <?php include("../components/sidebar.php"); ?>

        <div class="rightbar">

            <?php
            // ============================================================
// This is your existing subjects.php
// ADD the 3 lines marked with ← NEW before the include line
// ============================================================
            
            $subject = $_GET['subject'] ?? null;

            $subjectMap = [
                // Grade 12
                "philosophy" => "../Grade_12/philosophy/philosophy.php",
                "ucsp" => "../Grade_12/ucsp/ucsp.php",
                "css" => "../Grade_12/css/css.php",
                "pe" => "../Grade_12/pe/pe.php",
                "3i" => "../Grade_12/3i/3i.php",
                "entrep" => "../Grade_12/entrep/entrep.php",
                "work_immersion" => "../Grade_12/work_immersion/work_immersion.php",

                // Grade 11
                "media_info_literature" => "../Grade_11/media_info_literature/media_info_literature.php",
                "p_e" => "../Grade_11/p_e/pe.php",
                "css_11" => "../Grade_11/css_11/css.php",
                "reading_writing" => "../Grade_11/reading_writing/reading_writing.php",
                "pagbasa_pagsusuri" => "../Grade_11/pagbasa_pagsusuri/pagbasa_pagsusuri.php",
                "practical_research" => "../Grade_11/practical_research/practical_research.php",
                "physical_science" => "../Grade_11/physical_science/physical_science.php",
                "statistics_probability" => "../Grade_11/statistics_probability/statistics_probability.php",
            ];

            if ($subject && isset($subjectMap[$subject])) {

                // ← NEW: fetch the feed items from DB before loading the subject file
                // This makes $feedItems available inside philosophy.php, ucsp.php, etc.
                require_once "../app/models/Students.php";      // ← NEW
                $studentModel = new Students();                 // ← NEW
                $feedItems = $studentModel->getSubjectFeed($subject);  // ← NEW
            
                include $subjectMap[$subject];

            } else {
                echo "<h3>Select a subject</h3>";
            }
            ?>

        </div>
    </div>

    <!-- bootstrap link javascript -->
    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>

    <!-- <script>
        const params = new URLSearchParams(window.location.search);
        const subject = params.get("subject") || "default";

        document.querySelectorAll(".module-progress").forEach(moduleCard => {
            const moduleId = moduleCard.dataset.moduleId;

            const completed =
                localStorage.getItem(`${subject}_${moduleId}_completedLessons`) || 0;

            const total =
                localStorage.getItem(`${subject}_${moduleId}_totalLessons`) || 0;

            const percent =
                localStorage.getItem(`${subject}_${moduleId}_lessonPercent`) || 0;

            moduleCard.querySelector(".lessonText").innerText =
                `${completed} of ${total} lessons`;

            moduleCard.querySelector(".lessonPercent").innerText =
                `${percent}%`;

            moduleCard.querySelector(".progress").style.width =
                percent + "%";
        }); -->
    </script>

</body>

</html>