/* ==========================
   SUBJECT DETECTION
========================== */
const params = new URLSearchParams(window.location.search);
const subject = params.get("subject") || "default";
const moduleParam = params.get("module");

/* ==========================
   MODULE + LESSON DATA
   Each lesson has: title, body, videos[], images[], activities{}, quiz{}
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
                `,
                videos: [
                    { title: "Introduction to Computer Networks", url: "https://www.youtube.com/embed/3QhU9jd03a0", duration: "5:24", type: "Lecture" },
                    { title: "How Data Travels in a Network", url: "https://www.youtube.com/embed/3QhU9jd03a0", duration: "8:12", type: "Animation" }
                ],
                images: [
                    { title: "Network Diagram", url: "https://upload.wikimedia.org/wikipedia/commons/thumb/9/9b/Social_Network_Analysis_Visualization.png/800px-Social_Network_Analysis_Visualization.png", description: "A visual diagram of how devices are connected in a network." },
                    { title: "Node Connections", url: "https://upload.wikimedia.org/wikipedia/commons/thumb/9/9b/Social_Network_Analysis_Visualization.png/800px-Social_Network_Analysis_Visualization.png", description: "Illustration of nodes and how they link together." },
                    { title: "Wireless Links", url: "https://upload.wikimedia.org/wikipedia/commons/thumb/9/9b/Social_Network_Analysis_Visualization.png/800px-Social_Network_Analysis_Visualization.png", description: "Example of wireless connections between devices." }
                ],
                activities: {
                    title: "Network Basics Activity",
                    instructions: "Answer the following questions about computer network fundamentals.",
                    points: 10, time: 20,
                    questions: [
                        { type: "essay", text: "In your own words, what is a computer network? Give one example." },
                        { type: "essay", text: "What is the difference between a node and a link in a network?" },
                        { type: "multiple_choice", text: "Which best describes bandwidth?", choices: ["Number of devices", "Capacity of the network connection", "Physical cable used", "IP address"], correct: 1 },
                        { type: "multiple_choice", text: "What is a protocol in networking?", choices: ["A type of cable", "A wireless signal", "Rules governing communication between devices", "A network device"], correct: 2 }
                    ]
                },
                quiz: {
                    title: "Network Basics Quiz",
                    instructions: "Answer all questions carefully. You need 75% to pass.",
                    time: 15, passing: 75,
                    questions: [
                        { text: "What is a computer network?", choices: ["A single computer", "A collection of interconnected devices that share resources", "A software application", "A storage device"], correct: 1 },
                        { text: "What is a node in a network?", choices: ["A type of cable", "A wireless signal", "Any device connected to the network", "A communication rule"], correct: 2 },
                        { text: "What does bandwidth refer to?", choices: ["Physical width of cable", "Capacity of the network connection", "Number of nodes", "A type of protocol"], correct: 1 },
                        { text: "Which is an example of a protocol?", choices: ["Ethernet cable", "Router", "TCP/IP", "Hard drive"], correct: 2 },
                        { text: "What connects nodes in a network?", choices: ["Protocols", "Links (physical or wireless connections)", "Bandwidth", "IP addresses"], correct: 1 },
                        { text: "Which device connects multiple networks?", choices: ["Switch", "Hub", "Router", "Modem"], correct: 2 },
                        { text: "What does LAN stand for?", choices: ["Large Area Network", "Local Area Network", "Linked Access Node", "Logical Access Network"], correct: 1 },
                        { text: "Which is a wireless networking standard?", choices: ["USB", "HDMI", "Wi-Fi (IEEE 802.11)", "VGA"], correct: 2 },
                        { text: "What is the purpose of a firewall?", choices: ["Speed up internet", "Monitor and control network traffic", "Convert digital to analog", "Store data"], correct: 1 },
                        { text: "Which OSI layer handles IP addressing?", choices: ["Physical", "Data Link", "Network", "Transport"], correct: 2 }
                    ]
                }
            },
            {
                title: "Types of Computer Networks",
                body: `
                    <h4>Network Types</h4>
                    <p><b>LAN (Local Area Network):</b> Covers a small area like a home or office.</p>
                    <p><b>WAN (Wide Area Network):</b> Covers large geographic areas, like the internet.</p>
                    <p><b>MAN (Metropolitan Area Network):</b> Covers a city or campus.</p>
                `,
                videos: [
                    { title: "LAN vs WAN vs MAN Explained", url: "https://www.youtube.com/embed/3QhU9jd03a0", duration: "6:10", type: "Lecture" }
                ],
                images: [
                    { title: "LAN Diagram", url: "https://upload.wikimedia.org/wikipedia/commons/thumb/9/9b/Social_Network_Analysis_Visualization.png/800px-Social_Network_Analysis_Visualization.png", description: "A diagram of a Local Area Network." }
                ],
                activities: {
                    title: "Network Types Activity",
                    instructions: "Answer the following questions about network types.",
                    points: 10, time: 15,
                    questions: [
                        { type: "essay", text: "What is the main difference between a LAN and a WAN?" },
                        { type: "multiple_choice", text: "Which network type covers the largest area?", choices: ["LAN", "MAN", "WAN", "PAN"], correct: 2 }
                    ]
                },
                quiz: {
                    title: "Network Types Quiz",
                    instructions: "Choose the best answer for each question.",
                    time: 10, passing: 75,
                    questions: [
                        { text: "LAN stands for?", choices: ["Large Area Network", "Local Area Network", "Linked Access Node", "Long Area Network"], correct: 1 },
                        { text: "Which covers the widest area?", choices: ["LAN", "PAN", "WAN", "MAN"], correct: 2 },
                        { text: "A school network is usually a?", choices: ["WAN", "MAN", "LAN", "PAN"], correct: 2 },
                        { text: "The internet is an example of?", choices: ["LAN", "MAN", "WAN", "VPN"], correct: 2 },
                        { text: "MAN stands for?", choices: ["Minor Area Network", "Metropolitan Area Network", "Multi Access Node", "Main Area Network"], correct: 1 }
                    ]
                }
            },
            {
                title: "Network Topologies",
                body: `
                    <h4>Topologies</h4>
                    <p><b>Star:</b> All devices connect to a central hub.</p>
                    <p><b>Bus:</b> All devices share a single communication line.</p>
                    <p><b>Ring:</b> Each device connects to two others, forming a ring.</p>
                    <p><b>Mesh:</b> Every device connects to every other device.</p>
                `,
                videos: [
                    { title: "Network Topologies Overview", url: "https://www.youtube.com/embed/3QhU9jd03a0", duration: "7:45", type: "Lecture" }
                ],
                images: [
                    { title: "Star Topology", url: "https://upload.wikimedia.org/wikipedia/commons/thumb/9/9b/Social_Network_Analysis_Visualization.png/800px-Social_Network_Analysis_Visualization.png", description: "Diagram of a star topology." },
                    { title: "Mesh Topology", url: "https://upload.wikimedia.org/wikipedia/commons/thumb/9/9b/Social_Network_Analysis_Visualization.png/800px-Social_Network_Analysis_Visualization.png", description: "Diagram of a mesh topology." }
                ],
                activities: {
                    title: "Topology Activity",
                    instructions: "Identify and describe the network topologies.",
                    points: 10, time: 20,
                    questions: [
                        { type: "essay", text: "Describe the star topology and give one advantage and one disadvantage." },
                        { type: "multiple_choice", text: "Which topology uses a central hub?", choices: ["Bus", "Ring", "Star", "Mesh"], correct: 2 }
                    ]
                },
                quiz: {
                    title: "Topology Quiz",
                    instructions: "Choose the best answer.",
                    time: 10, passing: 75,
                    questions: [
                        { text: "Which topology connects all devices to a central hub?", choices: ["Bus", "Ring", "Star", "Mesh"], correct: 2 },
                        { text: "In a ring topology, data travels in?", choices: ["Random order", "A circle", "Straight line", "Broadcast"], correct: 1 },
                        { text: "Which topology is the most expensive?", choices: ["Bus", "Star", "Ring", "Mesh"], correct: 3 },
                        { text: "Bus topology uses a?", choices: ["Hub", "Switch", "Single communication line", "Wireless signal"], correct: 2 },
                        { text: "Which topology is easiest to troubleshoot?", choices: ["Bus", "Ring", "Mesh", "Star"], correct: 3 }
                    ]
                }
            },
            {
                title: "Network Protocols",
                body: `
                    <h4>What are Protocols?</h4>
                    <p>Protocols are sets of rules that govern how data is transmitted over a network.</p>
                    <p><b>TCP/IP:</b> The foundational protocol of the internet.</p>
                    <p><b>HTTP/HTTPS:</b> Used for web browsing.</p>
                    <p><b>FTP:</b> File Transfer Protocol for transferring files.</p>
                    <p><b>DNS:</b> Translates domain names to IP addresses.</p>
                `,
                videos: [],
                images: [],
                activities: {
                    title: "Protocols Activity",
                    instructions: "Answer questions about network protocols.",
                    points: 10, time: 15,
                    questions: [
                        { type: "essay", text: "Why are protocols important in networking? Give an example." },
                        { type: "multiple_choice", text: "Which protocol is used for web browsing?", choices: ["FTP", "DNS", "HTTP/HTTPS", "TCP"], correct: 2 }
                    ]
                },
                quiz: {
                    title: "Protocols Quiz",
                    instructions: "Choose the correct answer.",
                    time: 10, passing: 75,
                    questions: [
                        { text: "TCP/IP stands for?", choices: ["Transfer Control Protocol / IP", "Transmission Control Protocol / Internet Protocol", "Transfer Communication / IP", "None of the above"], correct: 1 },
                        { text: "HTTP is used for?", choices: ["File transfer", "Web browsing", "Email", "DNS lookup"], correct: 1 },
                        { text: "FTP is used for?", choices: ["Web browsing", "Transferring files", "Sending emails", "Video streaming"], correct: 1 },
                        { text: "DNS translates?", choices: ["IP to MAC", "Domain names to IP addresses", "HTTP to HTTPS", "Data packets"], correct: 1 },
                        { text: "Which protocol secures web traffic?", choices: ["HTTP", "FTP", "HTTPS", "DNS"], correct: 2 }
                    ]
                }
            },
            {
                title: "IP Addressing",
                body: `
                    <h4>IP Addresses</h4>
                    <p>An IP address is a unique identifier assigned to each device on a network.</p>
                    <p><b>IPv4:</b> 32-bit address (e.g., 192.168.1.1)</p>
                    <p><b>IPv6:</b> 128-bit address for more devices</p>
                    <p><b>Static IP:</b> Fixed address assigned manually</p>
                    <p><b>Dynamic IP:</b> Assigned automatically by DHCP</p>
                `,
                videos: [], images: [],
                activities: {
                    title: "IP Addressing Activity",
                    instructions: "Answer the following about IP addressing.",
                    points: 10, time: 15,
                    questions: [
                        { type: "essay", text: "What is the difference between IPv4 and IPv6?" },
                        { type: "multiple_choice", text: "IPv4 uses how many bits?", choices: ["64", "128", "32", "16"], correct: 2 }
                    ]
                },
                quiz: {
                    title: "IP Addressing Quiz",
                    instructions: "Choose the best answer.",
                    time: 10, passing: 75,
                    questions: [
                        { text: "An IPv4 address is how many bits?", choices: ["16", "64", "32", "128"], correct: 2 },
                        { text: "What does DHCP do?", choices: ["Assigns static IPs", "Assigns dynamic IPs automatically", "Transfers files", "Browses the web"], correct: 1 },
                        { text: "Example of an IPv4 address?", choices: ["192.168.1.1", "2001:db8::1", "http://example.com", "255.255"], correct: 0 },
                        { text: "IPv6 uses how many bits?", choices: ["32", "64", "128", "256"], correct: 2 },
                        { text: "A static IP is?", choices: ["Assigned automatically", "Fixed and assigned manually", "Only for IPv6", "Temporary"], correct: 1 }
                    ]
                }
            },
            {
                title: "Network Devices",
                body: `
                    <h4>Common Network Devices</h4>
                    <p><b>Router:</b> Connects different networks and directs traffic.</p>
                    <p><b>Switch:</b> Connects devices within the same network.</p>
                    <p><b>Hub:</b> Broadcasts data to all devices.</p>
                    <p><b>Modem:</b> Connects a network to the internet via ISP.</p>
                    <p><b>Access Point:</b> Provides wireless connectivity.</p>
                `,
                videos: [], images: [],
                activities: {
                    title: "Network Devices Activity",
                    instructions: "Identify and describe each network device.",
                    points: 10, time: 20,
                    questions: [
                        { type: "essay", text: "What is the difference between a router and a switch?" },
                        { type: "multiple_choice", text: "Which device connects a home network to the internet?", choices: ["Switch", "Hub", "Router/Modem", "Access Point"], correct: 2 }
                    ]
                },
                quiz: {
                    title: "Network Devices Quiz",
                    instructions: "Choose the best answer.",
                    time: 10, passing: 75,
                    questions: [
                        { text: "A router connects?", choices: ["Devices in same network", "Different networks", "Only wireless devices", "Only wired devices"], correct: 1 },
                        { text: "A hub broadcasts to?", choices: ["Only one device", "All connected devices", "Only the router", "Only wireless devices"], correct: 1 },
                        { text: "An access point provides?", choices: ["Wired connection", "Wireless connectivity", "IP addresses", "Firewall protection"], correct: 1 },
                        { text: "A modem connects to?", choices: ["Switch", "Hub", "ISP / Internet", "Printer"], correct: 2 },
                        { text: "A switch is smarter than a hub because?", choices: ["It is wireless", "It sends data only to the target device", "It has more ports", "It is cheaper"], correct: 1 }
                    ]
                }
            },
            {
                title: "OSI Model",
                body: `
                    <h4>The OSI Model</h4>
                    <p>The OSI model has 7 layers that describe how data is transmitted.</p>
                    <p><b>Layer 1 - Physical:</b> Cables, signals</p>
                    <p><b>Layer 2 - Data Link:</b> MAC addresses</p>
                    <p><b>Layer 3 - Network:</b> IP addressing, routing</p>
                    <p><b>Layer 4 - Transport:</b> TCP/UDP</p>
                    <p><b>Layer 5 - Session:</b> Session management</p>
                    <p><b>Layer 6 - Presentation:</b> Data formatting</p>
                    <p><b>Layer 7 - Application:</b> HTTP, DNS</p>
                `,
                videos: [], images: [],
                activities: {
                    title: "OSI Model Activity",
                    instructions: "Answer questions about the OSI Model.",
                    points: 10, time: 20,
                    questions: [
                        { type: "essay", text: "Why is the OSI model important in networking?" },
                        { type: "multiple_choice", text: "Which layer handles IP addressing?", choices: ["Physical", "Data Link", "Network", "Transport"], correct: 2 }
                    ]
                },
                quiz: {
                    title: "OSI Model Quiz",
                    instructions: "Choose the correct answer.",
                    time: 10, passing: 75,
                    questions: [
                        { text: "How many layers does the OSI model have?", choices: ["4", "5", "6", "7"], correct: 3 },
                        { text: "Layer 1 is the?", choices: ["Application", "Network", "Physical", "Session"], correct: 2 },
                        { text: "TCP/UDP operate at which layer?", choices: ["Network", "Transport", "Session", "Data Link"], correct: 1 },
                        { text: "HTTP operates at which layer?", choices: ["Physical", "Transport", "Network", "Application"], correct: 3 },
                        { text: "MAC addresses are at which layer?", choices: ["Physical", "Data Link", "Network", "Transport"], correct: 1 }
                    ]
                }
            },
            {
                title: "Network Security Basics",
                body: `
                    <h4>Network Security</h4>
                    <p>Network security protects data and resources from unauthorized access.</p>
                    <p><b>Firewall:</b> Monitors and controls traffic based on rules.</p>
                    <p><b>Encryption:</b> Converts data into a secure format.</p>
                    <p><b>Authentication:</b> Verifies the identity of users.</p>
                    <p><b>VPN:</b> Creates a secure tunnel over the internet.</p>
                `,
                videos: [], images: [],
                activities: {
                    title: "Security Activity",
                    instructions: "Answer questions about network security.",
                    points: 10, time: 20,
                    questions: [
                        { type: "essay", text: "What is a firewall and why is it important?" },
                        { type: "multiple_choice", text: "What does a VPN do?", choices: ["Speeds up internet", "Creates a secure tunnel", "Assigns IP addresses", "Connects LANs"], correct: 1 }
                    ]
                },
                quiz: {
                    title: "Security Quiz",
                    instructions: "Choose the best answer.",
                    time: 10, passing: 75,
                    questions: [
                        { text: "A firewall?", choices: ["Speeds up internet", "Monitors and controls traffic", "Assigns IP", "Creates VPN"], correct: 1 },
                        { text: "Encryption?", choices: ["Speeds up data", "Converts data to secure format", "Monitors traffic", "Assigns MAC"], correct: 1 },
                        { text: "VPN stands for?", choices: ["Virtual Private Network", "Very Private Node", "Virtual Protocol Network", "Verified Private Node"], correct: 0 },
                        { text: "Authentication verifies?", choices: ["Data packets", "Network speed", "User identity", "IP addresses"], correct: 2 },
                        { text: "HTTPS is secure because?", choices: ["Uses FTP", "Uses VPN", "Uses Encryption (SSL/TLS)", "Uses DNS"], correct: 2 }
                    ]
                }
            },
            {
                title: "Wireless Networking",
                body: `
                    <h4>Wireless Networks</h4>
                    <p>Wireless networking allows devices to connect without physical cables.</p>
                    <p><b>Wi-Fi:</b> IEEE 802.11 standard for wireless LANs.</p>
                    <p><b>Bluetooth:</b> Short-range wireless communication.</p>
                    <p><b>4G/5G:</b> Mobile wireless networks.</p>
                `,
                videos: [], images: [],
                activities: {
                    title: "Wireless Networking Activity",
                    instructions: "Answer questions about wireless networking.",
                    points: 10, time: 15,
                    questions: [
                        { type: "essay", text: "What are the advantages and disadvantages of wireless networking?" },
                        { type: "multiple_choice", text: "Wi-Fi uses which standard?", choices: ["IEEE 802.3", "IEEE 802.11", "IEEE 802.15", "IEEE 802.16"], correct: 1 }
                    ]
                },
                quiz: {
                    title: "Wireless Networking Quiz",
                    instructions: "Choose the correct answer.",
                    time: 10, passing: 75,
                    questions: [
                        { text: "Wi-Fi uses which IEEE standard?", choices: ["802.3", "802.11", "802.15", "802.16"], correct: 1 },
                        { text: "Bluetooth is used for?", choices: ["Long range", "Short range wireless", "Internet access", "VPN"], correct: 1 },
                        { text: "5G is a type of?", choices: ["Wi-Fi", "Bluetooth", "Mobile wireless network", "Ethernet"], correct: 2 },
                        { text: "An access point extends?", choices: ["Wired network", "Wireless network coverage", "Internet speed", "IP range"], correct: 1 },
                        { text: "WPA2 is related to?", choices: ["Wired security", "Wireless security", "Routing protocol", "DNS"], correct: 1 }
                    ]
                }
            },
            {
                title: "Network Troubleshooting",
                body: `
                    <h4>Troubleshooting Networks</h4>
                    <p>Network troubleshooting involves identifying and resolving network issues.</p>
                    <p><b>ping:</b> Tests connectivity to another device.</p>
                    <p><b>ipconfig/ifconfig:</b> Shows IP configuration.</p>
                    <p><b>traceroute:</b> Traces the path of a packet.</p>
                    <p><b>nslookup:</b> Queries DNS servers.</p>
                `,
                videos: [], images: [],
                activities: {
                    title: "Troubleshooting Activity",
                    instructions: "Answer questions about network troubleshooting.",
                    points: 10, time: 20,
                    questions: [
                        { type: "essay", text: "What steps would you take if you cannot connect to the internet?" },
                        { type: "multiple_choice", text: "The ping command tests?", choices: ["DNS resolution", "Connectivity to another device", "IP configuration", "Routing path"], correct: 1 }
                    ]
                },
                quiz: {
                    title: "Troubleshooting Quiz",
                    instructions: "Choose the best answer.",
                    time: 10, passing: 75,
                    questions: [
                        { text: "ping tests?", choices: ["IP assignment", "Connectivity", "DNS", "Routing"], correct: 1 },
                        { text: "ipconfig shows?", choices: ["Routing table", "IP configuration", "DNS servers", "Active connections"], correct: 1 },
                        { text: "traceroute shows?", choices: ["IP address", "Packet path", "DNS records", "Wireless signal"], correct: 1 },
                        { text: "nslookup queries?", choices: ["IP addresses", "DNS servers", "Routing tables", "MAC addresses"], correct: 1 },
                        { text: "First step when internet is down?", choices: ["Call ISP", "Restart browser", "Check physical connection", "Format PC"], correct: 2 }
                    ]
                }
            }
        ]
    },
    {
        moduleId: "module2",
        moduleTitle: "Network Security",
        lessons: [
            {
                title: "Security Basics",
                body: `<h4>Security Concepts</h4><p>Firewalls, Encryption, Authentication</p>`,
                videos: [], images: [],
                activities: { title: "Security Basics Activity", instructions: "Answer the following.", points: 10, time: 15, questions: [{ type: "essay", text: "What is a firewall and how does it work?" }] },
                quiz: { title: "Security Basics Quiz", instructions: "Choose the correct answer.", time: 10, passing: 75, questions: [{ text: "A firewall protects against?", choices: ["Power outages", "Unauthorized access", "Hardware failure", "DNS errors"], correct: 1 }, { text: "Encryption converts data to?", choices: ["Plain text", "Secure/unreadable format", "HTML", "Binary"], correct: 1 }, { text: "Authentication verifies?", choices: ["Network speed", "User identity", "IP range", "DNS"], correct: 1 }, { text: "A VPN creates?", choices: ["A public tunnel", "A secure tunnel", "A new IP", "A new DNS"], correct: 1 }, { text: "HTTPS uses?", choices: ["HTTP only", "SSL/TLS encryption", "FTP", "DNS"], correct: 1 }] }
            },
            {
                title: "Advanced Security",
                body: `<h4>Advanced Topics</h4><p>VPNs, IDS, IPS</p>`,
                videos: [], images: [],
                activities: { title: "Advanced Security Activity", instructions: "Answer the following.", points: 10, time: 15, questions: [{ type: "essay", text: "What is the difference between IDS and IPS?" }] },
                quiz: { title: "Advanced Security Quiz", instructions: "Choose the correct answer.", time: 10, passing: 75, questions: [{ text: "IDS stands for?", choices: ["Intrusion Detection System", "Internet Data Service", "IP Detection System", "Internal Data Server"], correct: 0 }, { text: "IPS can?", choices: ["Only detect threats", "Detect and prevent threats", "Only prevent", "Only monitor"], correct: 1 }, { text: "VPN stands for?", choices: ["Virtual Private Network", "Very Private Node", "Verified Protocol Network", "Virtual Protocol Node"], correct: 0 }, { text: "A DMZ in networking is?", choices: ["A secure zone", "A semi-trusted zone between public and private", "A VPN type", "A firewall"], correct: 1 }, { text: "Zero-day vulnerability means?", choices: ["Old vulnerability", "Unknown/unpatched vulnerability", "Fixed vulnerability", "Low-risk vulnerability"], correct: 1 }] }
            },
            { title: "Threat Types", body: `<h4>Types of Threats</h4><p>Malware, Phishing, DDoS, Man-in-the-Middle</p>`, videos: [], images: [], activities: { title: "Threat Types Activity", instructions: "Answer the following.", points: 10, time: 15, questions: [{ type: "essay", text: "What is phishing and how can you avoid it?" }] }, quiz: { title: "Threat Types Quiz", instructions: "Choose the correct answer.", time: 10, passing: 75, questions: [{ text: "Phishing is?", choices: ["A hardware attack", "A social engineering attack", "A VPN type", "A firewall bypass"], correct: 1 }, { text: "DDoS stands for?", choices: ["Distributed Denial of Service", "Direct Data on Server", "Dynamic DNS", "Data Delivery Service"], correct: 0 }, { text: "Malware is?", choices: ["Good software", "Malicious software", "A network device", "An IP address"], correct: 1 }, { text: "Man-in-the-middle attack?", choices: ["Destroys hardware", "Intercepts communication between two parties", "DNS queries only", "Firewall rules"], correct: 1 }, { text: "Ransomware?", choices: ["Speeds up PC", "Encrypts files and demands payment", "Protects data", "Monitors network"], correct: 1 }] } },
            { title: "Security Policies", body: `<h4>Security Policies</h4><p>Password policies, Access control, Audit logs</p>`, videos: [], images: [], activities: { title: "Security Policies Activity", instructions: "Answer the following.", points: 10, time: 15, questions: [{ type: "essay", text: "Why are password policies important?" }] }, quiz: { title: "Security Policies Quiz", instructions: "Choose the correct answer.", time: 10, passing: 75, questions: [{ text: "A strong password should?", choices: ["Be short", "Use personal info", "Contain letters, numbers, and symbols", "Be the same everywhere"], correct: 2 }, { text: "Access control limits?", choices: ["Network speed", "Who can access resources", "IP addresses", "DNS queries"], correct: 1 }, { text: "Audit logs track?", choices: ["Network speed", "User activity", "IP assignments", "Hardware failures"], correct: 1 }, { text: "MFA stands for?", choices: ["Multi-Factor Authentication", "Main Firewall Access", "Managed File Archive", "Multiple Fixed Addresses"], correct: 0 }, { text: "Least privilege means?", choices: ["Give all permissions", "Give minimum needed permissions", "Remove all access", "Disable firewall"], correct: 1 }] } },
            { title: "Incident Response", body: `<h4>Incident Response</h4><p>Preparation, Detection, Containment, Recovery</p>`, videos: [], images: [], activities: { title: "Incident Response Activity", instructions: "Answer the following.", points: 10, time: 15, questions: [{ type: "essay", text: "What are the steps of an incident response plan?" }] }, quiz: { title: "Incident Response Quiz", instructions: "Choose the correct answer.", time: 10, passing: 75, questions: [{ text: "First step in incident response?", choices: ["Recovery", "Preparation", "Containment", "Detection"], correct: 1 }, { text: "Containment means?", choices: ["Ignoring the threat", "Limiting the spread of the incident", "Deleting all data", "Restarting the network"], correct: 1 }, { text: "After containment, next step is?", choices: ["Preparation", "Detection", "Eradication", "Reporting"], correct: 2 }, { text: "A CSIRT is?", choices: ["A firewall type", "Computer Security Incident Response Team", "A VPN protocol", "A DNS service"], correct: 1 }, { text: "Forensics involves?", choices: ["Speeding up recovery", "Collecting evidence", "Assigning new IPs", "Updating firewall"], correct: 1 }] } }
        ]
    },
    {
        moduleId: "module3",
        moduleTitle: "Cloud Networking",
        lessons: [
            { title: "Introduction to Cloud", body: `<h4>Cloud Computing</h4><p>Cloud computing delivers computing services over the internet.</p><p><b>IaaS:</b> Infrastructure as a Service</p><p><b>PaaS:</b> Platform as a Service</p><p><b>SaaS:</b> Software as a Service</p>`, videos: [], images: [], activities: { title: "Cloud Intro Activity", instructions: "Answer the following.", points: 10, time: 15, questions: [{ type: "essay", text: "What is the difference between IaaS, PaaS, and SaaS?" }] }, quiz: { title: "Cloud Intro Quiz", instructions: "Choose the correct answer.", time: 10, passing: 75, questions: [{ text: "IaaS provides?", choices: ["Software only", "Infrastructure resources", "Platform tools", "All services"], correct: 1 }, { text: "SaaS is?", choices: ["Hardware rental", "Software delivered over internet", "Platform for developers", "Infrastructure only"], correct: 1 }, { text: "Cloud provider example?", choices: ["Google", "AWS", "Microsoft Azure", "All of the above"], correct: 3 }, { text: "PaaS is used by?", choices: ["End users", "Developers", "ISPs", "Hardware vendors"], correct: 1 }, { text: "Cloud storage means?", choices: ["Local hard drive", "Data stored on remote servers", "USB storage", "RAM"], correct: 1 }] } },
            { title: "Virtual Networks", body: `<h4>Virtual Networking</h4><p>VLANs, SDN, NFV</p>`, videos: [], images: [], activities: { title: "Virtual Networks Activity", instructions: "Answer the following.", points: 10, time: 15, questions: [{ type: "essay", text: "What is a VLAN and why is it used?" }] }, quiz: { title: "Virtual Networks Quiz", instructions: "Choose the correct answer.", time: 10, passing: 75, questions: [{ text: "VLAN stands for?", choices: ["Virtual Local Area Network", "Very Large Area Network", "Virtual Long Access Node", "Verified LAN"], correct: 0 }, { text: "SDN stands for?", choices: ["Software-Defined Networking", "Static Data Node", "Secure DNS Network", "Standard Device Node"], correct: 0 }, { text: "NFV stands for?", choices: ["Network Function Virtualization", "New Firewall Version", "Network File View", "Node Function Verification"], correct: 0 }, { text: "VLANs segment?", choices: ["Hardware", "Network traffic logically", "Physical cables", "IP ranges only"], correct: 1 }, { text: "SDN separates?", choices: ["Hardware and software", "Control plane and data plane", "LAN and WAN", "IPv4 and IPv6"], correct: 1 }] } },
            { title: "Cloud Security", body: `<h4>Cloud Security</h4><p>Shared responsibility, Data protection, Compliance</p>`, videos: [], images: [], activities: { title: "Cloud Security Activity", instructions: "Answer the following.", points: 10, time: 15, questions: [{ type: "essay", text: "What is the shared responsibility model in cloud security?" }] }, quiz: { title: "Cloud Security Quiz", instructions: "Choose the correct answer.", time: 10, passing: 75, questions: [{ text: "Shared responsibility means?", choices: ["Only cloud provider is responsible", "Only customer is responsible", "Both share security responsibilities", "No one is responsible"], correct: 2 }, { text: "Data encryption in cloud?", choices: ["Is optional", "Is not needed", "Protects data at rest and in transit", "Only for on-premise"], correct: 2 }, { text: "Compliance in cloud includes?", choices: ["GDPR, HIPAA, ISO", "Only local laws", "Speed requirements", "Hardware specs"], correct: 0 }, { text: "IAM stands for?", choices: ["Internet Access Management", "Identity and Access Management", "Internal Account Monitor", "IP Address Manager"], correct: 1 }, { text: "Multi-tenancy means?", choices: ["One user per server", "Multiple users share resources", "Only enterprise use", "Private cloud only"], correct: 1 }] } },
            { title: "Load Balancing", body: `<h4>Load Balancing</h4><p>Distributes network traffic across multiple servers.</p>`, videos: [], images: [], activities: { title: "Load Balancing Activity", instructions: "Answer the following.", points: 10, time: 15, questions: [{ type: "essay", text: "What is load balancing and why is it important?" }] }, quiz: { title: "Load Balancing Quiz", instructions: "Choose the correct answer.", time: 10, passing: 75, questions: [{ text: "Load balancing distributes?", choices: ["IP addresses", "Network traffic across servers", "DNS queries", "Firewall rules"], correct: 1 }, { text: "A benefit of load balancing?", choices: ["Slower response", "Higher availability", "More hardware cost", "Less security"], correct: 1 }, { text: "Round-robin is a load balancing?", choices: ["Protocol", "Algorithm", "Device", "Topology"], correct: 1 }, { text: "Load balancers prevent?", choices: ["DNS errors", "Server overload", "IP conflicts", "VPN issues"], correct: 1 }, { text: "CDN helps with?", choices: ["Security only", "Load balancing content delivery", "DNS resolution", "VPN creation"], correct: 1 }] } },
            { title: "Cloud Migration", body: `<h4>Cloud Migration</h4><p>Moving data and apps from on-premise to cloud.</p>`, videos: [], images: [], activities: { title: "Cloud Migration Activity", instructions: "Answer the following.", points: 10, time: 15, questions: [{ type: "essay", text: "What challenges might a company face when migrating to the cloud?" }] }, quiz: { title: "Cloud Migration Quiz", instructions: "Choose the correct answer.", time: 10, passing: 75, questions: [{ text: "Cloud migration moves?", choices: ["Only emails", "Data and apps to cloud", "Hardware to cloud", "Users to new office"], correct: 1 }, { text: "Lift and shift means?", choices: ["Rebuild app for cloud", "Move app as-is to cloud", "Delete on-premise data", "Use hybrid cloud"], correct: 1 }, { text: "Hybrid cloud combines?", choices: ["Public and private cloud", "Two public clouds", "Two private clouds", "LAN and WAN"], correct: 0 }, { text: "Main challenge of cloud migration?", choices: ["Too fast", "Data security and compatibility", "Too cheap", "Too simple"], correct: 1 }, { text: "ROI means?", choices: ["Return on Investment", "Rate of Internet", "Risk of Implementation", "Record of IP"], correct: 0 }] } }
        ]
    },
    {
        moduleId: "module4",
        moduleTitle: "Cloud Networking",
        lessons: [
            { title: "Introduction to Cloud", body: `<h4>Cloud Computing</h4><p>Cloud computing delivers computing services over the internet.</p><p><b>IaaS:</b> Infrastructure as a Service</p><p><b>PaaS:</b> Platform as a Service</p><p><b>SaaS:</b> Software as a Service</p>`, videos: [], images: [], activities: { title: "Cloud Intro Activity", instructions: "Answer the following.", points: 10, time: 15, questions: [{ type: "essay", text: "What is the difference between IaaS, PaaS, and SaaS?" }] }, quiz: { title: "Cloud Intro Quiz", instructions: "Choose the correct answer.", time: 10, passing: 75, questions: [{ text: "IaaS provides?", choices: ["Software only", "Infrastructure resources", "Platform tools", "All services"], correct: 1 }, { text: "SaaS is?", choices: ["Hardware rental", "Software delivered over internet", "Platform for developers", "Infrastructure only"], correct: 1 }, { text: "Cloud provider example?", choices: ["Google", "AWS", "Microsoft Azure", "All of the above"], correct: 3 }, { text: "PaaS is used by?", choices: ["End users", "Developers", "ISPs", "Hardware vendors"], correct: 1 }, { text: "Cloud storage means?", choices: ["Local hard drive", "Data stored on remote servers", "USB storage", "RAM"], correct: 1 }] } },
            { title: "Virtual Networks", body: `<h4>Virtual Networking</h4><p>VLANs, SDN, NFV</p>`, videos: [], images: [], activities: { title: "Virtual Networks Activity", instructions: "Answer the following.", points: 10, time: 15, questions: [{ type: "essay", text: "What is a VLAN and why is it used?" }] }, quiz: { title: "Virtual Networks Quiz", instructions: "Choose the correct answer.", time: 10, passing: 75, questions: [{ text: "VLAN stands for?", choices: ["Virtual Local Area Network", "Very Large Area Network", "Virtual Long Access Node", "Verified LAN"], correct: 0 }, { text: "SDN stands for?", choices: ["Software-Defined Networking", "Static Data Node", "Secure DNS Network", "Standard Device Node"], correct: 0 }, { text: "NFV stands for?", choices: ["Network Function Virtualization", "New Firewall Version", "Network File View", "Node Function Verification"], correct: 0 }, { text: "VLANs segment?", choices: ["Hardware", "Network traffic logically", "Physical cables", "IP ranges only"], correct: 1 }, { text: "SDN separates?", choices: ["Hardware and software", "Control plane and data plane", "LAN and WAN", "IPv4 and IPv6"], correct: 1 }] } },
            { title: "Cloud Security", body: `<h4>Cloud Security</h4><p>Shared responsibility, Data protection, Compliance</p>`, videos: [], images: [], activities: { title: "Cloud Security Activity", instructions: "Answer the following.", points: 10, time: 15, questions: [{ type: "essay", text: "What is the shared responsibility model in cloud security?" }] }, quiz: { title: "Cloud Security Quiz", instructions: "Choose the correct answer.", time: 10, passing: 75, questions: [{ text: "Shared responsibility means?", choices: ["Only cloud provider is responsible", "Only customer is responsible", "Both share security responsibilities", "No one is responsible"], correct: 2 }, { text: "Data encryption in cloud?", choices: ["Is optional", "Is not needed", "Protects data at rest and in transit", "Only for on-premise"], correct: 2 }, { text: "Compliance in cloud includes?", choices: ["GDPR, HIPAA, ISO", "Only local laws", "Speed requirements", "Hardware specs"], correct: 0 }, { text: "IAM stands for?", choices: ["Internet Access Management", "Identity and Access Management", "Internal Account Monitor", "IP Address Manager"], correct: 1 }, { text: "Multi-tenancy means?", choices: ["One user per server", "Multiple users share resources", "Only enterprise use", "Private cloud only"], correct: 1 }] } },
            { title: "Load Balancing", body: `<h4>Load Balancing</h4><p>Distributes network traffic across multiple servers.</p>`, videos: [], images: [], activities: { title: "Load Balancing Activity", instructions: "Answer the following.", points: 10, time: 15, questions: [{ type: "essay", text: "What is load balancing and why is it important?" }] }, quiz: { title: "Load Balancing Quiz", instructions: "Choose the correct answer.", time: 10, passing: 75, questions: [{ text: "Load balancing distributes?", choices: ["IP addresses", "Network traffic across servers", "DNS queries", "Firewall rules"], correct: 1 }, { text: "A benefit of load balancing?", choices: ["Slower response", "Higher availability", "More hardware cost", "Less security"], correct: 1 }, { text: "Round-robin is a load balancing?", choices: ["Protocol", "Algorithm", "Device", "Topology"], correct: 1 }, { text: "Load balancers prevent?", choices: ["DNS errors", "Server overload", "IP conflicts", "VPN issues"], correct: 1 }, { text: "CDN helps with?", choices: ["Security only", "Load balancing content delivery", "DNS resolution", "VPN creation"], correct: 1 }] } },
            { title: "Cloud Migration", body: `<h4>Cloud Migration</h4><p>Moving data and apps from on-premise to cloud.</p>`, videos: [], images: [], activities: { title: "Cloud Migration Activity", instructions: "Answer the following.", points: 10, time: 15, questions: [{ type: "essay", text: "What challenges might a company face when migrating to the cloud?" }] }, quiz: { title: "Cloud Migration Quiz", instructions: "Choose the correct answer.", time: 10, passing: 75, questions: [{ text: "Cloud migration moves?", choices: ["Only emails", "Data and apps to cloud", "Hardware to cloud", "Users to new office"], correct: 1 }, { text: "Lift and shift means?", choices: ["Rebuild app for cloud", "Move app as-is to cloud", "Delete on-premise data", "Use hybrid cloud"], correct: 1 }, { text: "Hybrid cloud combines?", choices: ["Public and private cloud", "Two public clouds", "Two private clouds", "LAN and WAN"], correct: 0 }, { text: "Main challenge of cloud migration?", choices: ["Too fast", "Data security and compatibility", "Too cheap", "Too simple"], correct: 1 }, { text: "ROI means?", choices: ["Return on Investment", "Rate of Internet", "Risk of Implementation", "Record of IP"], correct: 0 }] } }
        ]
    }
];

/* ==========================
   ACTIVE MODULE
========================== */
let currentModuleIndex = modules.findIndex(m => m.moduleId === moduleParam);
if (currentModuleIndex === -1) currentModuleIndex = 0;

