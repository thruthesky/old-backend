<h1>Admin page</h1>

<?php
    if ( ! isAdmin() ) {
     echo "You are not admin!";
     return;
     }
?>

<h2>Create/Edit/Delete Categories</h2>
<form class="category-edit">
    <input type='hidden' name='route' value='company.Controller.createCategory'>
    <input type="text" name="code" placeholder="Category name">
    <input type="text" name="value" value="" placeholder="Comment">
    <input type="submit">
</form>
<div class="company-category-list"></div>

