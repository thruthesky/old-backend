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
<div class="companies">
<?php
    $companies = company()->loadQuery("category=$category_id");

    foreach ( $companies as $company ) {
    $homepage = $company->homepage;
    if ( strpos( $homepage, "http://www.philgo.com/?module=post&action=view&idx=" ) !== false ) $homepage = str_replace("http://www.philgo.com/?module=post&action=view&idx=", "www.philgo.com?", $homepage);
        echo <<<EOH
            <div class="company">
                <div class="company-name">{$company->company_name}</div>
                <div class="title">{$company->title}</div>
                <div class="numbers">{$company->mobile} {$company->landline}</div>
EOH;
                if ( $company->kakao ) echo "<div class='kakao'>카카오톡 : {$company->kakao}</div>";
        echo <<<EOH
                <div class="region">{$company->region}</div>
                <a href="{$company->homepage}" target="_blank"><div class="homepage">{$homepage}</div></a>
            </div>
EOH;
    }
?>
</div>
