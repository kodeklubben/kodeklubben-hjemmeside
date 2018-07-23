var gulp = require('gulp'),
    plumber = require('gulp-plumber'),
    autoprefixer = require('gulp-autoprefixer'),
    imagemin = require('gulp-imagemin'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    cssnano = require('gulp-cssnano'),
    htmlmin = require('gulp-htmlmin'),
    rename = require('gulp-rename'),
    sass = require('gulp-sass'),
    changed = require('gulp-changed');

var path = {
    dist: 'web/',
    src: 'app/Resources/assets/'
};

function stylesProd(){
    var dest = path.dist + 'css/';
    gulp.src(path.src + 'scss/**/*.scss')
        .pipe(plumber())
        .pipe(changed(dest))
        .pipe(sass())
        .pipe(autoprefixer())
        .pipe(cssnano())
        .pipe(gulp.dest(dest))
}

function scriptsProd(){
    var dest = path.dist + 'js/';
    gulp.src(path.src + 'js/**/*.js')
        .pipe(plumber())
        .pipe(changed(dest))
        .pipe(uglify())
        .pipe(gulp.dest(dest))
}

function imagesProd(){
    var dest = path.dist + 'img/';
    gulp.src(path.src + 'img/**/*')
        .pipe(plumber())
        .pipe(changed(dest))
        .pipe(imagemin({
            progressive: false,
            interlaced: false,
            optimizationLevel: 1
        }))
        .pipe(gulp.dest(dest))
}

function stylesDev(){
    var dest = path.dist + 'css/';
    return gulp.src(path.src + 'scss/**/*.scss')
        .pipe(plumber())
        .pipe(changed(dest))
        .pipe(sass())
        .pipe(autoprefixer())
        .pipe(gulp.dest(dest))
}

function scriptsDev(){
    var dest = path.dist + 'js/';
    return gulp.src(path.src + 'js/**/*.js')
        .pipe(plumber())
        .pipe(changed(dest))
        .pipe(gulp.dest(dest))
}

function vendor(){
    var ret = true;

    ret = ret && gulp.src('node_modules/ckeditor/**/*')
        .pipe(gulp.dest('web/js/vendor/ckeditor/'));
    ret = ret && gulp.src('node_modules/bootstrap-sass/assets/javascripts/bootstrap.min.js')
        .pipe(gulp.dest('web/js/'));
    ret = ret && gulp.src('node_modules/jquery/dist/jquery.min.js')
        .pipe(gulp.dest('web/js/'));
    ret = ret && gulp.src('node_modules/bootstrap-sass/assets/fonts/bootstrap/*')
        .pipe(gulp.dest('web/fonts/bootstrap'));
    ret = ret && gulp.src('node_modules/font-awesome/fonts/*')
        .pipe(gulp.dest('web/fonts/'));

    return ret;
}

function imagesDev(){
    var dest = path.dist + 'img/';
    return gulp.src(path.src + 'img/**/*')
        .pipe(plumber())
        .pipe(changed(dest))
        .pipe(gulp.dest(dest))
}

function compressImages(){
    var dest = 'web/img/';
    return gulp.src('web/img/**/*')
        .pipe(plumber())
        .pipe(imagemin({
            progressive: false,
            interlaced: false,
            optimizationLevel: 1
        }))
        .pipe(gulp.dest(dest))
}

function config(){
    return gulp.src('app/Resources/config/ckeditor.js')
        .pipe(concat('config.js'))
        .pipe(gulp.dest('web/bundles/ivoryckeditor/'));
}

function files(){
    return gulp.src(path.src + 'files/*')
        .pipe(gulp.dest('web/files/'))
}

function watch(){
    gulp.watch(path.src + 'scss/**/*.scss', stylesDev);
    gulp.watch(path.src + 'js/**/*.js', scriptsDev);
    gulp.watch(path.src + 'images/*', imagesDev);
}

gulp.task('build:prod', gulp.parallel(stylesProd, scriptsProd, imagesProd, files, vendor, config));
gulp.task('build:dev', gulp.parallel(stylesDev, scriptsDev, imagesDev, files, vendor, config));
gulp.task('default', gulp.series('build:dev', watch));

exports.watch = watch;
