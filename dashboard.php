<?php require_once 'head.php'; ?>
            <nav aria-label="breadcrumb" class="">
                <ol class="breadcrumb p-3">
                    <li class="breadcrumb-item"><a href="/"><i class="fa-solid fa-house" style="color: #005eff;"></i></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="/dashboard">Dashboard</a></li>
                </ol>
            </nav>
        </div>


        <style>
            /* Add custom styles for the chart */
            .bar {
            /* background-color: #FFF;  */
            color: #000; 
            height: 50px; /* Set bar height */
            margin-bottom: 5px; /* Set margin between bars */
            text-align: start; /* Align text in bars */
            line-height: 30px; /* Set line height for text */
            padding: 10px;
            }
        </style>


        <div class="col-12 p-1">
            <div class="row" id="dashBoard">
                <div class="col-lg-6 px-2 mx-0"> <div class="box-shadow p-2 ">
                    <canvas id="myBarChart1" style="width: 100%; height: 300px;"></canvas>
                </div></div>
                <div class="col-lg-6 px-2 mx-0"> <div class="box-shadow p-2 ">
                    <canvas id="myBarChart2" style="width: 100%; height: 300px;"></canvas>
                </div></div>
                <div class="col-lg-12 px-2 mx-auto"> <div class="box-shadow p-2 ">
                    <canvas id="myBarChart3" style="width: 100%; height: 300px;"></canvas>
                </div></div>
                
            </div>  
        </div>
        <!-- <div class="col-12 bg-white p-3 box-shadow">
        </div> -->
        <?php require_once 'footer.php'; ?>
    </div>

<?php require_once 'body.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function () {
        sidebarActivate('./dashboard');
        let userRole = $('#userType').attr('usertype');
        if(userRole == 'seller' || userRole == 'library' || userRole == 'publication' ){
            $.ajax({
                type: "GET",
                url: "https://bookhavenapi.sankarsan.xyz/api/v1/vendor/dashboard2",
                contentType: "application/json",
                headers: {
                    Authorization: 'Bearer '+getCookie('token')
                },
                success: function (data) {
                    console.log(data);
                    makeChart2(
                        "myBarChart1",
                        data.days,
                        "Order Delivered",
                        'Order Created',
                        data.deliveredC,
                        data.createdC
                    );
                    makeChart2(
                        "myBarChart2",
                        data.days,
                        "Order Delivered (TK.)",
                        'Order Created (TK.)',
                        data.deliveredP,
                        data.createdP
                    );
                    setTimeout(hideSpinner, 500);
                }
            });
            $.ajax({
                type: "GET",
                url: "https://bookhavenapi.sankarsan.xyz/api/v1/vendor/dashboard",
                contentType: "application/json",
                headers: {
                    Authorization: 'Bearer '+getCookie('token')
                },
                success: function (data) {
                    console.log(data);
                    makeChart(
                        'pie',
                        "myBarChart3",
                        data.names,
                        "Books",
                        data.quantities
                    );
                    
                        
                }
            });
        } 
        else if(userRole == 'admin'){
            $.ajax({
                type: "GET",
                url: "https://bookhavenapi.sankarsan.xyz/api/v1/admin/dashboard",
                contentType: "application/json",
                headers: {
                    Authorization: 'Bearer '+getCookie('token')
                },
                success: function (data) {
                    console.log(data);
                    makeChart2(
                        "myBarChart1",
                        data.days,
                        "Order Delivered",
                        'Order Created',
                        data.deliveredCounts,
                        data.createdCounts
                    );
                    
                    
                        
                }
            });
            $.ajax({
                type: "GET",
                url: "https://bookhavenapi.sankarsan.xyz/api/v1/admin/dashboard3",
                contentType: "application/json",
                headers: {
                    Authorization: 'Bearer '+getCookie('token')
                },
                success: function (data) {
                    console.log(data);
                    makeChart2(
                        "myBarChart2",
                        data.days,
                        "Order Delivered (TK.)",
                        'Order Created (TK.)',
                        data.delivered,
                        data.created
                    );
                    
                        
                }
            });
            $.ajax({
                type: "GET",
                url: "https://bookhavenapi.sankarsan.xyz/api/v1/admin/dashboard2",
                contentType: "application/json",
                headers: {
                    Authorization: 'Bearer '+getCookie('token')
                },
                success: function (data) {
                    console.log(data);
                    makeChart(
                        'pie',
                        "myBarChart3",
                        data.names,
                        "Top Selling",
                        data.quantities
                    );
                    
                        setTimeout(hideSpinner, 500);
                }
            });
        } else{
            location.replace('/');
        }

        function makeChart(type,canvas_id, label, dataSet, data) {
            var data = {
            labels: label,
            datasets: [
                {
                label: dataSet,
                backgroundColor: [
                    "rgba(0, 123, 255, 0.7)",
                    "rgba(255, 193, 7, 0.7)",
                    "rgba(40, 167, 69, 0.7)",
                    "rgba(220, 53, 69, 0.7)",
                    "rgba(255, 0, 0, 0.7)",
                ],
                data: data,
                },
            ],
            };
            var options = {
                indexAxis: "y",
                scales: {
                    y: {
                    beginAtZero: true,
                    },
                },
                layout:{
                    padding:{
                        left:100
                    }
                }
            };
            var ctx = document.getElementById(canvas_id).getContext("2d");
            var myBarChart = new Chart(ctx, {
            type: type,
            data: data,
            options: options,
            });
        }

        function makeChart2(canvas_id, label, dataSet1 , dataSet2, data1 , data2) {
            var data = {
            labels: label,
            datasets: [
                {
                label: dataSet1,
                backgroundColor: "rgba(0, 123, 255, 0.7)",
                data: data1,
                },
                {
                label: dataSet2,
                backgroundColor: "rgba(255, 193, 7, 0.7)",
                data: data2,
                }
            ],
            };
            var options = {
            scales: {
                y: {
                beginAtZero: true,
                },
            },
            };
            var ctx = document.getElementById(canvas_id).getContext("2d");
            var myBarChart = new Chart(ctx, {
            type: "bar",
            data: data,
            options: options,
            });
        }

        // setTimeout(hideSpinner, 500);
    });
</script>
<?php require_once 'script.php'; ?>





