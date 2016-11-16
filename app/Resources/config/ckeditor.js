CKEDITOR.editorConfig = function(config){

    // The toolbar groups arrangement
    config.toolbarGroups = [
        //{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
        //{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
        // { name: 'insert' },
        //{ name: 'forms' },
        //{ name: 'tools'},
        // { name: 'document',	   groups: ['Sourcedialog', 'mode', 'document', 'doctools' ] },
        { name: 'styles' },
        { name: 'colors' },
         '/',  //<= when uncommented this will put the following controls on a new line in the interface
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup'] },
        { name: 'paragraph', groups: [ 'list', 'align'] },
        // { name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
        { name: 'links' }
        //{ name: 'about' }
    ];

    //Removing some buttons that is not needed.
    //For a list of the names of the buttons:
    //http://ckeditor.com/forums/CKEditor/Complete-list-of-toolbar-items
    config.removeButtons = 'Subscript,Superscript,Anchor';

    // Set the most common block elements.
    config.format_tags = 'p;h1;h2;h3;pre';

    // Simplify the dialog windows.
    config.removeDialogTabs = 'image:advanced;link:advanced';

    //UI color
    // config.uiColor = '#008CBA';

};
