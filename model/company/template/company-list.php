<?php
$category = category(hi('cid'));
$category_name = $category->code;
$category_comment = $category->value;
$category_id = $category->id;
$count_company = company()->count("category=$category_id");

?>
    <style>
        .companies {

        }
        .companies .company {
            margin:.4em 0;
            background-color:#efefef;
        }
    </style>
    <h2>
        <?php echo $category->code?>
    </h2>
    <h3>
        <?php echo $category->value?>
    </h3>
    <div class="desc">
        총 <?php echo $count_company?> 개의
        <?php echo $category_name?> 업소가 있습니다.
    </div>
<?php if ( $count_company == 0 ) return ?>

<?php
$companies = company()->loadQuery("category=$category_id");
display_companies($companies);
?>
