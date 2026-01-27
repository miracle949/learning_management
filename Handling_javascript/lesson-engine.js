/* ==========================
   SUBJECT DETECTION
========================== */
const params = new URLSearchParams(window.location.search);
const subject = params.get("subject");

/* ==========================
   SUBJECT DATA MUST EXIST
========================== */
if (!window.SUBJECT_MODULES) {
    console.error("No subject data loaded!");
}

/* ==========================
   MODULE STATE
========================== */
let modules = window.SUBJECT_MODULES;
let currentModuleIndex = 0;
let currentLessonIndex = 0;

const savedModule = localStorage.getItem(subject + "_currentModule");
const savedLesson = localStorage.getItem(subject + "_currentLesson");

if (savedModule !== null) currentModuleIndex = parseInt(savedModule);
if (savedLesson !== null) currentLessonIndex = parseInt(savedLesson);

let currentModule = modules[currentModuleIndex];
let lessons = currentModule.lessons;

/* ==========================
   UI ELEMENTS
========================== */
const progressBar = document.querySelector(".progress-lesson");
const progressPercent = document.querySelector(".progress-title span");

/* ==========================
   LOAD LESSON
========================== */
function loadLesson() {
    const lesson = lessons[currentLessonIndex];

    document.getElementById("lesson-title").innerText = lesson.title;
    document.getElementById("lesson-body").innerHTML = lesson.body;

    document.getElementById("lesson-count").innerText =
        `Lesson ${currentLessonIndex + 1} of ${lessons.length}`;

    document.getElementById("page-indicator").innerText =
        `${currentLessonIndex + 1} / ${lessons.length}`;

    updateProgress();
}

/* ==========================
   UPDATE PROGRESS
========================== */
function updateProgress() {
    const completed = currentLessonIndex + 1;
    const total = lessons.length;
    const percent = Math.round((completed / total) * 100);

    progressBar.style.width = percent + "%";
    progressPercent.innerText = percent + "%";

    localStorage.setItem(`${subject}_${currentModule.moduleId}_completedLessons`, completed);
    localStorage.setItem(`${subject}_${currentModule.moduleId}_totalLessons`, total);
    localStorage.setItem(`${subject}_${currentModule.moduleId}_lessonPercent`, percent);

    localStorage.setItem(subject + "_currentModule", currentModuleIndex);
    localStorage.setItem(subject + "_currentLesson", currentLessonIndex);
}

/* ==========================
   PAGINATION
========================== */
document.getElementById("prevBtn").onclick = () => {
    if (currentLessonIndex > 0) {
        currentLessonIndex--;
        loadLesson();
    }
};

document.getElementById("nextBtn").onclick = () => {
    if (currentLessonIndex < lessons.length - 1) {
        currentLessonIndex++;
        loadLesson();
    }
};

loadLesson();
