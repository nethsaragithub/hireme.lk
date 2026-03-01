# 📚 HireMe.lk Task System - Complete Documentation Index

## 🎯 Start Here

Choose your role to get started:

### 👤 I'm a Developer/Admin
**Quick Installation:**
1. Read: [QUICK_START.md](QUICK_START.md) (5 minutes)
2. Execute: `database_adjustments.sql`
3. Test the system
4. Deploy to production

**Deep Dive:**
1. [IMPLEMENTATION_SUMMARY.md](IMPLEMENTATION_SUMMARY.md) - What was built
2. [ARCHITECTURE.md](ARCHITECTURE.md) - System design
3. [TASK_SYSTEM_README.md](TASK_SYSTEM_README.md) - Complete features
4. [TASK_FLOW_DIAGRAM.md](TASK_FLOW_DIAGRAM.md) - User journeys

### 👨‍💼 I'm a Business Owner
**Understanding the System:**
1. [QUICK_START.md](QUICK_START.md) - How it works
2. [TASK_FLOW_DIAGRAM.md](TASK_FLOW_DIAGRAM.md) - User journeys
3. [IMPLEMENTATION_SUMMARY.md](IMPLEMENTATION_SUMMARY.md) - Features overview

### 👨‍💻 I'm a Tester
**Testing Checklist:**
1. [IMPLEMENTATION_SUMMARY.md](IMPLEMENTATION_SUMMARY.md#testing-checklist)
2. [TASK_SYSTEM_README.md](TASK_SYSTEM_README.md#testing-checklist)
3. Test each scenario in [TASK_FLOW_DIAGRAM.md](TASK_FLOW_DIAGRAM.md)

---

## 📁 File Structure & Purposes

### 🚀 Getting Started (Read These First)
```
├── QUICK_START.md ........................ 5-minute setup guide
├── IMPLEMENTATION_SUMMARY.md ............. What was built & checklist
└── SETUP.sh .............................. Automated setup script
```

### 📖 Complete Documentation
```
├── TASK_SYSTEM_README.md ................. Full feature documentation
│   ├── Installation steps
│   ├── Database schema
│   ├── Usage guide
│   ├── API endpoints
│   ├── Utility functions
│   ├── Security features
│   └── Troubleshooting
│
├── ARCHITECTURE.md ........................ System design & data flow
│   ├── Project structure
│   ├── Data relationships
│   ├── Request/response flow
│   ├── Security layers
│   ├── Performance optimization
│   └── Scaling considerations
│
└── TASK_FLOW_DIAGRAM.md .................. User journeys & scenarios
    ├── Customer (task poster) flow
    ├── Runner (task picker) flow
    ├── Auto-expiration flow
    ├── State diagram
    ├── Concurrent user handling
    ├── Real-world examples
    └── Error scenarios
```

### 💾 Database
```
└── database_adjustments.sql .............. SQL schema changes
    ├── Adds new columns to tasks table
    ├── Creates task_activity_log table
    ├── Sets up indexes
    ├── Creates helpful views
    └── Ensures data integrity
```

### 🔧 PHP Application Files

**NEW FILES:**
```
├── pick-task.php ......................... Task acceptance logic
│   ├── GET - Show confirmation page
│   └── POST - Accept task (prevents double-picking)
│
├── task-expiry-handler.php ............... Auto-expire old tasks
│   ├── Run via cron job
│   ├── Run via command line
│   ├── Run via HTTP with token
│   └── Updates status & logs activity
│
└── includes/ ............................. Helper files
    └── task-utils.php ................... Utility functions
        ├── checkAndExpireOldTasks()
        ├── getOpenTasksCount()
        ├── getRunnerActiveTasksCount()
        ├── getRunnerCompletedTasksCount()
        ├── getRunnerTotalEarnings()
        ├── getTaskDetails()
        ├── isTaskAvailable()
        ├── getTaskTimeRemaining()
        └── getTaskActivity()
```

**UPDATED FILES:**
```
├── tasks.php ............................. Browse open tasks
│   ├── Show only open tasks < 24 hours
│   ├── Category filtering
│   ├── Location search
│   ├── "Accept Task" button for runners
│   └── Error/success messages
│
└── runner-dashboard.php .................. Manage accepted tasks
    ├── Show runner's tasks
    ├── Filter by status
    ├── Mark as completed
    ├── Cancel tasks
    └── Visual status indicators
```

---

## 🔑 Key Features

### ✅ Task Browsing
- [x] Browse all open tasks
- [x] Filter by category
- [x] Search by location
- [x] View task details
- [x] See time since posted
- [x] Only shows available tasks

### ✅ Task Picking
- [x] Confirmation page
- [x] Prevents accidental selection
- [x] Shows full details
- [x] Status auto-updates
- [x] Prevents double-picking
- [x] Instant feedback

### ✅ Task Management
- [x] Dashboard for runners
- [x] Filter tasks by status
- [x] Mark completed
- [x] Cancel tasks
- [x] View earnings
- [x] Track history

### ✅ Auto-Expiration
- [x] Tasks expire after 24 hours (if not picked)
- [x] Status auto-set to 'cancelled'
- [x] Can run via cron job
- [x] Can run via CLI
- [x] Can run via HTTP API
- [x] Activity logged

### ✅ Data Integrity
- [x] Database constraints
- [x] Race condition prevention
- [x] Activity logging
- [x] Audit trail
- [x] Timestamp tracking
- [x] Foreign key relationships

---

## 🚀 Installation Quick Links

### Dependencies
- MySQL/MariaDB database
- PHP 7.2+ with MySQLi
- Web server (Apache/Nginx)
- Cron support (for auto-expiration)

### Installation (3 Steps)
1. **Import SQL**: Execute `database_adjustments.sql` on database
   ```bash
   mysql -u root -p hireme < database_adjustments.sql
   ```

2. **Update Token**: Edit `task-expiry-handler.php` line 13
   ```php
   $EXPIRY_TOKEN = "your-secret-token-change-this";
   ```

3. **Setup Cron** (optional):
   ```bash
   0 * * * * curl -s http://yoursite.com/task-expiry-handler.php?token=your-token
   ```

---

## 📊 Database Schema Summary

### Tasks Table Changes
```sql
-- New columns:
ALTER TABLE tasks ADD runner_id INT NULL;
ALTER TABLE tasks ADD taken_at TIMESTAMP NULL;
ALTER TABLE tasks ADD expired_at TIMESTAMP NULL;

-- Updated column:
ALTER TABLE tasks MODIFY status ENUM('open', 'taken', 'cancelled', 'completed');
```

### New Table: task_activity_log
```sql
CREATE TABLE task_activity_log (
  id INT AUTO_INCREMENT PRIMARY KEY,
  task_id INT NOT NULL,
  runner_id INT NULL,
  action VARCHAR(50),
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (task_id) REFERENCES tasks(id),
  FOREIGN KEY (runner_id) REFERENCES users(id)
);
```

### Database Views Created
```sql
-- View for open tasks
CREATE VIEW open_tasks_view AS
SELECT * FROM tasks WHERE status = 'open' ...

-- View for taken tasks  
CREATE VIEW taken_tasks_view AS
SELECT * FROM tasks WHERE status = 'taken' ...
```

---

## 🔄 Task Status Lifecycle

```
CREATED (open)
    ↓
[24 HOURS]
    ├─ No Runner? → EXPIRED (cancelled)
    └─ Runner Accepted? → TAKEN (taken)
         ↓
         ├─ Completed? → DONE (completed)
         └─ Cancelled? → CANCELLED (cancelled)
```

---

## 🛠️ Common Operations

### As a Developer

**Run task expiration check:**
```bash
# Via cron job
0 * * * * curl -s http://yoursite.com/task-expiry-handler.php?token=secret

# Via command line
php task-expiry-handler.php

# Via HTTP
curl "http://yoursite.com/task-expiry-handler.php?token=secret"
```

**Use utility functions:**
```php
require_once "includes/task-utils.php";

$expired = checkAndExpireOldTasks($conn);
$open_count = getOpenTasksCount($conn);
$earnings = getRunnerTotalEarnings($conn, $runner_id);
```

**Query the database:**
```sql
-- Find open tasks
SELECT * FROM tasks WHERE status = 'open' AND age < 24 hours;

-- Find runner's tasks
SELECT * FROM tasks WHERE runner_id = ?;

-- Track activity
SELECT * FROM task_activity_log WHERE task_id = ?;
```

### As a User (Runner)

1. **Browse tasks**: Go to `/tasks.php`
2. **Filter**: By category, search by location
3. **Pick task**: Click "Accept This Task"
4. **Manage**: Check `/runner-dashboard.php`
5. **Complete**: Mark task as done
6. **Earn**: Get rewarded!

---

## 📈 Monitoring & Analytics

### Key Metrics
- Open tasks available
- Tasks picked per day
- Completion rate
- Average pickup time
- Runner earnings
- Expiration rate

### Useful Queries
```sql
-- Daily new tasks
SELECT DATE(created_at) as date, COUNT(*) 
FROM tasks WHERE status = 'open' 
GROUP BY DATE(created_at);

-- Top earning runners
SELECT runner_id, SUM(reward) as earnings 
FROM tasks WHERE status = 'completed' 
GROUP BY runner_id ORDER BY earnings DESC;

-- Task completion time
SELECT TIMESTAMPDIFF(MINUTE, taken_at, NOW()) as minutes_elapsed
FROM tasks WHERE status = 'completed';
```

---

## 🔐 Security Checklist

- [ ] Change `$EXPIRY_TOKEN` in `task-expiry-handler.php`
- [ ] Verify database constraints applied
- [ ] Check prepared statements in PHP files
- [ ] Verify authentication on sensitive routes
- [ ] Test race condition prevention
- [ ] Review activity logs regularly
- [ ] Backup database regularly
- [ ] Monitor error logs

---

## 🐛 Troubleshooting Flowchart

**Problem**: Tasks not expiring after 24 hours
```
├─ Is cron job running?
│  └─ Check: crontab -l
├─ Is task-expiry-handler.php accessible?
│  └─ Check: curl http://yoursite.com/task-expiry-handler.php
└─ Check database status column
   └─ Run: SELECT * FROM tasks WHERE status = 'open' AND created_at < NOW() - INTERVAL 1 DAY
```

**Problem**: Runners can't pick tasks
```
├─ Is runner logged in?
│  └─ Check: $_SESSION['role'] === 'runner'
├─ Does task have status = 'open'?
│  └─ Check database
├─ Is task less than 24 hours old?
│  └─ Check created_at timestamp
└─ Check browser console for errors
   └─ F12 → Console tab
```

**Problem**: Activity log not updating
```
├─ Does task_activity_log table exist?
│  └─ Check: SHOW TABLES;
├─ Are INSERT statements in code?
│  └─ Check: pick-task.php, runner-dashboard.php
└─ Check database permissions
   └─ Verify user can INSERT to table
```

---

## 📞 Support Resources

### Documentation Files
- [QUICK_START.md](QUICK_START.md) - 5-minute setup
- [TASK_SYSTEM_README.md](TASK_SYSTEM_README.md) - Complete guide
- [ARCHITECTURE.md](ARCHITECTURE.md) - Technical details
- [TASK_FLOW_DIAGRAM.md](TASK_FLOW_DIAGRAM.md) - User scenarios
- [IMPLEMENTATION_SUMMARY.md](IMPLEMENTATION_SUMMARY.md) - What's included

### Code Files
- `pick-task.php` - Task acceptance
- `task-expiry-handler.php` - Auto-expiration
- `includes/task-utils.php` - Helper functions
- `tasks.php` - Browse tasks
- `runner-dashboard.php` - Manage tasks

### Database
- `database_adjustments.sql` - Schema

---

## ✨ Next Steps (Optional Enhancements)

1. **Add Ratings**: Star ratings after task completion
2. **Notifications**: Email/SMS task updates
3. **Real-time**: WebSocket for live updates
4. **Maps**: Show pickup/delivery locations
5. **Analytics**: Dashboard with metrics
6. **Mobile App**: Native iOS/Android app
7. **Payments**: Automated reward processing
8. **Instant Chat**: Message runners
9. **Verification**: ID verification for users
10. **Availability**: Calendar for runner availability

---

## 📋 Version Info

**Version**: 1.0  
**Status**: Production Ready ✓  
**Last Updated**: March 2, 2026  
**Database Version**: MySQL 5.7+  
**PHP Version**: 7.2+  

---

## 🎓 Learning Path

### Beginner
1. QUICK_START.md
2. TASK_FLOW_DIAGRAM.md (Customer/Runner flows only)
3. Set up and test the system

### Intermediate  
1. TASK_SYSTEM_README.md
2. Database schema section
3. API endpoints section
4. Test advanced features

### Advanced
1. ARCHITECTURE.md
2. Database design patterns
3. Security layers
4. Performance optimization
5. Scaling considerations

---

## 🏁 Success Criteria

After setup, you should see:
- ✅ Open tasks in browse page
- ✅ Runner can pick task
- ✅ Task disappears from browse
- ✅ Task appears in dashboard
- ✅ Can mark completed
- ✅ Activity logged
- ✅ Old tasks expire
- ✅ Status tags working

---

## 📞 Quick Reference

**File Locations:**
- PHP Files: `/`
- Database: `database_adjustments.sql`
- Utilities: `includes/task-utils.php`
- Documentation: `*.md` files

**Key Functions:**
```php
checkAndExpireOldTasks($conn)
getOpenTasksCount($conn)
getRunnerActiveTasksCount($conn, $runner_id)
isTaskAvailable($conn, $task_id)
```

**Key Endpoints:**
```
GET  /tasks.php                      Browse open tasks
GET  /pick-task.php?task_id=123     Confirm task
POST /pick-task.php                 Accept task
GET  /runner-dashboard.php          View my tasks
POST /runner-dashboard.php          Update task
GET  /task-expiry-handler.php?token=xxx  Expire tasks
```

**Database Queries:**
```sql
SELECT * FROM tasks WHERE status = 'open' AND age < 24 HOURS
UPDATE tasks SET status = 'taken' WHERE id = ? AND runner_id IS NULL
SELECT * FROM task_activity_log WHERE task_id = ?
```

---

## 🎉 You're All Set!

Your HireMe.lk task system is ready to go!

**Next Step**: Read [QUICK_START.md](QUICK_START.md) and follow the 5-minute setup.

**Questions?** Every question is answered in the documentation files above.

---

💡 **Tip**: Bookmark this file for quick reference!

🚀 **Happy coding!**
