<style>
    .container-fluid .rightbar .module-title {
        width: 100%;
        /* height: 270px; */
        /* background-color: #00C950; */
        border: 1px solid rgba(0, 0, 0, 0.2);
        /* padding: 3rem; */
        color: white;
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
        border-radius: 10px;
    }

    /* .container-fluid .rightbar .module-title .module-picture {
        width: 100%;
        height: 200px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    } */

    .container-fluid .rightbar .module-title .module-picture {
        /* position: absolute;
        inset: 0; */
        background-image: url('../images/philosophy_picture.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        width: 100%;
        height: 180px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        /* background-position: center 30%;
        opacity: 0.55;
        height: 100%; */
    }

    .container-fluid .rightbar .module-title .module-body {
        background-color: #ffffff;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
    }

    .container-fluid .rightbar .module-title .module-body .module-body-child {
        position: relative;
        z-index: 2;
        display: flex;
        gap: 48px;
        align-items: center;
        padding: 1.8rem 2rem 1.8rem 2rem;
    }

    .container-fluid .rightbar .module-title .module-body .module-links {
        width: 55%;
    }

    .container-fluid .rightbar .module-title .module-body .module-links span {
        /* color: #ffffff; */
        color: #1A1A1A;
        text-transform: uppercase;
        color: var(--green);
        font-weight: 600;
        font-size: 13.5px;
    }

    .container-fluid .rightbar .module-title .module-body .module-links .module-buttons {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .container-fluid .rightbar .module-title .module-body .module-links .module-buttons a:nth-child(1) {
        color: white;
        background-color: var(--green);
    }

    .container-fluid .rightbar .module-title .module-body .module-links .module-buttons a:nth-child(2) {
        border: 2px solid var(--green);
        /* color: #ffffff; */
        /* color: #1A1A1A; */
        color: var(--green);
        padding: 10px 40px;
    }

    .container-fluid .rightbar .module-title .module-body .module-links .module-buttons a {
        text-decoration: none;
        padding: 10px 27px;
        border-radius: 28px;
        text-transform: uppercase;
        font-weight: 600;
        font-size: 13px;
    }

    .container-fluid .rightbar .module-title .module-body .module-links h1 {
        font-size: 22px;
        text-transform: uppercase;
        font-family: "Titan", sans-serif;
        /* color: #ffffff; */
        /* color: #1A1A1A; */
        color: var(--green-dark);
        margin-top: 0.7rem;
    }

    .container-fluid .rightbar .module-title .module-body .module-text {
        width: 45%;
    }

    .container-fluid .rightbar .module-title .module-body .module-text p {
        line-height: 26px;
        margin: 0;
        font-size: 14px;
        /* color: rgba(255, 255, 255, .85); */
        color: #1A1A1A;
    }

    .container-fluid .rightbar .module-parent-progress .module-progress .progress-bar .progress {
        position: absolute;
        padding: 0.7rem;
        left: 0;
        width: 60%;
        background-color: #00C950;
        border-radius: 28px;
    }
</style>

<div class="module-title">
    <!-- <h1>Understanding Culture Society and Politics</h1>
    <p><?= $_SESSION['section'] ?></p> -->
    <div class="module-picture">

    </div>
    <div class="module-body">
        <div class="module-body-child">
            <div class="module-links">
                <!-- <span>Currently Enrolled</span> -->
                <h1>Understanding Culture Society and Politics</h1>

                <div class="module-buttons">
                    <a href="/learning_management/public/?url=subjects_all">Browse Courses</a>
                    <a href="#">Learn More</a>
                </div>
            </div>
            <div class="module-text">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                    et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                    aliquip
                    ex ea commodo consequat. Duis aute irure dolor in </p>
            </div>
        </div>
    </div>
    <!-- <div class="module-body">
        <div class="module-picture"></div>
        <div class="module-body-child">
            <div class="module-links">
                <span>Currently Enrolled</span>
                <h1>Understanding Culture Society and Politics</h1>

                <div class="module-buttons">
                    <a href="/learning_management/public/?url=subjects_all">Browse Courses</a>
                    <a href="#">Learn More</a>
                </div>
            </div>
            <div class="module-text">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                    et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                    aliquip
                    ex ea commodo consequat. Duis aute irure dolor in </p>
            </div>
        </div>
    </div> -->
</div>

<div class="parent">
    <div class="module-parent-progress">

        <a href="?url=module_view&subject=ucsp&id=1">
            <div class="module-progress">
                <div class="module-parent">
                    <div class="module-icon">
                        <i class="fa fa-layer-group"></i>
                    </div>
                    <div class="module-content">
                        <span>New Material</span>

                        <h3>Name: Module 1 Week 1 - 3</h3>

                        <p>Topic: Introduction to Pholosophy</p>
                    </div>
                </div>

                <hr>

                <div class="module-date">
                    <p>Date Received: Mar 18</p>
                </div>
            </div>
        </a>

        <a href="?url=assignment_view&subject=ucsp&id=1">
            <div class="module-progress">
                <div class="module-parent">
                    <div class="module-icon">
                        <i class="fa fa-layer-group"></i>
                    </div>
                    <div class="module-content">
                        <span>New Assignments</span>

                        <h3>Name: Essay about what you understand in the subject Introduction to Pholosophy </h3>

                        <p>Description: Give me 3-5 sentences send in pdf format using the template below. </p>
                    </div>
                </div>

                <hr>

                <div class="module-date">
                    <p>Date Received: Mar 18</p>
                </div>
            </div>
        </a>

        <a href="?url=announcement_view&subject=ucsp&id=1">
            <div class="module-progress">
                <div class="module-parent">
                    <div class="module-icon">
                        <i class="fa fa-layer-group"></i>
                    </div>
                    <div class="module-content">
                        <span>Announcements</span>

                        <h3>Kindly check the module that i send and be ready for recitation and quiz after our
                            discussion.
                            Also , dont forget to submit the given task that i end here in our class. Submit it on time.
                        </h3>

                        <p>Thats all thank you eveyone . </p>
                    </div>
                </div>

                <hr>

                <div class="module-date">
                    <p>Date Received: Mar 18</p>
                </div>
            </div>
        </a>

        <!-- <div class="module-progress" data-module-id="module1">
            <div class="module-progress-header">

            </div>
            <div class="module-progress-body">
                <p>Understanding Self</p>
                <h3>Introduction to Philosophy of Human Person</h3>

                <p>Learn the fundamentals of philosophy, including major branches, key thinkers, and basic
                    philosophical concepts.</p>

                <div class="title-progress-bar">
                    <span class="lessonText">0 of 10 lessons</span>
                    <span class="lessonPercent">0%</span>
                </div>

                <div class="parent-progress-bar">
                    <div class="progress-bar">
                        <div class="progress"></div>
                    </div>
                </div>

                <div class="footer-bar">
                    <a href="/learning_management/public/?url=subject_lessons&subject=ucsp&module=module1">Continue
                        learning
                        <i class="fa fa-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <div class="module-progress" data-module-id="module2">
            <div class="module-progress-header">

            </div>
            <div class="module-progress-body">
                <p>Understanding Self</p>
                <h3>Introduction to Philosophy of Human Person</h3>

                <p>Learn the fundamentals of philosophy, including major branches, key thinkers, and basic
                    philosophical concepts.</p>

                <div class="title-progress-bar">
                    <span class="lessonText">0 of 10 lessons</span>
                    <span class="lessonPercent">0%</span>
                </div>

                <div class="parent-progress-bar">
                    <div class="progress-bar">
                        <div class="progress"></div>
                    </div>
                </div>

                <div class="footer-bar">
                    <a href="/learning_management/public/?url=subject_lessons&subject=ucsp&module=module2">Continue
                        learning
                        <i class="fa fa-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <div class="module-progress" data-module-id="module3">
            <div class="module-progress-header">

            </div>
            <div class="module-progress-body">
                <p>Understanding Self</p>
                <h3>Introduction to Philosophy of Human Person</h3>

                <p>Learn the fundamentals of philosophy, including major branches, key thinkers, and basic
                    philosophical concepts.</p>

                <div class="title-progress-bar">
                    <span class="lessonText">0 of 10 lessons</span>
                    <span class="lessonPercent">0%</span>
                </div>

                <div class="parent-progress-bar">
                    <div class="progress-bar">
                        <div class="progress"></div>
                    </div>
                </div>

                <div class="footer-bar">
                    <a href="/learning_management/public/?url=subject_lessons&subject=ucsp&module=module3">Continue
                        learning
                        <i class="fa fa-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <div class="module-progress" data-module-id="module4">
            <div class="module-progress-header">

            </div>
            <div class="module-progress-body">
                <p>Understanding Self</p>
                <h3>Introduction to Philosophy of Human Person</h3>

                <p>Learn the fundamentals of philosophy, including major branches, key thinkers, and basic
                    philosophical concepts.</p>

                <div class="title-progress-bar">
                    <span class="lessonText">0 of 10 lessons</span>
                    <span class="lessonPercent">0%</span>
                </div>

                <div class="parent-progress-bar">
                    <div class="progress-bar">
                        <div class="progress"></div>
                    </div>
                </div>

                <div class="footer-bar">
                    <a href="/learning_management/public/?url=subject_lessons&subject=ucsp&module=module3">Continue
                        learning
                        <i class="fa fa-arrow-right"></i></a>
                </div>
            </div>
        </div> -->

    </div>
</div>