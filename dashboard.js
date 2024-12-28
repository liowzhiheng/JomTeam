const Users = {
    labels: ['Active Users Today', 'Inactive Users'],
    datasets: [{
        label: 'User Activity',
        data: [activeUsersPercentage, inactiveUsersPercentage],
        backgroundColor: [
            'rgba(82, 82, 212, 0.7)',
            'rgba(200, 200, 200, 0.7)'
        ],
        borderColor: [
            'rgba(82, 82, 212, 1)',
            'rgba(200, 200, 200, 1)'
        ],
        borderWidth: 2
    }]
};

const Ads = {
    labels: ['Active Ads', 'Inactive Ads'],
    datasets: [{
        label: 'Active Ads',
        data: [activeAdsPercentage, inactiveAdsPercentage],
        backgroundColor: [
            'rgba(54, 162, 235, 0.7)',
            'rgba(200, 200, 200, 0.7)'
        ],
        borderColor: [
            'rgba(54, 162, 235, 1)',
            'rgba(200, 200, 200, 1)'
        ],
        borderWidth: 2
    }]
};

const upcomingMatchesData = {
    labels: ['Upcoming Matches'],
    datasets: [{
        label: 'Upcoming Matches',
        data: [upcomingMatches],
        backgroundColor: 'rgba(255, 159, 64, 0.5)',
        borderColor: 'rgba(255, 159, 64, 1)',
        borderWidth: 2
    }]
};

const newFeedbackData = {
    labels: ['New Feedback'],
    datasets: [{
        label: 'New Feedback',
        data: [newFeedback],
        backgroundColor: 'rgba(75, 192, 192, 0.5)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 2
    }]
};

const UsersCtx = document.getElementById('activeUsersChart').getContext('2d');
const activeUsersChart = new Chart(UsersCtx, {
    type: 'pie',
    data: Users,
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            },
            tooltip: {
                callbacks: {
                    label: function (tooltipItem) {
                        return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(2) + '%';
                    }
                }
            }
        }
    }
});

const AdsCtx = document.getElementById('activeAdsChart').getContext('2d');
const activeAdsChart = new Chart(AdsCtx, {
    type: 'pie',
    data: Ads,
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            },
            tooltip: {
                callbacks: {
                    label: function (tooltipItem) {
                        return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(2) + '%';
                    }
                }
            }
        }
    }
});

const upcomingMatchesCtx = document.getElementById('upcomingMatchesChart').getContext('2d');
const upcomingMatchesChart = new Chart(upcomingMatchesCtx, {
    type: 'bar',
    data: upcomingMatchesData,
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            },
            tooltip: {
                enabled: true,
            }
        }
    }
});

const newFeedbackCtx = document.getElementById('newFeedbackChart').getContext('2d');
const newFeedbackChart = new Chart(newFeedbackCtx, {
    type: 'bar',
    data: newFeedbackData,
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            },
            tooltip: {
                enabled: true,
            }
        }
    }
});
