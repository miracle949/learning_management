<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css_folder/lessons.css">
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
                    "view" => "../css_folder/css_lessons.php",
                    "js" => "../javascript_folder/css.js"
                ]
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

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