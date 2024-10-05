<x-app-layout title="Dashboard">
  @include('partials.header', ['dashboardName' => "$student->name's subjects"])
  <main id="dashboard2">
  
  </main>

  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', async () => {
        const studentId = @json($student->id);
        const path = '{{ config('app.url') }}'
        
        async function getStudentSubjects() {

          try {
            const response = await fetch(`${path}/api/students/${studentId}/subjects`)
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

        async function getStudentGradesAverage($studentId) {
          try {
            const response = await fetch(`${path}/api/students/${$studentId}/grades/average`)
            if (!response.ok) {
              throw new Error('Error al obtener el estudiante')
            }
            const data = await response.json()
            return data
          } catch (error) {
            console.error('Hubo un error:', error)
          }
        }

        async function drawSubjects() {
          const content = document.getElementById('dashboard2')
          const subjects = await getStudentSubjects()
          const gradesAverage = await getStudentGradesAverage(studentId)

          subjects.forEach(subject => {
            const square = document.createElement('div')
            const subjectAverage = gradesAverage.subjects.filter(grade => grade.subject === subject.name)[0].average
            const squareColor = subjectAverage >= 5.0 ? '#84BC42' : '#E75148'
            square.classList.add('square')
            square.innerHTML = `
              <a href=${'/dashboard/students/'+studentId+'/'+subject.name+'/grades'}>
                <h2>${subject.name}</h2>
                <h6>${Math.round(subjectAverage * 100) / 100}</h6>
                <button>Delete</button>
              </a>
            `
            square.style.backgroundColor = squareColor
            content.appendChild(square);
          })
        }

        drawSubjects()
      })
    </script>
  @endpush
</x-app-layout>