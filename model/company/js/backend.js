$(function() {

    setTimeout(function(){
        //  el.footer().find('[route="company.Controller.admin"]').click();
        //$('[fid="482"]').click(); // 카테고리
        //$('[route="company.Controller.admin"]').click();
        //$('[route="user.Controller.registerForm"]').click();

/**
        app.alert("안녕하세요.", 10, function() {
            console.log('app.alert closed');
        });
 */

        //console.log(ls.get('username'));
        //console.log(ls.get('signature'));


        //$('[route="user.Controller.editForm"]').click();//회사 정보 등록
ajax_load_route('company.Controller.view&id=3692'); // 회사 정보 번호 3692 번 열기




    },300);


    function initApp() {
        var m = '' +
            '<li class="item user-out" route="user.Controller.loginForm">로그인</li>' +
            '<li class="item user-in logout-button">로그아웃</li>' +
            '<li class="item user-out" route="user.Controller.registerForm">회원가입</li>' +
            '<li class="item user-in" route="user.Controller.editForm">회원 정보 수정</li>' +
            '<li class="item" username="admin" route="company.Controller.admin">관리자 페이지</li>' +
            '<li class="item" username="user3" route="company.Controller.admin">user3</li>' +
            '<li class="item" username="" route="company.Controller.admin">비 로그인</li>' +
            '<li class="item close close-panel-menu-button">메뉴닫기</li>' +
            '';
        app.panel.el().find('ul').append(m);
    }

    initApp();


    ajax_load_route('user.Controller.who', function(res){
        console.log(res);
    });


    on_submit('form.user-register', on_form_user_register_submit);
    on_submit('form.user-edit', on_form_user_edit_submit);
    on_submit('form.login', on_form_login_submit);

    on_click('.logout-button', on_logout_button);


    on_submit('.category-edit', on_category_edit);
    on_submit('.company-edit', on_company_edit);

    on_click('.category-edit-button', on_category_edit_form);
    on_click('.category-edit-cancel', on_category_edit_cancel);
    on_click('.delete-category-icon', on_delete_category_icon);
    on_click('.categories [cid]', on_category_click);

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

function on_delete_category_icon(e) {
    var $this = $(this);
    //$this.parents('.row').find('.content').show();
    var $img = $this.parent().find('img');
    var fid = $img.attr('fid');
    ajax_load_route( 'data.Controller.fileDelete&id=' + fid, function(res) {
        var re = JSON.parse(res);
        if ( re['code'] ) return alert('파일 삭제에 실패하였습니다. ' + re['message']);
        $this.parent().empty();
    });
}


function on_company_edit(e){
    console.log("on_company_edit");
    e.preventDefault();
    var $form = $(this);
    var params = $form.serialize();
    var url = url_backend + '?' + params;
    ajax_load(url, function(res) {
        console.log(res);
        var re = JSON.parse(res);
        if ( re['code'] ) return alert(re['message']);
        //console.log("success on editing company information.")
        alert("회사 정보를 등록하였습니다.");
        ajax_load_route('company.Controller.view&id='+re['data']['id']);
    });

}

function on_category_click(e) {
    var $this = $(this);
    var cid = $this.attr('cid');
    ajax_load_route('company.Controller.collect&cid='+cid);
}

function on_form_user_register_submit(e) {
    e.preventDefault();
    ajax_load( app.urlServer() + '?' + $(this).serialize(), function(res) {
        var re = JSON.parse( res );
        if ( re['code'] ) return app.alert( re['message'] );
        app.alert("회원 가입을 하였습니다.", function(){
            ajax_load_route('user.Controller.loginForm');
        });
    });
    return false;
}

function on_form_user_edit_submit(e) {
    e.preventDefault();
    var $form = $(this);
    var params = $form.serialize();
    var o = {
        'url' : url_backend,
        'data' : params + '&username=' + ls.get('username') + '&signature=' + ls.get('signature'),
        'type' : 'POST'
    };
    ajax_load(o, function(res) {
        console.log(res);
        var re = JSON.parse( res );
        if ( re['code'] ) return alert( re['message'] );
        alert("회원 정보를 수정하였습니다.");
    });
    return false;
}
function on_form_login_submit(e) {
    e.preventDefault();
    var $this = $(this);
    var username = $this.find("[name='username']").val();
    ajax_load( app.urlServer() + '?' + $(this).serialize(), function(res) {
        console.log(res);
        var re = JSON.parse( res );
        if ( re['code'] ) return app.alert( re['message'] );
        ls.set('username', username);
        ls.set('signature', re['data']['signature']);
        updateUserLogin();
        app.alert("회원 로그인을 하였습니다.", function(res){
            ajax_load_route( 'company.Controller.frontPage' );
        });
    });
    return false;
}

function on_logout_button() {
    ls.set('username', '');
    updateUserLogin();
}