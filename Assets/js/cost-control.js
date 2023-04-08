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

