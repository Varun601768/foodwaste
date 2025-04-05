// Toggle Sidebar
document.querySelector('.toggle-sidebar').addEventListener('click', function() {
    document.querySelector('.sidebar').classList.toggle('collapsed');
    document.querySelector('.main-content').classList.toggle('expanded');
});

// Initialize Charts
document.addEventListener('DOMContentLoaded', function() {
    // Gender Distribution Chart
    const genderCtx = document.getElementById('genderChart').getContext('2d');
    new Chart(genderCtx, {
        type: 'bar',
        data: {
            labels: ['Male', 'Female'],
            datasets: [{
                data: [<?= $male ?>, <?= $female ?>],
                backgroundColor: ['#06C167', '#3A7BFF'],
                borderColor: ['#06C167', '#3A7BFF'],
                borderWidth: 1,
                borderRadius: 6,
                hoverBackgroundColor: ['#05A357', '#2D6BFF'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'User Gender Distribution',
                    font: { size: 16 }
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    padding: 12,
                    cornerRadius: 6,
                }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.1)' } },
                x: { grid: { display: false } }
            },
        }
    });

    // Donation Location Chart
    const donationCtx = document.getElementById('donationChart').getContext('2d');
    new Chart(donationCtx, {
        type: 'bar',
        data: {
            labels: ['Puttur', 'Sulya', 'Vitla'],
            datasets: [{
                data: [<?= $puttur ?>, <?= $sulya ?>, <?= $vitla ?>],
                backgroundColor: ['#06C167', '#3A7BFF', '#FF6384'],
                borderColor: ['#06C167', '#3A7BFF', '#FF6384'],
                borderWidth: 1,
                borderRadius: 6,
                hoverBackgroundColor: ['#05A357', '#2D6BFF', '#E55373'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Food Donations by Location',
                    font: { size: 16 }
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    padding: 12,
                    cornerRadius: 6,
                }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.1)' } },
                x: { grid: { display: false } }
            },
        }
    });
});