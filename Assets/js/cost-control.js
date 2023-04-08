// KANBOARD PLUGIN ASSET FILE

// FILTER CURRENCY LIST TABLE http://jsfiddle.net/7BUmG/2/
$(document).ready(function(){
    var $rows = $('#CurrenciesTable tr.rate-results');
    $('#CurrencyCodeSearch').keyup(function() {
        var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

        $rows.show().filter(function() {
            var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
            return !~text.indexOf(val);
        }).hide();
    });
});

// PREVENTS ENTER KEY FROM BEING USED FOR THIS FORM
$(document).ready(function() {
    $("#CurrencyCodeSearch").on("keypress", function (event) {
        //console.log("Enter key pressed on input field inside form");
        var keyPressed = event.keyCode || event.which;
        if (keyPressed === 13) {
            alert("The enter key is ignored as it is not required in this filter");
            event.preventDefault();
            return false;
        }
    });
});

// SELECT INPUT ON MOUSOVER
$(document).ready(function() {
    const input = document.getElementById("CurrencyCodeSearch");
    if (input !== null) {
        input.addEventListener('mouseover', () => {
            input.select();
        })
    }
});
