# Lab 7 Peer Tutoring Hub

Keep the Lab 4 HTML, CSS, includes, checkbox topics, radio time choices and existing validation tests. Add student name/email and persistent SQLite storage. The approved Week 6 database has only one `topic` column. This teaching version stores a JSON string in that TEXT column with `reason` and `topics` so both Lab 4 values survive without changing the ERD. The `preferred_time` column stores the Lab 4 values Morning, Afternoon or Evening (not a clock time).

Lab 7 adds complete user management (UM-01 through UM-10): public Student/Tutor registration, login/logout, profile editing, password changes, administrator search/role/status/delete workflows, and protected tutoring requests. Passwords are stored using `password_hash`; every state-changing form uses a CSRF token; and the session guard refreshes the current account and enforces a 20-minute idle timeout.

The original Lab 6 request workflow remains three-tiered: browser page → service → repository → SQLite. User SQL is in `repositories/userPDO.php`, account rules are in `services/userService.php`, and shared session/CSRF/role checks are in `includes/auth.php`.

This version uses PHP 7-compatible syntax and requires the PDO SQLite and mbstring extensions.

## Run in GitHub Codespaces

1. From this folder, start with a fresh database if desired: `rm -f database/tutoring.db && sqlite3 database/tutoring.db < database/setup-database.sql`. Existing Lab 6 databases are upgraded on first connection.
2. Run `php -S 0.0.0.0:8000 php/router.php` and open forwarded port 8000.
3. Visit `/` or `/index.php`; it opens login. Create a Student or Tutor account at `/php/register.php`. The bootstrap administrator is `admin@example.edu` with password `password`; change or remove this classroom-only account before deployment.
4. Students may submit, view, edit, and cancel only their own Open requests. Administrators may manage all requests and accounts. Tutors have no tutoring-request workflow.
5. Run `php tests/integration-tests.php` and `php tests/user-management-tests.php` in a separate terminal. The Lab 4 validation checks remain in `tests/validation-tests.php`.

## Traceability summary

| Use case | Primary implementation | Automated evidence |
|---|---|---|
| UM-01 Register | `register.php`, `registerUser()` | registration, duplicate, role tests |
| UM-02 View own account | `profile.php`, `requireLogin()` | guarded profile code review |
| UM-03 Search accounts | `manage-users.php`, `searchUsers()` | search test |
| UM-04 Update profile | `edit-profile.php`, `saveOwnProfile()` | profile update test |
| UM-05 Change password | `change-password.php`, `changeOwnPassword()` | old/new password tests |
| UM-06 Update role | `update-role.php`, `setManagedRole()` | role update test |
| UM-07 Account status | `update-status.php`, `setManagedStatus()` | inactive-login and last-admin tests |
| UM-08 Delete account | `delete-user.php`, `removeManagedUser()` | allowed/blocked deletion tests |
| UM-09 Login | `login.php`, `authenticateUser()` | valid/invalid credential tests |
| UM-10 Logout | `logout.php`, `logoutSession()` | code-level session invalidation review |

**Classroom only:** the implementation follows the assigned security controls but is not a production deployment. Use HTTPS, a dedicated public document root, rate limiting, secure reset flows, audit logging, and real secrets before public use. Do not use real student information in this demo.
