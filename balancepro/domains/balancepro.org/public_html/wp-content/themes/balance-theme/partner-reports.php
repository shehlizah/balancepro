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
   // $websites = [];
    if ($wpdb->get_var("SHOW TABLES LIKE '{$white_label_websites_table_name}'") === $white_label_websites_table_name) {
        // Fetch websites if table exists
        //$websites = $wpdb->get_results("SELECT white_label_website_id, name title {$white_label_websites_table_name}");
        $wlw = $wpdb->get_results("SELECT white_label_website_id, title FROM {$white_label_websites_table_name}");


    }

    ob_start();
    ?>
    
    <!-- Date Picker, Report Format, and Generate Report Section -->
     <!-- <div class="module-wrapper wlw_admins-module-module-wrapper-0" data-visible-on="tab-11">-->
    <div class="dropdown-wrapper">
    <b style="font-size:12px;">Partner Website:</b>
    <input type="text" id="searchDropdown" placeholder="Search websites..." onkeyup="filterDropdown()">
    <div class="dropdown-content" id="websiteDropdown" style="max-height: 200px; overflow-y: auto;">
        <?php
        if (!empty($wlw)) {
            foreach ($wlw as $website) { ?>
                <div class="dropdown-item" data-id="<?php echo esc_attr($website->white_label_website_id); ?>">
                    <?php echo esc_html($website->title); ?>
                </div>
            <?php }
        } else { ?>
            <div class="dropdown-item">No websites available</div>
        <?php }
        ?>
    </div>

        <b style="font-size:12px;">Report Date:</b>
        <select name="reportDate" id="reportDate1" style="font-size:12px;">
            <option value="0"></option>
            <option value="1">Last Week</option>
            <option value="2">Last Month</option>
            <option value="3">Last Quarter</option>
            <option value="4">Last Year</option>
            <option value="5">This Year</option>
            <option value="6">Custom Date Range</option>
        </select>
        </div>
        <b style="font-size:12px;">&nbsp;&nbsp;&nbsp;From:</b>
        <input type="text" name="fromDate" id="fromDate1" style="font-size:12px;" class="datetimepicker" autocomplete="off" disabled>
        <b style="font-size:12px;">&nbsp;&nbsp;&nbsp;To:</b>
        <input type="text" name="toDate" id="toDate1" style="font-size:12px;" class="datetimepicker" autocomplete="off" disabled>

        <b style="font-size:12px;">&nbsp;&nbsp;&nbsp;Report Format:</b>
        <input type="radio" id="html" class="fav_language" name="fav_language" value="html"><label for="html" style="font-size:12px;">HTML</label>
        <input type="radio" id="pdf" class="fav_language" name="fav_language" value="pdf"><label for="pdf" style="font-size:12px;">PDF</label>
        <input type="radio" id="excel" class="fav_language" name="fav_language" value="excel"><label for="excel" style="font-size:12px;">Excel</label>
        <input type="radio" id="csv" class="fav_language" name="fav_language" value="csv"><label for="csv" style="font-size:12px;">CSV</label>

        <span id="generateReports" style="display: inline-block; padding: 5px 10px; font-size: 12px; font-weight: 600; text-align: center; text-decoration: none; color: #333; background-color: #e0e0e0; border: 1px solid #000; border-radius: 4px; transition: background-color 0.2s, color 0.2s, border-color 0.2s; cursor: pointer;">
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

  <script>
    let selectedWebsiteId = null; // Variable to store selected ID

    // Define the filterDropdown function before using it
    function filterDropdown() {
        const input = document.getElementById("searchDropdown");
        const filter = input.value.toLowerCase();
        const dropdown = document.getElementById("websiteDropdown");
        const items = dropdown.getElementsByClassName("dropdown-item");

        let hasMatchingItems = false; // Flag to check if there are matching items

        for (let i = 0; i < items.length; i++) {
            const text = items[i].textContent || items[i].innerText;
            if (text.toLowerCase().includes(filter)) {
                items[i].style.display = ""; // Show matching items
                hasMatchingItems = true; // Found matching items
            } else {
                items[i].style.display = "none"; // Hide non-matching items
            }
        }

        // If no items match, show "No websites available"
        if (!hasMatchingItems) {
            const noMatch = document.createElement("div");
            noMatch.classList.add("dropdown-item");
            noMatch.textContent = "No matching websites found";
            dropdown.appendChild(noMatch);
        }

        // Hide the dropdown if input is empty
        dropdown.style.display = filter ? "block" : "none";
    }

    jQuery(document).ready(function ($) {

        // Initialize datepickers
        $("#fromDate1, #toDate1").datepicker({ dateFormat: "mm/dd/yy" });

        // Handle report date selection logic
        $("#reportDate1").change(function () {
            const selectedOption = $(this).val();
            const now = new Date();
            let fromDate, toDate;

            // Reset date fields
            $("#fromDate1, #toDate1").val("").prop("disabled", true);

            if (selectedOption == "1") {
                // Last Week
                const lastWeekStart = new Date(now);
                lastWeekStart.setDate(now.getDate() - now.getDay() - 7);
                const lastWeekEnd = new Date(lastWeekStart);
                lastWeekEnd.setDate(lastWeekStart.getDate() + 6);

                $("#fromDate1").datepicker("setDate", lastWeekStart);
                $("#toDate1").datepicker("setDate", lastWeekEnd);
            } else if (selectedOption == "2") {
                // Last Month
                fromDate = new Date(now.getFullYear(), now.getMonth() - 1, 1);
                toDate = new Date(now.getFullYear(), now.getMonth(), 0);

                $("#fromDate1").datepicker("setDate", fromDate);
                $("#toDate1").datepicker("setDate", toDate);
            } else if (selectedOption == "3") {
                // Last Quarter
                const currentMonth = now.getMonth();
                const quarterStartMonth = Math.floor(currentMonth / 3) * 3 - 3;
                fromDate = new Date(now.getFullYear(), quarterStartMonth, 1);
                toDate = new Date(now.getFullYear(), quarterStartMonth + 3, 0);

                $("#fromDate1").datepicker("setDate", fromDate);
                $("#toDate1").datepicker("setDate", toDate);
            } else if (selectedOption == "4") {
                // Last Year
                fromDate = new Date(now.getFullYear() - 1, 0, 1);
                toDate = new Date(now.getFullYear() - 1, 11, 31);

                $("#fromDate1").datepicker("setDate", fromDate);
                $("#toDate1").datepicker("setDate", toDate);
            } else if (selectedOption == "5") {
                // This Year
                fromDate = new Date(now.getFullYear(), 0, 1);
                toDate = now;

                $("#fromDate1").datepicker("setDate", fromDate);
                $("#toDate1").datepicker("setDate", toDate);
            } else if (selectedOption == "6") {
                // Custom Date Range
                $("#fromDate1, #toDate1").prop("disabled", false);
            }
        });

        // Handle dropdown item selection
        $('#websiteDropdown').on('click', '.dropdown-item', function () {
            const selectedValue = $(this).text().trim();
            selectedWebsiteId = $(this).data('id');

            $('#searchDropdown').val(selectedValue);
            $('#websiteDropdown').hide(); // Hide the dropdown

            console.log('Selected Website ID:', selectedWebsiteId);
        });

        // Filter dropdown based on user input
        $('#searchDropdown').keyup(function () {
            filterDropdown(); // Call the filterDropdown function directly
        });

        // Handle generate report button click
        $("#generateReports").click(function () {
            const selectedWebsite = $("#searchDropdown").val().trim();
            const reportDate = $("#reportDate1").val();
            const fromDate = $("#fromDate1").val();
            const toDate = $("#toDate1").val();
            const format = $("input[name='fav_language']:checked").val();

            if (!selectedWebsite || !reportDate || !fromDate || !toDate || !format) {
                alert("Please select all options before generating the report.");
                return;
            }

            console.log("Generating report for:", selectedWebsite);
            console.log("Generating report for:", selectedWebsiteId);
            // AJAX request to call the PHP function and fetch data
            $.ajax({
                url: ajaxurl, // WordPress AJAX URL
                method: 'POST',
                data: {
                    action: 'get_partner_report', // Custom action
                    white_label_website_id: selectedWebsiteId,
                    queryFrom: fromDate,
                    queryTo: toDate,
                    queryFormat: format,
                    reportDate: reportDate
                },
                success: function(response) {
                    // Assuming response contains HTML for the table rows
                    $('#reportTable tbody').empty(); // Clear previous rows
                    $('#reportTable tbody').append(response); // Append new rows
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching report:", error);
                    alert("An error occurred while fetching the report.");
                }
            });
        });
            // Your report generation logic goes here
    });
</script>

    <?php
    echo ob_get_clean();
}
?>
<style>

#websiteDropdown {
    display: none; /* Hide the dropdown initially */
}

.dropdown-content {
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: #fff;
}

.dropdown-item {
    padding: 8px;
    cursor: pointer;
}

.dropdown-item:hover {
    background-color: #f1f1f1;
}

.dropdown-wrapper {
    position: relative;
    width: 50%;
}

.dropdown-content {
    max-height: 200px;
    overflow-y: auto;
    position: absolute;
    top: 100%;
    width: 50%;
    z-index: 999;
}

    #generateReports {
        background-color: #e0e0e0;
        cursor: not-allowed;
        padding: 10px 20px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
        color: #333;
    }

    #generateReports:enabled {
        background-color: #4CAF50;
        cursor: pointer;
        color: white;
    }

    .fav_language {
        margin-left: 5px;
    }
</style>
