<?php
$subject = $_GET['subject'] ?? null;

$subjectMap = [
    "philosophy" => "../philosophy_folder/philosophy.php",
    "ucsp" => "../ucsp_folder/ucsp.php",
    "css" => "../css_folder/css.php",
];

if ($subject && isset($subjectMap[$subject])) {
    include $subjectMap[$subject];
} else {
    echo "<h3>Select a subject</h3>";
}
?>

<script>
    /* ==========================
        SUBJECT DETECTION
    ========================== */
    const params = new URLSearchParams(window.location.search);
    const subject = params.get("subject") || "default";

    /* ==========================
       MODULE ID (IMPORTANT)
       MUST match moduleId in lesson JS
    ========================== */
    const moduleId = "module1"; // change if needed

    /* ==========================
       GET SAVED MODULE PROGRESS
    ========================== */
    const completedLessons = parseInt(
        localStorage.getItem(`${subject}_${moduleId}_completedLessons`)
    ) || 0;

    const totalLessons = parseInt(
        localStorage.getItem(`${subject}_${moduleId}_totalLessons`)
    ) || 0;

    const percent = parseInt(
        localStorage.getItem(`${subject}_${moduleId}_lessonPercent`)
    ) || 0;

    /* ==========================
       UPDATE MODULE UI
    ========================== */
    document.getElementById("lessonText").innerText =
        `${completedLessons} of ${totalLessons} lessons`;

    document.getElementById("lessonPercent").innerText =
        `${percent}%`;

    document.getElementById("moduleProgress").style.width =
        Math.min(percent, 100) + "%";
</script>