function DocumentManager (dropboxview, current_path, current_category){
    // Constructor
    var dropbox = dropboxview;
    var path = current_path;
    var category = current_category;
    var logger = Log4js.getLogger("DocumentManager");
    var documentManager = null;
    var files = new Array();
    
    logger.setLevel(logLevel);
    logger.addAppender(new Log4js.BrowserConsoleAppender());
    logger.debug("New DocumentManager");
    
    // Methods
    
    this.addFile = function() {
        $('#filesPromptInput').val('');
        $('#filesPromptList').html('');
        $( "#filePrompt" ).dialog({
            resizable: false,
            width:350,
            modal: true,
            buttons: {
                "Yes": function() {
                    $( this ).dialog( "destroy" );
                    fileUploader.uploadFile($('#filesPromptInput').get(0).files, path, category);
                },
                Cancel: function() {
                    $( this ).dialog( "destroy" );
                }
            }
        });
    }
    
    this.addFolder = function(confirm){
        if (!confirm) {
            $.ajax({
                url: 'documents/addfolder',
                cache: false,
                success: function(data) {
                    $( "#attachdocument" ).html(data);
                    $( "#attachdocument" ).dialog({
                        resizable: false,
                        width:400,
                        modal: true,
                        buttons: {
                            "Create Folder": function() {
                                $( this ).dialog( "destroy" );
                                documentManager.addFolder(true);
                            },
                            Cancel: function() {
                                $( this ).dialog( "destroy" );
                            }
                        }
                    });
                },
                error: function(data) {
                    notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
                }
            });
        } else {
            $('input[name="data[Document][path]"]').val(path);
            $('input[name="data[Document][category]"]').val(category);
            $.ajax({
                url: 'documents/addfolder',
                data: $('#addfolder').serialize(),
                type: 'POST',
                cache: false,
                success: function(data) {
                    if (parseInt(data) > 0) {
                        notify("Folder Created");
                        loadPage(page, true);
                    } else {
                        notify ("Oups, an error occured ! Please check your data and try again.");
                    }
                },
                error: function() {
                    notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
                }
            });
        }
    };
    
    this.refresh = function() {};
    
    this.dragEnter  = function(evt){
        logger.debug("Files drag enter");
        evt.stopPropagation();
        evt.preventDefault();
        $(dropbox).addClass('ui-widget-shadow');
    };
    
    this.dragExit = function(evt){
        logger.debug("Files dragged exit");
        evt.stopPropagation();
        evt.preventDefault();
        $(dropbox).removeClass('ui-widget-shadow');
    };
                
    this.dragOver = function(evt){
        logger.debug("Files dragged over");
        evt.stopPropagation();
        evt.preventDefault();
    };
                
    this.drop = function(evt) { 
        logger.debug("Files dropped");
        /* Uploading will here. */
        evt.stopPropagation();
        evt.preventDefault();
        $(dropbox).removeClass('ui-widget-shadow');
        fileUploader.uploadFile(evt.dataTransfer.files, path, category);
    };
    
    this.selectAll = function (evt) {
        logger.debug("select all ");
        for (var i in files) {
            if (evt.target.checked != files[i].isSelected())
                files[i].selected();
        }
        var selected = $('#select_all', dropbox).is(':checked');
        $('#fileList input:checkbox', dropbox).attr('checked', selected);
    };
    
    this.getFiles = function (evt) {
        return files;
    };
    
    this.openFolder = function (folder) {
        /*if (path != "/")
            path += "/";*/
        var url = encodeURI('documents/?category=' + category + '&path=' + path +  folder);
        loadPage(url);
    }
    
    this.remove = function(confirm) {
        var items_to_delete = $('#fileList input:checkbox:checked', dropbox);
        if (!confirm) {            
            if (items_to_delete.length > 0) {
                $( "#confirmation" ).dialog({
                    resizable: false,
                    height:140,
                    modal: true,
                    buttons: {
                        "Yes": function() {
                            $( this ).dialog( "destroy" );
                            documentManager.remove(true);
                        },
                        "No": function() {
                            $( this ).dialog( "destroy" );
                        }
                    }
                });
            }
        } else {
            var nbdeleted = 0;
            $(items_to_delete).each(function() {
                var id = $(this).parent().parent().attr('data-file');
                $.ajax({
                    url: 'documents/delete/' + id,
                    type: 'GET',
                    cache: false,
                    async: false,
                    success: function() {
                        nbdeleted++;
                    }
                });
                
            });
            notify( nbdeleted + " / " + items_to_delete.length + " documents deleted");
            loadPage(page, true);
        }
    };
    
    this.selectedFiles = function () {
        return $('#fileList tr[data-type!="dir"] input:checkbox:checked').toArray();
    }
    
    documentManager = this;
    dropbox.addEventListener("dragenter", this.dragEnter, false);
    dropbox.addEventListener("dragexit", this.dragExit, false);
    dropbox.addEventListener("dragover", this.dragOver, false);
    dropbox.addEventListener('drop', this.drop, false);
    $('#select_all', dropbox).bind('click', function(evt) {
        documentManager.selectAll(evt);
    });
    $('#fileList > tr', dropbox).each(function() { 
        files.push(new FileActions(this, documentManager));
    });
    $('#doc_content', dropbox).contextMenu('contextualDocuments', {
        bindings: {
            'folder': function() {
                documentManager.addFolder();
            },
            'file': function(evt) {
                docManager.addFile();
            }
        }
    });
    
    this.category = function() {
        return category;
    }
    
}