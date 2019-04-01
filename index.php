<?php
ini_set("session.gc_maxlifetime", 864000);
session_start();
if(!isset($_SESSION['path'])) {
  $_SESSION['path'] = __DIR__ ; // Set current directory as default directory to open
  array_map('unlink', glob("pages/editor/*.php")); // Deletes temporary files, created for editing
}
if(!isset($_SESSION['fm_active'])) {
    header('Location: pages/login.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>The File Manager</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="css/style.css">

    <link rel="icon" href=" ">

</head>

<body  ondragstart="return false;" ondrop="return false;">

<!-- LOADER -->
<div class="loader-container">
    <div class="loader-card">
        <div class="loader"></div>
        <div class="loader-text">Loading...</div>
    </div>
</div>
<!-- END OF LOADER -->

<!-- MODAL CONTAINER -->
<div class="modal-container">
    <div class="modal-window">
        <div class="modal-head">
            <span></span>
            <div class="btn-group">
                <button class="close-btn maximize-btn" onclick="maximizeModal()"><i class="maximize-icon"></i></button>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
        </div>
        <div class="modal">
            <!-- <div class='flex space-between'>
                <span id="uploadingFileName" style="max-width:200px">fdsfsdfs.txt</span>
                <span id="filesCount">3/8</span>
            </div>
            <div class="progress-bar">
                <div class="progress-bar-fill">  </div>
            </div> -->
        </div>
    </div>
</div>
<!-- END OF MODAL CONTAINER -->

<!-- IMAGE VIEWER -->
<div class="image-viewer">
    <div class="image-viewer-head">
        <span></span>
        <button class="close-btn" onclick="closeImageViewer()">&times;</button>
    </div>
    <div class="image-container">
    </div>
</div>
<!-- END OF IMAGE VIEWER -->

<!-- NAVIGATION BAR -->
<nav>
    <a href="javascript:scanPath('../')" class="round-btn"><img src="./icons/back.svg" class="icon"></a>
    <ol class="breadcrumb" id="breadcrumb">
        <!-- Fetched by PHP through AJAX -->
    </ol>
    <div class="options-menu">
        <div class="dropdown"> 
            <a class="dropdown-title" onclick="showDropdown(this)"><img src="./icons/new.svg" class="icon"> New</a>
            <div class="dropdown-item-container">
                <a href="javascript:showModal('createFolder')" class="dropdown-item"><img src="./icons/folder-alt.svg" class="icon"> Folder</a>
                <a href="javascript:showModal('createFile')" class="dropdown-item"><img src="./icons/file-alt.svg" class="icon"> File</a>
            </div>
        </div>
        <div class="dropdown"> 
            <a class="dropdown-title" onclick="showDropdown(this)"><img src="./icons/upload.svg" class="icon"> Upload</a>
            <div class="dropdown-item-container">
                <a href="javascript:showModal('uploadFolder')" class="dropdown-item"><img src="./icons/folder-alt.svg" class="icon"> Folder</a>
                <a href="javascript:showModal('uploadItem')" class="dropdown-item"><img src="./icons/file-alt.svg" class="icon"> Files</a>
            </div>
        </div>
        <div class="dropdown"> 
            <a class="dropdown-title" onclick="showDropdown(this)"><img src="./icons/list.svg" class="icon view-icon"> View</a>
            <div class="dropdown-item-container">
                <a href="javascript:changeView('list')" class="dropdown-item"><img src="./icons/list.svg" class="icon"> List</a>
                <a href="javascript:changeView('grid')" class="dropdown-item"><img src="./icons/grid.svg" class="icon"> Grid</a>
                <a href="javascript:changeView('details')" class="dropdown-item"><img src="./icons/details.svg" class="icon"> Details</a>
            </div>
        </div>
        <div class="dropdown" id="moreOptions" style="display:none;"> 
            <a class="dropdown-title" onclick="showDropdown(this)"><img src="./icons/more.svg" class="icon"></a>
            <div class="dropdown-item-container">
                <a href="javascript:showModal('deleteItem')" class="dropdown-item" ><img src="./icons/delete.svg" class="icon">Delete</a>
                <a href="javascript:pasteItem()" class="dropdown-item" id="pasteButton" style="display:none"><img src="./icons/paste.svg" class="icon">Paste</a>
                <a href="javascript:copyItem()" class="dropdown-item"><img src="./icons/copy.svg" class="icon">Copy</a>
                <a href="javascript:cutItem()" class="dropdown-item"><img src="./icons/cut.svg" class="icon">Cut</a>
                <a href="javascript:editFile()" class="dropdown-item" id="editButton"><img src="./icons/edit.svg" class="icon">Edit</a>
                <a href="javascript:showModal('renameItem')" class="dropdown-item" id="renameButton"><img src="./icons/rename.svg" class="icon">Rename</a>
                <a href="javascript:showModal('createZip')" class="dropdown-item"><img src="./icons/zip.svg" class="icon">Create ZIP</a>
                <a href="javascript:extractZip()" class="dropdown-item" id="extractButton"><img src="./icons/zip.svg" class="icon"> Extract</a>
                <a href="javascript:downloadFiles()" class="dropdown-item"><img src="./icons/download.svg" class="icon">Download</a>
            </div>
        </div>
        <div class="dropdown"> 
            <a href="pages/logout.php" class="dropdown-title"><img src="./icons/logout.svg" class="icon"> Logout</a>
        </div>
    </div>
</nav>
<!-- END OF NAVIGATION BAR -->

<div class="file-tree">
    <!-- Fetched by PHP through AJAX -->
</div>
<div class="splitter"></div>
<div class="list-group-section">
    <a class="list-group-head">
        <span style="width:25px;margin:0 10px;" class="icon-text">Icon</span>
        <span class="name" onclick="sortByName()">Name</span>
        <span class="size">Size</span>
        <span class="file-type">Type</span>
        <span class="date">Modified</span>
    </a>

    <div class="main-container">
        <div class="list-group" id="contentArea">
            <!-- Fetched by PHP through AJAX -->
        </div>
        <div class="select-box"></div>
    </div>
</div>

<!-- NOTIFICATIONS -->
<div class="notification-container">
</div>
<!-- END OF NOTIFICATIONS -->


 <!-- CONTEXT MENU -->
<div class="context-menu">
    <a href="javascript:pasteItem()" class="context-menu-item paste-btn" style="display:none"><img src="./icons/paste.svg" class="icon"> Paste</a>
    <div class="context-menu-upper">
        <a href="javascript:editFile()" class="context-menu-item edit-btn"><img src="./icons/edit.svg" class="icon"> Edit</a>
        <a href="javascript:copyItem()" class="context-menu-item"><img src="./icons/copy.svg" class="icon"> Copy</a>
        <a href="javascript:cutItem()" class="context-menu-item"><img src="./icons/cut.svg" class="icon"> Cut</a>
        <a href="javascript:showModal('deleteItem')" class="context-menu-item"><img src="./icons/delete.svg" class="icon"> Delete</a>
        <a href="javascript:showModal('renameItem')" class="context-menu-item rename-btn"><img src="./icons/rename.svg" class="icon"> Rename</a>
        <a href="javascript:showModal('createZip')" class="context-menu-item"><img src="./icons/zip.svg" class="icon"> Create ZIP</a>
        <a href="javascript:extractZip()" class="context-menu-item extract-btn"><img src="./icons/zip.svg" class="icon"> Extract</a>
        <a href="javascript:downloadFiles()" class="context-menu-item"><img src="./icons/download.svg" class="icon"> Download</a>
    </div>
    <div class="context-menu-lower">
        <a href="javascript:selectAll()" class="context-menu-item"><img src="./icons/select-all.svg" class="icon"> Select All</a>
        <a href="javascript:scanPath('./')" class="context-menu-item"><img src="./icons/reload.svg" class="icon"> Refresh</a>
        <div class="context-menu-item has-dropdown"><img src="./icons/new.svg" class="icon"> New
            <div class="context-menu-dropdown">
                <a href="javascript:showModal('createFolder')" class="context-dropdown-item"><img src="./icons/folder-alt.svg" class="icon"> Folder</a>
                <a href="javascript:showModal('createFile')" class="context-dropdown-item"><img src="./icons/file-alt.svg" class="icon"> File</a>
            </div>
        </div>
        <div class="context-menu-item has-dropdown"><img src="./icons/upload.svg" class="icon"> Upload
            <div class="context-menu-dropdown">
                <a href="javascript:showModal('uploadFolder')" class="context-dropdown-item"><img src="./icons/folder-alt.svg" class="icon"> Folder</a>
                <a href="javascript:showModal('uploadItem')" class="context-dropdown-item"><img src="./icons/file-alt.svg" class="icon"> Files</a>
            </div>
        </div>

    </div>
</div>
<!-- END OF CONTEXT MENU -->

<!-- MENU FOR EDITOR -->
<div class="menu-container">
    <a href="javascript:saveFile()" class="menu-item">Save</a>
    <a href="javascript:closeModal()" class="menu-item">Exit</a>
</div>
<!-- END OF MENU FOR EDITOR -->

<!-- JS Scripts -->
<script src="js/main.js"></script>
</body>
</html>