let currentLessonIndex = 0;
const savedLesson = localStorage.getItem(`${subject}_${modules[currentModuleIndex].moduleId}_currentLesson`);
if (savedLesson !== null) currentLessonIndex = parseInt(savedLesson);

let currentModule = modules[currentModuleIndex];
let lessons = currentModule.lessons;

/* ==========================
   STATE
========================== */
let activeTab = "lesson";
let quizAnswers = [];
let quizSubmitted = false;
let quizCurrentPage = 0;
let activitySubmitted = false;

// ── NEW: activity pagination state ──────────────────────────────────────────
let activityCurrentPage = 0;
let activityAnswers = {};   // keyed by global question index
const ITEMS_PER_PAGE = 5;  // shared page size for both activity and quiz

/* ==========================
   LOAD LESSON
========================== */
function loadLesson() {
    const lesson = lessons[currentLessonIndex];

    activeTab = "lesson";
    quizAnswers = [];
    quizSubmitted = false;
    quizCurrentPage = 0;
    activitySubmitted = false;

    // reset activity state on each new lesson load
    activityCurrentPage = 0;
    activityAnswers = {};

    document.getElementById("lesson-title").innerText = lesson.title;
    document.getElementById("lesson-count").innerText = `Lesson ${currentLessonIndex + 1} of ${lessons.length}`;
    document.getElementById("page-indicator").innerText = `${currentLessonIndex + 1} / ${lessons.length}`;

    // update tab counts
    document.getElementById("tab-video-count").innerText = lesson.videos ? lesson.videos.length : 0;
    document.getElementById("tab-image-count").innerText = lesson.images ? lesson.images.length : 0;
    document.getElementById("tab-activity-count").innerText = lesson.activities && lesson.activities.questions ? lesson.activities.questions.length : 0;
    document.getElementById("tab-quiz-count").innerText = lesson.quiz && lesson.quiz.questions ? lesson.quiz.questions.length : 0;

    const prevBtn = document.getElementById("prevBtn");
    prevBtn.classList.toggle("disabled", currentLessonIndex === 0);
    prevBtn.style.pointerEvents = currentLessonIndex === 0 ? "none" : "auto";

    buildSidebar();
    switchTab("lesson");
    updateProgress();
}

