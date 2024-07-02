const checkboxes = document.querySelectorAll(".checkbox");

checkboxes.forEach(checkbox => {
    addEventListener("click", () => {
        
        const checkboxId = parseInt(checkbox.getAttribute("checkbox-id"));
        const state = checkbox.checked;
        
        if (state == true) {
            const textarea = document.getElementById(`textarea-${checkboxId + 1}`);
            textarea.classList.remove("hidden");
        } else {
            const textarea = document.getElementById(`textarea-${checkboxId + 1}`);
            textarea.classList.add("hidden");
        }

    });

});