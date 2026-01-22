const lessons = [
    {
        title: "What is a Computer Network?",
        body: `
        
      <h4>Understanding Computer Networks</h4>
      <p>A computer network is a collection of interconnected devices that can communicate and share resources with each other. These devices, known as nodes, can include computers, servers,smartphones, printers, and other hardware.</p>

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
    
     <p>Networks can range from small home networks connecting a few devices to massive enterprise
     networks spanning multiple locations worldwide.</p>
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
      <p>Each topology has advantages and disadvantages.</p>
    `
    },
    {
        title: "Network Devices",
        body: `
      <h4>Important Devices</h4>
      <p>Router, Switch, Hub, Modem</p>
      <p>They manage data flow in a network.</p>
    `
    },
    {
        title: "Network Security Basics",
        body: `
      <h4>Security Concepts</h4>
      <p>Firewalls, Encryption, Authentication</p>
      <p>Protects data and network access.</p>
    `
    }
];

let currentPage = 0;

function loadLesson(page) {
    document.getElementById("lesson-title").innerText = lessons[page].title;
    document.getElementById("lesson-body").innerHTML = lessons[page].body;

    document.getElementById("lesson-count").innerText =
        `Lesson ${page + 1} of ${lessons.length}`;

    document.getElementById("page-indicator").innerText =
        `${page + 1} / ${lessons.length}`;

    document.getElementById("prevBtn").classList.toggle("disabled", page === 0);
    document.getElementById("nextBtn").classList.toggle(
        "disabled",
        page === lessons.length - 1
    );
}

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

// Initial load
loadLesson(currentPage);