/* ==========================
   SWITCH TAB
========================== */
function switchTab(tab) {
    activeTab = tab;
    const lesson = lessons[currentLessonIndex];

    document.querySelectorAll(".tab-btn").forEach(b => b.classList.toggle("active-tab", b.dataset.tab === tab));
    document.querySelectorAll(".tab-panel").forEach(p => p.style.display = "none");

    if (tab === "lesson") {
        document.getElementById("panel-lesson").style.display = "block";
        document.getElementById("lesson-body").innerHTML = lesson.body;
    }
    if (tab === "videos") {
        document.getElementById("panel-videos").style.display = "block";
        renderVideos(lesson.videos || []);
    }
    if (tab === "images") {
        document.getElementById("panel-images").style.display = "block";
        renderImages(lesson.images || []);
    }
    if (tab === "activities") {
        document.getElementById("panel-activities").style.display = "block";
        renderActivity(lesson.activities);
    }
    if (tab === "quiz") {
        document.getElementById("panel-quiz").style.display = "block";
        renderQuiz(lesson.quiz);
    }
}

/* ==========================
   RENDER VIDEOS
========================== */
function renderVideos(videos) {
    const c = document.getElementById("videos-list");
    if (!videos || videos.length === 0) { c.innerHTML = `<div class="empty-tab"><i class="fa fa-video"></i><p>No videos for this lesson.</p></div>`; return; }
    c.innerHTML = videos.map(v => `
        <div class="video-card">
            <div class="video-thumb"><iframe src="${v.url}" frameborder="0" allowfullscreen></iframe></div>
            <div class="video-info"><p class="video-title">${v.title}</p><span class="video-meta">${v.type} · ${v.duration}</span></div>
        </div>`).join("");
}

