var globalform;
function FileUploader (uploaderview){
    // Constructor
    var view = uploaderview;
    var logger = Log4js.getLogger("FileUploader");
    var fileUploader = null;
    
    var uploadingFiles = new Object;
    
    this.nbElement = 0;
    
    logger.setLevel(logLevel);
    logger.addAppender(new Log4js.BrowserConsoleAppender());
    logger.debug("New FileUploader");
    
    // Methods
        
    this.uploadFile = function(files, path, category){
        if (!path)
            path = "/";
        if (!category)
            category = "Personal";
        
        logger.debug("Uploading Files");
        logger.debug("Current path = " + path);
        logger.debug("Current category = " + category);
        
        var count = files.length;        
        if (count > 0) {
            if (this.nbElement == 0) {
                $('ul', view).html('');
                $(view).show();
            }
            this.nbElement += count;
            $(files).each(function() {
                var key = fileUploader.guidGenerator ();
                uploadingFiles[key] = this; 
                
                $('ul', view).append("<li id='f-" + key + "'>" + this.fileName + "<progress value=\"0\" max=\"100\"></progress> </li>");
                
                var fd = new FormData();
                fd.append("data[Document][category]", category);
                fd.append("data[Document][path]", path);
                fd.append("Filedata", this);
                var xhr = new XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    fileUploader.uploadProgress(evt, key)
                }, false);
                xhr.addEventListener("load", function(evt) {
                    fileUploader.uploadComplete(evt, key)
                }, false);
                xhr.addEventListener("error", fileUploader.uploadFailed, false);
                xhr.addEventListener("abort", fileUploader.uploadCanceled, false);
                xhr.open("POST", "documents/add/");
                xhr.send(fd);
            });
        }
    };
    
    this.refresh = function() {
        if (this.nbElement > 0) {
            $('ul', view).html('');
            for (var i in uploadingFiles) {
                $('ul', view).append("<li id='f-" + i + "'>" + uploadingFiles[i].fileName + "<progress value=\"0\" max=\"100\"></progress> </li>");
            };
            $(view).show('slow');
        } else {
            $(view).hide('slow');
        }
    };
    
    this.uploadProgress = function (evt, key) {
        if (evt.lengthComputable) {
            var percentComplete = Math.round(evt.loaded * 100 / evt.total);
            $('#f-' + key + ' > progress').attr('value', percentComplete );
        }
        else {
            document.getElementById('progressNumber').innerHTML = 'unable to compute';
        }
    }

    this.uploadComplete = function (evt, key) {
        /* This event is raised when the server send back a response */
        delete uploadingFiles[key];        
        this.nbElement--;
        
        if (this.nbElement > 0)
            $('#f-' + key ).hide('slow', function(){
                $('#f-' + key ).remove()
            });
        else
            $(view).hide();
        
        if (evt.target.responseText == 1) {
            if (this.nbElement == 0)
                loadPage(page, true);
        } else {
            notify ("Oups, an error occured ! Please try again, if the error persist, please contact the administrator.", "Error", "error");
        }
    }

    this.uploadFailed = function (evt) {
        alert("There was an error attempting to upload the file.");
    }

    this.uploadCanceled = function (evt) {
        alert("The upload has been canceled by the user or the browser dropped the connection.");
    }
    
    this.getUploadingFiles = function () {
        return uploadingFiles;
    }
    
    this.guidGenerator = function () {
        var S4 = function() {
            return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
        };
        return (S4()+S4()+"-"+S4()+"-"+S4()+"-"+S4()+"-"+S4()+S4()+S4());
    }
    
    fileUploader = this;
}