<x-app-layout title="Dashboard">
  @include('partials.header', ['dashboardName' => "$student->name's $subject->name assignatura"])
  <main id="dashboard3">
    <canvas id="grades-chart"></canvas>
    <div id="grades-container"></div>
  </main>

  @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', async () => {
      const student = @json($student);
      const subject = @json($subject);
      const studentId = @json($student->id);
      const subjectId = @json($subject->id);
      const path = '{{ config('app.url') }}'

      async function getStudentGradesBySubject($studentId, $subjectId) {
      try {
        const response = await fetch(`${path}/api/students/${$studentId}/${$subjectId}/grades`)
        if (!response.ok) {
        throw new Error('Error al obtener el estudiante')
        }

        const data = await response.json()
        return data
      }
      catch (error) {
        console.error('Hubo un error:', error)
      }
      }

      async function drawGraphic(grades) {
      const ctx = document.getElementById('grades-chart').getContext('2d');

      const gradesLabels = []
      const gradesValues = []
      let index = 1
      for (const grade of grades.grades) {
        gradesValues.push(grade.grade_value)
        gradesLabels.push(`Nota ${index}`)
        index++
      }

      const myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
        labels: gradesLabels,
        datasets: [{
          label: 'Evolució de les notes',
          data: gradesValues,
          borderColor: 'rgba(75, 192, 192, 1)',
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          borderWidth: 2,
          tension: 0.4,
        }]
        },
        options: {
        responsive: true,
        scales: {
          y: {
          beginAtZero: true
          }
        }
        }
      })
      }

      async function drawContainer(grades) {
      const gradesContainer = document.getElementById('grades-container')
      const average = grades.average

      average > 5.0 ? averageColor = '#84BC42' : averageColor = '#E75148'

      const msg = `
        <table>
          <thead>
          <tr>
            <th>Nom</th>
            <th>Assignatura</th>
            <th>Nota mitjana</th>
            <th>Nota mínima</th>
            <th>Nota máxima</th>
          </tr>
          </thead>

          <tbody>
          <tr>
            <td>${student.name} ${student.surname}</td>
            <td>${subject.name}</td>
            <td style="font-weight: bold; color: ${averageColor}">${average}</td>
            <td>${grades.min}</td>
            <td>${grades.max}</td>
          </tr>
          </tbody>
        </table>
        `
      gradesContainer.innerHTML = msg
      }

      const grades = await getStudentGradesBySubject(studentId, subjectId)
      drawGraphic(grades)
      drawContainer(grades)
    })
    </script>
  @endpush
</x-app-layout>