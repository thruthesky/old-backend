<?php
$cats = category()->loadAllArray();
?>
<style>
    .row .code {
        background-color:#f0f0f0;
    }
    .row .icon {
        display:inline-block;
        width:60px;
        height:60px;
    }
    .row .icon img {
        width:100%;
    }
</style>
<h1>Company category list</h1>
<div class="list">
    <?php foreach ( $cats as $cat ) {

    ?>
    <div class="row" rid="<?php echo $cat['id']?>">

        <div class="content">
            <span class="icon"><?php
            $file = data()->load("gid='company-category' AND code='$cat[id]'");
                if ( $file ) {
                    $id = $file->get('id');
                $url = $file->get('url');
                    echo "<img width='100%' fid='$id' src='$url'><span class='button delete-category-icon'>삭제</span>";
                }
            ?></span>
            <span class="code"><?php echo $cat['code']?></span>
            <span class="value"><?php echo $cat['value']?></span>
            <span class="button category-edit-button">Edit</span>
        <span class="button category-delete-button"
              route="company.Controller.categoryDelete&id=<?php echo $cat['id'] ?>"
              callback="reloadCategoryList"
        >Delete</span>
            <form class='philgo-banner-form' action="<?php echo url_script()?>?route=data.Controller.fileUpload" method="post" enctype="multipart/form-data">
                <input type="hidden" name="gid" value="company-category">
                <input type="hidden" name="code" value="<?php echo $cat['id']?>">
                <input type="hidden" name="unique" value="1">
                <input type="hidden" name="finish" value="1">
                <input type="file" name="userfile" onchange="on_change_file_upload(this);">
                </form>
        </div>
    </div>
    <?php } ?>
</div>
<script>
    function reloadCategoryList() {
        ajax_load_route('company.Controller.categoryList', '.company-category-list');
    }
    function on_change_file_upload(filebox) {
        var $filebox = $(filebox);
        if ( $filebox.val() == '' ) return;
        var $form = $filebox.parents("form");
        var $row = $filebox.parents(".row");
        $form.ajaxSubmit({
            error : function (xhr) {
                alert("ERROR on ajaxSubmit() ...");
            },
            complete: function (xhr) {
                console.log("File upload completed through jquery.form.js");
                var re;
                try {
                    re = JSON.parse(xhr.responseText);
                }
                catch (e) {
                    //$form.after().html(xhr.responseText);
                    console.log(xhr.responseText);
                    return alert("ERROR: JSON.parse() error : Failed on file upload...");
                }
                if ( re['code'] ) {
                    return app.alert(re['message']);
                }
                else {
                    $row.find('.icon').html( get_markup_icon(re['data']['id'], re['data']['url']) );
                }
                console.log(re);
            }
        });
        $filebox.val('');
    }
    function get_markup_icon( id, url ) {
        return "<img width='100%' fid='"+id+"' src='"+url+"'><span class='button delete-category-icon'>삭제</span>";
    }

</script>