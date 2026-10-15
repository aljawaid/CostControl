// KANBOARD PLUGIN ASSET FILE
// Filters currency rows without jQuery. Related coverage: tests/maintenance_contract.php.

document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById("CurrencyCodeSearch");
    const table = document.getElementById("CurrenciesTable");

    if (input === null || table === null) {
        return;
    }

    const rows = table.querySelectorAll("tr.rate-results");

    input.addEventListener("input", () => {
        const value = input.value.trim().replace(/ +/g, " ").toLowerCase();

        rows.forEach((row) => {
            const text = row.textContent.replace(/\s+/g, " ").toLowerCase();
            row.hidden = !text.includes(value);
        });
    });

    if (input.form !== null) {
        input.form.addEventListener("submit", (event) => {
            event.preventDefault();
            window.alert("The enter key is ignored as it is not required in this filter");
        });
    }

    input.addEventListener("mouseenter", () => {
        input.select();
    });
});
