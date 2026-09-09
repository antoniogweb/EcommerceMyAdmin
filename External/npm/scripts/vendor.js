const fs = require('fs');

// jQuery
fs.copyFileSync(
    'node_modules/jquery/dist/jquery.min.js',
    '../../Public/Js/vendor/jquery/jquery.min.js'
); 

// jQuery UI: JavaScript and widget structure are supplied by npm. The
// ui-lightness ThemeRoller variant used by the application is kept under
// assets, because jquery-ui-dist ships only its base theme.
fs.rmSync(
    '../../Public/Js/vendor/jquery-ui/images',
    { recursive: true, force: true }
);

fs.cpSync(
    'assets/jquery-ui-theme/images',
    '../../Public/Js/vendor/jquery-ui/images',
    { recursive: true }
);

const sourcePath = 'node_modules/jquery-ui-dist/';
const destinationPath = '../../Public/Js/vendor/jquery-ui/';

const files = [
    'jquery-ui.min.js',
];

files.forEach(file => {
    fs.copyFileSync(
        sourcePath + file,
        destinationPath + file
    );
});

fs.copyFileSync(
    'assets/jquery-ui-theme/jquery-ui.theme.min.css',
    destinationPath + 'jquery-ui.theme.min.css'
);

fs.writeFileSync(
    destinationPath + 'jquery-ui.min.css',
    fs.readFileSync(sourcePath + 'jquery-ui.structure.min.css', 'utf8') + '\n' +
    fs.readFileSync('assets/jquery-ui-theme/jquery-ui.theme.min.css', 'utf8')
);

for (const file of ['jquery-ui.structure.min.css', 'jquery-ui.theme.min.css']) {
    fs.rmSync(destinationPath + file, { force: true });
}