/* ==========================
   RENDER IMAGES
========================== */
function renderImages(images) {
    const c = document.getElementById("images-list");
    if (!images || images.length === 0) { c.innerHTML = `<div class="empty-tab"><i class="fa fa-image"></i><p>No images for this lesson.</p></div>`; return; }
    c.innerHTML = images.map(img => `
        <div class="image-card">
            <img src="${img.url}" alt="${img.title}">
            <div class="image-info"><p class="image-title">${img.title}</p><span class="image-desc">${img.description}</span></div>
        </div>`).join("");
}

/* ==========================
   RENDER ACTIVITY  (now with pagination)
========================== */
function renderActivity(activity) {
    const c = document.getElementById("panel-activities");
    if (!activity || !activity.questions || activity.questions.length === 0) {
        c.innerHTML = `<div class="empty-tab"><i class="fa fa-circle-question"></i><p>No activity for this lesson.</p></div>`;
        return;
    }
    if (activitySubmitted) {
        c.innerHTML = `<div class="activity-success"><div class="success-icon">🎉</div><h4>Activity Submitted!</h4><p>Your answers have been submitted. Your teacher will review and grade your work.</p><span class="success-badge">✅ Submitted for grading</span></div>`;
        return;
    }

    // ── pagination math ──────────────────────────────────────────────────────
    const totalQ = activity.questions.length;
    const totalPages = Math.ceil(totalQ / ITEMS_PER_PAGE);
    const pageStart = activityCurrentPage * ITEMS_PER_PAGE;
    const pageEnd = Math.min(pageStart + ITEMS_PER_PAGE, totalQ);

    // which questions on this page are answered?
    const pageQuestions = activity.questions.slice(pageStart, pageEnd);
    const pageAllAnswered = pageQuestions.every((q, pi) => {
        const qi = pageStart + pi;
        if (q.type === "essay") return (activityAnswers[qi] || "").trim() !== "";
        return activityAnswers[qi] !== undefined;
    });

    // overall answered count
    const totalAnswered = activity.questions.filter((q, i) => {
        if (q.type === "essay") return (activityAnswers[i] || "").trim() !== "";
        return activityAnswers[i] !== undefined;
    }).length;
    const allAnswered = totalAnswered === totalQ;

    // dot indicators
    const dots = activity.questions.map((_, i) => {
        const answered = (activity.questions[i].type === "essay")
            ? (activityAnswers[i] || "").trim() !== ""
            : activityAnswers[i] !== undefined;
        const onPage = Math.floor(i / ITEMS_PER_PAGE) === activityCurrentPage;
        return `<span class="q-dot${answered ? " dot-answered" : ""}${onPage ? " dot-active" : ""}"></span>`;
    }).join("");

    // render questions for this page
    const questionsHTML = pageQuestions.map((q, pi) => {
        const qi = pageStart + pi;
        return `
        <div class="activity-question">
            <p class="aq-num">Question ${qi + 1}</p>
            <p class="aq-text">${q.text}</p>
            ${q.type === "essay"
                ? `<textarea class="activity-answer" data-qi="${qi}" rows="4"
                       placeholder="Type your answer here..."
                       oninput="saveActivityEssay(this)">${activityAnswers[qi] || ""}</textarea>`
                : `<div class="mc-choices">${q.choices.map((ch, ci) => `
                        <label class="mc-label">
                            <input type="radio" name="activity_q${qi}" value="${ci}"
                                data-qi="${qi}"
                                ${activityAnswers[qi] === ci ? "checked" : ""}
                                onchange="saveActivityMC(this)">
                            <span class="mc-letter">${["A", "B", "C", "D"][ci]}</span>
                            <span>${ch}</span>
                        </label>`).join("")}
                   </div>`}
        </div>`;
    }).join("");

    // navigation footer
    let nav = "";
    if (totalPages === 1) {
        // only one page — show submit directly
        nav = `<div class="quiz-nav">
                   <span class="quiz-status">${totalAnswered}/${totalQ} answered</span>
                   ${allAnswered
                ? `<button class="btn-submit-activity" onclick="submitActivity()">Submit Activity</button>`
                : `<button class="btn-submit-activity" disabled>Answer all to submit</button>`}
               </div>`;
    } else if (activityCurrentPage < totalPages - 1) {
        // not the last page
        nav = `<div class="quiz-nav">
                   <span class="quiz-status">${totalAnswered}/${totalQ} answered
                       ${!pageAllAnswered ? " — answer this page to continue" : ""}
                   </span>
                   <div style="display:flex;gap:10px;">
                       ${activityCurrentPage > 0
                ? `<button class="btn-quiz-prev" onclick="activityPrevPage()">‹ Previous</button>`
                : ""}
                       <button class="btn-quiz-next" onclick="activityNextPage()"
                           ${!pageAllAnswered ? "disabled" : ""}>
                           Next ${Math.min(ITEMS_PER_PAGE, totalQ - pageEnd)} Questions ›
                       </button>
                   </div>
               </div>`;
    } else {
        // last page
        nav = `<div class="quiz-nav">
                   <span class="quiz-status">${totalAnswered}/${totalQ} answered</span>
                   <div style="display:flex;gap:10px;">
                       ${activityCurrentPage > 0
                ? `<button class="btn-quiz-prev" onclick="activityPrevPage()">‹ Previous</button>`
                : ""}
                       ${allAnswered
                ? `<button class="btn-submit-activity" onclick="submitActivity()">Submit Activity</button>`
                : `<button class="btn-submit-activity" disabled>Answer all to submit</button>`}
                   </div>
               </div>`;
    }

    c.innerHTML = `
        <div class="activity-intro">
            <div class="activity-intro-icon">📝</div>
            <div>
                <p class="activity-intro-title">${activity.title}</p>
                <p class="activity-intro-desc">${activity.instructions}</p>
                <div class="activity-meta-pills">
                    <span class="meta-pill pill-purple">${totalQ} Questions</span>
                    <span class="meta-pill pill-green">${activity.points} pts</span>
                    <span class="meta-pill pill-gray">⏱ ${activity.time} min</span>
                </div>
            </div>
        </div>

        <div class="quiz-page-indicator">
            <span class="quiz-page-label">
                Page ${activityCurrentPage + 1} of ${totalPages} · Questions ${pageStart + 1}–${pageEnd}
            </span>
            <div class="quiz-dots">${dots}</div>
        </div>

        <div>${questionsHTML}</div>
        ${nav}`;
}

