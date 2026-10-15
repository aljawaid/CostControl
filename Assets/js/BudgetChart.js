// KANBOARD PLUGIN ASSET FILE
// Initialises the budget chart. Related coverage: tests/maintenance_contract.php.

document.addEventListener("DOMContentLoaded", () => {
    const budgetChart = document.getElementById("budget-chart");
    const chart = document.getElementById("chart");

    if (budgetChart !== null && chart !== null) {
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
    }

    const taskCostChart = document.getElementById("task-cost-summary-chart");

    if (taskCostChart !== null) {
        const metrics = JSON.parse(taskCostChart.dataset.metrics);
        const labels = JSON.parse(taskCostChart.dataset.labels);
        const columns = [
            [labels["budget"], metrics["budget"]],
            [labels["pending"], metrics["pending"]],
            [labels["confirmed"], metrics["confirmed"]]
        ];
        const colors = {
            [labels["budget"]]: "#5858FA",
            [labels["pending"]]: "#FF6500",
            [labels["confirmed"]]: "#DF3A01"
        };

        c3.generate({
            bindto: "#task-cost-summary-chart",
            data: {
                columns,
                colors,
                type: "bar"
            },
            bar: {
                width: {
                    ratio: 0.35
                }
            },
            grid: {
                y: {
                    show: true
                }
            }
        });
    }
});
