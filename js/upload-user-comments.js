
const uploadUserCommentsBtns = document.querySelectorAll('.upload-user-comments');

uploadUserCommentsBtns.forEach((btn) => {
    btn.addEventListener('click', (e) => {
        const userId = e.target.getAttribute('user_id');
        const userInputSectionId = e.target.getAttribute('user_input_section_id');
        const userDataName = e.target.getAttribute('userdata_name');
        const textareaId = e.target.getAttribute('textarea_id');
        const isThereData = e.target.getAttribute('is_there_data');
        const userInput = document.getElementById(textareaId).value;
        const userCommentId = e.target.getAttribute('user_comment_id');
        const stage = e.target.getAttribute('stage');

        fetch('http://unlimitedrevisions.ca/includes/add-comment-to-user.php', {
            method: 'POST',
            body: JSON.stringify({
                user_id: userId,
                user_input_section_id: userInputSectionId,
                userdata_name: userDataName,
                user_input: userInput,
                is_there_data: isThereData,
                user_comment_id: userCommentId,
                stage: stage
            }),
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(res => {
                return res.json();
            })
            .then(data => {
                console.log(data);
                if (data.status === "success") {
                    alert(data.message);
                } else {
                    alert(data.message);
                }

            })
            .catch(err => {
                console.log(err);
                alert('There was an error. Please try again later');
            })

    })
})