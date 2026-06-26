# Task Testing Workflow

This portal uses **CodeIgniter 3** (not Laravel). The workflow maps your spec to existing tables:

| Spec | Database table |
|------|----------------|
| Modules | `modules` |
| Issues | `task_issues` only (`task_id` = parent main task in `task_list`) — **no extra rows in `task_list` for issues** |
| ActivityLog | `task_activity_log` |
| Tasks | `task_list` (one row per main task only) |
| Issue images | `task_issue_images` |

## Time tracking (`task_list`)

| Field | Meaning |
|-------|---------|
| `actual_hrs` / `actual_min` on **main** task | Developer original implementation time |
| `tester_hrs` / `tester_min` on **main** task | Total QA / testing time (sum of per-issue tester time) |
| `task_issues.time_spent_*` | Developer fix time per issue |
| `task_issues.task_id` | Links each issue to the one main task row |
| `parent_id` / `workflow_kind` on `task_list` | Optional columns for main tasks only (`workflow_kind = main`); do not insert issue rows here |

**Total task time** = developer + tester + issue fix time (shown on Report issues and Edit task).

Run migrations:

```bash
mysql -u root -p new_emp_portal_cursor < database/alter_task_list_testing_columns.sql
mysql -u root -p new_emp_portal_cursor < database/alter_task_list_parent_workflow.sql
mysql -u root -p new_emp_portal_cursor < database/alter_task_issues_tester_time.sql
mysql -u root -p new_emp_portal_cursor < database/task_issue_images.sql
```

## Setup

1. Run the migration SQL:

```bash
mysql -u root new_emp_portal_cursor < database/testing_workflow_migration.sql
```

2. Uncomment any `ALTER TABLE` lines in that file if columns are missing.

3. Configure outbound email in `application/config/email.php` and optionally set in `application/config/config.php`:

```php
$config['task_notify_from'] = 'noreply@yourcompany.com';
```

## Roles

- **Team Leaders** (`employees.department = 'Teamleader'`): create and manage **modules** per project at `/manage-modules`.
- **Developers**: create tasks, mark **Ready for Testing**, log fix time (`actual_hrs` / `actual_min`).
- **Testers** (`employees.department = 'Software Testing'`): add issues, assign fixes, log tester time, mark **Completed**.
- **Admins** (`admin_section = yes`): full access including modules and **Testing Dashboard** at `/testing-dashboard`.

## Managing modules

There was no module UI before; it is now available here:

| Who | Where |
|-----|--------|
| Team Leader or Admin | Sidebar → **Task** → **Manage Modules** |
| Same users | Project detail page → **Manage modules** button |

URL: `/manage-modules` (optional filter: `?project_id=12`)

**POST `/save-module`** — create or update:

```
project_id=12
module_name=Login & Auth
type=development
module_id=        (empty = new, or id to edit)
```

**POST `/delete-module`** — `module_id=3` (blocked if tasks already use that module).

## Module rules

- **Development** projects/services → module **required**.
- **Digital** only → module **optional**.
- **Mixed** projects → rule follows the **selected service** category (`project_service_list.category_name`).

The task form calls `POST /check-module-required` when project or service changes.

## URLs

| Action | URL |
|--------|-----|
| Testing queue (all users) | Sidebar → **Ready for Testing** or `/my-task/Ready%20for%20Testing` |
| Legacy URL | `/testing-tasks` (redirects to Ready for Testing list) |
| Admin dashboard | `/testing-dashboard` |
| Save task | `POST /save-task` |
| Report issues (page) | `/report-issues/{task_id}` |
| Save issues | `POST /save-issue` (`submit_action`: `save_only` or `save_and_return`) |
| Assign issue | `POST /assign-issue` |
| Finalize QA time | `POST /finalize-testing` |
| Log developer fix time | `POST /update_task_time` |
| Activity log JSON | `POST /get_task_activity_log` |

---

## Examples

### 1. Developer adds a task

1. Open any page → **Add Task**.
2. Select **Project** → **Service** → **Module** (if required).
3. Enter title, optional description.
4. **Assign Developer(s)** (multi-select).
5. **Assign Tester** (Software Testing staff only).
6. Save.

**POST `/save-task`** (simplified):

```
proj_name=12
service_name=45
module_id=3
task_title=Login API validation
task_desc=Validate OTP flow
assignees[]=EMP001
tester_id=EMP099
task_status=Pending
```

### 2. Developer sends to testing

On dashboard or **My Task**, click **Ready for Testing**.

**Mandatory time before status transfer** (enforced in UI popup + server):

| Who | Action | Time logged to |
|-----|--------|----------------|
| Developer | Status → **Ready for Testing** (dropdown or button) | `actual_hrs` / `actual_min` (added to existing) |
| Tester | Status → **In Progress** (return to developer) | `tester_hrs` / `tester_min` |
| Tester | Status → **Completed** / **Mark complete** | `tester_hrs` / `tester_min` |
| Tester | **Submit issues & return to developer** (report issues page) | `tester_hrs` / `tester_min` |
| Tester | **Log QA time** (finalize modal) | `tester_hrs` / `tester_min` |
| Developer | **Log fix time** | Issue `time_spent_*` and/or `actual_hrs` |

