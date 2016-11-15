//Cache the loading wheel image
var loading =$('<img/>');
loading.attr('src', "/img/loader.gif");
loading.attr('width', 20);

function getLoadingWheel(){
    return loading.clone();
}

/**
 * Disables elements with class 'onclick-disable' when they are clicked
 */
(function onclickDisable(){
    var $noSubmitElements = $('.onclick-disable[type!=submit]')
    var $submitElements = $('.onclick-disable[type=submit]')

    $noSubmitElements.on('click', function(e){
        var $target = $(e.target).closest('.onclick-disable')
        if(!$target.attr('disabled')){
            disableElement($target)
        }else{
            e.preventDefault()
        }
    })

    $submitElements.closest('form').submit(function(){
        var $ele =  $(this).find('.onclick-disable')
        if(!$ele.attr('disabled')){
            disableElement($ele)
        }
    })

    function disableElement($ele){
        $ele.empty()
        $ele.attr('disabled', true)
        $ele.append(loading.clone())
    }
})();

/**
 * Click the closest button when enter is pressed
 */
(function enterClickButton(){
    var $forms = $('.enter-click-button')
    $forms.keydown(function(e){
        if(e.keyCode === 13){
            $(e.target).closest('.enter-click-button').find('button').click()
            e.preventDefault()
        }
    })
})();

/**
 * Adds the active class to active navigation links
 */
(function addActiveClassToLinks() {
    var pathname = window.location.pathname
    var $activeLink = $('a[href="' + pathname + '"]')
    $activeLink.closest('li').addClass('active')

    //Add active class to control panel link in main navigation if a control panel page is active
    if (pathname.indexOf('/kontrollpanel') > -1) {
        var $controlPanelLink = $('ul.nav a[href="/kontrollpanel"]')
        $controlPanelLink.closest('li').addClass('active')
    }
})();

/**
 * Sort Table By Attribute
 * @param $table jQuery table element
 * @param {string} attr Attribute to be sorted by
 * @param {boolean} ascending True will sort ascending order
 */
function sortTableByAttribute($table, attr, ascending){
    var rows = $table.find('tbody  tr').get();

    rows.sort(function(a, b) {

        var A = $(a).attr(attr);
        var B = $(b).attr(attr);

        if(A < B) {
            return ascending ? -1 : 1;
        }

        if(A > B) {
            return ascending ? 1 : -1;
        }

        return 0;

    });

    $.each(rows, function(index, row) {
        $table.children('tbody').append(row);
    });
};

Date.prototype.getWeekNumber = function(){
    var d = new Date(+this);
    d.setHours(0,0,0,0);
    // Set to nearest Thursday: current date + 4 - current day number
    // Make Sunday's day number 7
    d.setDate(d.getDate() + 4 - (d.getDay()||7));
    // Get first day of year
    var yearStart = new Date(d.getFullYear(),0,1);
    // Calculate full weeks to nearest Thursday
    return Math.ceil(( ( (d - yearStart) / 86400000) + 1)/7);
};
