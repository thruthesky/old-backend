<?php
$key = hi('keyword');
$companies = company()->loadQuery("ceo_name LIKE '%$key%' OR company_name LIKE '%$key%' OR title LIKE '%$key%'");
$count = count($companies);
?>
    <style>
        .companies {

        }
        .companies .company {
            margin:.4em 0;
            background-color:#efefef;
        }
    </style>

<?php
if ( $count == 0 ) return;
display_companies($companies);
?>