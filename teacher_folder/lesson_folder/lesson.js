document.addEventListener("DOMContentLoaded", function () {

    const contentContainer = document.getElementById("contentContainer");

    const addLessonBtn = document.getElementById("addLessonBtn");
    const addVideoBtn = document.getElementById("addVideoBtn");
    const addImageBtn = document.getElementById("addImageBtn");
    const addActivityBtn = document.getElementById("addActivityBtn");

    function updateNumbers(typeClass, labelClass, labelText) {
        const items = document.querySelectorAll("." + typeClass);
        items.forEach((item, index) => {
            item.querySelector("." + labelClass).textContent =
                labelText + " " + (index + 1);
        });
    }

    function createRemoveButton() {
        return `
            <button type="button" class="remove-item btn btn-sm btn-danger">
                <i class="fa fa-times"></i>
            </button>
        `;
    }

    function checkIfEmpty() {
        const lessons = document.querySelectorAll(".lesson-item").length;
        const videos = document.querySelectorAll(".video-item").length;
        const images = document.querySelectorAll(".image-item").length;
        const activities = document.querySelectorAll(".activity-item").length;

        if (lessons === 0 && videos === 0 && images === 0 && activities === 0) {
            document.querySelector(".text-content").style.display = "flex";
        }
    }

    document.addEventListener("click", function (e) {

        const removeBtn = e.target.closest(".remove-item");

        if (removeBtn) {

            const item =
                removeBtn.closest(".lesson-item") ||
                removeBtn.closest(".video-item") ||
                removeBtn.closest(".image-item") ||
                removeBtn.closest(".activity-item");

            if (item) {
                item.remove();
            }

            checkIfEmpty(); // check after removing
        }

    });

    // ===================== LESSON =====================
    addLessonBtn.addEventListener("click", function () {

        document.querySelector(".text-content").style.display = "none";

        const lesson = document.createElement("div");
        lesson.className = "card-body lesson-item";
        lesson.innerHTML = `
            <div class="card-body-header d-flex justify-content-between align-items-center">
                <div class="card-nav">
                    <div class="card-icon">
                        <i class="fa fa-file"></i>
                    </div>
                    <p class="lesson-label">Lesson</p>
                </div>
                ${createRemoveButton()}
            </div>

            <div class="row">
                <div class="col-lg-12 mt-4">
                    <label>Title *</label>
                    <input type="text" name="lesson_title[]" 
                    class="form-control mt-2" placeholder="Enter lesson title">
                </div>

                <div class="col-lg-12 mt-4">
                    <label>Content *</label>
                    <textarea name="lesson_content[]" 
                    class="form-control mt-2" rows="7"
                    placeholder="Enter lesson content"></textarea>
                </div>
            </div>
        `;

        contentContainer.appendChild(lesson);
        updateNumbers("lesson-item", "lesson-label", "Lesson");
    });

    // ===================== VIDEO =====================
    addVideoBtn.addEventListener("click", function () {

        document.querySelector(".text-content").style.display = "none";

        const video = document.createElement("div");
        video.className = "card-body video-item";
        video.innerHTML = `
            <div class="card-body-header d-flex justify-content-between align-items-center">
                <div class="card-nav">
                    <div class="card-icon">
                        <i class="fa fa-video"></i>
                    </div>
                    <p class="video-label">Video</p>
                </div>
                ${createRemoveButton()}
            </div>

            <div class="row">
                <div class="col-lg-12 mt-4">
                    <label>Title *</label>
                    <input type="text" name="video_title[]" 
                    class="form-control mt-2" placeholder="Enter video title">
                </div>
                <div class="col-lg-12 mt-4">
                    <label>Video URL *</label>
                    <input type="text" name="video_url[]" 
                    class="form-control mt-2" placeholder="https://youtube.com/...">
                </div>

                <div class="col-lg-12 mt-4">
                    <label>Description *</label>
                    <textarea name="video_description[]" 
                    class="form-control mt-2" rows="7"
                    placeholder="Video description"></textarea>
                </div>
        `;

        contentContainer.appendChild(video);
        updateNumbers("video-item", "video-label", "Video");
    });

    // ===================== IMAGE =====================
    addImageBtn.addEventListener("click", function () {

        document.querySelector(".text-content").style.display = "none";

        const image = document.createElement("div");
        image.className = "card-body image-item";
        image.innerHTML = `
            <div class="card-body-header d-flex justify-content-between align-items-center">
                <div class="card-nav">
                    <div class="card-icon">
                        <i class="fa fa-image"></i>
                    </div>
                    <p class="image-label">Image</p>
                </div>
                ${createRemoveButton()}
            </div>

            <div class="row">
                <div class="col-lg-12 mt-4"
                    <label>Title *</label>
                    <input type="text" name="image_title[]" 
                    class="form-control mt-2" placeholder="Enter image title">
                </div>


                <div class="col-lg-12 mt-4">
                    <label>Image URL *</label>
                    <input type="text" name="image_url[]" 
                    class="form-control mt-2" placeholder="https://example.com/image.jpg">
                </div>

                <div class="col-lg-12 mt-4">
                    <label>Description *</label>
                    <textarea name="image_description[]" 
                    class="form-control mt-2" rows="7"
                    placeholder="Image description"></textarea>
                </div>
            </div>
        `;

        contentContainer.appendChild(image);
        updateNumbers("image-item", "image-label", "Image");
    });

    // ===================== ACTIVITY =====================
    addActivityBtn.addEventListener("click", function () {

        document.querySelector(".text-content").style.display = "none";

        const activity = document.createElement("div");
        activity.className = "card-body activity-item";
        activity.innerHTML = `
            <div class="card-body-header d-flex justify-content-between align-items-center">
                <div class="card-nav">
                    <div class="card-icon">
                        <i class="fa fa-circle-question"></i>
                    </div>
                    <p class="activity-label">Activity</p>
                </div>
                ${createRemoveButton()}
            </div>

            <div class="row">
                <div class="col-lg-12 mt-4">
                    <label>Title *</label>
                    <input type="text" name="activity_title[]"class="form-control mt-2" placeholder="Enter activity title">
                </div>
                <div class="col-lg-12 mt-4">
                    <label>Content *</label>
                    <textarea name="activity_content[]" 
                    class="form-control mt-2" rows="7"
                    placeholder="Enter activity content"></textarea>
                </div>
            </div>
        `;

        contentContainer.appendChild(activity);
        updateNumbers("activity-item", "activity-label", "Activity");
    });

    // ===================== REMOVE =====================
    contentContainer.addEventListener("click", function (e) {
        if (e.target.closest(".remove-item")) {
            const item = e.target.closest(".card-body");
            item.remove();

            updateNumbers("lesson-item", "lesson-label", "Lesson");
            updateNumbers("video-item", "video-label", "Video");
            updateNumbers("image-item", "image-label", "Image");
            updateNumbers("activity-item", "activity-label", "Activity");
        }
    });

});