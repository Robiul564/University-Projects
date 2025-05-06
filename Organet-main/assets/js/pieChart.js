document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('projectChart').getContext('2d');

    // Create the chart using values from PHP
    const chart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Active Projects', 'Complete Projects'],
            datasets: [
                {
                    label: 'Project Status',
                    data: [activeProjects, inactiveProjects],
                    backgroundColor: ['#C62300', '#0A6847'], // Active = red, Complete = green
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        color: '#fff', // Set white color for the legend text
                    },
                },
            },
        },
    });
});
