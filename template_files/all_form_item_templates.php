<?php

// The input text variable

"
<div id ='" . $id . "' class='accordion-item'>
    <h2 class='accordion-header'>
        <button class='accordion-button' type='button' data-bs-toggle='collapse' data-bs-target='#collapseOne' aria-expanded='true' aria-controls='collapseOne'>
            <input class='form-check-input mb-2' type='checkbox' value='' aria-label='Checkbox for following text input'>
            <div style='padding-left: 10px;'>
                <h6>" . $question_name . "</h6>
            </div>
        </button>
    </h2>
    <div id='collapseOne' class='accordion-collapse collapse show' data-bs-parent='#accordionExample'>
        <div class='accordion-body'>
            <textarea name='" . $name_of_question_in_database . "' class='form-control' rows='5' id='comment' placeholder='" . $place_holder_name . "'></textarea>
            <br>
        </div>
    </div>
</div>
";