/* ── Activity answer savers (preserve state + update footer live) ── */
function saveActivityEssay(el) {
    activityAnswers[parseInt(el.dataset.qi)] = el.value;
    updateActivityFooter();
}

function saveActivityMC(el) {
    activityAnswers[parseInt(el.dataset.qi)] = parseInt(el.value);
    updateActivityFooter();
}

/**
 * Patches ONLY the footer/nav area and dot indicators after each answer,
 * so the textarea never loses focus and typed content is preserved.
 */
function updateActivityFooter() {
    const activity = lessons[currentLessonIndex].activities;
    if (!activity || !activity.questions) return;

    const totalQ = activity.questions.length;
    const totalPages = Math.ceil(totalQ / ITEMS_PER_PAGE);
    const pageStart = activityCurrentPage * ITEMS_PER_PAGE;
    const pageEnd = Math.min(pageStart + ITEMS_PER_PAGE, totalQ);

    // recalculate answered counts
    const totalAnswered = activity.questions.filter((q, i) => {
        if (q.type === "essay") return (activityAnswers[i] || "").trim() !== "";
        return activityAnswers[i] !== undefined;
    }).length;

    const pageAllAnswered = activity.questions.slice(pageStart, pageEnd).every((q, pi) => {
        const qi = pageStart + pi;
        if (q.type === "essay") return (activityAnswers[qi] || "").trim() !== "";
        return activityAnswers[qi] !== undefined;
    });

    const allAnswered = totalAnswered === totalQ;

    // ── update dot indicators ────────────────────────────────────────────────
    const dotsContainer = document.querySelector("#panel-activities .quiz-dots");
    if (dotsContainer) {
        dotsContainer.innerHTML = activity.questions.map((q, i) => {
            const answered = (q.type === "essay")
                ? (activityAnswers[i] || "").trim() !== ""
                : activityAnswers[i] !== undefined;
            const onPage = Math.floor(i / ITEMS_PER_PAGE) === activityCurrentPage;
            return `<span class="q-dot${answered ? " dot-answered" : ""}${onPage ? " dot-active" : ""}"></span>`;
        }).join("");
    }

    // ── update the footer nav without touching question cards ────────────────
    const navContainer = document.querySelector(
        "#panel-activities .quiz-nav, #panel-activities .activity-footer"
    );
    if (!navContainer) return;

    if (totalPages === 1) {
        navContainer.innerHTML = `
            <span class="quiz-status">${totalAnswered}/${totalQ} answered</span>
            ${allAnswered
                ? `<button class="btn-submit-activity" onclick="submitActivity()">Submit Activity</button>`
                : `<button class="btn-submit-activity" disabled>Answer all to submit</button>`}`;
    } else if (activityCurrentPage < totalPages - 1) {
        navContainer.innerHTML = `
            <span class="quiz-status">${totalAnswered}/${totalQ} answered
                ${!pageAllAnswered ? " — answer this page to continue" : ""}
            </span>
            <div style="display:flex;gap:10px;">
                ${activityCurrentPage > 0
                ? `<button class="btn-quiz-prev" onclick="activityPrevPage()">‹ Previous</button>`
                : ""}
                <button class="btn-quiz-next" onclick="activityNextPage()"
                    ${!pageAllAnswered ? "disabled" : ""}>
                    Next ${Math.min(ITEMS_PER_PAGE, totalQ - pageEnd)} Questions ›
                </button>
            </div>`;
    } else {
        navContainer.innerHTML = `
            <span class="quiz-status">${totalAnswered}/${totalQ} answered</span>
            <div style="display:flex;gap:10px;">
                ${activityCurrentPage > 0
                ? `<button class="btn-quiz-prev" onclick="activityPrevPage()">‹ Previous</button>`
                : ""}
                ${allAnswered
                ? `<button class="btn-submit-activity" onclick="submitActivity()">Submit Activity</button>`
                : `<button class="btn-submit-activity" disabled>Answer all to submit</button>`}
            </div>`;
    }
}

