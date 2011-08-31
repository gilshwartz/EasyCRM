<head>
    <?php
    $css = array(
        'common',
        'jquery-ui',
        'selectmenu',
        'pnotify',
        'uploadify',
        'autocomplete',
        'editable-select',
        'ui.daterangepicker',
    );
    $js1 = array(
        'jquery',
        'jquery-ui',
        'selectmenu',
        'pnotify',
        'blockUI',
        'uploadify',
        'swfobject',
        'autocomplete',
        'editable-select',
        'sha1'
    );
    $js2 = array(
        'htmlencode',
        'planningforce',
        'daterangepicker.jQuery',
    );
    $minify->css($css);
    $minify->js($js1);
    $minify->js($js2);
    ?>

    <script type="text/javascript" src="js/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="js/ckeditor/adapters/jquery.js"></script>
    <script type="text/javascript" src="js/contextmenu.js"></script>
    <script type="text/javascript" src="js/documents/documentmanager.js"></script>
    <script type="text/javascript" src="js/documents/fileuploader.js"></script>    
    <script type="text/javascript" src="js/documents/fileactions.js"></script>

    <script type="text/javascript" src="https://www.google.com/jsapi?key=ABQIAAAAzxapLamB-u8IXkQLkxlTmRTuim4sXuH7lN7ENXgXh2OQBJXaPhR0Po9RAtqf7m1X0zXDWDxIQEUXJw"></script>

    <script type="text/javascript" src="js/log4js/log4js.js"></script>
    <script type="text/javascript">
        var logLevel = Log4js.Level.ERROR;
    </script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>PlanningForce CRM</title>
</head>

<body class="<?php
    echo 'header' . $role_accro;
    ?>">
    <div id="container">
        <?php
        echo '<img src="img/logo' . $role_accro . '.png" class="logotype" alt=""/>';
        ?>
        <div id="header">
            <?php if ($role_accro != "ptnr") { ?>
                <script type="text/javascript">
                    function research(val) {
                        if (val != "I'm looking for...") {
                            loadPage("search/index/" + escape(val));
                        }
                        return false;
                    }
                </script>
                <form action="" class="searchform" onsubmit="research($('#searchbar').val());">
                    <input type="text" name="search" id="searchbar"/>
                    <input type="submit" value="" class="searchbtn"/>
                </form>
                <?php
            }
            echo $this->element('menu/' . $role_accro);
            echo "<script>var type = \"" . $role_accro . "\"</script>";
            ?>
        </div>
        <div id="webpage"></div>

        <!-- END assign request modal -->
    </div>

    <!-- BEGIN confirmation modal -->
    <div class="modal" id="confirmation">
        This action can not be undone.<br/>
        Do you confirm you want to delete this item?
    </div>
    <!-- END confirmation modal -->

    <!-- BEGIN confirmation modal -->
    <div class="modal" id="licconfirmation">
        This action can not be undone.<br/>
        Do you confirm?
    </div>
    <!-- END confirmation modal -->

    <!-- BEGIN unsaved data modal -->
    <div id="alertUnsaved" class="modal">
        You have unsaved data! <br/>
        Do you want to continue?
    </div>
    <!-- END unsaved data modal -->

    <!-- BEGIN AJAX modal -->
    <div class="modal" id="ajaxmodal">
    </div>
    <!-- END confirmation modal -->

    <!-- BEGIN operation progress modal -->
    <div class="modal" id="progress">
        Operation in progress...
    </div>
    <!-- END operation progress modal -->

    <!-- BEGIN new request modal -->
    <div class="modal" id="newrequest">
        You have new request!!!
    </div>
    <!-- END new requst modal -->

    <!-- BEGIN logout modal -->
    <div class="modal" id="logout">
        Are you sure you want to logout?
    </div>
    <!-- END logout modal -->

    <!-- BEGIN inactivity modal -->
    <div class="modal" id="incativity">
        No activity detected for 10 minutes, disconnect ?
    </div>
    <!-- END logout modal -->

    <!-- Attach document -->
    <div id="attachdocument" title="Attach a document"></div>
    <!-- -->

    <!-- edit description -->
    <div id="editor" class="modal"><textarea name="editor"></textarea></div>
    <!-- -->

    <!-- edit description -->
    <div id="requestsaccept" class="modal">
        Do you accept to follow this request or not?
    </div>
    <!-- -->

    <!-- Create a company -->
    <div id="createcompany" title="Add a company"><br />
        <form id="formcreatecompany" action="">
            Name of the company: <input name="data[Company][name]" type="text" class="editable" />
        </form>
        <div id="errorcreatecompany"style="float:left;margin-top: 10px;color:red"></div>
    </div>
    <!-- -->

    <!-- File Uploader -->
    <div id="fileupload">
        <p class="clearboth"><b>Uploading file :</b></p>
        <ul id="filestoupload">

        </ul>
    </div>
    <script type="text/javascript">
        var fileUploader = new FileUploader($('#fileupload'));
    </script>
    <!-- -->

    <!-- File Prompt -->
    <div id="filePrompt" title="Select a file" style="display: none">
        <input type="file" id="filesPromptInput" name="files[]" multiple />
        <output id="filesPromptList"></output>
    </div>
    <script>
        function handleFileSelect(evt) {
            var files = evt.target.files; // FileList object

            // files is a FileList of File objects. List some properties.
            var output = [];
            for (var i = 0, f; f = files[i]; i++) {
                output.push('<li><strong>', f.name, '</strong> - ',
                f.size, ' bytes</li>');
            }
            document.getElementById('filesPromptList').innerHTML = '<ul>' + output.join('') + '</ul>';
        }

        document.getElementById('filesPromptInput').addEventListener('change', handleFileSelect, false);
    </script>
    <!-- -->
</body>
