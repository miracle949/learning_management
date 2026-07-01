<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landingpage</title>
    <link rel="stylesheet" href="../css_folder/landingpage.css">

    <!-- bootstrap link -->
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>

    <div class="container-fluid p-0">

        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample"
            aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasExampleLabel">Offcanvas</h5>
                <!-- <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button> -->
            </div>
            <div class="offcanvas-body">
                <div>
                    Some text as placeholder. In real life you can have the elements you have chosen. Like, text,
                    images, lists, etc.
                </div>
                <!-- <div class="dropdown mt-3">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Dropdown button
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                    </ul>
                </div> -->

                <a href="/learning_management/public/?url=login">Login</a>
            </div>
        </div>

        <nav>
            <div class="nav-logo">
                <!-- <img src="../images/login-logo2.jpg" alt=""> -->
                <!-- <img src="../images/ilearn-logo4.png" alt=""> -->
                <!-- <div class="logo-icon">
                    <i class="fa-solid fa-lightbulb"></i>
                </div>
                <div class="logo-text">
                    <p><b>i</b>Learn</p>
                </div> -->
                <!-- <img src="../images/iLearn-7.png" alt=""> -->
                <!-- <div class="img-border"></div> -->
                <img src="../images/logo1.png" alt="">
                <h3>SHS Strand</h3>
            </div>

            <div class="nav-list">
                <ul class="m-0 p-0">
                    <!-- <li>
                        <a href="#homepage">Home</a>
                    </li> -->
                    <li>
                        <a href="#home">Home</a>
                    </li>
                    <li>
                        <a href="#about">About</a>
                    </li>
                    <li>
                        <a href="#features">Strands</a>
                    </li>
                    <li>
                        <a href="#skills-training">Tracks</a>
                    </li>
                    <li>
                        <a href="#how-it-works">How it Works</a>
                    </li>
                    <li>
                        <a href="#stories">Stories</a>
                    </li>
                </ul>
            </div>

            <div class="nav-acc">
                <a href="/learning_management/public/?url=login">Get Started <i class="fa fa-arrow-right"></i></a>
            </div>

            <div class="nav-menu">
                <button type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample"
                    aria-controls="offcanvasExample"><i class="fa fa-bars"></i></button>
            </div>
        </nav>

        <div class="main-hero">
            <div class="hero">
                <div class="hero-text">
                    <div class="hero-content">
                        <div class="hero-eyebrow">Academic & Career Pathways</div>
                        <h1 class="hero-title">
                            Choose the Path That, <b>Matches Your Passion</b>
                        </h1>
                        <p class="hero-sub">
                            Every great journey starts with the right direction. Discover the strand that best fits your
                            passion, talents, and career aspirations. Explore opportunities that will develop your
                            skills,
                            unlock your potential, and prepare you for a successful future.
                        </p>
                        <div class="hero-cta-row">
                            <a href="#about" class="btn-primary">Explore the Strand</a>
                            <a href="#modules" class="btn-secondary">View Modules</a>
                        </div>
                    </div>

                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="stat-number">NC II</div>
                            <div class="stat-label">Tesda Certification</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">160+</div>
                            <div class="stat-label">Training Hours</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">1,000+</div>
                            <div class="stat-label">Job Openings/Year</div>
                        </div>
                    </div>
                </div>
                <div class="hero-img">
                    <img src="../images/5.png" alt="">
                    <img src="../images/6.png" alt="">
                    <img src="../images/7.png" alt="">

                    <div class="hero-badge hero-badge-top">
                        <div class="hero-badge-icon"><i class="fa fa-star"></i></div>
                        <div class="hero-badge-text">
                            <div class="hero-badge-title">NC II Ready</div>
                            <div class="hero-badge-sub">TESDA Certified Track</div>
                        </div>
                    </div>

                    <div class="hero-badge hero-badge-bottom">
                        <div class="hero-badge-num">98%</div>
                        <div class="hero-badge-label">Pass Rate</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FLOATING STRAND CARDS -->
        <div class="strands-float-wrap">
            <div class="strands-grid">
                <div class="strand-card accent-blue">
                    <img class="strand-card-img"
                        src="https://images.unsplash.com/photo-1551434678-e076c223a692?w=600&q=80" alt="ICT Strand">
                    <div class="strand-card-body">
                        <div class="strand-card-eyebrow">TVL · ICT</div>
                        <div class="strand-card-title">Electrical & Electronics Technology</div>
                        <div class="strand-card-desc">Pairs naturally with CSS — where CSS builds and repairs the
                            machine,
                            ICT focuses on the programs and systems that run on it.</div>
                        <a href="#" class="strand-card-btn">View Strand →</a>
                    </div>
                </div>

                <div class="strand-card accent-gold">
                    <img class="strand-card-img"
                        src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=600&q=80"
                        alt="Electronics Strand">
                    <div class="strand-card-body">
                        <div class="strand-card-eyebrow">TVL · Electronics</div>
                        <div class="strand-card-title">Electrical & Electronics Technology</div>
                        <div class="strand-card-desc">Shares core competencies with CSS in circuitry, soldering, and
                            component-level troubleshooting.</div>
                        <a href="#" class="strand-card-btn">View Strand →</a>
                    </div>
                </div>

                <div class="strand-card accent-purple">
                    <img class="strand-card-img"
                        src="https://images.unsplash.com/photo-1581092160562-40aa08e78837?w=600&q=80"
                        alt="Industrial Arts Strand">
                    <div class="strand-card-body">
                        <div class="strand-card-eyebrow">TVL · Industrial Arts</div>
                        <div class="strand-card-title">Industrial Arts & Technical Drafting</div>
                        <div class="strand-card-desc">Teaches the technical drawing and fabrication skills CSS students
                            use
                            when documenting layouts and server room setups.</div>
                        <a href="#" class="strand-card-btn">View Strand →</a>
                    </div>
                </div>
            </div>
            <div class="strands-spacer"></div>
        </div>

        <section class="section1" id="about">

            <div class="about-parent">
                <div class="about-text reveal">
                    <div class="section-icon">
                        Why it matters
                    </div>

                    <h2>Your Strand Shapes <b>Your Next Step</b></h2>

                    <p>Choosing a Senior High School strand is one of the biggest decisions before college or work.
                        Every track — Academic, Technical-Vocational-Livelihood, Sports, and Arts & Design — leads to a
                        different mix of subjects, skills, and opportunities after graduation. <br> <br>

                        This page brings every option together so you can compare what each strand teaches, the tools
                        and equipment you'll actually use, and the careers or college courses each one prepares you for
                        — before you have to choose.</p>

                    <div class="tag-row">
                        <div class="tag tag-cyan">Academic Track</div>
                        <div class="tag tag-cyan">TVL Track</div>
                        <div class="tag tag-purple">Sports Track</div>
                        <div class="tag tag-purple">Arts & Design</div>
                        <div class="tag tag-gold">Deped K-12</div>
                        <div class="tag tag-gold">Career Guidance</div>
                    </div>

                    <div class="inside-parent">
                        <div class="inside">
                            <div class="inside-icon">
                                <i class="fa fa-check"></i>
                            </div>
                            <span>Assemble, disassemble, and troubleshoot computer hardware</span>
                        </div>

                        <div class="inside">
                            <div class="inside-icon">
                                <i class="fa fa-check"></i>
                            </div>
                            <span>Install and configure operating systems (Windows, Linux)</span>
                        </div>

                        <div class="inside">
                            <div class="inside-icon">
                                <i class="fa fa-check"></i>
                            </div>
                            <span>Set up and manage Local Area Networks (LAN/WAN)</span>
                        </div>

                        <div class="inside">
                            <div class="inside-icon">
                                <i class="fa fa-check"></i>
                            </div>
                            <span>Perform preventive maintenance and system diagnostics</span>
                        </div>

                        <div class="inside">
                            <div class="inside-icon">
                                <i class="fa fa-check"></i>
                            </div>
                            <span>Configure peripherals, drivers, and system software</span>
                        </div>

                        <div class="inside">
                            <div class="inside-icon">
                                <i class="fa fa-check"></i>
                            </div>
                            <span>Earn your TESDA National Certificate II (NC II)</span>
                        </div>
                    </div>
                </div>

                <div class="about-image reveal reveal-delay-2">
                    <iframe width="560" height="380" src="https://www.youtube.com/embed/ML_hMpuXTHk?si=fdMLQ3Ms6_0x2CCv"
                        title="YouTube video player" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
            </div>


        </section>

        <div class="trust">
            <span class="trust-label reveal">Recognized by</span>
            <div class="trust-logos">
                <span class="reveal reveal-delay-1">DepEd</span>
                <span class="reveal reveal-delay-2">TESDA</span>
                <span class="reveal reveal-delay-3">CHED</span>
                <span class="reveal reveal-delay-4">EdTech PH</span>
                <span class="reveal reveal-delay-1">YoungMinds Award</span>
            </div>
        </div>

        <section class="section2" id="features">
            <div class="learn-own-parents">

                <div class="learn-text reveal">
                    <div class="student-icon">
                        All Strands
                    </div>
                    <h2>Explore Every Strand</h2>

                    <p>A quick look at the strands offered under each Senior High School track, so you can see the full
                        picture before narrowing things down..</p>
                </div>

                <div class="learn-body">
                    <div class="card-features reveal reveal-delay-1">
                        <div class="card-features-header">
                            <img class="module-img" src="../images/css.jpg" alt="">
                        </div>
                        <div class="card-features-body">
                            <div class="module-id">TVL Track</div>

                            <h5>CSS - Computer System Servicing</h5>

                            <p>Hands-on training in PC assembly, OS installation, networking, and hardware repair,
                                leading to a TESDA NC II certificate.</p>

                        </div>
                    </div>

                    <div class="card-features reveal reveal-delay-1">
                        <div class="card-features-header">
                            <img class="module-img" src="../images/stem.jpg" alt="">
                        </div>
                        <div class="card-features-body">
                            <div class="module-id">Academic Track</div>

                            <h5>STEM - Science, Tech, Engineering & Math</h5>

                            <p>Heavy on math and lab sciences. Built for students aiming at engineering, medicine, IT,
                                or pure science degrees.</p>

                        </div>
                    </div>

                    <div class="card-features reveal reveal-delay-2">
                        <div class="card-features-header">
                            <img class="module-img" src="../images/abm.jpg" alt="">
                        </div>

                        <div class="card-features-body">
                            <div class="module-id">Academic Track</div>

                            <h5>ABM — Accountancy, Business & Management</h5>

                            <p>Covers finance, marketing, and entrepreneurship — a direct path into business,
                                accountancy, or management degrees.</p>
                        </div>
                    </div>

                    <div class="card-features reveal reveal-delay-3">
                        <div class="card-features-header">
                            <img class="module-img" src="../images/humms.jpg" alt="">
                        </div>

                        <div class="card-features-body">
                            <div class="module-id">Academic Track</div>

                            <h5>HUMSS — Humanities & Social Sciences</h5>

                            <p>Focuses on communication, law, and social issues — ideal for future lawyers, teachers,
                                writers, and public servants.</p>

                        </div>
                    </div>

                    <div class="card-features reveal reveal-delay-4">
                        <div class="card-features-header">
                            <img class="module-img" src="../images/cookery.jpg" alt="">
                        </div>

                        <div class="card-features-body">
                            <div class="module-id">TVL Track</div>

                            <h5>CBF - Cookery Bread & Pastries Food & Beverage Services</h5>

                            <p>Develop skills in cooking, baking, and food service, preparing students for careers in
                                restaurants, hotels, and the hospitality industry.</p>

                        </div>
                    </div>

                    <div class="card-features reveal reveal-delay-4">
                        <div class="card-features-header">
                            <img class="module-img" src="../images/beauty.avif" alt="">
                        </div>

                        <div class="card-features-body">
                            <div class="module-id">TVL Track</div>

                            <h5>BHW - Beauty Care Hair Dressing Wellness Massage</h5>

                            <p>Learn beauty care, hairstyling, and wellness massage, leading to careers in salons, spas,
                                and the beauty industry.</p>

                        </div>
                    </div>

                    <div class="card-features reveal reveal-delay-4">
                        <div class="card-features-header">
                            <img class="module-img" src="../images/epas.avif" alt="">
                        </div>

                        <div class="card-features-body">
                            <div class="module-id">TVL Track</div>

                            <h5>EPAS - Electronic Products Assembly & Servicing</h5>

                            <p>Gain hands-on experience in assembling, testing, and repairing electronic devices for
                                careers in electronics and technical servicing.</p>

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- <section class="section4" id="skills-training">
            <div class="skills-training-parent">
                <div class="ready-parents reveal">
                    <div class="ready-icon">
                        Training Laboratory
                    </div>
                    <h2>Hands-On CSS Skills Lab</h2>

                    <p>Students actively exploring computer hardware components during a CSS classroom session, learning
                        to
                        identify parts such as the motherboard, RAM slots, expansion cards, and peripheral connectors
                        under
                        teacher guidance.
                    </p>
                </div>

                <div class="ready-body">
                    <div class="ready-card reveal reveal-delay-1">
                        <div class="image-ready">
                            <img src="../images/preparing-css.jpg" alt="">
                        </div>
                        <div class="image-text">
                            <h5>System Unit Assembly & Disassembly</h5>

                            <p>"Students actively participating in a CSS classroom session, exploring computer hardware
                                components and learning the fundamentals of system unit identification. Under the
                                guidance
                                of their teacher, they examine each part carefully — identifying the motherboard, RAM
                                slots,
                                expansion cards, and peripheral connectors. This foundational activity builds the
                                recognition skills needed before performing actual assembly and disassembly tasks in the
                                TESDA NC II assessment."</p>
                        </div>
                    </div>

                    <div class="ready-card reveal reveal-delay-2">
                        <div class="image-text">
                            <h5>Network Cable Crimping (RJ45)</h5>

                            <p>"Students practicing hands-on network cable crimping using RJ45 connectors and crimping
                                tools
                                — a core skill required in the TESDA NC II networking competency. Working in groups,
                                they
                                carefully arrange the eight copper wires following the T568B wiring standard before
                                inserting them into the RJ45 connector and applying pressure with the crimping tool.
                                Each
                                finished cable is then tested using a LAN tester to verify that all wire pairs are
                                correctly
                                connected and the cable is ready for use in a live network setup."</p>
                        </div>
                        <div class="image-ready">
                            <img src="../images/crimping-1.jpg" alt="">
                        </div>
                    </div>

                    <div class="ready-card reveal reveal-delay-3">
                        <div class="image-ready">
                            <img src="../images/demo-3.jpg" alt="">
                        </div>
                        <div class="image-text">
                            <h5>Supervised Hardware Installation</h5>

                            <p>"A student performs system unit assembly under close teacher supervision, practicing the
                                correct installation of internal components including the power supply unit,
                                motherboard,
                                and drive connections. The activity emphasizes proper handling techniques — such as
                                grounding yourself before touching components, using the correct screwdriver, and
                                following
                                the right sequence of installation. Mastering this task is essential for passing the
                                hardware assembly portion of the TESDA CSS NC II practical examination."</p>
                        </div>
                    </div>
                </div>
            </div>
        </section> -->


        <!-- TRACKS COMPARISON -->
        <section id="components">
            <div class="components-parent">
                <div class="components-header">
                    <div class="section-label">Compare Tracks</div>
                    <div class="section-title">What Each Track Focuses On</div>
                    <p class="section-desc">Every strand belongs to one of four major tracks. Here's what sets each
                        track
                        apart
                        — the subjects, skills, and overall focus.</p>
                </div>

                <div class="tab-buttons">
                    <button class="tab-btn active" onclick="switchTab('hardware', this)">Academic Track</button>
                    <button class="tab-btn" onclick="switchTab('software', this)">TVL Track</button>
                    <button class="tab-btn" onclick="switchTab('network', this)">Sports &amp; Arts Tracks</button>
                </div>

                <!-- ACADEMIC TAB -->
                <div class="tab-pane active" id="tab-hardware">
                    <div class="parts-grid">
                        <div class="part-card">
                            <img class="part-img"
                                src="https://images.unsplash.com/photo-1532094349884-543bc11b234d?w=400&q=80"
                                alt="Laboratory equipment">
                            <div class="part-name">STEM</div>
                            <div class="part-desc">Advanced math, physics, chemistry, and biology for students aiming at
                                engineering or medical degrees.</div>
                        </div>
                        <div class="part-card">
                            <img class="part-img"
                                src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=400&q=80"
                                alt="Business charts">
                            <div class="part-name">ABM</div>
                            <div class="part-desc">Business math, financial statements, and entrepreneurship for future
                                managers and accountants.</div>
                        </div>
                        <div class="part-card">
                            <img class="part-img"
                                src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?w=400&q=80"
                                alt="Group discussion">
                            <div class="part-name">HUMSS</div>
                            <div class="part-desc">Philosophy, social science, and creative writing for future lawyers,
                                teachers, and writers.</div>
                        </div>
                        <div class="part-card">
                            <img class="part-img"
                                src="https://images.unsplash.com/photo-1551836022-d5f88ed5a5f9?w=400&q=80"
                                alt="General academic studies">
                            <div class="part-name">General Academic Strand</div>
                            <div class="part-desc">A flexible mix of subjects for students who aren't sure which
                                specific
                                path to commit to yet.</div>
                        </div>
                    </div>
                </div>

                <!-- TVL TAB -->
                <div class="tab-pane" id="tab-software">
                    <div class="parts-grid">
                        <div class="part-card">
                            <img class="part-img"
                                src="https://images.unsplash.com/photo-1518770660439-4636190af475?w=400&q=80"
                                alt="Computer networking">
                            <div class="part-name">Computer Systems Servicing</div>
                            <div class="part-desc">PC assembly, OS installation, and basic networking — a path into IT
                                support.</div>
                        </div>
                        <div class="part-card">
                            <img class="part-img"
                                src="https://images.unsplash.com/photo-1551218808-94e220e084d2?w=400&q=80"
                                alt="Cooking class">
                            <div class="part-name">Cookery &amp; Food Service</div>
                            <div class="part-desc">Food prep, kitchen safety, and service skills for the hospitality
                                industry.</div>
                        </div>
                        <div class="part-card">
                            <img class="part-img"
                                src="https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?w=400&q=80"
                                alt="Electrical wiring">
                            <div class="part-name">Electrical Installation</div>
                            <div class="part-desc">Wiring, circuits, and electrical maintenance leading to a trade
                                certification.</div>
                        </div>
                        <div class="part-card">
                            <img class="part-img"
                                src="https://images.unsplash.com/photo-1571260899304-425eee4c7efc?w=400&q=80"
                                alt="Farming">
                            <div class="part-name">Agri-Fishery Arts</div>
                            <div class="part-desc">Crop and livestock production, plus aquaculture, for agribusiness
                                careers.</div>
                        </div>
                    </div>
                </div>

                <!-- SPORTS & ARTS TAB -->
                <div class="tab-pane" id="tab-network">
                    <div class="parts-grid">
                        <div class="part-card">
                            <img class="part-img"
                                src="https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=400&q=80"
                                alt="Athletes training">
                            <div class="part-name">Sports Science</div>
                            <div class="part-desc">Fitness training, sports officiating, and coaching fundamentals.
                            </div>
                        </div>
                        <div class="part-card">
                            <img class="part-img"
                                src="https://images.unsplash.com/photo-1513364776144-60967b0f800f?w=400&q=80"
                                alt="Visual arts studio">
                            <div class="part-name">Visual Arts</div>
                            <div class="part-desc">Drawing, design fundamentals, and digital art tools for media and
                                design careers.</div>
                        </div>
                        <div class="part-card">
                            <img class="part-img"
                                src="https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=400&q=80"
                                alt="Performing arts">
                            <div class="part-name">Performing Arts</div>
                            <div class="part-desc">Music, theater, and dance for students pursuing the performing arts.
                            </div>
                        </div>
                        <div class="part-card">
                            <img class="part-img"
                                src="https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=400&q=80"
                                alt="Film and media production">
                            <div class="part-name">Media Arts</div>
                            <div class="part-desc">Photography, film, and digital storytelling for media production
                                careers.</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- VIDEO SECTION -->
        <div class="video">
            <div class="video-section">
                <div class="inner">
                    <div class="video-header">
                        <div class="section-label">Watch &amp; Learn</div>
                        <div class="section-title">Strands in Action</div>
                        <p class="section-desc">See what day-to-day life actually
                            looks like across different strands — straight from real classrooms and labs.</p>
                    </div>

                    <div class="video-grid">
                        <div class="video-embed">
                            <iframe id="mainVideo" src="https://www.youtube.com/watch?v=oFEFKl_Xiug"
                                title="Choosing a Senior High Strand"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        </div>

                        <div class="video-list">
                            <div class="video-thumb"
                                onclick="changeVideo('https://www.youtube.com/watch?v=w7BsTdmprjk')">
                                <img src="https://img.youtube.com/vi/tK2N9dO5mZY/mqdefault.jpg" alt="Choosing a strand">
                                <div>
                                    <div class="video-thumb-title">How to Choose the Right Strand</div>
                                    <div class="video-thumb-meta">Guidance · 14 min</div>
                                </div>
                            </div>
                            <div class="video-thumb"
                                onclick="changeVideo('https://www.youtube.com/watch?v=swapSSDN8g')">
                                <img src="https://img.youtube.com/vi/swapSSDN8g/mqdefault.jpg" alt="STEM classroom">
                                <div>
                                    <div class="video-thumb-title">A Day in STEM Class</div>
                                    <div class="video-thumb-meta">Academic Track · 11 min</div>
                                </div>
                            </div>
                            <div class="video-thumb"
                                onclick="changeVideo('https://www.youtube.com/watch?v=Sfxqq5A8cJE')">
                                <img src="https://img.youtube.com/vi/Sfxqq5A8cJE/mqdefault.jpg" alt="TVL workshop">
                                <div>
                                    <div class="video-thumb-title">Inside a TVL Workshop</div>
                                    <div class="video-thumb-meta">TVL Track · 16 min</div>
                                </div>
                            </div>
                            <div class="video-thumb"
                                onclick="changeVideo('https://www.youtube.com/watch?v=bS0JgBKFodc')">
                                <img src="https://img.youtube.com/vi/bS0JgBKFodc/mqdefault.jpg" alt="Arts and sports">
                                <div>
                                    <div class="video-thumb-title">Sports &amp; Arts Track Highlights</div>
                                    <div class="video-thumb-meta">Sports &amp; Arts · 13 min</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="section3" id="how-it-works">
            <div class="how-it-works-parent">
                <div class="create-manage-parents reveal">
                    <div class="create-text">
                        <div class="teacher-icon">
                            How it Works
                        </div>
                        <h2>Start Learning in Simple Steps</h2>

                        <p>Start your learning journey in just a few simple steps. Access lessons, complete activities,
                            and
                            develop your CSS skills through interactive learning experiences.</p>
                    </div>
                </div>
                <div class="create-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-12">
                            <div class="card reveal reveal-delay-1">
                                <div class="step-icon">
                                    <i class="fa fa-user"></i>
                                </div>

                                <h5>Access Your Account</h5>

                                <p>Login your account using LRN number and access your assigned classes and learning
                                    materials. Make sure your LRN and password are correct. If you're a first-time user,
                                    your teacher will provide your login credentials before your first session.</p>
                            </div>
                        </div>
                    </div>

                    <div class="row d-flex justify-content-end">
                        <div class="col-lg-4 col-md-12 d-flex justify-content-end">
                            <div class="card reveal reveal-delay-2">
                                <div class="step-icon">
                                    <i class="fa fa-book-open"></i>
                                </div>

                                <h5>Learn Through Modules</h5>

                                <p>Study CSS lessons, tutorials, and learning resources prepared by your teachers. Each
                                    module is structured to follow the TESDA CSS NC II curriculum — covering topics like
                                    hardware installation, OS setup, and network configuration in a clear, step-by-step
                                    format.</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-md-12">
                            <div class="card reveal reveal-delay-3">
                                <div class="step-icon">
                                    <i class="fa fa-desktop"></i>
                                </div>

                                <h5>Practice with Simulations</h5>

                                <p>Develop practical skills through interactive CSS simulations and hands-on activities.
                                    Apply what you've learned in a virtual environment — assemble PC components,
                                    configure
                                    networks, and troubleshoot system errors without needing physical equipment.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row d-flex justify-content-end">
                        <div class="col-lg-4 col-md-12 d-flex justify-content-end">
                            <div class="card reveal reveal-delay-4">
                                <div class="step-icon">
                                    <i class="fa fa-clipboard-check"></i>
                                </div>

                                <h5>Complete Assessments</h5>

                                <p>Take quizzes and activities to test your understanding of CSS concepts. Assessments
                                    are
                                    available after each module. You'll receive instant feedback on your answers so you
                                    can
                                    review and improve before moving on to the next topic.</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-md-12">
                            <div class="card reveal reveal-delay-1">
                                <div class="step-icon">
                                    <i class="fa fa-line-chart"></i>
                                </div>

                                <h5>Track Your Progress</h5>

                                <p>Monitor completed modules, assessment scores, and learning achievements. Your
                                    dashboard
                                    shows a full overview of your learning journey — see which lessons you've finished,
                                    your
                                    quiz scores, and how close you are to completing the full CSS program.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="section5" id="stories">
            <div class="stories-parent">
                <div class="stories-text reveal">
                    <div class="stories-tag">Student Stories</div>
                    <h2>What Our CSS Students Say</h2>

                    <p>Real students who used TechVoc Hub to prepare for their TESDA assessments and land their first
                        tech
                        jobs.</p>
                </div>

                <div class="stories-body">
                    <div class="card-box reveal reveal-delay-1">
                        <div class="parent-stars">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </div>

                        <p>"The hardware assembly videos helped me so much. I used to be scared of opening a PC, but
                            after
                            practicing with TechVoc Hub's modules I assembled my own PC at home and passed the TESDA
                            hardware task with no problem."</p>

                        <div class="profile">
                            <div class="profile-icon">

                            </div>
                            <div class="profile-text">
                                <h5>Marco Santos</h5>
                                <p>CSS Grade 12 · Cavite</p>
                            </div>
                        </div>
                    </div>

                    <div class="card-box reveal reveal-delay-2">
                        <div class="parent-stars">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </div>

                        <p>"The hardware assembly videos helped me so much. I used to be scared of opening a PC, but
                            after
                            practicing with TechVoc Hub's modules I assembled my own PC at home and passed the TESDA
                            hardware task with no problem."</p>

                        <div class="profile">
                            <div class="profile-icon">

                            </div>
                            <div class="profile-text">
                                <h5>Marco Santos</h5>
                                <p>CSS Grade 12 · Cavite</p>
                            </div>
                        </div>
                    </div>

                    <div class="card-box reveal reveal-delay-3">
                        <div class="parent-stars">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </div>

                        <p>"The hardware assembly videos helped me so much. I used to be scared of opening a PC, but
                            after
                            practicing with TechVoc Hub's modules I assembled my own PC at home and passed the TESDA
                            hardware task with no problem."</p>

                        <div class="profile">
                            <div class="profile-icon">

                            </div>
                            <div class="profile-text">
                                <h5>Marco Santos</h5>
                                <p>CSS Grade 12 · Cavite</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section6" id="cta-section">
            <h2 class="reveal">Are You Ready To Start Your Journey?</h2>
            <p class="reveal reveal-delay-1">Begin your path to becoming a skilled Computer System Servicing
                professional. Access interactive lessons,
                hands-on activities, and assessments designed to help you build confidence and succeed in your CSS
                studies.</p>

            <a href="/learning_management/public/?url=login" class="reveal reveal-delay-2">Start Now</a>
        </div>

        <footer>
            <div class="footer-parent">
                <div class="footer-logo">
                    <!-- <img src="../images/iLearn-8.png" alt=""> -->
                    <h3>SHS Strand</h3>

                    <p>An interactive Computer System Servicing learning platform built to help senior high school
                        students
                        master TESDA NC II competencies.</p>
                </div>

                <div class="footer-platform">
                    <h5>Platform</h5>

                    <p>Features</p>
                    <p>Skills Training</p>
                    <p>How it Works</p>
                </div>

                <div class="footer-resources">
                    <h5>Resources</h5>

                    <p>About CSS</p>
                    <p>Student Stories</p>
                    <p>Get Started</p>
                </div>

                <div class="footer-connect">
                    <h5>Connect</h5>

                    <p>Support</p>
                    <p>Contact Teacher</p>
                    <p>FAQ</p>
                </div>
            </div>

            <div class="footer-bottom">© 2026 iLearn-CSS. Built for senior high school TVL-ICT students.</div>
        </footer>

        <div class="back-top" id="backTop">
            <i class="fa fa-arrow-up"></i>
        </div>
    </div>

    <!-- bootstrap link javascript -->
    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>

    <script>
        // Scroll reveal
        const reveals = document.querySelectorAll('.reveal');
        const io = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
        }, { threshold: 0.15 });
        reveals.forEach(r => io.observe(r));

        // Back to top
        const backTop = document.getElementById('backTop');
        window.addEventListener('scroll', () => {
            backTop.classList.toggle('show', window.scrollY > 600);
        });
        backTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    </script>

    <script>
        // ── TAB SWITCHER ──
        function switchTab(id, btn) {
            document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById('tab-' + id).classList.add('active');
            btn.classList.add('active');
        }

        // ── VIDEO SWITCHER ──
        function changeVideo(src) {
            document.getElementById('mainVideo').src = src;
        }

        // ── SCROLL REVEAL ──
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.08 });

        document.querySelectorAll('.module-card, .part-card, .career-card, .panel, .about-grid').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(24px)';
            el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            observer.observe(el);
        });

        // ── HERO TITLE FLICKER ──
        const line2 = document.querySelector('.hero-title .line2');
        setTimeout(() => {
            let flickers = 0;
            const flicker = setInterval(() => {
                line2.style.opacity = line2.style.opacity === '0' ? '1' : '0';
                flickers++;
                if (flickers >= 6) {
                    clearInterval(flicker);
                    line2.style.opacity = '1';
                }
            }, 80);
        }, 800);
    </script>
</body>

</html>