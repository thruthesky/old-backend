<style>
    .categories ul {
        list-style: none;
        margin:0;
        padding:0;
        overflow: auto;
        font-size:.9em;
    }
    .categories ul li {
        float:left;
        padding:.1em;
        box-sizing: border-box;
        width:25%;
        cursor:pointer;
    }

    .categories ul div {
        position:relative;
        background: #fff;
        padding:.4em;
        text-align:center;
    }
    .categories ul img {
        display:block;
        margin:0 auto;
        width:66px;
        height:auto;
    }

    .categories ul .text {
        display:block;
        top:52px;
        left:0;
        right:0;
    }
</style>
<div class="front-page">

    총 <span class="count"><?php echo company()->count()?></span> 개의 업소가 등록되어져 있습니다.


    <form class='search'>
        <input type="text" name="keyword" value="">
        <input type="submit" value="Search">
    </form>

    <div class="categories">

        <ul>
            <?php

            $cats = category()->loadAllArray();
            foreach ( $cats as $cat ) {

            $file = data()->load("gid='company-category' AND code='$cat[id]'");
            $url_install_dir = url_install_dir();
            if ( $file ) {
            $id = $file->get('id');
            $url = $file->get('url');
            $img = "<img fid='$id' src='$url'>";
            }
            else $img = "<img src='{$url_install_dir}model/company/tmp/category-icon/marketing.png'>";
            ?>
            <li cid="<?php echo $cat['id']?>">
                <div>
                    <?php echo $img?>
                    <span class="text"><?php echo $cat['code']?></span>
                </div>
            </li>
            <?php
            }
?>
        </ul>
    </div>



</div>