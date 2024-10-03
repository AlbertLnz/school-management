<x-app-layout title="Dashboard">
  @include('partials.header', ['dashboardName' => $student->name])
  <main id="dashboard2">
  
  </main>

  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', async () => {
        const studentId = @json($student->id)
        
        async function getStudentSubjects() {

          try {
            const response = await fetch(`http://localhost:8000/api/students/${studentId}/subjects`)
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

        // BETTER USE ONLY THIS ONE: /students/{studentId}/grades/average
        async function getStudentGradesBySubject($studentId, $subjectId) {
          try {
            const response = await fetch(`http://localhost:8000/api/students/${$studentId}/${$subjectId}/grades`)
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
        
        async function drawSubjects() {
          const content = document.getElementById('dashboard2')
          const subjects = await getStudentSubjects()

          for (const subject of subjects) {
            const square = document.createElement('div')
            square.classList.add('square')

            const subjectAverage = await getStudentGradesBySubject(studentId, subject.id)
            const squareColor = subjectAverage.average > 5.0 ? '#84BC42' : '#E75148'
            const subjectName = subject.name.toLowerCase().replace(/\s+/g, '_');

            square.innerHTML = `
                <a href=${'/dashboard/students/'+studentId+'/'+subjectName+'/grades'}>
                  <h2>${subject.name}</h2>
                  <h6>${Math.round(subjectAverage.average * 100) / 100}</h6>
                  <button>Delete</button>
                </a>
            `

            square.style.backgroundColor = squareColor
            content.appendChild(square);
          }
        }
        drawSubjects()
      })
    </script>
  @endpush
</x-app-layout>