@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  Chart.defaults.font.family = "'Montserrat', 'Inter', sans-serif";

  const officeCtx = document.getElementById('officeChart');
  if (officeCtx) {
    const officeAmountsData = {!! json_encode($metrics['officeAmounts'] ?? json_decode('{}')) !!};
    
    const officeLabels = Object.keys(officeAmountsData);
    const officeData = Object.values(officeAmountsData);

    if (officeLabels.length === 0) {
      officeCtx.style.display = 'none';
      const noDataDiv = document.createElement('div');
      noDataDiv.className = 'text-center py-5 text-muted';
      noDataDiv.innerHTML = '<i class="bi bi-graph-down display-6 d-block mb-2"></i> No data recorded for this filtered timeline.';
      officeCtx.parentNode.appendChild(noDataDiv);
    } else {
      new Chart(officeCtx, {
        type: 'bar',
        data: {
          labels: officeLabels,
          datasets: [{
            label: 'Forwarded Amount',
            data: officeData,
            backgroundColor: 'rgb(240, 255, 230)',
            borderColor: '#044709',
            borderWidth: 2
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: {
              callbacks: {
                label: function(context) {
                  return '₱' + Number(context.raw).toLocaleString('en-PH', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                  });
                }
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                callback: function(value) {
                  return '₱' + Number(value).toLocaleString();
                }
              }
            }
          }
        }
      });
    }
  }
});
</script>