Without at least 1 minute entered, the status change is rejected.

**POST `/update_task_status`** (optional `workflow_hrs`, `workflow_min` when time is required):

```
selectedValue=Ready for Testing
selectBoxId=1234
```

→ Tester receives email (if `email` is set on employee record).

### Tester QA panel (sidebar)

| Menu | URL | Shows |
|------|-----|--------|
| **Ready for Testing** | `/my-task/Ready%20for%20Testing` | Tasks waiting for QA |
| **Pending / With developer** | `/my-task/Pending` | `Pending` + `In Progress` (developer fixing) |
| **Need Discussion** | `/my-task/Need%20Discussion` | Blocked — needs clarification |
| **QA Completed** | `/my-task/Completed` | Completed by QA |

**Mark complete:** On **Ready for Testing**, use **Mark complete** (or **Log QA time** → checkbox *Also mark Completed*). All issues must be closed first.

**Reopen:** On **QA Completed** or **Need Discussion**, use **Reopen task** / **Reopen for testing** → status **Ready for Testing**.

**Status dropdown:** Each row has a STATUS dropdown (Ready for Testing, Pending, In Progress, Need Discussion, Completed).

### 3. Tester adds issues

1. Go to **Ready for Testing**.
2. Click **Add issues** → opens page `/report-issues/{task_id}`.
3. Add one or more rows: title, priority, **assign developer**, description + screenshots (up to 10 per issue).
4. Choose a button:
   - **Submit** — saves issues only; task stays **Ready for Testing** (no testing time asked).
   - **Submit issues & return to developer** — opens a popup for **total testing time** for the whole session (e.g. 10 issues = one entry such as 3h 15m). That time is added to the main task `tester_hrs` / `tester_min`, then issues are assigned and task → **In Progress**.
   - **Cancel** — back to Ready for Testing list (no save).

**POST `/save-issue`** (multipart if images attached):

```
task_id=1234
issue_title[]=Invalid OTP message
issue_desc[]=Shows error 500
priority[]=High
assigned_to[]=EMP001
issue_images[0][]=  (files, optional — index matches issue row)
session_tester_hrs=3
session_tester_min=15
assigned_to[]=EMP001
```

Run `database/alter_task_issues_image.sql` (legacy single image column) and `database/task_issue_images.sql` (multiple images per issue). Files are stored under `uploads/issue_images/`.

### 4. Developer sees assigned issues

After tester uses **Submit issues & return to developer**, the main task moves to **In Progress**.

| Where | What developer sees |
|-------|---------------------|
| Sidebar → **Issues to fix** | `/my-task/In%20Progress` — tasks with open QA issues |
| **Dashboard** / **Today's Task** | Top section: tasks in **In Progress** / **Doing** with red **Issues: N** badge |
| **Edit task** | Full issue list (title, description, images, fix time) |
| Click **Issues: N** badge | Quick popup list of all reported issues |
| **Task issues list** | `/task-issues/{task_id}` — all reported issues for a main task; click a row to open issue detail |
| **Issue detail** | `/issue-detail/{issue_id}` — full description, metadata, image gallery, **per-issue status** (Open / Fixed / Closed / Reopened) |

**Per-issue status** (not the parent task): change on **Issue detail** page, **Edit task** → Reported Issues table, **Report issues** (existing list), or **Issues: N** popup dropdown.

Use **Log Fix Time** on the task, then **Ready for Testing** when fixes are done.

### 5. Developer fixes and logs time

Click **Log Time for Issue Fix** on the task (dashboard) or use edit task.

**POST `/update_task_time`**:

```
task_id=1234
issue_id=56
fix_hrs=1
fix_min=30
```

→ Adds to `actual_hrs` / `actual_min` and issue `time_spent_*`; marks issue **Fixed**.

### 5. Tester completes (no issues)

1. **Testing Tasks** → **Submit QA** → enter tester hours/minutes.
2. Check **Mark task completed** if no open issues.

**POST `/finalize-testing`**:

```
task_id=1234
tester_hrs=0
tester_min=45
mark_complete=1
```

Or use **Complete** (only when zero open issues).

### 6. Time totals

Total task time = **developer** (`actual_hrs/min`) + **tester** (`tester_hrs/min`) + **issue fix time** (`task_issues.time_spent_*`).

Shown on **Edit Task** and returned in `finalize-testing` / `update_task_time` JSON as `time_summary`.

---

## Admin dashboard filters

`GET /testing-dashboard?project_id=&module_id=&employee=&from_date=&to_date=`

Shows: completed / pending / ready for testing counts, total issues, module-wise breakdown.
