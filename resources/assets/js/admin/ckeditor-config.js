let config = {};

(function (config) {
    // Define changes to default configuration here.
    // For complete reference see:
    // http://docs.ckeditor.com/#!/api/CKEDITOR.config

    // The toolbar groups arrangement, optimized for two toolbar rows.
    config.toolbarGroups = [
        {name: 'clipboard', groups: ['clipboard', 'undo']},
        {name: 'editing', groups: ['find', 'selection', 'spellchecker']},
        {name: 'links'},
        {name: 'insert'},
        {name: 'forms'},
        {name: 'tools'},
        {name: 'document', groups: ['mode', 'document', 'doctools']},
        {name: 'others'},
        '/',
        {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
        {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi']},
        {name: 'styles'},
        {name: 'colors'},
        {name: 'about'}
    ];

    // Remove some buttons provided by the standard plugins, which are
    // not needed in the Standard(s) toolbar.
    config.removeButtons = 'Underline,Subscript,Superscript';

    // Set the most common block elements.
    config.format_tags = 'p;h1;h2;h3;pre';

    // Simplify the dialog windows.
    config.removeDialogTabs = 'image:advanced;link:advanced';

    config.extraPlugins = 'uploadimage,image2';
    config.uploadUrl = '/dist/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json';
    config.imageUploadUrl = '/dist/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images&responseType=json';
    config.filebrowserBrowseUrl = '/dist/ckfinder/ckfinder.html';
    config.filebrowserImageBrowseUrl = '/dist/ckfinder/ckfinder.html?type=Images';
    config.filebrowserUploadUrl = '/dist/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
    config.filebrowserImageUploadUrl = '/dist/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
})(config);

export default config;