/* ── Activity pagination controls ── */
function activityNextPage() {
    const activity = lessons[currentLessonIndex].activities;
    const pageStart = activityCurrentPage * ITEMS_PER_PAGE;
    const pageEnd = Math.min(pageStart + ITEMS_PER_PAGE, activity.questions.length);
    const pageQuestions = activity.questions.slice(pageStart, pageEnd);

    const pageAllAnswered = pageQuestions.every((q, pi) => {
        const qi = pageStart + pi;
        if (q.type === "essay") return (activityAnswers[qi] || "").trim() !== "";
        return activityAnswers[qi] !== undefined;
    });

    if (!pageAllAnswered) return;
    activityCurrentPage++;
    renderActivity(activity);
}

function activityPrevPage() {
    if (activityCurrentPage > 0) {
        activityCurrentPage--;
        renderActivity(lessons[currentLessonIndex].activities);
    }
}

/* ── Submit (now reads from activityAnswers, not the DOM) ── */
function submitActivity() {
    const activity = lessons[currentLessonIndex].activities;
    const allAnswered = activity.questions.every((q, i) => {
        if (q.type === "essay") return (activityAnswers[i] || "").trim() !== "";
        return activityAnswers[i] !== undefined;
    });
    if (!allAnswered) { alert("Please answer all questions before submitting."); return; }
    activitySubmitted = true;
    renderActivity(activity);
}

