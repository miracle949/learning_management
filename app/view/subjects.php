<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Introduction to Philosophy of Human Person</title>
    <link rel="stylesheet" href="../css_folder/subjects.css">
    <link rel="stylesheet" href="../css_folder/components.css">

    <!-- bootstrap link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <div class="container-fluid p-0">

        <?php include("../components/offcanvas.php"); ?>

        <?php include("../components/navbar.php"); ?>

        <?php include("../components/sidebar.php"); ?>

        <div class="rightbar">

            <?php
            $subject = $_GET['subject'] ?? null;

            $subjectMap = [
                "philosophy" => "../Grade_12/philosophy/philosophy.php",
                "ucsp" => "../Grade_12/ucsp/ucsp.php",
                "css" => "../Grade_12/css/css.php",
                "pe" => "../Grade_12/pe/pe.php",
                "3i" => "../Grade_12/3i/3i.php",
                "entrep" => "../Grade_12/entrep/entrep.php",
                "work_immersion" => "../Grade_12/work_immersion/work_immersion.php",
            ];

            if ($subject && isset($subjectMap[$subject])) {
                include $subjectMap[$subject];
            } else {
                echo "<h3>Select a subject</h3>";
            }
            ?>

        </div>
    </div>

    <!-- bootstrap link javascript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

    <script>
        /* ==========================
           SUBJECT DETECTION
        ========================== */
        const params = new URLSearchParams(window.location.search);
        const subject = params.get("subject") || "default";

        /* ==========================
           UPDATE ALL MODULE CARDS
        ========================== */
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
        });
    </script>

</body>

</html>