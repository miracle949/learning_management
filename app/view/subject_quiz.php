<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <link rel="stylesheet" href="../css_folder/subject_quiz.css">
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

            $quizMap = [
                "philosophy" => [
                    "view" => "../Grade_12/philosophy/philosophy_quiz.php",
                    "js" => "../Grade_12/philosophy/philosophy_quiz.js"
                ],
                "ucsp" => [
                    "view" => "../Grade_12/ucsp/ucsp_quiz.php",
                    "js" => "../Grade_12/ucsp/ucsp_quiz.js"
                ]
                // add more subjects here later
            ];

            if ($subject && isset($quizMap[$subject])) {
                include $quizMap[$subject]["view"];
                echo '<script src="' . $quizMap[$subject]["js"] . '"></script>';
            } else {
                echo "<h3>No quiz available for this subject.</h3>";
            }
            ?>
            
        </div>
    </div>

    <!-- bootstrap link javascript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
<!-- 
    <script src="../Grade_12/philosophy/philosophy_quiz.js"></script> -->
</body>

</html>