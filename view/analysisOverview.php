<?php
extendView('analysis');

beginPartial('analysisContent');
?>

    <script>
        var overviewGridData = <?php echo json_encode($totals); ?>;
    </script>

    <div class="container">

        <div class="row">
            <table id="tbl_overview"
                   class="table table-bordered table-striped tablesorter">
                <thead>
                <tr>
                    <th>Account Name</th>
                    <th>Cost*</th>
                    <th>Daily</th>
                    <th>Weekly</th>
                    <th>Monthly</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

<?
endPartial();