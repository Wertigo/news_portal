const $ = require('jquery');

require('../css/app.scss');

const postView = require("./post-view.js");
$(document).ready(function () {
    postView($);
});
//require('../../public/js/select2.min.js');