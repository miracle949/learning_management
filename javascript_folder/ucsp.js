/* ==========================
   SUBJECT DETECTION
========================== */
const params = new URLSearchParams(window.location.search);
const subject = params.get("subject") || "default";
const moduleParam = params.get("module"); // module1, module2, etc.

/* ==========================
   MODULE + LESSON DATA
========================== */
const modules = [
    {
        moduleId: "module1",
        moduleTitle: "Introduction to Networking",
        lessons: [
            {
                title: "What is a Computer Network?",
                body: `
                <h4>Understanding Computer Networks</h4> 
                <p>A computer network is a collection of interconnected devices that can communicate and share resources with each other.</p>

                <h4>Key Notes:</h4> 
                <p><b>Nodes:</b> Any device connected to the network</p> 
                <p><b>Links:</b> Physical or wireless connections between nodes</p> 
                <p><b>Protocols:</b> Rules governing communication between devices</p> 
                <p><b>Bandwidth:</b> The capacity of the network connection</p> 

                <h4>Benefits of Networking:</h4>
                <p>Resource sharing (files, printers, internet connection)</p> 
                <p>Enhanced communication (email, messaging, video calls)</p> 
                <p>Data centralization and backup</p> 
                <p>Improved productivity and collaboration</p>
                `
            },
            {
                title: "Types of Computer Networks",
                body: `
                <h4>Network Types</h4>
                <p>LAN, WAN, MAN</p>
                `
            },
            {
                title: "Network Topologies",
                body: `
                <h4>Topologies</h4>
                <p>Star, Bus, Ring, Mesh</p>
                `
            },
            {
                title: "Network Topologies",
                body: `
                <h4>Topologies</h4>
                <p>Star, Bus, Ring, Mesh</p>
                `
            },
            {
                title: "Network Topologies",
                body: `
                <h4>Topologies</h4>
                <p>Star, Bus, Ring, Mesh</p>
                `
            },
            {
                title: "Network Topologies",
                body: `
                <h4>Topologies</h4>
                <p>Star, Bus, Ring, Mesh</p>
                `
            },
            {
                title: "Network Topologies",
                body: `
                <h4>Topologies</h4>
                <p>Star, Bus, Ring, Mesh</p>
                `
            },
            {
                title: "Network Topologies",
                body: `
                <h4>Topologies</h4>
                <p>Star, Bus, Ring, Mesh</p>
                `
            },
            {
                title: "Network Topologies",
                body: `
                <h4>Topologies</h4>
                <p>Star, Bus, Ring, Mesh</p>
                `
            },
            {
                title: "Network Topologies",
                body: `
                <h4>Topologies</h4>
                <p>Star, Bus, Ring, Mesh</p>
                `
            }
        ]
    },
    {
        moduleId: "module2",
        moduleTitle: "Network Security",
        lessons: [
            {
                title: "Security Basics",
                body: `
                <h4>Security Concepts</h4>
                <p>Firewalls, Encryption, Authentication</p>
                `
            },
            {
                title: "Advanced Security",
                body: `
                <h4>Advanced Topics</h4>
                <p>VPNs, IDS, IPS</p>
                `
            },
            {
                title: "Advanced Security",
                body: `
                <h4>Advanced Topics</h4>
                <p>VPNs, IDS, IPS</p>
                `
            },
            {
                title: "Advanced Security",
                body: `
                <h4>Advanced Topics</h4>
                <p>VPNs, IDS, IPS</p>
                `
            },
            {
                title: "Advanced Security",
                body: `
                <h4>Advanced Topics</h4>
                <p>VPNs, IDS, IPS</p>
                `
            }
        ]
    },
    {
        moduleId: "module3",
        moduleTitle: "Network Security",
        lessons: [
            {
                title: "Security Basics",
                body: `
                <h4>Security Concepts</h4>
                <p>Firewalls, Encryption, Authentication</p>
                `
            },
            {
                title: "Advanced Security",
                body: `
                <h4>Advanced Topics</h4>
                <p>VPNs, IDS, IPS</p>
                `
            },
            {
                title: "Advanced Security",
                body: `
                <h4>Advanced Topics</h4>
                <p>VPNs, IDS, IPS</p>
                `
            },
            {
                title: "Advanced Security",
                body: `
                <h4>Advanced Topics</h4>
                <p>VPNs, IDS, IPS</p>
                `
            },
            {
                title: "Advanced Security",
                body: `
                <h4>Advanced Topics</h4>
                <p>VPNs, IDS, IPS</p>
                `
            },
            {
                title: "Advanced Security",
                body: `
                <h4>Advanced Topics</h4>
                <p>VPNs, IDS, IPS</p>
                `
            }
        ]
    }
];

/* ==========================
   ACTIVE MODULE (FIXED)
========================== */
let currentModuleIndex = modules.findIndex(
    m => m.moduleId === moduleParam
);

if (currentModuleIndex === -1) currentModuleIndex = 0;

let currentLessonIndex = 0;

/* ==========================
   RESTORE SAVED LESSON (PER MODULE)
========================== */
const savedLesson = localStorage.getItem(
    `${subject}_${modules[currentModuleIndex].moduleId}_currentLesson`
);

if (savedLesson !== null) {
    currentLessonIndex = parseInt(savedLesson);
}

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

    const prevBtn = document.getElementById("prevBtn");
    prevBtn.classList.toggle("disabled", currentLessonIndex === 0);
    prevBtn.style.pointerEvents =
        currentLessonIndex === 0 ? "none" : "auto";

    updateProgress();
}

/* ==========================
   UPDATE PROGRESS
========================== */
function updateProgress() {
    const completedLessons = currentLessonIndex + 1;
    const totalLessons = lessons.length;
    const percent = Math.round((completedLessons / totalLessons) * 100);

    progressBar.style.width = percent + "%";
    progressPercent.innerText = percent + "%";

    localStorage.setItem(
        `${subject}_${currentModule.moduleId}_completedLessons`,
        completedLessons
    );

    localStorage.setItem(
        `${subject}_${currentModule.moduleId}_totalLessons`,
        totalLessons
    );

    localStorage.setItem(
        `${subject}_${currentModule.moduleId}_lessonPercent`,
        percent
    );

    localStorage.setItem(
        `${subject}_${currentModule.moduleId}_currentLesson`,
        currentLessonIndex
    );

    updateModuleCards();
}

/* ==========================
   UPDATE MODULE DASHBOARD
========================== */
function updateModuleCards() {
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
}

/* ==========================
   PAGINATION
========================== */
document.getElementById("prevBtn").addEventListener("click", e => {
    e.preventDefault();
    if (currentLessonIndex > 0) {
        currentLessonIndex--;
        loadLesson();
    }
});

document.getElementById("nextBtn").addEventListener("click", e => {
    e.preventDefault();
    if (currentLessonIndex < lessons.length - 1) {
        currentLessonIndex++;
        loadLesson();
    }
});

/* ==========================
   INITIAL LOAD
========================== */
loadLesson();
updateModuleCards();
