document.addEventListener('DOMContentLoaded', function () {

    const buttons = document.querySelectorAll('.add-section-btn');
    // For each button add an event listener to grab the section_id from the button
    buttons.forEach(button => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            const sectionId = button.getAttribute('section_id');
            console.log(sectionId);
            // Set the section_id the the button-modal div to grab when the type button is clicked
            const modalSection = document.getElementById('button-modal');
            modalSection.setAttribute('section_id', sectionId);
        });
    });

    //  <----------------------- End Of Placing Section id on button modal --------------------------->

    // adds an event listener on each button to add a new section so that it can add an action to the form with the corresponding section id
    modalButtons = document.querySelectorAll('.modal_button');
    modalButtons.forEach(modalButton => {
        modalButton.addEventListener('click', (event) => {
            event.preventDefault();
            const sectionId = document.getElementById('button-modal').getAttribute('section_id');
            const selectedElement = document.getElementById('selected_page');
            const page_num = selectedElement.value;
            console.log(page_num + ' <---page_num');
            const formType = modalButton.getAttribute('form_type');
            console.log(formType);

            if (formType == 'heading') {
                const headingForm = document.getElementById('heading_form');
                headingForm.setAttribute('action', `includes/modal_form/heading.inc.php?section_id=${sectionId}&page_num=${page_num}`);
            }
            else if (formType == 'subheading') {
                const subheadingForm = document.getElementById('subheading_form');
                subheadingForm.setAttribute('action', `includes/modal_form/subheading.inc.php?section_id=${sectionId}&page_num=${page_num}`);
            }
            else if (formType == 'quote') {
                const quoteForm = document.getElementById('quote_form');
                quoteForm.setAttribute('action', `includes/modal_form/quote.inc.php?section_id=${sectionId}&page_num=${page_num}`);
            }
            else if (formType == 'byline') {
                const bylineForm = document.getElementById('byline_form');
                bylineForm.setAttribute('action', `includes/modal_form/byline.inc.php?section_id=${sectionId}&page_num=${page_num}`);
            }
            else if (formType == 'story_box') {
                const story_boxForm = document.getElementById('story_box_form');
                story_boxForm.setAttribute('action', `includes/modal_form/story_box.inc.php?section_id=${sectionId}&page_num=${page_num}`);
            }
            else if (formType == 'video') {
                const videoForm = document.getElementById('video_form');
                videoForm.setAttribute('action', `includes/modal_form/video.inc.php?section_id=${sectionId}&page_num=${page_num}`);
            }
            else if (formType == 'check_box') {
                const check_boxForm = document.getElementById('check_box_form');
                check_boxForm.setAttribute('action', `includes/modal_form/check_box.inc.php?section_id=${sectionId}&page_num=${page_num}`);
            }
            else if (formType == 'check_list') {
                const check_listForm = document.getElementById('check_list_form');
                check_listForm.setAttribute('action', `includes/modal_form/check_list.inc.php?section_id=${sectionId}&page_num=${page_num}`);
            }
            else if (formType == 'image') {
                const imageForm = document.getElementById('image_form');
                imageForm.setAttribute('action', `includes/modal_form/image.inc.php?section_id=${sectionId}&page_num=${page_num}`);
            }
            else if (formType == 'bullets') {
                const bulletsForm = document.getElementById('bullet_form');
                bulletsForm.setAttribute('action', `includes/modal_form/bullets.inc.php?section_id=${sectionId}&page_num=${page_num}`);
            }
            else if (formType == 'text') {
                const textForm = document.getElementById('text_form');
                textForm.setAttribute('action', `includes/modal_form/text.inc.php?section_id=${sectionId}&page_num=${page_num}`);
            }
            else if (formType == 'comment') {
                const commentForm = document.getElementById('comment_form');
                commentForm.setAttribute('action', `includes/modal_form/comment.inc.php?section_id=${sectionId}&page_num=${page_num}`);
            }
            else if (formType == 'reference') {
                const referenceForm = document.getElementById('reference_form');
                referenceForm.setAttribute('action', `includes/modal_form/reference.inc.php?section_id=${sectionId}&page_num=${page_num}`);
            }
            else if (formType == 'user-input-table') {
                const referenceForm = document.getElementById('user-input-table-form');
                referenceForm.setAttribute('action', `includes/modal_form/user-input-table.inc.php?section_id=${sectionId}&page_num=${page_num}&part=1`);
            }
            else if (formType == 'user-input-table-2') {
                const referenceForm = document.getElementById('user-input-table-form');
                referenceForm.setAttribute('action', `includes/modal_form/user-input-table.inc.php?section_id=${sectionId}&page_num=${page_num}&part=2`);
            }
        });
    });

    //  <----------------------- End Of Setting Action to Modal Form --------------------------->

    // This gets all the delete buttons and add an event listener to all of them
    const delete_btns = document.querySelectorAll('.delete-section-btn');
    delete_btns.forEach(delete_btn => {
        delete_btn.addEventListener('click', function (event) {
            event.preventDefault();
            const section_id = delete_btn.getAttribute('section_id');
            const selectedElement = document.getElementById('selected_page');
            const page_num = selectedElement.value;
            const delete_section_form = document.getElementById('delete_section_form');
            // This sets the action of the delete button to the delete modal so the delete_section.php file can handle it
            delete_section_form.setAttribute('action', `includes/delete_section.inc.php?section_id=${section_id}&page_num=${page_num}`);
        });
    });

    // This will get the delete page button.
    if (document.getElementById('delete-page-btn')) {
        const delete_page_btn = document.getElementById('delete-page-btn');
        delete_page_btn.addEventListener('click', function (event) {
            event.preventDefault();
            const page_num = delete_page_btn.getAttribute('page_num');
            const delete_page_form = document.getElementById('delete_page_form');
            // This sets the action of the delete button to the delete modal so the delete_section.php file can handle it
            delete_page_form.setAttribute('action', `includes/delete_page.inc.php?page_num=${page_num}`);
        });
    }

    //  <----------------------- End Of Setting action to delete Form --------------------------->

    const click_list_input_container = document.getElementById('click_list_input_container');
    // Add a click event listener to each item
    const addCheckboxBtn = document.getElementById("add_item_btn");
    // This will will add a section in the click list container depending on if the user selected the checkbox or text area

    if (addCheckboxBtn) {
        addCheckboxBtn.addEventListener('click', function (event) {
            event.preventDefault(); // Prevent the default link behavior

            const numOfItems = click_list_input_container.children.length;

            const newItem = `
                <div id="item_${numOfItems}">
                    <input type="hidden" name="item_type[]" value="checkbox"> <!-- Hidden field for the type -->
                    <input type="hidden" name="placeholder_text[]" value=""> <!-- Hidden field for the type -->
                    <label class="d-flex justify-content-start">Check Box Title</label>
                    <input name="item_title[]" placeholder="Check Box Title/Question" type="text" class="form-control" />
                    <input type="hidden" name="item_type[]" value="textarea"> <!-- Hidden field for the type -->
                    <label class="d-flex justify-content-start">Textarea Title</label>
                    <input name="item_title[]" placeholder="Textarea Title/Question" type="text" class="form-control" />
                    <label class="d-flex justify-content-start">Placeholder text</label>
                    <input name="placeholder_text[]" placeholder="Examples/Explanation of question" type="text" class="form-control" />
                    <button class="btn btn-danger delete_item_btn" value="item_${numOfItems}"><i class="fas fa-trash-alt"></i></button>
                </div>`;
            click_list_input_container.insertAdjacentHTML('beforeend', newItem);
            // This calls the query selector all to get the new delete buttons
            getAllDeleteItemBtns();
        });
    }
    //  <----------------------- End Of Add Click List Item Section --------------------------->

    // This will add a bullet to the bullet modal so the user can add as many bullets as she wants
    const add_bullet_btn = document.getElementById('add_bullet_btn');
    const bullet_input_container = document.getElementById('bullet_input_container');

    if (add_bullet_btn) {
        add_bullet_btn.addEventListener('click', (event) => {
            event.preventDefault();
            const numOfItems = bullet_input_container.children.length;
            const newBullet = `
        <div id=bullet_${numOfItems}>
            <label class="form-label">Bullet Content</label>
            <input name="bullet_content[]" type="text" class="form-control"
            placeholder="Please enter bullet content" />
            <button class="btn btn-danger delete_item_btn" value="bullet_${numOfItems}"><i class="fas fa-trash-alt"></i></button>
        </div>
        `;
            bullet_input_container.insertAdjacentHTML('beforeend', newBullet);
            getAllDeleteItemBtns();
        });
    }

    //  <----------------------- End Of Add Bullet Button --------------------------->

    // Function that will get all the delete item buttons, add an event listener to delete the item section if not needed
    function getAllDeleteItemBtns() {
        const deleteItemButtons = document.querySelectorAll('.delete_item_btn');
        console.log(deleteItemButtons);
        deleteItemButtons.forEach(function (deleteItemButton) {
            deleteItemButton.addEventListener('click', (event) => {
                event.preventDefault();
                const itemToDeleteId = deleteItemButton.getAttribute('value');
                console.log(itemToDeleteId);
                document.getElementById(itemToDeleteId).remove();
            });
        });
    }
    //  <----------------------- End Delete Item Button Event Listeners --------------------------->

    // This will add an event listener to all the delete buttons to add a hover event listener so that when hovered, it will show the section she will add, delete or edit

    const deleteBtns = document.querySelectorAll('.delete-section-btn');

    deleteBtns.forEach(deleteBtn => {
        deleteBtn.addEventListener('mouseenter', (event) => {
            event.preventDefault();
            const section_id = deleteBtn.getAttribute('section_id');
            const section = document.getElementById(section_id);
            section.classList.add('delete-section');
        });
    });
    deleteBtns.forEach(deleteBtn => {
        deleteBtn.addEventListener('mouseout', (event) => {
            event.preventDefault();
            const section_id = deleteBtn.getAttribute('section_id');
            const section = document.getElementById(section_id);
            section.classList.remove('delete-section');
        });
    });

    //  <----------------------- End Delete Button Event Listeners --------------------------->

    // This will add an event listener to all the add buttons to add a hover event listener so that when hovered, it will show the section she will add, delete or edit
    const addSectionBtns = document.querySelectorAll('.add-section-btn');

    addSectionBtns.forEach(addBtn => {
        addBtn.addEventListener('mouseover', (event) => {
            event.preventDefault();
            const section_id = addBtn.getAttribute('section_id');
            const section = document.getElementById(`add${section_id}`);
            section.classList.remove('hide');
        });

        addBtn.addEventListener('mouseout', (event) => {
            event.preventDefault();
            const section_id = addBtn.getAttribute('section_id');
            const section = document.getElementById(`add${section_id}`);
            section.classList.add('hide');
        });
    });

    //  <----------------------- End Add Button Event Listeners --------------------------->

    // This will add an event listener to all the edit buttons to add a hover event listener so that when hovered, it will show the section she will add, delete or edit
    const editBtns = document.querySelectorAll('.edit-section-btn');

    editBtns.forEach(editBtn => {
        editBtn.addEventListener('mouseenter', (event) => {
            event.preventDefault();
            const section_id = editBtn.getAttribute('section_id');
            const section = document.getElementById(section_id);
            section.classList.add('edit-section');
        });
    });
    editBtns.forEach(editBtn => {
        editBtn.addEventListener('mouseout', (event) => {
            event.preventDefault();
            const section_id = editBtn.getAttribute('section_id');
            const section = document.getElementById(section_id);
            section.classList.remove('edit-section');
        });
    });

    //  <----------------------- End Edit Button Event Listeners --------------------------->

    // If There is the floating error or success handler, then add an event listener to the remove button, and remove the container once clicked
    if (document.getElementById('floating-error-btn')) {
        const floatingErrorBtn = document.getElementById('floating-error-btn');
        floatingErrorBtn.addEventListener('click', (event) => {
            event.preventDefault();
            const idOfErrorBox = floatingErrorBtn.value;
            const errorBox = document.getElementById(idOfErrorBox);
            errorBox.remove();
        });
    }

    console.log(document.getElementById('floating-success-btn'));
    if (document.getElementById('floating-success-btn')) {
        const floatingSuccessBtn = document.getElementById('floating-success-btn');
        floatingSuccessBtn.addEventListener('click', (event) => {
            event.preventDefault();
            const idOfSuccessBox = floatingSuccessBtn.value;
            const successBox = document.getElementById(idOfSuccessBox);
            successBox.remove();
        });
    }

    //  <----------------------- End Of Floating error or success handlers --------------------------->
});


// This will check all the input forms for the click list items and make sure there are no special characters in the item_userdata_name section
function changeInput(input) {
    const inputString = input.value;
    for (let i = 0; i < inputString.length; i++) {
        const char = inputString[i];

        // Check if the character is a letter (uppercase or lowercase) or a space
        if (!((char >= 'a' && char <= 'z') || (char >= 'A' && char <= 'Z') || char === ' ')) {
            const error = input.getAttribute('error');
            const errorContainer = document.getElementById(error);
            errorContainer.innerText = "No Special Characters Please";
            errorContainer.classList.remove('hide');
            input.classList.add('error');
            return false;  // Character is not valid
        } else {
            const error = input.getAttribute('error');
            const errorContainer = document.getElementById(error);
            errorContainer.innerText = "";
            errorContainer.classList.add('hide');
            input.classList.remove('error');
        }
    }

    return true;  // All characters are valid
}

//  <----------------------- End Of Error Check for Special Characters in click list item username --------------------------->