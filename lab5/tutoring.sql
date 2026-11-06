-- Part 1: Create Tables
CREATE TABLE if not exists students (
  student_id INTEGER PRIMARY KEY,
  student_name TEXT NOT NULL,
  student_email TEXT NOT NULL UNIQUE
);

CREATE TABLE if not exists tutoring_requests (
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



--Part 2: Insert students and tutoring requests
--1st insert students
INSERT OR ignore INTO students (student_name, student_email)
VALUES ('Jordan Lee', 'jordan.lee@example.edu');

INSERT OR ignore INTO students (student_name, student_email)
VALUES ('Avery Patel', 'avery.patel@example.edu');
--2nd insert tutoring requests
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

select * from tutoring_requests;

--Part 3:
select * from students;

select * from tutoring_requests;

select * from tutoring_requests where status='Open';

select * from tutoring_requests where request_id=3;

 SELECT
  r.request_id,
  s.student_name,
  s.student_email,
  r.course, r.topic,
  r.preferred_date, r.preferred_time, r.status
FROM tutoring_requests AS r
JOIN students AS s ON r.student_id = s.student_id
ORDER BY r.preferred_date;

--Part 4: Updating
UPDATE tutoring_requests
SET topic = 'This is a better Topic'
WHERE request_id = 1;

select * from tutoring_requests;

UPDATE tutoring_requests
SET status = 'Cancelled'
WHERE request_id = 3;


--Part 5: Delete Request
INSERT OR ignore INTO tutoring_requests
  (student_id, course, topic, preferred_date, preferred_time)
VALUES
  (2, 'IS 6465', 'DELETE PRACTICE', '2026-11-11', '14:15');

DELETE from tutoring_requests
WHERE request_id = 4;