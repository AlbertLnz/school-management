<x-app-layout title="Dashboard">
  @include('partials.header', ['dashboardName' => 'Dashboard'])
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

        const path = '{{ config('app.url') }}'
        const tableBody = $('#students-table-body')

        async function getClassrooms() {
          try {
            const response = await fetch(`${path}/api/school/classrooms`)
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
            const response = await fetch(`${path}/api/students`)
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
            const response = await fetch(`${path}/api/students/${id}`, {
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
              throw new Error('Error al crear un nuevo estudiante')
            }
          } catch (error) {
            console.error('Hubo un error:', error)
          }
        }

        async function addNewStudent() {
          try {
            const response = await fetch(`${path}/api/student`, {
              method: 'POST',
              body: JSON.stringify({
                name: $('#new-student-name').value,
                surname: $('#new-student-surname').value,
                age: $('#new-student-age').value,
                classroom_id: $('#new-student-classroom_id').value,
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
          renderMainTable()
        }

        async function addNewStudentInputsRow() {
          const newStudentRow = document.createElement('tr')
          newStudentRow.id = 'new-student-row'

          const newStudentIdInput = document.createElement('td')
          newStudentRow.appendChild(newStudentIdInput)

          const newStudentNameInput = document.createElement('td')
          newStudentNameInput.innerHTML = `<input id="new-student-name" type="text" placeholder="name">`
          newStudentRow.appendChild(newStudentNameInput)

          const newStudentSurnameInput = document.createElement('td')
          newStudentSurnameInput.innerHTML = `<input id="new-student-surname" type="text" placeholder="surname">`
          newStudentRow.appendChild(newStudentSurnameInput)

          const newStudentAgeInput = document.createElement('td')
          newStudentAgeInput.innerHTML = `<input id="new-student-age" type="number" min="12" max="16" placeholder="age">`
          newStudentRow.appendChild(newStudentAgeInput)

          const newStudentClassroomIdInput = document.createElement('td')
          newStudentClassroomIdInput.innerHTML = `
            <select id="new-student-classroom_id" name="courses">
              <option value="1">1r ESO</option>
              <option value="2">2n ESO</option>
              <option value="3">3r ESO</option>
              <option value="4">4t ESO</option>
            </select>
          `
          newStudentRow.appendChild(newStudentClassroomIdInput)

          const newStudentAddButton = document.createElement('td')
          newStudentAddButton.innerHTML = `<button>Add</button>`
          newStudentRow.appendChild(newStudentAddButton)

          tableBody.appendChild(newStudentRow)
        }

        async function renderMainTable() {
          tableBody.innerHTML = ''

          await addNewStudentInputsRow()

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

          const newStudentRow = $('#new-student-row')
          const addNewStudentBtn = $('#new-student-row button')

          addNewStudentBtn.addEventListener('click', async () => {
            addNewStudent()
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
                const response = await fetch(`${path}/api/students/${id}`, {
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