#!/bin/bash

# MySQL configuration
DB_USER="gradeplusclient"
DB_PASS="gradeplussql"
DB_NAME="gradeplus"

RESET_URL="http://localhost:8000/services/reset-demo.php"

# Function to run MySQL commands
run_mysql_command() {
  local sql_query="$1"
  mysql -u "$DB_USER" -p"$DB_PASS" -D "$DB_NAME" -e "$sql_query"
}

# Reset the database
echo "Resetting the database..."
curl -X POST -d "authorize=gradeplus" "$RESET_URL"
if [[ $? -eq 0 ]]; then
  echo "✅ Database reset successful."
else
  echo "❌ Database reset failed."
  exit 1
fi

# Insert dummy data
echo "Inserting dummy data..."
insert_sql="

INSERT INTO login (username, email, password, dname, usertype) VALUES
('Raja', 'raja@gradeplus.com', 'raja_pass', 'Raja', 'Instructor'),
('Hammed', 'hammed@gradeplus.com', 'hammed_pass', 'Hammed', 'Instructor');

INSERT INTO assignment (course_code, username, assignment_name, assignment_file, description, due_date, instructor_username) VALUES
('ECE 6400', 'student1', 'A1', NULL, 'Introduction to Software Development', '2024-11-15', 'Raja'),
('ECE 6400', 'student2', 'A1', NULL, 'Introduction to Software Development', '2024-11-15', 'Raja'),
('ECE 6400', 'student3', 'A1', NULL, 'Introduction to Software Development', '2024-11-15', 'Raja'),
('ECE 6400', 'student1', 'A2', NULL, 'Object-Oriented Design', '2024-12-01', 'Raja'),
('ECE 6500', 'student1', 'A1', NULL, 'Algorithm Analysis', '2024-11-20', 'Hammed'),
('ECE 6500', 'student2', 'A1', NULL, 'Algorithm Analysis', '2024-11-20', 'Hammed'),
('ECE 6500', 'student3', 'A1', NULL, 'Algorithm Analysis', '2024-11-20', 'Hammed');

INSERT INTO login (username, email, password, dname, usertype) VALUES
('student1', 'student1@gradeplus.com', 'stud1pass', 'Student One', 'Student'),
('student2', 'student2@gradeplus.com', 'stud2pass', 'Student Two', 'Student'),
('student3', 'student3@gradeplus.com', 'stud3pass', 'Student Three', 'Student');

INSERT INTO courses (course_code, course_name, course_banner, instructor_name, instructor_dname, invite_code) VALUES
('ECE6600', 'Data Structures', '../img/data_structures.jpg', 'Raja', 'Raja', 'MNOPQR');

INSERT INTO enrollment (username, course_code, course_name, pinned, invite_code, instructor) VALUES
('student1', 'ECE 6400', 'Software Development', 1, 'ABCDEF', 'Raja'),
('student2', 'ECE 6400', 'Software Development', 0, 'ABCDEF', 'Raja'),
('student3', 'ECE 6400', 'Software Development', 0, 'ABCDEF', 'Raja'),
('instructor', 'ECE 6400', 'Software Development', 1, 'ABCDEF', 'Raja'),

('student1', 'ECE 6500', 'Advanced Algorithms', 1, 'GHIJKL', 'Hammed'),
('student2', 'ECE 6500', 'Advanced Algorithms', 0, 'GHIJKL', 'Hammed'),
('student3', 'ECE 6500', 'Advanced Algorithms', 0, 'GHIJKL', 'Hammed'),
('Hammed', 'ECE 6500', 'Advanced Algorithms', 1, 'GHIJKL', 'Hammed'),

('student1', 'ECE 6600', 'Data Structures', 1, 'MNOPQR', 'Raja'),
('student2', 'ECE 6600', 'Data Structures', 0, 'MNOPQR', 'Raja'),
('student3', 'ECE 6600', 'Data Structures', 0, 'MNOPQR', 'Raja'),
('Raja', 'ECE 6600', 'Data Structures', 1, 'MNOPQR', 'Raja');

"

# Run insert SQL commands
if run_mysql_command "$insert_sql"; then
    echo "✅ Dummy data inserted."
else
    echo "❌ Failed to insert dummy data."
fi