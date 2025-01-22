<?php
// Callback function to render the Partner Reports page

function partner_reports_page() {
  ?>
  <!-- <div class="module-wrapper wlw_admins-module-module-wrapper-0" data-visible-on="tab-11"> -->
  <div class="wrap">

  <h1 class="wp-heading-inline">Partner Reports </h1><br><br>
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
    <b style="font-size:16px;">Partner Website:</b>
    <input type="text" id="searchDropdown" style="width:30%; font-size:15px;"  placeholder="Search websites..." onkeyup="filterDropdown()">
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

        <b style="font-size:16px;padding-left:98px;">Report Date:</b>
        
        <select name="reportDate" id="reportDate1" style="font-size:15px; mrgin-top:-3px;">
            <option value="0"></option>
            <option value="1">Last Week</option>
            <option value="2">Last Month</option>
            <option value="3">Last Quarter</option>
            <option value="4">Last Year</option>
            <option value="5">This Year</option>
            <option value="6">Custom Date Range</option>
        </select>
        </div>
        <br>
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

        <table class="wp-list-table widefat striped posts" id="reportTable1" style="margin-top: 20px;">
            
        </table>
    </div>
  </div>
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
	
    
}

.dropdown-content {
    max-height: 200px;
    overflow-y: auto;
    position: absolute;
    top: 100%;
    width: 30%;
    z-index: 999;
	left:11%;
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

    function getPageNum(e) {
    var fromDateVal = jQuery("#fromDate1").val(); // retrieve the value
    console.log("fromDate1: " + fromDateVal); // Debugging log
    var toDateVal = jQuery("#toDate1").val(); // retrieve the value
    console.log("toDate1: " + toDateVal); // Debugging log
    var reportDateVal = jQuery("#reportDate1").val(); // retrieve the value
    console.log("reportDate1: " + reportDateVal); // Debugging log
    //var selectedWebsiteId = jQuery("#searchDropdown").val();
    console.log("selectedWebsiteId: " + selectedWebsiteId); // Debugging log
    var pageNum = e;

    jQuery.ajax({
        url: "/wp-content/themes/balance-theme/inc/edit/modules/showexportdata.php", // Adjust path
        type: "POST",
        data: {
            fromDateVal: fromDateVal,
            toDateVal: toDateVal,
            wlwVal: selectedWebsiteId,
            reportDateVal: reportDateVal,
            pageNum: pageNum
        },

        success: function(result) {
                        
                        jQuery("#reportTable1").innerHTML=result;
                        jQuery("#reportTable1").html(result);

                        }
    });
}



    jQuery(document).ready(function ($) {

        // Initialize datepickers
        //$("#fromDate1, #toDate1").datepicker({ dateFormat: "Y-m-d" });
        $("#fromDate1, #toDate1").datepicker({ dateFormat: "yy-mm-dd" });


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

            // Format the dates as 'YYYY-MM-DD'
            fromDate = formatDate(lastWeekStart);
            toDate = formatDate(lastWeekEnd);

            $("#fromDate1").datepicker("setDate", lastWeekStart);
            $("#toDate1").datepicker("setDate", lastWeekEnd);
        } else if (selectedOption == "2") {
            // Last Month
            fromDate = new Date(now.getFullYear(), now.getMonth() - 1, 1);
            toDate = new Date(now.getFullYear(), now.getMonth(), 0);

            // Format the dates as 'YYYY-MM-DD'
            fromDate = formatDate(fromDate);
            toDate = formatDate(toDate);

            $("#fromDate1").datepicker("setDate", fromDate);
            $("#toDate1").datepicker("setDate", toDate);
        } else if (selectedOption == "3") {
            // Last Quarter
            const currentMonth = now.getMonth();
            const quarterStartMonth = Math.floor(currentMonth / 3) * 3 - 3;
            fromDate = new Date(now.getFullYear(), quarterStartMonth, 1);
            toDate = new Date(now.getFullYear(), quarterStartMonth + 3, 0);

            // Format the dates as 'YYYY-MM-DD'
            fromDate = formatDate(fromDate);
            toDate = formatDate(toDate);

            $("#fromDate1").datepicker("setDate", fromDate);
            $("#toDate1").datepicker("setDate", toDate);
        } else if (selectedOption == "4") {
            // Last Year
            fromDate = new Date(now.getFullYear() - 1, 0, 1);
            toDate = new Date(now.getFullYear() - 1, 11, 31);

            // Format the dates as 'YYYY-MM-DD'
            fromDate = formatDate(fromDate);
            toDate = formatDate(toDate);

            $("#fromDate1").datepicker("setDate", fromDate);
            $("#toDate1").datepicker("setDate", toDate);
        } else if (selectedOption == "5") {
            // This Year
            fromDate = new Date(now.getFullYear(), 0, 1);
            toDate = now;

            // Format the dates as 'YYYY-MM-DD'
            fromDate = formatDate(fromDate);
            toDate = formatDate(toDate);

            $("#fromDate1").datepicker("setDate", fromDate);
            $("#toDate1").datepicker("setDate", toDate);
        } else if (selectedOption == "6") {
            // Custom Date Range
            $("#fromDate1, #toDate1").prop("disabled", false);
        }

        // Get the selected website ID and report date
        const selectedWebsite = $("#searchDropdown").val().trim();
        const reportDate = $("#reportDate1").val();

        console.log({ fromDate, toDate, selectedWebsiteId, reportDate });

        $.ajax({
                        url: "/wp-content/themes/balance-theme/inc/edit/modules/showexportdata.php",
                        type: "POST",
                        data: {
                            fromDateVal: fromDate,
                            toDateVal: toDate,
                            wlwVal: selectedWebsiteId,
                            reportDateVal: reportDate
                          },
                        success: function(result) {
                                if(reportDate=="0"){
                                $( "span#generateReports" ).css({"pointer-events": "none","cursor":"default"});
                                        $("#reportTable1").html("Please Select Report Date");

                                }else if(result.trim()=="No data available"){
                                        $("#reportTable1").html("No data available");
                                $( "span#generateReports" ).css({"pointer-events": "none","cursor":"default"});
                                }
                                if(result.trim()!="No data available") {

                                $( "span#generateReports" ).css({"cursor": "pointer","pointer-events": "auto"});
                                        $("#reportTable1").html(result);
                                }
                            $("#reportTable1").innerHTML=result;

                        }

                });
               

    });
    $("#toDate1").change(function () {
            var fromDateVal = $("#fromDate1").val(); // retrieve the value
            var toDateVal = $("#toDate1").val(); // retrieve the value
            var reportDateVal = $("#reportDate1").val(); // retrieve the value
            //var wlwVal = $("#wlwid").val();

$.ajax({
                        url: "/wp-content/themes/balance-theme/inc/edit/modules/showexportdata.php",
                        type: "POST",
                        data: {
                            fromDateVal: fromDateVal,
                            toDateVal: toDateVal,
                            wlwVal: selectedWebsiteId,
                            reportDateVal: reportDateVal
                          },
                        success: function(result) {
if(reportDateVal=="0"){
$( "span#generateReport1" ).css({"pointer-events": "none","cursor":"default"});
        $("#reportTable1").html("Please Select Report Date");

}else if(result.trim()=="No data available"){
        $("#reportTable1").html("No data available");
$( "span#generateReport1" ).css({"pointer-events": "none","cursor":"default"});
}
if(result.trim()!="No data available") {

$( "span#generateReport1" ).css({"cursor": "pointer","pointer-events": "auto"});
        $("#reportTable1").html(result);
}
                                $("#reportTable1").innerHTML=result;

                        }

                });
});
   

    // Function to format date as 'YYYY-MM-DD'
    function formatDate(date) {
        const year = date.getFullYear();
        const month = ("0" + (date.getMonth() + 1)).slice(-2); // Add leading zero for single digit months
        const day = ("0" + date.getDate()).slice(-2); // Add leading zero for single digit days
        return `${year}-${month}-${day}`;
    }

        // Handle dropdown item selection
        $('#websiteDropdown').on('click', '.dropdown-item', function () {
            const selectedValue = $(this).text().trim();
            selectedWebsiteId = $(this).data('id');

            $('#searchDropdown').val(selectedValue);
            $('#websiteDropdown').hide(); // Hide the dropdown

            console.log('Selected Website ID:', selectedWebsiteId);
		 $('#reportDate1').val('0'); // Reset to default value
    $('#fromDate1, #toDate1').val('').prop('disabled', true);

    // Clear the report table
    $('#reportTable1').html('');
        });

        // Filter dropdown based on user input
        $('#searchDropdown').keyup(function () {
            filterDropdown(); // Call the filterDropdown function directly
        });

        // Handle generate report button click
        $("#generateReports").click(function () {
            const reportDate = jQuery("#reportDate1").val();
                const fromDate = jQuery("#fromDate1").val();
                const toDate = jQuery("#toDate1").val();
                const format = jQuery("input[name='fav_language']:checked").val();

                if (!format || !fromDate || !toDate) {
                    alert("Please select all options before generating the report.");
                    return;
                }

            if( $("input[type=radio]").is(":checked") && $("#fromDate1").val()!="" && $("#toDate1").val()!="" ){ // check if the radio is checked
//            var radioVal = $(this).val(); // retrieve the value
            var radioVal = $(".fav_language:checked").val();
            var fromDateVal = $("#fromDate1").val(); // retrieve the value
            var toDateVal = $("#toDate1").val(); // retrieve the value
            var reportDateVal = $("#reportDate1").val(); // retrieve the value
            //var wlwVal = $("#wlwid").val();
var reportFilterValue = reportDateVal+"-"+fromDateVal+"-"+toDateVal+"-"+radioVal;
sessionStorage.setItem("reportFilter",reportFilterValue);


                if(radioVal=="pdf"){
	var rootFolder = "/wp-content/themes/balance-theme/inc/edit/modules/exportpdf.php?fromDateVal="+fromDateVal+"&toDateVal="+toDateVal+"&radioVal="+radioVal+"&wlwVal="+selectedWebsiteId+"&reportDateVal="+reportDateVal;
                } if(radioVal!="pdf") {
 var rootFolder = "/wp-content/themes/balance-theme/inc/edit/modules/export.php?fromDateVal="+fromDateVal+"&toDateVal="+toDateVal+"&radioVal="+radioVal+"&wlwVal="+selectedWebsiteId+"&reportDateVal="+reportDateVal;
                }
$(".fav_language:checked").removeAttr("checked");

//  ob_start();
//   print_r(debug_backtrace()); 
//   $trace = ob_get_contents();
//   ob_end_clean();
  
window.location.replace(rootFolder);
            }
            });
    });
</script>

    <?php
    echo ob_get_clean();
}
?>
