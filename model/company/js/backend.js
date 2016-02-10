$(function() {
    on_submit('.category-edit', on_category_edit);
    on_click('.category-edit-button', on_category_edit_form);
    on_click('.category-edit-cancel', on_category_edit_cancel);
});
function on_category_edit(e){
    e.preventDefault();
    var $form = $(this);
    var params = $form.serialize();
    var url = url_backend + '?' + params;
    trace("url:" + url);
    ajax_load(url, function(res) {
        var re = JSON.parse( res );
        if ( re['code'] ) return alert( re['message'] );
        $form[0].reset();
        ajax_load_route('company.Controller.categoryList', '.company-category-list');
    });
    return false;
}

function on_category_edit_form(e) {
    var $this = $(this);
    var $parent = $this.parents('.row');
    var id = $parent.attr('rid');
    var code = $parent.find('.code').text();
    var value = $parent.find('.value').text();
    var m = '<form class="category-edit">' +
        '<input type="hidden" name="route" value="company.Controller.editCategory">' +
        '<input type="hidden" name="id" value="'+id+'">' +
            '<input type="text" name="code" value="'+code+'">' +
            '<input type="text" name="value" value="'+value+'">' +
            '<input type="submit">' +
        '<span class="button category-edit-cancel">Cancel</span>' +
        '</form>';

    console.log(m);

    $parent.find('.content').hide();
    $parent.append(m);
}

function on_category_edit_cancel(e) {
    var $this = $(this);
    $this.parents('.row').find('.content').show();
    $('form.category-edit').remove();

}