<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css_folder/lessons.css">
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

        <div class="rightbar">
            <?php
            $subject = $_GET['subject'] ?? null;

            $lessonMap = [
                "philosophy" => [
                    "view" => "../Grade_12/philosophy/philosophy_lessons.php",
                    "js" => "../Grade_12/philosophy/philosophy.js"
                ],
                "ucsp" => [
                    "view" => "../Grade_12/ucsp/ucsp_lessons.php",
                    "js" => "../Grade_12/ucsp/ucsp.js"
                ],
                "css" => [
                    "view" => "../Grade_12/css/css_lessons.php",
                    "js" => "../Grade_12/css/css.js"
                ],
                "pe" => [
                    "view" => "../Grade_12/pe/pe_lessons.php",
                    "js" => "../Grade_12/pe/pe.js"
                ],
                "3i" => [
                    "view" => "../Grade_12/3i/3i_lessons.php",
                    "js" => "../Grade_12/3i/3i.js"
                ],
                "entrep" => [
                    "view" => "../Grade_12/entrep/entrep_lessons.php",
                    "js" => "../Grade_12/entrep/entrep.js"
                ],
                "work_immersion" => [
                    "view" => "../Grade_12/work_immersion/work_immersion_lessons.php",
                    "js" => "../Grade_12/work_immersion/work_immersion.js"
                ],
                "media_info_literature" => [
                    "view" => "../Grade_11/media_info_literature/media_info_literature_lessons.php",
                    "js" => "../Grade_11/media_info_literature/media_info_literature.js"
                ],
                "p_e" => [
                    "view" => "../Grade_11/p_e/pe_lessons.php",
                    "js" => "../Grade_11/p_e/pe.js"
                ],
                "css_11" => [
                    "view" => "../Grade_11/css_11/css_lessons.php",
                    "js" => "../Grade_11/css_11/css.js"
                ],
                "reading_writing" => [
                    "view" => "../Grade_11/reading_writing/reading_writing_lessons.php",
                    "js" => "../Grade_11/reading_writing/reading_writing.js"
                ],
                "pagbasa_pagsusuri" => [
                    "view" => "../Grade_11/pagbasa_pagsusuri/pagbasa_pagsusuri_lessons.php",
                    "js" => "../Grade_11/pagbasa_pagsusuri/pagbasa_pagsusuri.js"
                ],
                "practical_research" => [
                    "view" => "../Grade_11/practical_research/practical_research_lessons.php",
                    "js" => "../Grade_11/practical_research/practical_research.js"
                ],
                "physical_science" => [
                    "view" => "../Grade_11/physical_science/physical_science_lessons.php",
                    "js" => "../Grade_11/physical_science/physical_science.js"
                ],
                "statistics_probability" => [
                    "view" => "../Grade_11/statistics_probability/statistics_probability_lessons.php",
                    "js" => "../Grade_11/statistics_probability/statistics_probability.js"
                ],
            ];

            if ($subject && isset($lessonMap[$subject])) {
                include $lessonMap[$subject]["view"];
                echo '<script src="' . $lessonMap[$subject]["js"] . '"></script>';
            } else {
                echo "<h3>Lesson not found</h3>";
            }
            ?>
        </div>
    </div>


    <!-- bootstrap link javascript -->
    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>

    <!-- <script src="../javascript_folder/philosophy.js"></script> -->

    <script>
        const lessonParams = new URLSearchParams(window.location.search);
        const lessonSubject = lessonParams.get("subject") || "philosophy";
        const lessonModule = lessonParams.get("module");

        const quizBtn = document.querySelector(".take-quiz a");

        if (lessonModule) {
            quizBtn.href =
                `/learning_management/public/?url=subject_quiz&subject=${lessonSubject}&module=${lessonModule}`;
        }
    </script>

</body>

</html>