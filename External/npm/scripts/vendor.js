const fs = require('fs');

fs.copyFileSync(
    'node_modules/jquery/dist/jquery.min.js',
    '../../Public/Js/vendor/jquery/jquery.min.js'
); 
