<x-app-layout title="Dashboard">
  @include('partials.header', ['dashboardName' => "$student->name's subjects"])
  <main id="dashboard2">
  
  </main>

  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', async () => {
        const studentId = @json($student->id);
        const path = '{{ config('app.url') }}'
        const deleteButtons = document.querySelectorAll('button[id^="student-"]')

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
            const subjectName = subject.name;
            const modifiedSubjectName = subjectName.replace(/ /g, '_')
            const subjectAverage = gradesAverage.subjects.filter(grade => grade.subject === subject.name)[0].average
            const squareColor = subjectAverage >= 5.0 ? '#84BC42' : '#E75148'
            square.classList.add('square')
            square.innerHTML = `
              <a href=${'/dashboard/students/'+studentId+'/'+modifiedSubjectName+'/grades'}>
                <h2>${subject.name}</h2>
                <h6>${Math.round(subjectAverage * 100) / 100}</h6>
                <button id="student-${studentId}/subject-${subject.id}">View</button>
              </a>
            `
            square.style.backgroundColor = squareColor
            content.appendChild(square);
          })
        }

        // deleteButtons.forEach(button => {
        //   button.addEventListener('click', async () => {
        //     const studentId = button.id.split('-')[1]
        //     const subjectId = button.id.split('-')[2]
        //     console.log('eliminar id:', studentId, subjectId)
        //     // deleteSubject(studentId, subjectId)
        //   })
        // })
        // async function deleteSubject(subjectId) {
        //   try {
        //     const response = await fetch(`${path}/api/subjects/${subjectId}`, {
        //       method: 'DELETE',
        //     })
        //     if (!response.ok) {
        //       throw new Error('Error al eliminar el estudiante')
        //     }
        //     renderMainTable()
        //   } catch (error) {
        //     console.error('Hubo un error:', error)
        //   }
        // }

        drawSubjects()
      })
    </script>
  @endpush
</x-app-layout>