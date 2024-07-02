// Variable to store the fetched data
let referencesData = [];

// Function to fetch data from the server and populate referencesData
function fetchData() {
    fetch('../includes/get-references.inc.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }
            referencesData = data; // Store the fetched data in referencesData variable
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
}

// Call fetchData function when the page loads
window.addEventListener('load', fetchData);

const pageSelector = document.getElementById("page-select");
const referenceSelector = document.getElementById("reference-select");

pageSelector.addEventListener("change", function (event) {
    event.preventDefault();
    const pageNum = pageSelector.value;

    // Reset options in referenceSelector
    referenceSelector.innerHTML = '<option value="no_reference_num" selected disabled>Please Select A Reference</option>';

    // Filter data based on pageNum from referencesData
    const filteredData = referencesData.filter(item => item.page_num == pageNum);

    // Append options to referenceSelector
    filteredData.forEach(item => {
        const option = document.createElement('option');
        option.value = item.userdata_name; // Change this to the appropriate property
        option.textContent = item.section_name; // Change this to the appropriate property
        referenceSelector.appendChild(option);
    });
});