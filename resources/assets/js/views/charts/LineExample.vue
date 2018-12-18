<script>
import { Line } from 'vue-chartjs'

export default Line.extend({
  mounted () {
    this.renderChart({
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
      datasets: [
        {
          label: 'Data One',
          backgroundColor: '#f87979',
          data: [40, 39, 10, 40, 39, 80, 40, 35, 24, 58, 67, 17 ]
        }
      ]
    }, {responsive: true, maintainAspectRatio: false})
  }
})
</script>
