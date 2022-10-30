const { src, dest }  = require("gulp");
const minify = require("gulp-minify");

function minifyjs() {

    src('public/zoom-client-js/tool.js', { allowEmpty: true }) 
        .pipe(minify({noSource: true}))
        .pipe(dest('public/zoom-client-js'))

    return src('public/zoom-client-js/meeting.js', { allowEmpty: true }) 
        .pipe(minify({noSource: true}))
        .pipe(dest('public/zoom-client-js'))
}

exports.default = minifyjs;