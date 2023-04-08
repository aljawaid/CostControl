// KANBOARD PLUGIN ASSET FILE

// FILTER TABLES IN THE INSTALLED PLUGINS PAGE
$(document).ready(function(){
    $("#CurrencyCodeSearch").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $(".currencies-table").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
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
