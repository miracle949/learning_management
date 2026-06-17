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
                <img src="../images/iLearn.png" alt="">
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
                        <a href="#features">Features</a>
                    </li>
                    <li>
                        <a href="#skills-training">Skills Training</a>
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

        <main>
            <div class="hero-bg"></div>
            <div class="stars" id="stars"></div>
            <div class="orb orb-1"></div>
            <div class="orb orb-2"></div>
            <div class="orb orb-3"></div>
            <!-- <div class="hero-bg"></div>
            <div class="stars" id="stars"></div>
            <div class="orb orb-1"></div>
            <div class="orb orb-2"></div>
            <div class="orb orb-3"></div> -->
            <!-- <div class="hero-blob"></div>
            <svg class="hero-geo" viewBox="0 0 700 700" xmlns="http://www.w3.org/2000/svg">
                <circle cx="350" cy="350" r="200" />
                <circle cx="350" cy="350" r="280" />
                <circle cx="350" cy="350" r="340" />
                <rect x="170" y="170" width="360" height="360" rx="80" transform="rotate(15 350 350)" />
                <rect x="220" y="220" width="260" height="260" rx="60" transform="rotate(30 350 350)" />
            </svg> -->

            <div class="hero-card c1"><span class="card-dot"></span>🖥️ Hardware Assembly & Troubleshooting</div>

            <div class="hero-card c2 blue-card"><span class="card-dot"></span>🔧 PC Repair · Networking · OS Install
            </div>

            <div class="hero-card c3"><span class="card-dot"></span>🏆 TESDA NC II Certified in 6 Months</div>

            <div class="hero-card c4"><span class="card-dot"></span>🏆 TESDA NC II Certified in 6 Months</div>


            <div class="main-text">
                <div class="main-icon">
                    <i class="fa fa-shield"></i>
                    <p>Built for Senior High School Students</p>
                </div>
                <!-- <h2>Transform
                    <span class="word-wrap">
                        <span>Education</span>
                        <span>Learning</span>
                        <span>Mastery</span>
                        <span>Knowledge</span>
                        <span>Teaching</span>
                    </span>
                    with Moderm LMS
                </h2> -->
                <!-- <h2>Study smarter, not <b>harder.</b></h2> -->
                <h2>
                    Study smart, play your part,

                    <div class="highlight">learning here is just the start.</div>
                </h2>
                <p>Study smart, play your part, and discover endless opportunities to learn. iLearn combines engaging
                    lessons, interactive activities, and realistic Computer System Servicing simulations to help
                    students build knowledge, gain practical experience, and achieve their full potential.</p>
                <div class="learning-today">
                    <a href="#">
                        <span>Explore Courses</span>
                        <!-- <i class="fa fa-arrow-right"></i> -->
                    </a>

                    <a href="#">
                        <span>See how it works</span>
                        <!-- <i class="fa fa-arrow-right"></i> -->
                    </a>
                </div>

                <!-- <div class="main-progress">
                    <div class="active-students">
                        <p>10k+</p>
                        <span>Active Students</span>
                    </div>

                    <div class="teachers">
                        <p>2k+</p>
                        <span>Teachers</span>
                    </div>

                    <div class="lessons">
                        <p>50k+</p>
                        <span>Lessons Completed</span>
                    </div>
                </div> -->

            </div>

            <!-- <div class="main-image">
                <div class="border"></div>
            </div> -->

            <!-- <div style="position:absolute;bottom:0;left:0;right:0;z-index:3;line-height:0; border: none;">
                <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"
                    style="width:100%;height:80px;display:block; border: none;">
                    <path d="M0,40 C360,90 1080,0 1440,50 L1440,80 L0,80 Z" fill="#FBF8F3" />
                </svg>
            </div> -->
        </main>

        <div class="trust">
            <span class="trust-label">Recognized by</span>
            <div class="trust-logos">
                <span>DepEd</span><span>TESDA</span><span>CHED</span><span>EdTech PH</span><span>YoungMinds Award</span>
            </div>
        </div>

        <section class="section1" id="about">

            <div class="about-text">
                <div class="section-icon">
                    What is CSS?
                </div>

                <h2>Computer System Servicing (CSS) - Learn, Practice and Troubleshoot</h2>
                <p>Computer Systems Servicing (CSS) is a TESDA-accredited technical-vocational program that trains
                    students in the installation, configuration, maintenance, and repair of computer hardware and
                    software systems. CSS graduates are in high demand across every industry that uses computers — which
                    is basically everywhere.</p>

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

            <div class="about-image">
                <iframe width="530" height="320" style="border-radius: 10px;"
                    src="https://www.youtube.com/embed/L8-2Rjgdgu0?si=yNirZMF6JJSYmqUV" title="YouTube video player"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>


        </section>

        <section class="section2" id="features">
            <div class="learn-own-parents">

                <!-- <div class="learn-image-parents">
                    <div class="learn-image">
                        <img src="../images/learn-photo.jpg" alt="">
                    </div>
                </div> -->
                <div class="learn-text">
                    <div class="student-icon">
                        Explore Our features
                    </div>
                    <h2>Everything You Need to Succeed in CSS</h2>

                    <p>Enhance your Computer Systems Servicing skills through interactive simulations, engaging learning
                        modules, assessments, and real-time progress tracking—all within one specialized learning
                        platform.</p>
                </div>

                <div class="learn-body">
                    <div class="card-features">
                        <div class="icon-features">
                            <i class="fa fa-desktop"></i>
                        </div>

                        <h5>Interactive Simulations</h5>

                        <p>Practice hardware assembly, troubleshooting, and networking tasks through engaging
                            simulations.</p>


                        <span>Experience hands-on virtual labs that mimic real-world CSS scenarios — from building a PC
                            from scratch to diagnosing network failures.</span>
                    </div>

                    <div class="card-features">
                        <div class="icon-features">
                            <i class="fa fa-book-open"></i>
                        </div>

                        <h5>Learning Modules</h5>

                        <p>Access lessons, activities, and resources anytime and anywhere.</p>


                        <span>Structured learning paths aligned with the TESDA CSS NC II competency standards — covering
                            OS installation, LAN setup, and system maintenance at your own pace.</span>
                    </div>

                    <div class="card-features">
                        <div class="icon-features">
                            <i class="fa fa-chart-line"></i>
                        </div>

                        <h5>Progress Tracking</h5>

                        <p>Monitor your achievements, completed activities, and learning progress.</p>


                        <span>Get a clear visual overview of your performance across all modules. Spot areas that need
                            improvement and stay motivated with milestone badges and completion rates.</span>
                    </div>

                    <div class="card-features">
                        <div class="icon-features">
                            <i class="fa fa-clipboard-check"></i>
                        </div>

                        <h5>Assessments & Quizzes</h5>

                        <p>Test your knowledge and receive instant feedback.</p>


                        <span>Challenge yourself with topic-based quizzes, timed assessments, and mock TESDA-style
                            exams. Instant scoring and detailed explanations help you build exam confidence.</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="section4" id="skills-training">
            <div class="ready-parents">
                <div class="ready-icon">
                    Training Laboratory
                </div>
                <h2>Hands-On CSS Skills Lab</h2>

                <p>Students actively exploring computer hardware components during a CSS classroom session, learning to
                    identify parts such as the motherboard, RAM slots, expansion cards, and peripheral connectors under
                    teacher guidance.
                </p>
            </div>

            <div class="ready-body">
                <div class="ready-card">
                    <div class="image-ready">
                        <img src="../images/preparing-css.jpg" alt="">
                    </div>
                    <div class="image-text">
                        <!-- <h5>System Unit Assembly & Disassembly</h5> -->

                        <p>"Students actively participating in a CSS classroom session, exploring computer hardware
                            components and learning the fundamentals of system unit identification. Under the guidance
                            of their teacher, they examine each part carefully — identifying the motherboard, RAM slots,
                            expansion cards, and peripheral connectors. This foundational activity builds the
                            recognition skills needed before performing actual assembly and disassembly tasks in the
                            TESDA NC II assessment."</p>
                    </div>
                </div>

                <div class="ready-card">
                    <div class="image-text">
                        <!-- <h5>Network Cable Crimping (RJ45)</h5> -->

                        <p>"Students practicing hands-on network cable crimping using RJ45 connectors and crimping tools
                            — a core skill required in the TESDA NC II networking competency. Working in groups, they
                            carefully arrange the eight copper wires following the T568B wiring standard before
                            inserting them into the RJ45 connector and applying pressure with the crimping tool. Each
                            finished cable is then tested using a LAN tester to verify that all wire pairs are correctly
                            connected and the cable is ready for use in a live network setup."</p>
                    </div>
                    <div class="image-ready">
                        <img src="../images/crimping-1.jpg" alt="">
                    </div>
                </div>

                <div class="ready-card">
                    <div class="image-ready">
                        <img src="../images/demo-3.jpg" alt="">
                    </div>
                    <div class="image-text">
                        <!-- <h5>Supervised Hardware Installation</h5> -->

                        <p>"A student performs system unit assembly under close teacher supervision, practicing the
                            correct installation of internal components including the power supply unit, motherboard,
                            and drive connections. The activity emphasizes proper handling techniques — such as
                            grounding yourself before touching components, using the correct screwdriver, and following
                            the right sequence of installation. Mastering this task is essential for passing the
                            hardware assembly portion of the TESDA CSS NC II practical examination."</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="section3" id="how-it-works">
            <div class="create-manage-parents">
                <div class="create-text">
                    <div class="teacher-icon">
                        How it Works
                    </div>
                    <h2>Start Learning in Simple Steps</h2>

                    <p>Start your learning journey in just a few simple steps. Access lessons, complete activities, and
                        develop your CSS skills through interactive learning experiences.</p>
                </div>
            </div>
            <div class="create-body">
                <div class="row">
                    <div class="col-lg-4 col-md-12">
                        <div class="card">
                            <!-- <span>STEP 01</span> -->

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
                        <div class="card">
                            <!-- <span>STEP 02</span> -->

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
                        <div class="card">
                            <!-- <span>STEP 03</span> -->

                            <div class="step-icon">
                                <i class="fa fa-desktop"></i>
                            </div>

                            <h5>Practice with Simulations</h5>

                            <p>Develop practical skills through interactive CSS simulations and hands-on activities.
                                Apply what you've learned in a virtual environment — assemble PC components, configure
                                networks, and troubleshoot system errors without needing physical equipment.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row d-flex justify-content-end">
                    <div class="col-lg-4 col-md-12 d-flex justify-content-end">
                        <div class="card">
                            <!-- <span>STEP 04</span> -->

                            <div class="step-icon">
                                <i class="fa fa-clipboard-check"></i>
                            </div>

                            <h5>Complete Assessments</h5>

                            <p>Take quizzes and activities to test your understanding of CSS concepts. Assessments are
                                available after each module. You'll receive instant feedback on your answers so you can
                                review and improve before moving on to the next topic.</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-12">
                        <div class="card">
                            <!-- <span>STEP 05</span> -->

                            <div class="step-icon">
                                <i class="fa fa-line-chart"></i>
                            </div>

                            <h5>Track Your Progress</h5>

                            <p>Monitor completed modules, assessment scores, and learning achievements. Your dashboard
                                shows a full overview of your learning journey — see which lessons you've finished, your
                                quiz scores, and how close you are to completing the full CSS program.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="section5" id="stories">
            <div class="stories-text">
                <div class="stories-tag">Student Stories</div>
                <h2>What Our CSS Students Say</h2>

                <p>Real students who used TechVoc Hub to prepare for their TESDA assessments and land their first tech
                    jobs.</p>
            </div>

            <div class="stories-body">
                <div class="card-box">
                    <div class="parent-stars">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>

                    <p>"The hardware assembly videos helped me so much. I used to be scared of opening a PC, but after
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

                <div class="card-box">
                    <div class="parent-stars">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>

                    <p>"The hardware assembly videos helped me so much. I used to be scared of opening a PC, but after
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

                <div class="card-box">
                    <div class="parent-stars">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>

                    <p>"The hardware assembly videos helped me so much. I used to be scared of opening a PC, but after
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

        <div class="section6" id="cta-section">
            <h2>Are You Ready To Start Your Journey?</h2>
            <p>Begin your path to becoming a skilled Computer System Servicing professional. Access interactive lessons, hands-on activities, and assessments designed to help you build confidence and succeed in your CSS studies.</p>

            <a href="/learning_management/public/?url=login">Start Now</a>
        </div>

        <footer>
            <div class="footer-logo">
                <div class="logo-icon">
                    <i class="fa-solid fa-lightbulb"></i>
                </div>
                <div class="logo-text">
                    <p><b>i</b>Learn</p>
                </div>
            </div>

            <div class="footer-copy">
                <p><i class="fa-solid fa-copyright"></i> 2026 iLearn. All rights reserved.</p>
            </div>

            <div class="footer-message">
                <p>Built for students and teachers</p>
            </div>
        </footer>
    </div>

    <!-- bootstrap link javascript -->
    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const wrap = document.querySelector('.main-text h2 .word-wrap');
            const spans = wrap.querySelectorAll('span');

            // Set initial width to first word
            wrap.style.width = spans[0].offsetWidth + 'px';

            setInterval(() => {
                spans.forEach(span => {
                    const opacity = parseFloat(getComputedStyle(span).opacity);
                    if (opacity > 0.5) {
                        wrap.style.width = span.offsetWidth + 'px';
                    }
                });
            }, 100);
        });
    </script>
</body>

</html>