<x-app-layout title="Dashboard">
  <main id="dashboard">
    <table class="table table-sm">
      <thead>
          <tr>
              <th>id</th>
              <th>Name</th>
              <th>Surname</th>
              <th>Age</th>
              <th>Course</th>
              <th>Actions</th>
          </tr>
      </thead>
      <tbody id="students-table-body">
      </tbody>
    </table>
  </main>

  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', async () => {
        const $ = (el) => document.querySelector(el)
        const $$ = (els) => document.querySelectorAll(els)

        const tableBody = $('#students-table-body')

        async function getClassrooms() {
          try {
            const response = await fetch('http://localhost:8000/api/school/classrooms')
            if (!response.ok) {
              throw new Error('Error al obtener las clases')
            }

            const data = await response.json()
            return data

          } catch (error) {
            console.error('Hubo un error:', error)
          }
        }

        async function getStudents() {
          try {
            const response = await fetch('http://localhost:8000/api/students')
            if (!response.ok) {
              throw new Error('Error al obtener los estudiantes')
            }

            const data = await response.json()
            return data

          } catch (error) {
            console.error('Hubo un error:', error)
          }
        }

        async function updateStudent(id) {
          try {
            const response = await fetch(`http://localhost:8000/api/students/${id}`, {
              method: 'PUT',
              body: JSON.stringify({
                name: $(`#student-name-${id}`).value,
                surname: $(`#student-surname-${id}`).value,
                age: $(`#student-age-${id}`).value,
              }),
              headers: {
                'Content-Type': 'application/json',
              },
            })
            if (!response.ok) {
              throw new Error('Error al actualizar el estudiante')
            }
          } catch (error) {
            console.error('Hubo un error:', error)
          }
        }

        async function renderMainTable() {
          tableBody.innerHTML = ''

          const students = await getStudents()
          const classRooms = await getClassrooms()

          students.forEach(student => {

            const classRoomName = classRooms.find(classRoom => classRoom.id === student.classroom_id).course

            const row = `
              <tr>
                <td>${student.id}</td>
                <td>
                  <input id="student-name-${student.id}" type="text" value="${student.name}" readonly>
                </td>
                <td>
                  <input id="student-surname-${student.id}" type="text" value="${student.surname}" readonly>
                </td>
                <td>
                  <input id="student-age-${student.id}" type="text" value="${student.age}" readonly>
                </td>
                <td>${classRoomName}</td>
                <td>
                  <a href=${'/dashboard/students/'+student.id}>View</a>
                  <button id=${`edit-${student.id}`}>Edit</button>
                  <button id=${`delete-${student.id}`}>Delete</button>
                </td>
              </tr>
            `
            tableBody.innerHTML += row
          })

          const editButtons = $$('button[id^="edit-"]')
          const deleteButtons = $$('button[id^="delete-"]') 

          editButtons.forEach(button => {
            button.addEventListener('click', async () => {
              const id = button.id.split('-')[1]
              const inputsValues = $$('input[id^="student-"]')

              if (button.innerHTML === 'Save') {
                button.innerHTML = 'Edit'
                button.style.backgroundColor = '#F0F0F0'

                inputsValues.forEach(input => {
                  input.setAttribute('readonly', true)
                
                updateStudent(id)
                })
              } else {
                button.innerHTML = 'Save'
                button.style.backgroundColor = '#84BC42'

                inputsValues.forEach(input => {
                  input.removeAttribute('readonly')
                })
              }
            })
          })

          deleteButtons.forEach(button => {
            button.addEventListener('click', async () => {
              try {
                const id = button.id.split('-')[1]
                console.log('eliminar id:', id)
                const response = await fetch(`http://localhost:8000/api/students/${id}`, {
                  method: 'DELETE',
                })
                if (!response.ok) {
                  throw new Error('Error al eliminar el estudiante')
                }
                renderMainTable()
              } catch (error) {
                console.error('Hubo un error:', error)
              }
            })
          })
        }
        renderMainTable()
      })
    </script>
  @endpush

</x-app-layout>