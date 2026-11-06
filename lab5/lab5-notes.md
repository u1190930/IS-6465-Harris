Part 1: Create the Database tables:

1. SQLite version 3.34.1 2021-01-20 14:10:07
Enter ".help" for usage hints.
sqlite> .tables
students           tutoring_requests
sqlite> .schema
CREATE TABLE students (
  student_id INTEGER PRIMARY KEY,
  student_name TEXT NOT NULL,
  student_email TEXT NOT NULL UNIQUE
);
CREATE TABLE tutoring_requests (
  request_id INTEGER PRIMARY KEY,
  student_id INTEGER NOT NULL,
  course TEXT NOT NULL,
  topic TEXT NOT NULL,
  preferred_date TEXT NOT NULL,
  preferred_time TEXT NOT NULL,
  status TEXT NOT NULL DEFAULT 'Open',
  created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES students(student_id)
);
2. student_id & request_id are the primary keys
3. student_id is the foreign key in tutoring requests. It represents that a student can have a tutoring request
4. It is marked unique so that no 2 students can have the same email. It could also be used as an identifier for a student if needed.


Part 2: Insert students and tutoring requests

1. Insert Statements: 
INSERT OR ignore INTO students (student_name, student_email)
VALUES ('Jordan Lee', 'jordan.lee@example.edu');

INSERT OR ignore INTO students (student_name, student_email)
VALUES ('Avery Patel', 'avery.patel@example.edu');

INSERT OR ignore INTO tutoring_requests
  (student_id, course, topic, preferred_date, preferred_time)
VALUES
  (1, 'IS 6465', 'SQL SELECT', '2026-11-11', '14:15');

INSERT OR ignore INTO tutoring_requests
  (student_id, course, topic, preferred_date, preferred_time)
VALUES
  (1, 'IS 6465', 'Forms', '2026-11-10', '14:00');

INSERT OR ignore INTO tutoring_requests
  (student_id, course, topic, preferred_date, preferred_time)
VALUES
  (2, 'IS 6465', 'SQLite CRUD', '2026-11-10', '14:00');

2. Results of select * from tutoring_requests;
1|1|IS 6465|SQL SELECT|2026-11-11|14:15|Open|2026-11-06 20:09:27
2|1|IS 6465|Forms|2026-11-10|14:00|Open|2026-11-06 20:09:27
3|2|IS 6465|SQLite CRUD|2026-11-10|14:00|Open|2026-11-06 20:09:27

3. Jordan has one student row, but 2 request rows because he is one student in the student table, but there are 2 requests in the requests table that are connected to him.

Part 3: Read the Stored Data
SELECT
  r.request_id,
  s.student_name,
  s.student_email,
  r.course, r.topic,
  r.preferred_date, r.preferred_time, r.status
FROM tutoring_requests AS r
JOIN students AS s ON r.student_id = s.student_id
ORDER BY r.preferred_date;
2|Jordan Lee|jordan.lee@example.edu|IS 6465|Forms|2026-11-10|14:00|Open
3|Avery Patel|avery.patel@example.edu|IS 6465|SQLite CRUD|2026-11-10|14:00|Open
1|Jordan Lee|jordan.lee@example.edu|IS 6465|SQL SELECT|2026-11-11|14:15|Open

1. Join connects the two tables together by the foreign key, student_id
2. Join is used to connect two or more different tables into one query. The where clause helps filter query results to a specified field.
3. because request_id is the primary key for the requests table. The result for that should always be one because primary keys are a unique identifier.

Part 4:
1. 
UPDATE tutoring_requests
SET topic = 'This is a better Topic'
WHERE request_id = 1;
UPDATE tutoring_requests
SET status = 'Cancelled'
WHERE request_id = 3;
2&3. 
Before updates: 
2|Jordan Lee|jordan.lee@example.edu|IS 6465|Forms|2026-11-10|14:00|Open
3|Avery Patel|avery.patel@example.edu|IS 6465|SQLite CRUD|2026-11-10|14:00|Open
1|Jordan Lee|jordan.lee@example.edu|IS 6465|SQL SELECT|2026-11-11|14:15|Open
After updates:
2|Jordan Lee|jordan.lee@example.edu|IS 6465|Forms|2026-11-10|14:00|Open
3|Avery Patel|avery.patel@example.edu|IS 6465|SQLite CRUD|2026-11-10|14:00|Cancelled
1|Jordan Lee|jordan.lee@example.edu|IS 6465|This is a better Topic|2026-11-11|14:15|Open
4. It gets the correct record that you would like to update. If you didn't have that, it would update all requests.
5. Because it's always a good idea to hold onto data for a minute, because you never know if you'll need to roll back what you have done.

Part 5:
1. 
INSERT OR ignore INTO tutoring_requests
  (student_id, course, topic, preferred_date, preferred_time)
VALUES
  (2, 'IS 6465', 'DELETE PRACTICE', '2026-11-11', '14:15');
2. 
DELETE from tutoring_requests
WHERE request_id = 4;
3. 
Before:
2|Jordan Lee|jordan.lee@example.edu|IS 6465|Forms|2026-11-10|14:00|Open
3|Avery Patel|avery.patel@example.edu|IS 6465|SQLite CRUD|2026-11-10|14:00|Cancelled
1|Jordan Lee|jordan.lee@example.edu|IS 6465|This is a better Topic|2026-11-11|14:15|Open
4|Avery Patel|avery.patel@example.edu|IS 6465|DELETE PRACTICE|2026-11-11|14:15|Open
After:
2|Jordan Lee|jordan.lee@example.edu|IS 6465|Forms|2026-11-10|14:00|Open
3|Avery Patel|avery.patel@example.edu|IS 6465|SQLite CRUD|2026-11-10|14:00|Cancelled
1|Jordan Lee|jordan.lee@example.edu|IS 6465|This is a better Topic|2026-11-11|14:15|Open
4. Like update, it identifies which record you want to affect. Again, it wouldn't be great if you didn't indicate where you wanted that change to occur.

Part 6. 
CRUD tables answers:
Create: INSERT -> INSERT OR ignore INTO students (student_name, student_email)
VALUES ('Jordan Lee', 'jordan.lee@example.edu');
Read: SELECT -> select * from tutoring_requests;
Update: UPDATE -> UPDATE tutoring_requests
SET status = 'Cancelled'
WHERE request_id = 3;
Delete: DELETE -> DELETE from tutoring_requests
WHERE request_id = 4;
1. It keeps the connection by using foreign and primary keys to join multiple tables.
2. The fact that the field is designated as "unique".
3. It would update or delete all rows. Including a where is very important.
4. Because this prevents SQL injection. If a user submits input that is also SQL commands, so long as they are parameters, then nothing will happen, no sql will be executed.