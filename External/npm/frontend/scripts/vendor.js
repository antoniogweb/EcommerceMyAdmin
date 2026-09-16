const fs = require('fs');

const vendorPaths = {
    jquery: '../../../Frontend/Public/Js/vendor/jquery/',
    jqueryUi: '../../../Frontend/Public/Js/vendor/jquery-ui/',
};

for (const path of Object.values(vendorPaths)) {
    fs.rmSync(path, { recursive: true, force: true });
    fs.mkdirSync(path, { recursive: true });
}

// jQuery
fs.copyFileSync(
    'node_modules/jquery/dist/jquery.min.js',
    vendorPaths.jquery + 'jquery.min.js'
);

// jQuery UI: JavaScript and widget structure are supplied by npm. The
// ui-lightness ThemeRoller variant used by the application is kept under
// assets, because the official package ships only its base theme.
fs.cpSync(
    'assets/jquery-ui-theme/images',
    vendorPaths.jqueryUi + 'images',
    { recursive: true }
);

const jqueryUiSourcePath = 'node_modules/jquery-ui/';
const destinationPath = vendorPaths.jqueryUi;

fs.copyFileSync(
    jqueryUiSourcePath + 'dist/jquery-ui.min.js',
    destinationPath + 'jquery-ui.min.js'
);

fs.cpSync(
    jqueryUiSourcePath + 'ui/i18n',
    destinationPath + 'i18n',
    { recursive: true }
);

const jqueryUiCssFiles = [
    'core.css',
    'accordion.css',
    'autocomplete.css',
    'button.css',
    'checkboxradio.css',
    'controlgroup.css',
    'datepicker.css',
    'dialog.css',
    'draggable.css',
    'menu.css',
    'progressbar.css',
    'resizable.css',
    'selectable.css',
    'selectmenu.css',
    'slider.css',
    'sortable.css',
    'spinner.css',
    'tabs.css',
    'tooltip.css',
];

fs.writeFileSync(
    destinationPath + 'jquery-ui.min.css',
    jqueryUiCssFiles.map(file => fs.readFileSync(
        jqueryUiSourcePath + 'themes/base/' + file,
        'utf8'
    )).join('\n') + '\n' +
    fs.readFileSync('assets/jquery-ui-theme/jquery-ui.theme.min.css', 'utf8')
);
