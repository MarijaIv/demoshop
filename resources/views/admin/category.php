<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="/css/admin/dashboard.css">
    <link rel="stylesheet" href="/css/admin/treeview.css">
    <link rel="stylesheet" href="/css/admin/category.css">
    <link rel="stylesheet" href="/css/admin/popup.css">
    <script type="text/javascript" src="/js/Ajax/AjaxService.js"></script>
    <script type="text/javascript" src="/js/CategoryService/CategoryService.js"></script>
    <script type="text/javascript" src="/js/Category/treeview.js"></script>
    <script type="text/javascript" src="/js/Category/editCategory.js"></script>
    <script type="text/javascript" src="/js/Category/createCategory.js"></script>
    <script type="text/javascript" src="/js/Category/popup.js"></script>
    <script type="text/javascript" src="/js/Category/messages.js"></script>
    <title>Categories</title>
    <meta charset="utf-8">
</head>
<body onload="Demoshop.Category.treeView.listCategory()">
<?php
require 'navigation/navigation.php';
?>
<div class="tabcontent-category">
    <div class="column-left" id="treeViewDiv">
        <ul id="tree" class="myUL">
        </ul>
        <input type="submit" value="Add root category" class="add-category"
               onclick="Demoshop.Category.createCategory.displayOrCancelAddCategory(true);">
        <input type="submit" value="Add subcategory" class="add-sub"
               onclick="Demoshop.Category.createCategory.addSubcategory();">
    </div>
    <div class="column-right" id="rightTab">
        <div class="category-content" id="selectedCategory">
            <div id="header" class="header">
                Selected category
            </div>
            <div class="right-tab-container">
                <div class="right-tab-content">
                    <label class="right-tab-label" for="title">Title:</label>
                    <label for="parentCategory" class="right-tab-label" hidden>Parent category:</label>
                    <label for="parentCategoryInput" class="right-tab-label">Parent category:</label>
                    <label for="code" class="right-tab-label">Code:</label>
                    <label for="description" class="right-tab-label">Description:</label>
                </div>
                <div class="right-tab-content">
                    <input type="text" id="title" class="input-right"
                           oninput="Demoshop.CategoryPopups.popup.filledOut();">
                    <select id="parentCategory" class="input-right" hidden>
                    </select>
                    <input type="text" id="parentCategoryInput" class="input-right">
                    <input type="text" id="code" class="input-right"
                           oninput="Demoshop.CategoryPopups.popup.filledOut();">
                    <textarea id="description" class="ta-right" oninput="Demoshop.CategoryPopups.popup.filledOut();">
                    </textarea>
                </div>
                <div id="popupRequiredTitle" class="popup-required-title">
                    <span class="popup-text">Please fill out this field.</span>
                </div>
                <div id="popupRequiredCode" class="popup-required-code">
                    <span class="popup-text">Please fill out this field.</span>
                </div>
                <div id="popupRequiredDesc" class="popup-required-desc">
                    <span class="popup-text">Please fill out this field.</span>
                </div>
            </div>
            <input id="deleteBtn" type="button" value="Delete" class="delete"
                   onclick="Demoshop.Category.treeView.confirmDelete();">
            <input id="editBtn" type="button" value="Edit" class="edit"
                   onclick="Demoshop.Category.editCategory.edit();" disabled>
            <input id="cancelBtn" type="button" value="Cancel" class="cancel"
                   onclick="Demoshop.Category.createCategory.displayOrCancelAddCategory(false);" hidden>
            <input id="okBtn" type="button" value="OK" class="ok"
                   onclick="Demoshop.Category.createCategory.saveCategory();" hidden>
            <input id="cancelEdit" type="button" value="Cancel" class="cancel"
                   onclick="Demoshop.Category.editCategory.cancelEdit();" hidden>
            <input id="okEdit" type="button" value="OK" class="ok"
                   onclick="Demoshop.Category.editCategory.saveEdited();" hidden>
        </div>
    </div>
</div>
<div id="popup" class="popup">
    <div class="popup-content">
        <div class="popup-header">
            <span class="close" onclick="Demoshop.CategoryMessages.messages.closePopup();">&times</span>
        </div>
        <output id="message" class="message"></output>
    </div>
</div>
<div id="confirm" class="popup">
    <div class="popup-content">
        <div class="popup-header">
            <span class="close" onclick="Demoshop.CategoryMessages.messages.closeConfirm();">&times</span>
        </div>
        <output id="messageConf" class="message"></output>
        <input id="confirm" type="button" value="OK" class="confirm" onclick="Demoshop.Category.treeView.delete();">
    </div>
</div>
<div id="info" class="popup">
    <div class="popup-content">
        <div class="popup-header">
            <span class="close" onclick="Demoshop.CategoryMessages.messages.closeInfo();">&times</span>
        </div>
        <output id="messageInfo" class="message"></output>
        <input id="confirm" type="button" value="OK" class="confirm"
               onclick="Demoshop.CategoryMessages.messages.closeInfo();">
    </div>
</div>
</body>
</html>