// This is for editing the heading

if (document.querySelector(".heading")) {
    const displayHeading = document.querySelector(".heading");
    const headingInput = document.getElementById("heading_input");

    if (displayHeading) {
        headingInput.addEventListener("input", function (event) {
            event.preventDefault();

            const input = headingInput.value;
            console.log(input);
            displayHeading.innerHTML = input;
        });
    }
}

// This is for editing the subheading

if (document.querySelector(".subheading")) {
    const displaySubheading = document.querySelector(".subheading");
    const subheadingInput = document.getElementById("subheading_input");

    if (displaySubheading) {
        subheadingInput.addEventListener("input", function (event) {
            event.preventDefault();

            const input = subheadingInput.value;
            console.log(input);
            displaySubheading.innerHTML = input;
        });
    }
}

// This is for editing the quote

if (document.querySelector(".quote")) {
    const displayQuote = document.querySelector(".quote");
    const quoteInput = document.getElementById("quote_input");

    if (displayQuote) {
        quoteInput.addEventListener("input", function (event) {
            event.preventDefault();

            const input = quoteInput.value;
            console.log(input);
            displayQuote.innerHTML = input;
        });
    }
}

// This is for editing the byline

if (document.querySelector(".byline")) {
    const displayByline = document.querySelector(".byline");
    const bylineInput = document.getElementById("byline_input");

    if (displayByline) {
        bylineInput.addEventListener("input", function (event) {
            event.preventDefault();

            const input = bylineInput.value;
            console.log(input);
            displayByline.innerHTML = input;
        });
    }
}

// This is for editing the text

if (document.querySelector(".text")) {
    const displayText = document.querySelector(".text");
    const textInput = document.getElementById("text_input");

    if (displayText) {
        textInput.addEventListener("input", function (event) {
            event.preventDefault();

            const input = textInput.value;
            console.log(input);
            displayText.innerHTML = input;
        });
    }
}

// This is for editing the story box

if (document.querySelector(".story_box")) {
    const displayStoryboxTitle = document.querySelector(".story_box");
    const storyboxTitleInput = document.getElementById("section_name");
    const displayStoryboxPlaceholdertext = document.getElementById("comment");
    const storyboxPlaceholderInput = document.getElementById("placeholder_text");

    storyboxTitleInput.addEventListener("input", function (event) {
        event.preventDefault();

        const input = storyboxTitleInput.value;
        displayStoryboxTitle.innerHTML = input;
    });

    storyboxPlaceholderInput.addEventListener("input", function (event) {
        event.preventDefault();

        const input = storyboxPlaceholderInput.value;
        displayStoryboxPlaceholdertext.placeholder = input;
    });
}

// This is for editing the comment section

if (document.querySelector(".comment_input")) {

    const displayCommentTitle = document.querySelector(".comment_title");
    const commentTitleInput = document.getElementById("section_name");
    const displayCommentPlaceholderText = document.querySelector(".comment_input");
    const commentPlaceholderInput = document.getElementById("comment_placeholder");

    commentTitleInput.addEventListener("input", function (event) {
        event.preventDefault();

        const input = commentTitleInput.value;
        displayCommentTitle.innerHTML = input;

    });

    commentPlaceholderInput.addEventListener("input", function (event) {
        event.preventDefault();

        const input = commentPlaceholderInput.value;
        displayCommentPlaceholderText.placeholder = input;

    });
}

// This is for editing the video section

if (document.querySelector(".video")) {
    document.getElementById('customFile').addEventListener('change', function () {
        updateVideo();
    });
}

function updateVideo() {
    var input = document.getElementById('customFile');
    var video = document.querySelector('.video');

    var file = input.files[0];

    if (file) {
        // Check if the file type is video
        if (file.type.startsWith('video/')) {
            // Check if the file size is within the limit (40MB)
            var maxSizeBytes = 40 * 1024 * 1024; // 40MB in bytes
            if (file.size <= maxSizeBytes) {
                try {
                    var objectURL = URL.createObjectURL(file);
                    video.src = objectURL;
                } catch (error) {
                    console.error('Error updating video:', error.message);
                    alert('An error occurred while updating the video. Please try again.');
                    input.value = '';  // Clear the selected file
                }
            } else {
                alert('The file size exceeds the limit of 40MB. Please upload a smaller video file.');
                input.value = '';  // Clear the selected file
            }
        } else {
            alert('Please upload a valid video file.');
            input.value = '';  // Clear the selected file
        }
    }
}

// This is for editing the image section

if (document.querySelector(".image")) {
    document.getElementById('customFile').addEventListener('change', function () {
        updateImage();
    });

    const displayImageText = document.querySelector(".image_text");
    const imageTextInput = document.getElementById("image_text");

    imageTextInput.addEventListener("input", function (event) {
        event.preventDefault();

        const input = imageTextInput.value;

        displayImageText.innerHTML = input;
        
    });

}

function updateImage() {
    var input = document.getElementById('customFile');
    var image = document.querySelector('.image');

    var file = input.files[0];

    if (file) {
        // Check if the file type is an image
        if (file.type.startsWith('image/')) {
            try {
                var objectURL = URL.createObjectURL(file);
                image.src = objectURL;
            } catch (error) {
                console.error('Error updating image:', error.message);
                alert('An error occurred while updating the image. Please try again.');
                input.value = '';  // Clear the selected file
            }
        } else {
            alert('Please upload a valid image file.');
            input.value = '';  // Clear the selected file
        }
    }
}