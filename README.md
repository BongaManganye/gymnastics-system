# Gymnastics Training & Membership System 

Modern web system developed for sports academy management adhering to Material Design principles.

---

## 1. System Setup & Installation

### Prerequisites
- **XAMPP** (Apache, MySQL, PHP 8.x)[cite: 6]
- **Node.js** (v18+) & **npm**[cite: 6]
- Modern web browser (Google Chrome recommended)[cite: 6]

### Setup Instructions
1. **Database Setup**:
   - Start Apache and MySQL in the XAMPP Control Panel.
   - Open phpMyAdmin at `http://localhost/phpmyadmin`[cite: 7].
   - Import the database script located at `/database/gymnastics_db.sql`[cite: 7, 8].
2. **Project Files**:
   - Place the `/gymnastics-system/` directory inside `C:/xampp/htdocs/`[cite: 7, 8].
   - Ensure the logs directory (`/logs/deleted_log.txt`) has write permissions[cite: 7].
3. **Run PHP Application**:
   - Navigate to `http://localhost/gymnastics-system/` in your browser[cite: 8].
4. **Run Node.js Mock API & React Frontend**:
   - Navigate to `/server/` in your terminal and run:
     ```bash
     npm install
     node server.js
     ```
     The mock API listens on `http://localhost:5000`[cite: 5].
   - Open a separate terminal, navigate to `/react/`, and run:
     ```bash
     npm install
     npm run dev
     ```

---

## 2. User Roles & Access

- **Admin Staff**: Full CRUD access[cite: 1, 8]. Can register gymnasts, view dashboard, update records, delete records, and generate/download reports[cite: 1, 8].
- **Gymnasts**: Read-only access to view their own profile and download enrollment slips/profile summaries[cite: 1, 8].

---

## 3. Core Features

- **Gymnast Registration**: Responsive Material Design form with fields: Full Name, Membership ID, Email, Contact Number, DOB (age 5+ rule), Training Program, and Enrollment Date[cite: 1].
- **Dashboard Management**: Dynamic table loaded via PHP arrays with live JavaScript search (Name/Email) and dropdown filters (Program/Status)[cite: 1, 2, 8].
- **Status Constants**: Built with strict constants (`ACTIVE`, `ON_HOLD`, `COMPLETED`, `PENDING`) displayed via color-coded badges[cite: 1, 2, 8].
- **Audit Logging**: Soft/Hard delete logs removed records with timestamps to `/logs/deleted_log.txt` via `file_put_contents`[cite: 2, 4, 8].
- **Reports**:
  - **Profile Summary**: Exportable PDF generated client-side using `jsPDF`[cite: 1, 2, 5, 8].
  - **Enrollment Slip**: Printable HTML view (`window.print()`) featuring registration timestamps and program details[cite: 1, 2, 5, 8].

---

## 4. Security Hardening

- **SQL Injection Prevention**: All queries utilize `mysqli_prepare()` and `bind_param()` prepared statements[cite: 2, 3, 4, 6].
- **Cross-Site Scripting (XSS)**: Output escaping using `htmlspecialchars()` across all rendering points[cite: 2, 3, 4, 6].
- **Input Sanitization & Validation**:
  - `trim()` and `stripslashes()` for clean input[cite: 1, 2].
  - `strlen()` ensures minimum name length (>= 3 chars)[cite: 2, 4, 5].
  - `filter_var()` validates RFC-compliant email formats[cite: 1, 2, 4, 5].
  - `preg_match()` validates 10-digit contact numbers and `GYMB-xxx` ID format[cite: 1, 2, 4, 5].
- **Error Handling**: Database operations use `try/catch` and `error_log()` to prevent disclosing raw SQL exceptions to the user[cite: 2, 3, 6, 7].

---

## 5. React Architecture & Justification

- **Functional Components vs. Class-Based**:
  - **Hooks over Boilerplate**: Functional components using `useState` and `useEffect` eliminate boilerplate code, binding methods, and complex lifecycle methods (`componentDidMount`, `componentWillUnmount`)[cite: 3, 5, 6].
  - **Better State Management**: Decouples logic into reusable custom hooks, improving readability and maintainability[cite: 3].
  - **Modern Standard**: Aligns with modern React best practices recommended for production frontends.
- **Components Implemented**:
  - `GymnastDashboard.jsx`: Functional dashboard handling state, asynchronous fetch to the Node.js mock API (`/api/gymnasts`), and deletion triggers[cite: 4, 5, 6].
  - `ProfileCard.jsx`: Reusable functional presentation component displaying member cards with props[cite: 4, 6].