/* ==========================
   RENDER QUIZ
========================== */
function renderQuiz(quiz) {
    const c = document.getElementById("panel-quiz");
    if (!quiz || !quiz.questions || quiz.questions.length === 0) {
        c.innerHTML = `<div class="empty-tab"><i class="fa fa-pen-to-square"></i><p>No quiz for this lesson.</p></div>`;
        return;
    }
    rebuildQuizUI(quiz);
}

function rebuildQuizUI(quiz) {
    const c = document.getElementById("panel-quiz");
    const totalQ = quiz.questions.length;
    const perPage = ITEMS_PER_PAGE;
    const totalPages = Math.ceil(totalQ / perPage);
    const pageStart = quizCurrentPage * perPage;
    const pageEnd = Math.min(pageStart + perPage, totalQ);

    // ── FIX: gate by CURRENT page's questions, not always the first 5 ──────
    const curPageAllAnswered = quiz.questions
        .slice(pageStart, pageEnd)
        .every((_, pi) => quizAnswers[pageStart + pi] !== undefined);

    const allDone = quiz.questions.every((_, i) => quizAnswers[i] !== undefined);
    const answered = quizAnswers.filter(a => a !== undefined).length;

    const dots = quiz.questions.map((_, i) => {
        const onPage = Math.floor(i / perPage) === quizCurrentPage;
        return `<span class="q-dot${quizAnswers[i] !== undefined ? " dot-answered" : ""}${onPage ? " dot-active" : ""}"></span>`;
    }).join("");

    const qs = quiz.questions.slice(pageStart, pageEnd).map((q, pi) => {
        const qi = pageStart + pi;
        return `<div class="q-card">
            <p class="q-number">Question ${qi + 1}</p>
            <p class="q-text">${q.text}</p>
            <div class="q-choices">${q.choices.map((ch, ci) => {
            let cls = "q-choice";
            if (quizSubmitted) {
                if (ci === q.correct) cls += " correct";
                else if (quizAnswers[qi] === ci && ci !== q.correct) cls += " wrong";
            } else if (quizAnswers[qi] === ci) cls += " selected";
            return `<div class="${cls}" onclick="selectQuizChoice(this,${qi},${ci})">
                    <span class="choice-letter">${["A", "B", "C", "D"][ci]}</span><span>${ch}</span>
                </div>`;
        }).join("")}</div>
        </div>`;
    }).join("");

    let nav = "";
    if (quizSubmitted) {
        const correct = quiz.questions.filter((q, i) => quizAnswers[i] === q.correct).length;
        const pct = Math.round(correct / totalQ * 100);
        nav = `<div class="quiz-nav">
                   <span class="quiz-status" style="color:${pct >= quiz.passing ? '#10b981' : '#ef4444'}">
                       ${pct >= quiz.passing ? '✅ You passed!' : '❌ Below passing score.'}
                   </span>
                   <button class="btn-retry-quiz" onclick="retryQuiz()">↺ Try Again</button>
               </div>`;
    } else if (quizCurrentPage < totalPages - 1) {
        // not the last page
        nav = `<div class="quiz-nav">
                   <span class="quiz-status">${answered}/${totalQ} answered${!curPageAllAnswered ? " — answer all to continue" : ""}</span>
                   <div style="display:flex;gap:10px;">
                       ${quizCurrentPage > 0 ? `<button class="btn-quiz-prev" onclick="quizPrevPage()">‹ Previous</button>` : ""}
                       <button class="btn-quiz-next" onclick="quizNextPage()" ${!curPageAllAnswered ? "disabled" : ""}>
                           Next ${Math.min(perPage, totalQ - pageEnd)} Questions ›
                       </button>
                   </div>
               </div>`;
    } else {
        // last page
        nav = `<div class="quiz-nav">
                   <span class="quiz-status">${answered}/${totalQ} answered</span>
                   <div style="display:flex;gap:10px;">
                       ${quizCurrentPage > 0 ? `<button class="btn-quiz-prev" onclick="quizPrevPage()">‹ Previous</button>` : ""}
                       ${allDone ? `<button class="btn-submit-quiz" onclick="submitQuiz()">Submit Quiz</button>` : ""}
                   </div>
               </div>`;
    }

    const result = quizSubmitted ? (() => {
        const correct = quiz.questions.filter((q, i) => quizAnswers[i] === q.correct).length;
        const pct = Math.round(correct / totalQ * 100);
        const passed = pct >= quiz.passing;
        return `<div class="quiz-result">
            <div class="result-score">${correct}/${totalQ}</div>
            <p class="result-label">You answered ${correct} out of ${totalQ} correctly (${pct}%).</p>
            <span class="result-badge ${passed ? "badge-pass" : "badge-fail"}">${passed ? "🎉 Passed!" : "😞 Failed — review and try again."}</span>
        </div>`;
    })() : "";

    c.innerHTML = `
        <div class="quiz-intro"><div class="quiz-intro-icon">📋</div><div>
            <p class="quiz-intro-title">${quiz.title}</p>
            <p class="quiz-intro-desc">${quiz.instructions}</p>
            <div class="activity-meta-pills">
                <span class="meta-pill pill-purple">📋 ${totalQ} Questions</span>
                <span class="meta-pill pill-green">⏱ ${quiz.time} min</span>
                <span class="meta-pill pill-red">🎯 Passing: ${quiz.passing}%</span>
            </div>
        </div></div>
        ${result}
        <div class="quiz-page-indicator">
            <span class="quiz-page-label">Page ${quizCurrentPage + 1} of ${totalPages} · Questions ${pageStart + 1}–${pageEnd}</span>
            <div class="quiz-dots">${dots}</div>
        </div>
        <div>${qs}</div>${nav}`;
}

