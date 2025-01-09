<?php
// Callback function to render the Partner Reports page
function partner_reports_page() {
  ?>
  <div class="module-wrapper wlw_admins-module-module-wrapper-0" data-visible-on="tab-11">
    <?php
    global $wpdb, $post;

    // Check if the table exists and fetch white-label websites
    $prefix = $wpdb->prefix;
    $white_label_websites_table_name = $prefix . 'white_label_websites';
    $websites = [];
   $wlw = $wpdb->get_results("SELECT white_label_website_id, title FROM {$white_label_websites_table_name}");
    if ($wpdb->get_var("SHOW TABLES LIKE '{$white_label_websites_table_name}'") === $white_label_websites_table_name) {
        // Fetch websites if table exists
        $websites = $wpdb->get_results("SELECT white_label_website_id, name FROM {$white_label_websites_table_name}");

    }

    ob_start();
    ?>

    


    <!-- Date Picker, Report Format, and Generate Report Section -->
    <div class="module-wrapper wlw_admins-module-module-wrapper-0" data-visible-on="tab-11">
    <div class="dropdown">
    <button onclick="myFunction()" class="dropbtn">Select White-Label Website</button>
    <div id="myDropdown" class="dropdown-content">
        
        

        <!-- Check if the $wlw array is not empty -->
        <?php if (!empty($wlw)) : ?>
            <!-- Loop through each white-label website and create a div with a data attribute for website ID -->
            <input type="text" placeholder="Search..." id="myInput" onkeyup="filterFunction()">
            <select id="searchableDropdown">
                 <!-- Default option -->
                
                <!--<input type="text" id="searchInput" placeholder="Search options">-->
                <?php foreach ($wlw as $website) : ?>
                    <!-- Add options dynamically with the website ID as value -->
                    <option value="<?php echo esc_attr($website->white_label_website_id); ?>">
                        <?php echo esc_html($website->title); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php else : ?>
                <a href="#">No websites available</a>
            <?php endif; ?>
        </div>
    </div>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>

        <b style="font-size:12px;">Report Date:</b>
        <select name="reportDate" id="reportDate" style="font-size:12px;">
            <option value="0"></option>
            <option value="1">Last Week</option>
            <option value="2">Last Month</option>
            <option value="3">Last Quarter</option>
            <option value="4">Last Year</option>
            <option value="5">This Year</option>
            <option value="6">Custom Date Range</option>
        </select>

        <b style="font-size:12px;">&nbsp;&nbsp;&nbsp;From:</b>
        <input type="text" name="fromDate" id="fromDate" style="font-size:12px;" class="datetimepicker" autocomplete="off" disabled>
        <b style="font-size:12px;">&nbsp;&nbsp;&nbsp;To:</b>
        <input type="text" name="toDate" id="toDate" style="font-size:12px;" class="datetimepicker" autocomplete="off" disabled>

        <b style="font-size:12px;">&nbsp;&nbsp;&nbsp;Report Format:</b>
        <input type="radio" id="html" class="fav_language" name="fav_language" value="html"><label for="html" style="font-size:12px;">HTML</label>
        <input type="radio" id="pdf" class="fav_language" name="fav_language" value="pdf"><label for="pdf" style="font-size:12px;">PDF</label>
        <input type="radio" id="excel" class="fav_language" name="fav_language" value="excel"><label for="excel" style="font-size:12px;">Excel</label>
        <input type="radio" id="csv" class="fav_language" name="fav_language" value="csv"><label for="csv" style="font-size:12px;">CSV</label>

        <span id="generateReport" style="display: inline-block; padding: 5px 10px; font-size: 12px; font-weight: 600; text-align: center; text-decoration: none; color: #333; background-color: #e0e0e0; border: 1px solid #000; border-radius: 4px; transition: background-color 0.2s, color 0.2s, border-color 0.2s; cursor: pointer;">
            Generate Report
        </span>

        <table class="wp-list-table widefat striped posts" id="reportTable" style="margin-top: 20px;">
            <thead>
                <tr>
                    <th>Credit Union</th>
                    <th>Date</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Street Address</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Zip</th>
                    <th>Email</th>
                    <th>Name of Quiz</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>
                <!-- Rows will be dynamically inserted here -->
            </tbody>
        </table>
    </div>
  </div>

    <!-- JavaScript for Dropdown and Report Functionality -->
    <script>
         // Function to show or hide the dropdown
    function myFunction() {
        document.getElementById("myDropdown").classList.toggle("show");
    }

    
    function filterFunction() {
        var input, filter, dropdown, items, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        dropdown = document.getElementById("myDropdown");
        items = dropdown.getElementsByClassName("dropdown-item"); // Get all items

        // Loop through all dropdown items and hide those who don't match the search query
        for (i = 0; i < items.length; i++) {
            txtValue = items[i].textContent || items[i].innerText;
            // If the search input doesn't match, hide the item
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                items[i].style.display = "";
            } else {
                items[i].style.display = "none";
            }
        }
    }
    // Enabling the "Generate Report" button when a white-label website is selected
    document.getElementById("searchableDropdown").addEventListener("change", function() {
            var selectedWebsite = this.value;
            var generateButton = document.getElementById("generateReport");

            if (selectedWebsite !== "") {
                generateButton.style.backgroundColor = "#4CAF50"; // Green color
                generateButton.style.cursor = "pointer";
                generateButton.removeAttribute("disabled");
            } else {
                generateButton.style.backgroundColor = "#e0e0e0"; // Disabled color
                generateButton.style.cursor = "not-allowed";
                generateButton.setAttribute("disabled", "disabled");
            }
        });
    document.getElementById("searchInput").addEventListener("input", function() {
        const searchValue = this.value.toLowerCase();
const dropdown = document.getElementById("searchableDropdown");
        const options = dropdown.querySelectorAll("option");

        options.forEach(option => {
            if (option.textContent.toLowerCase().includes(searchValue)) {
                option.style.display = ""; // Show matching option
            } else {
                option.style.display = "none"; // Hide non-matching option
            }
        });
    });
    // Handle the click on a dropdown item and use its value
    document.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', function() {
            var selectedValue = this.getAttribute('data-value');
            console.log("Selected Website ID: " + selectedValue); // Handle this value as needed
        });
    });
        jQuery(document).ready(function () {
            jQuery(".datetimepicker").datepicker();

            jQuery("#reportDate").change(function () {
                const selectedOption = jQuery(this).val();
                const now = new Date();

                if (selectedOption === "0") {
                    jQuery("#fromDate, #toDate").val("").prop("disabled", true);
                } else if (selectedOption === "1") {
                    const lastWeekStart = new Date(now.setDate(now.getDate() - 7));
                    const lastWeekEnd = new Date(now.setDate(now.getDate() + 6));
                    jQuery("#fromDate").datepicker("setDate", lastWeekStart).prop("disabled", true);
                    jQuery("#toDate").datepicker("setDate", lastWeekEnd).prop("disabled", true);
                } else if (selectedOption === "6") {
                    jQuery("#fromDate, #toDate").prop("disabled", false);
                }
            });

            jQuery("#generateReport").click(function () {
                const reportDate = jQuery("#reportDate").val();
                const fromDate = jQuery("#fromDate").val();
                const toDate = jQuery("#toDate").val();
                const format = jQuery("input[name='fav_language']:checked").val();

                if (!format || !fromDate || !toDate) {
                    alert("Please select all options before generating the report.");
                    return;
                }

                const url = format === "pdf"
                    ? `/wp-content/themes/balance-theme/inc/edit/modules/exportpdf.php?fromDate=${fromDate}&toDate=${toDate}&format=${format}`
                    : `/wp-content/themes/balance-theme/inc/edit/modules/export.php?fromDate=${fromDate}&toDate=${toDate}&format=${format}`;

                window.location.href = url;
            });
        });
    </script>

    <?php
    echo ob_get_clean();
}
?>
<style>
    /* Style for dropdown */
    .dropdown {
        position: relative;
        display: inline-block;
        width: 100%;
        max-width: 161px;
    }

    .dropdown .dropbtn {
        background-color: #4CAF50;
        color: white;
        padding: 5px;
        font-size: 12px;
        font-weight: 600;;
        border: none;
        cursor: pointer;
        width: 100%;
        margin-top:10px;
        margin-bottom: 10px;;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 200px;
        box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        z-index: 1;
        max-height: 200px;
        overflow-y: auto;
    }

    .dropdown-content .dropdown-select {
        width: 100%;
        padding: 8px;
        margin: 8px 0;
        border: 1px solid #ddd;
        font-size: 14px;
    }

    .dropdown-content input {
        width: 100%;
        padding: 8px;
        margin: 8px 0;
        border: 1px solid #ddd;
    }

    .dropdown-content .dropdown-item {
        padding: 8px;
        display: block;
        cursor: pointer;
        text-decoration: none;
        color: #333;
    }

    .dropdown-content .dropdown-item:hover {
        background-color: #ddd;
    }

    .dropdown .show {
        display: block;
    }

    .datetimepicker {
        width: 150px;
        padding: 8px;
    }

    #generateReport {
        background-color: #e0e0e0;
        cursor: not-allowed;
        padding: 10px 20px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
        color: #333;
    }

    #generateReport:enabled {
        background-color: #4CAF50;
        cursor: pointer;
        color: white;
    }

    .fav_language {
        margin-left: 5px;
    }
</style>
