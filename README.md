## POSTER 

https://www.canva.com/design/DAG7vouTmLc/0SN8Joj_Q76Xt2tlYPiWqQ/view?utm_content=DAG7vouTmLc&utm_campaign=designshare&utm_medium=link2&utm_source=uniquelinks&utlId=h7c454fce26

# iPetro / RBIMS  
**Risk-Based Inspection Management System**

## 1. System Overview

iPetro (RBIMS) is a **web-based Risk-Based Inspection (RBI) Management System** designed to support industrial inspection workflows.  
The system enables organizations to manage equipment inventories, inspection plans, inspection results, and user profiles in a centralized platform.

RBIMS also integrates **AI-assisted data extraction** to automate the interpretation of technical drawings, reducing manual data entry and improving inspection efficiency.

---

## 2. Technical Architecture

The system follows a **monolithic web application architecture**, where the frontend and backend are served from a single Node.js application.

---

## 3. Technology Stack

### 3.1 Backend

- **Runtime:** Node.js  
- **Web Framework:** Express.js  
- **Entry Point:** `server.js`

The backend handles:
- HTTP request routing
- Business logic
- Authentication and authorization
- Database transactions
- AI service integration

**Middleware Used:**
- `cors` – Enables Cross-Origin Resource Sharing
- `body-parser` – Parses JSON and URL-encoded request bodies
- `multer` – Handles file uploads (e.g., profile images, inspection documents)
- `express-session` – Manages user sessions and authentication state

---

### 3.2 Database

- **Database Type:** PostgreSQL (Relational Database)
- **Schema Definition:** `rr4.sql`

The database stores structured data for:
- Users and roles
- Equipment master data
- Inspection records
- Inspection parts and methods

The backend communicates with the database using a connection pool and parameterized queries to ensure data integrity and security.

---

### 3.3 Frontend

- **Technologies:** HTML, CSS, JavaScript
- **Rendering Model:** Server-side served (monolithic)

**Directory Structure:**
- `public/` – Publicly accessible pages (e.g., Login)
- `private/` – Authenticated pages (e.g., dashboard, inspection modules)

The frontend interacts with backend REST APIs for data retrieval and persistence.

---

## 4. Authentication & Authorization

The system implements session-based authentication:

- User login and logout handled via `AuthController.js`
- Sessions managed using `express-session`
- Role-based access control (e.g., Admin vs Inspector)
- Admin users have additional permissions for user and system management

---

## 5. Core Functional Modules

### 5.1 Authentication Management
- User login and logout
- Session validation
- Role-based access checks

### 5.2 User Management
- Admin-managed user accounts
- User profile management
- Role assignment

### 5.3 Equipment Management
- CRUD operations for industrial equipment
- Equipment metadata storage (design code, pressure, temperature, etc.)

### 5.4 Inspection Management
- Inspection planning and execution
- Inspection history tracking
- Risk rating calculation based on operating parameters
- Inspection report generation

### 5.5 File and Image Handling
- Uploading and storing profile images
- Uploading technical drawings and inspection evidence
- Backend support for document/image processing

---

## 6. AI Integration

### 6.1 AI Model Used

- **AI Provider:** Google Generative AI
- **Model:** `gemini-2.5-flash`
- **Type:** Multimodal (Text + Image)

### 6.2 AI Capabilities

The AI module is used to:
- Analyze technical drawings and engineering documents
- Extract structured design and inspection data from images
- Infer missing engineering attributes (e.g., phase, material type)
- Generate structured JSON output for direct database insertion

This significantly improves inspection efficiency by reducing manual data extraction from drawings and datasheets.

---

## 7. Summary

RBIMS (iPetro) combines traditional inspection management workflows with modern web technologies and AI-powered automation.  
The system is designed to be scalable, secure, and extensible, supporting both operational inspection needs and advanced risk-based analysis.




## OUTPUT :
# LOGIN MODULE:
<img width="461" height="267" alt="image" src="https://github.com/user-attachments/assets/206d6294-f846-45d4-8ace-c25cd37435f4" />

# DASHBOARD PAGE:
<img width="1248" height="609" alt="image" src="https://github.com/user-attachments/assets/3acc1672-7376-4ded-a09c-bac68a08e1d2" />

# INSPECTION PIPELINE FOR DATA EXTRACTION:
<img width="893" height="444" alt="image" src="https://github.com/user-attachments/assets/5890963b-03af-4541-96e5-07ba729f7d65" />

# EQUIPMENT MANAGER PAGE:
<img width="1047" height="519" alt="image" src="https://github.com/user-attachments/assets/72c9e8ad-c185-4a8c-9ed1-8927a8697840" />

# ADD NEW EQUIPMENT INTERFACE:
<img width="335" height="464" alt="image" src="https://github.com/user-attachments/assets/40e97bab-1df8-49cd-ace7-4dded0b1898a" />

# INTERFACE OF INSPECTION PLAN HISTORY:
<img width="1093" height="476" alt="image" src="https://github.com/user-attachments/assets/9e23de94-81c2-4766-af8a-86750a5472a3" />

# USER MANAGEMENT INTERFACE:
<img width="792" height="537" alt="image" src="https://github.com/user-attachments/assets/434ae343-619e-44b5-9886-f4ba2ed27f2b" />

# ADD USER INTERFACE:
<img width="369" height="460" alt="image" src="https://github.com/user-attachments/assets/dbe7f419-e427-422e-a1d9-60ff147b428f" />

#  EDIT MODE OF PREVIEW  INTERFACE:
<img width="748" height="646" alt="image" src="https://github.com/user-attachments/assets/11445576-4d7a-4d96-b5b9-7b4d672ff434" />

# MY PROFILE  INTERFACE:
<img width="742" height="406" alt="image" src="https://github.com/user-attachments/assets/2d676ad4-c1ce-48fd-af38-0905dc2dd7ec" />

# PDF BEING GENERATED:
<img width="562" height="554" alt="image" src="https://github.com/user-attachments/assets/e6b7a931-765c-457d-bc71-16250bff01b0" />

# POWERPOINT  GENERATED:
<img width="747" height="359" alt="image" src="https://github.com/user-attachments/assets/b1411429-8bc3-42ed-9cac-451a3cca2343" />

# INSPECTION PLAN  EXCEL  FILE IS GENERATED:
<img width="1242" height="485" alt="image" src="https://github.com/user-attachments/assets/632e8016-688b-47ef-b7e1-6484b33a60a2" />

# DASHBOARD FOR SYSTEM ANALYSIS:
<img width="897" height="472" alt="image" src="https://github.com/user-attachments/assets/9700325a-cc58-4a8d-8181-60792c3e854c" />

# MASTERFILE EXCEL FILE  BEING GENERATED FROM SYSTEM ANALYSIS PAGE:
<img width="899" height="398" alt="image" src="https://github.com/user-attachments/assets/56da9ced-7227-4246-b3ab-868f4936c6ad" />