function selectQuizChoice(el, qi, ci) {
    if (quizSubmitted) return;
    quizAnswers[qi] = ci;
    rebuildQuizUI(lessons[currentLessonIndex].quiz);
}

function quizNextPage() {
    const quiz = lessons[currentLessonIndex].quiz;
    const pageStart = quizCurrentPage * ITEMS_PER_PAGE;
    const pageEnd = Math.min(pageStart + ITEMS_PER_PAGE, quiz.questions.length);

    // gate: every question on the CURRENT page must be answered
    const curPageAllAnswered = quiz.questions
        .slice(pageStart, pageEnd)
        .every((_, pi) => quizAnswers[pageStart + pi] !== undefined);

    if (!curPageAllAnswered) return;
    quizCurrentPage++;
    rebuildQuizUI(quiz);
}

function quizPrevPage() {
    if (quizCurrentPage > 0) { quizCurrentPage--; rebuildQuizUI(lessons[currentLessonIndex].quiz); }
}

function submitQuiz() {
    if (!lessons[currentLessonIndex].quiz.questions.every((_, i) => quizAnswers[i] !== undefined)) return;
    quizSubmitted = true;
    rebuildQuizUI(lessons[currentLessonIndex].quiz);
}

function retryQuiz() {
    quizAnswers = []; quizSubmitted = false; quizCurrentPage = 0;
    rebuildQuizUI(lessons[currentLessonIndex].quiz);
}

/* ==========================
   UPDATE PROGRESS
========================== */
function updateProgress() {
    const completedLessons = currentLessonIndex + 1;
    const totalLessons = lessons.length;
    const percent = Math.round((completedLessons / totalLessons) * 100);

    const progressBar = document.querySelector(".progress-lesson");
    const progressPercent = document.querySelector(".progress-title span");
    if (progressBar) progressBar.style.width = percent + "%";
    if (progressPercent) progressPercent.innerText = percent + "%";

    localStorage.setItem(`${subject}_${currentModule.moduleId}_completedLessons`, completedLessons);
    localStorage.setItem(`${subject}_${currentModule.moduleId}_totalLessons`, totalLessons);
    localStorage.setItem(`${subject}_${currentModule.moduleId}_lessonPercent`, percent);
    localStorage.setItem(`${subject}_${currentModule.moduleId}_currentLesson`, currentLessonIndex);

    updateModuleCards();
}

/* ==========================
   UPDATE MODULE CARDS
========================== */
function updateModuleCards() {
    document.querySelectorAll(".module-progress").forEach(moduleCard => {
        const moduleId = moduleCard.dataset.moduleId;
        const completed = localStorage.getItem(`${subject}_${moduleId}_completedLessons`) || 0;
        const total = localStorage.getItem(`${subject}_${moduleId}_totalLessons`) || 0;
        const percent = localStorage.getItem(`${subject}_${moduleId}_lessonPercent`) || 0;
        const lt = moduleCard.querySelector(".lessonText");
        const lp = moduleCard.querySelector(".lessonPercent");
        const pb = moduleCard.querySelector(".progress");
        if (lt) lt.innerText = `${completed} of ${total} lessons`;
        if (lp) lp.innerText = `${percent}%`;
        if (pb) pb.style.width = percent + "%";
    });
}

/* ==========================
   BUILD SIDEBAR
========================== */
function buildSidebar() {
    const ul = document.querySelector(".sidebar-menu ul");
    if (!ul) return;
    ul.innerHTML = lessons.map((l, i) => `
        <li class="${i === currentLessonIndex ? "active-lesson" : ""}">
            <a href="#" onclick="jumpToLesson(${i}); return false;">
                <i class="fa ${i < currentLessonIndex ? "fa-check" : "fa-circle"} lesson-icon-status"></i>
                <span>${l.title}</span>
            </a>
        </li>`).join("");
}

function jumpToLesson(index) {
    currentLessonIndex = index;
    loadLesson();
}

/* ==========================
   PAGINATION + INIT
   DOMContentLoaded ensures PHP-included HTML exists before JS runs
========================== */
document.addEventListener("DOMContentLoaded", function () {

    document.getElementById("prevBtn").addEventListener("click", e => {
        e.preventDefault();
        if (currentLessonIndex > 0) { currentLessonIndex--; loadLesson(); }
    });

    document.getElementById("nextBtn").addEventListener("click", e => {
        e.preventDefault();
        if (currentLessonIndex < lessons.length - 1) { currentLessonIndex++; loadLesson(); }
    });

    loadLesson();
    updateModuleCards();
});