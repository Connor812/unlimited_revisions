document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('journal-form');
  const submitBtn = document.getElementById('journal-submit-btn');
  let formChanged = false;
  console.log(submitBtn);

  // Add an event listener for form input changes
  form.addEventListener('change', function () {
    console.log('form has changed');
    formChanged = true;
    // Show the submit button
    submitBtn.classList.remove('hide');
  });

  // Add an event listener for form submission
  form.addEventListener('submit', function () {
    formChanged = false;
    // Hide the submit button after form submission
    submitBtn.style.display = 'none';
  });

  // Add an event listener for beforeunload
  window.addEventListener('beforeunload', function (e) {
    if (formChanged) {

      // You can prevent the page from unloading immediately by returning a confirmation message.
      e.returnValue = 'You have unsaved changes. Do you want to leave this page?';
    }

  });

  // ? This is going to change the background of the checkboxes if they are complete

  // Function to create the SVG element
  function createSvgElement() {
    const svgElement = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    svgElement.setAttribute("xmlns", "http://www.w3.org/2000/svg");
    svgElement.setAttribute("width", "24");
    svgElement.setAttribute("height", "24");
    svgElement.setAttribute("fill", "#198754");
    svgElement.setAttribute("class", "bi bi-check2-circle");
    svgElement.setAttribute("style", "margin-left: 20px;");
    svgElement.setAttribute("viewBox", "0 0 16 16");
    svgElement.innerHTML = '<path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0" />' +
      '<path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z" />';
    return svgElement;
  }

  const checkboxes = document.querySelectorAll(".checkbox-btn");

  checkboxes.forEach(function (checkbox) {
    // Get the parent section of the checkbox
    const accordionSection = checkbox.closest(".accordion");

    // Get all form elements within the accordion body
    const formElements = accordionSection.querySelectorAll(".accordion-body .form-check .checkbox, .accordion-body .form-check .textarea");

    // Use the Array.from() method to convert the NodeList to an array
    const formElementsArray = Array.from(formElements);

    // Check if any form element has information
    const hasInformation = formElementsArray.some(function (element) {
      if (element.classList.contains("checkbox")) {
        return element.checked;
      } else if (element.classList.contains("textarea")) {
        return element.value.trim() !== '';
      }
    });

    // Output a message based on whether there is information
    if (hasInformation) {
      console.log("Accordion with ID " + accordionSection.id + " has information");
      const svgElement = createSvgElement();
      checkbox.appendChild(svgElement);
    }
  });

  const storyBoxes = document.querySelectorAll(".story_box");

  storyBoxes.forEach(function (storyBox) {
    console.log(storyBox);
    const textarea = storyBox.querySelector(".accordion-body .story-box-textarea");
    const button = storyBox.querySelector(".accordion-button");

    if (textarea.value.trim() !== "") {

      const svgElement = createSvgElement();
      button.appendChild(svgElement);
    }
  });
});