/*--------------------
|
| HELPERS
|
--------------------*/

var setImageValue = function(url){
    $('.mce-btn.mce-open').parent().find('.mce-textbox').val(url);
}

exports.setImageValue = setImageValue;
