<link rel="stylesheet" type="text/css" href="css/styles-classlist.css">
<?php session_start();?>

<body>
    <table class="table_students bwcolor">
        <thead>
            <th style="padding-left: 2rem;">Profile Picture</th>
            <th>Name</th>
            <th>Username</th>
            <th>Email</th>
            <?php if ($_SESSION['usertype'] == "Instructor"):?>
            <th>Remove</th>
            <?php endif;?>
        </thead>
        <tbody id="student-body" class="bwcolor">
            <!-- Data Will populate here -->
        </tbody>
    </table>
</body>
<script>
    //Get Students
    $.ajax({
        url: "services/get-students.php",
        type: "POST",
        data: {
            authorize: 'gradeplus',
            invite_code: $('p.side-nav-course-invite').text()
        },
        success: function(response) {
            if (response['success'] == 1) {
                response.data.forEach(function(student) {
                    var profilePicSrc = student.profile_picture;
                    var row = `
                            <tr>
                                <td class='classlist-profile'>
                                    ${student.profile_picture ? `<img src="${profilePicSrc}" class="classlist-profile-pic">` : '<i class="material-symbols-outlined">account_circle</i>'}
                                </td>
                                <td class='classlist-dname'>${student.dname}</td>
                                <td class='classlist-username'>${student.username}</td>
                                <td class='classlist-email'>${student.email}</td>
                                <?php if ($_SESSION['usertype'] == "Instructor"): ?>
                                <td class='classlist-remove'>
                                    <button class='btn-flat waves-effect waves-light classlist-remove-student' data-username="${student.username}">
                                        <i class="material-icons" style="color: red;">delete</i>
                                    </button>
                                </td>
                                <?php endif; ?>
                            </tr>`;
                    $('#student-body').append(row);
                });
                //Remove Students Only For Instructors
                $("button.classlist-remove-student").click(function() {
                    var username = $(this).data('username');
                    var invite_code = $('p.side-nav-course-invite').text();
                    var row = $(this).closest('tr');
                    $.ajax({
                        url: "services/remove-student.php",
                        type: "POST",
                        data: {
                            'authorize': 'gradeplus',
                            'username': username,
                            'invite_code': invite_code
                        },
                        success: function(response) {
                            if (response['success'] == 1) {
                                row.remove();
                            } else {
                                window.alert("500 - Server Error");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log("Failed to remove student: ", status, error);
                        }
                    });
                });
            } else {
                window.alert("500 - Server Error");
            }
        },
        error: function(xhr, status, error) {
            console.log("Failed to update classlist: ", status, error);
        }
    });
</script>