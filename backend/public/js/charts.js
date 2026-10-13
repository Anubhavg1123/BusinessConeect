// Chart.js Analytics Configurations for Dashboards
window.initDashboardCharts = function() {
  // 1. Business Growth Chart (Line Chart)
  const growthCtx = document.getElementById('growthChart');
  if (growthCtx) {
    new Chart(growthCtx, {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
        datasets: [{
          label: 'Business Registrations',
          data: [12, 19, 25, 32, 48, 65, 82, 104],
          borderColor: '#4f46e5',
          backgroundColor: 'rgba(79, 70, 229, 0.1)',
          fill: true,
          tension: 0.4
        }]
      },
      options: { responsive: true, plugins: { legend: { display: false } } }
    });
  }

  // 2. Products vs Services Chart (Doughnut Chart)
  const categoryCtx = document.getElementById('categoryDistributionChart');
  if (categoryCtx) {
    new Chart(categoryCtx, {
      type: 'doughnut',
      data: {
        labels: ['Technology', 'Healthcare', 'Real Estate', 'Retail', 'Financial Services'],
        datasets: [{
          data: [35, 25, 15, 15, 10],
          backgroundColor: ['#4f46e5', '#06b6d4', '#10b981', '#f59e0b', '#ec4899']
        }]
      },
      options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });
  }

  // 3. Customer Inquiries Breakdown (Bar Chart)
  const inquiryCtx = document.getElementById('inquiryChart');
  if (inquiryCtx) {
    new Chart(inquiryCtx, {
      type: 'bar',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
          label: 'Inquiries Received',
          data: [45, 68, 92, 115, 140, 185],
          backgroundColor: '#06b6d4'
        }]
      },
      options: { responsive: true }
    });
  }
};

document.addEventListener('DOMContentLoaded', () => {
  if (typeof Chart !== 'undefined') {
    window.initDashboardCharts();
  }
});
