/* ==========================
   LESSON DATA
========================== */
const lessons = [
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
      <p><b>LAN:</b> Local Area Network</p>
      <p><b>WAN:</b> Wide Area Network</p>
      <p><b>MAN:</b> Metropolitan Area Network</p>
    `
    },
    {
        title: "Network Topologies",
        body: `
      <h4>Common Topologies</h4>
      <p>Star, Bus, Ring, Mesh</p>
    `
    },
    {
        title: "Network Devices",
        body: `
      <h4>Important Devices</h4>
      <p>Router, Switch, Hub, Modem</p>
    `
    },
    {
        title: "Network Security Basics",
        body: `
      <h4>Security Concepts</h4>
      <p>Firewalls, Encryption, Authentication</p>
    `
    },
    {
        title: "Network Security Basics",
        body: `
      <h4>Security Concepts</h4>
      <p>Firewalls, Encryption, Authentication</p>
    `
    },
    {
        title: "Network Security Basics",
        body: `
      <h4>Security Concepts</h4>
      <p>Firewalls, Encryption, Authentication</p>
    `
    },
    {
        title: "Network Security Basics",
        body: `
      <h4>Security Concepts</h4>
      <p>Firewalls, Encryption, Authentication</p>
    `
    },
    {
        title: "Network Security Basics",
        body: `
      <h4>Security Concepts</h4>
      <p>Firewalls, Encryption, Authentication</p>
    `
    },
    {
        title: "Network Security Basics",
        body: `
      <h4>Security Concepts</h4>
      <p>Firewalls, Encryption, Authentication</p>
    `
    },
];

/* ==========================
   PROGRESS SETUP
========================== */
const totalLessons = lessons.length;
const progressBar = document.querySelector(".progress-lesson");
const progressPercent = document.querySelector(".progress-title span");

// Restore saved lesson
let savedPage = localStorage.getItem("lessonProgress");
let currentPage = savedPage !== null ? parseInt(savedPage) : 0;

/* ==========================
   LOAD LESSON
========================== */
function loadLesson(page) {
    document.getElementById("lesson-title").innerText = lessons[page].title;
    document.getElementById("lesson-body").innerHTML = lessons[page].body;

    document.getElementById("lesson-count").innerText =
        `Lesson ${page + 1} of ${lessons.length}`;

    document.getElementById("page-indicator").innerText =
        `${page + 1} / ${lessons.length}`;

    const prevBtn = document.getElementById("prevBtn");

    prevBtn.classList.toggle("disabled", page === 0);

    if (page === 0) {
        prevBtn.style.border = "";
        prevBtn.style.color = "";
        prevBtn.style.pointerEvents = "none";
    } else {
        prevBtn.style.border = "1px solid #4F39F6";
        prevBtn.style.color = "#4F39F6";
        prevBtn.style.pointerEvents = "auto";
    }

    updateProgress(page);
}

/* ==========================
   UPDATE PROGRESS
   (LESSON + MODULE)
========================== */
function updateProgress(page) {
    const completedLessons = page + 1;
    const percent = Math.round((completedLessons / totalLessons) * 100);

    // Lesson page UI
    progressBar.style.width = percent + "%";
    progressPercent.innerText = percent + "%";

    // Save lesson + module progress
    localStorage.setItem("lessonProgress", page);
    localStorage.setItem("completedLessons", completedLessons);
    localStorage.setItem("totalLessons", totalLessons);
    localStorage.setItem("lessonPercent", percent);
}

/* ==========================
   PAGINATION
========================== */
document.getElementById("prevBtn").addEventListener("click", e => {
    e.preventDefault();
    if (currentPage > 0) {
        currentPage--;
        loadLesson(currentPage);
    }
});

document.getElementById("nextBtn").addEventListener("click", e => {
    e.preventDefault();
    if (currentPage < lessons.length - 1) {
        currentPage++;
        loadLesson(currentPage);
    }
});

/* ==========================
   INITIAL LOAD
========================== */
loadLesson(currentPage);
