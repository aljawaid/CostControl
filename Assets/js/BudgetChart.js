// KANBOARD PLUGIN ASSET FILE
// Initialises the budget chart. Related coverage: tests/maintenance_contract.php.

document.addEventListener("DOMContentLoaded", () => {
    const budgetChart = document.getElementById("budget-chart");
    const chart = document.getElementById("chart");

    if (budgetChart === null || chart === null) {
        return;
    }

    const categories = [];
    const metrics = JSON.parse(chart.dataset.metrics);
    const labels = JSON.parse(chart.dataset.labels);
    const inputFormat = d3.time.format("%Y-%m-%d");
    const outputFormat = d3.time.format(chart.dataset.dateFormat);
    const columns = [
        [labels["in"]],
        [labels["left"]],
        [labels["out"]]
    ];
    const colors = {
        [labels["in"]]: "#5858FA",
        [labels["left"]]: "#04B404",
        [labels["out"]]: "#DF3A01"
    };

    metrics.forEach((metric) => {
        categories.push(outputFormat(inputFormat.parse(metric["date"])));
        columns[0].push(metric["in"]);
        columns[1].push(metric["left"]);
        columns[2].push(metric["out"]);
    });

    c3.generate({
        bindto: "#chart",
        data: {
            columns,
            colors,
            type: "bar"
        },
        bar: {
            width: {
                ratio: 0.25
            }
        },
        grid: {
            x: {
                show: true
            },
            y: {
                show: true
            }
        },
        axis: {
            x: {
                type: "category",
                categories
            }
        }
    });
});
