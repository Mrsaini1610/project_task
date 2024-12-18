<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Form</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .error {
            color: red;
        }
    </style>
</head>

<body>
    <div class="style-main-wrapper">

        <div class="style-form-wrapper">
            <h2>Submit User Form</h2>
            <form id="userForm" enctype="multipart/form-data">
                <div class="style-input-flex">
                    <div>
                        <label for="firstname" class="style-form-label"> First name </label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            placeholder="Jane"
                            class="style-form-input" required />
                        <br>
                        <span class="error" id="nameError"></span><br>
                    </div>
                    <div>
                        <label for="role_id" class="style-form-label">Role ID</label>
                        <select name="role_id" id="role_id" class="style-form-input" required>
                            <option value="" disabled selected>Select Role ID</option>
                        </select>
                        <br>
                        <span class="error" id="roleError" aria-live="polite"></span>
                        <br>
                    </div>
                </div>

                <div class="style-input-flex">
                    <div>
                        <label for="email" class="style-form-label"> E-Mail </label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            placeholder="jhon@mail.com"
                            class="style-form-input" required><br>
                        <span class="error" id="emailError"></span><br>
                    </div>
                    <div>
                        <label for="phone" class="style-form-label"> Phone </label>

                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            class="style-form-input"
                            placeholder="9722200000"

                            maxlength="10" required><br>
                        <span class="error" id="phoneError"></span><br>
                    </div>
                </div>



                <div>
                    <label for="description " class="style-form-label"> Description </label>
                    <textarea
                        rows="6"
                        name="description"
                        id="description"
                        placeholder="Type your Description "
                        class="style-form-input"></textarea><br>
                    <span class="error" id="descriptionError"></span><br>
                </div>
                <div>
                    <label for="profile" class="style-form-label"> Profile Image </label>
                    <input type="file" id="profile_image" name="profile_image" accept="image/*" required><br>
                    <span class="error" id="profileImageError"></span><br>
                </div>

                <button class="style-btn">
                    Submit
                </button>
            </form>
        </div>
    </div>

    <!-- //user data -->
    <div class="style-main-wrapper">
        <div class="style-form-wrapper">
            <h3>User Data</h3>
            <table border="1" id="userTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Description</th>
                        <th>Role ID</th>
                        <th>Profile Image</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
          $(document).ready(function() {
            fetchRoleBase();
        });
      
        const form = document.getElementById('userForm');
        const tableBody = document.querySelector('#userTable tbody');


        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;


        form.addEventListener('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(form);


            document.querySelectorAll('.error').forEach(el => el.innerText = '');

            axios.post('/users', formData)
                .then(response => {
                    alert(response.data.success);
                    form.reset();
                    fetchUsers();
                })
                .catch(error => {
                    if (error.response && error.response.data.errors) {
                        let errors = error.response.data.errors;
                        for (const key in errors) {
                            document.getElementById(key + 'Error').innerText = errors[key][0];
                        }
                    }
                });
        });

        // Fetch users and display in table
        function fetchUsers() {
            axios.get('/users')
                .then(response => {
                    tableBody.innerHTML = '';
                    response.data.forEach(userlist => {
                        tableBody.innerHTML += `
                            <tr>
                                <td>${userlist.name}</td>
                                <td>${userlist.email}</td>
                                <td>${userlist.phone}</td>
                                <td>${userlist.description ?? ''}</td>
                                <td>${userlist.role ? userlist.role.name : 'No role'}</td>
                                <td><img src="/images/${userlist.profile_image}" width="50"></td>
                            </tr>
                        `;
                    });
                });
        }

        function fetchRoleBase() {
            $roleDropdown = $('#role_id');

            axios.get('/role_base')
                .then(response => {

                    console.log('Response:', response.data); // Debug log

                    if (response.data && Array.isArray(response.data)) {
                        response.data.forEach(role => {
                            $roleDropdown.append(`<option value="${role.id}">${role.name}</option>`);
                        });
                    } else {
                        console.error('Unexpected response format:', response);
                        $roleDropdown.append('<option value="">No roles available</option>');
                    }

                });
        }


        fetchUsers();
    </script>
</body>

</html>