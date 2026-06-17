# QR Manager Project Specification

## Purpose

QR Manager is an internal Laravel 8 web platform for storing, managing, and serving collections of technical PDFs through Project-based QR codes. It runs on a local LAMPP server and is optimized for warehouse, factory, and admin workflows.

This document is the source of truth for the app's behavior, data structure, and implementation boundaries.

## Architecture Goals

- Make the **Project** the primary business object for identification and QR routing.
- Keep every QR code tied to a stable **Project identifier (slug)**.
- Provide an **isolated file system view** for each project to manage and display multiple related documents.
- Route scans through Laravel to a project landing page, not directly to files.
- Serve PDFs inline in the browser when selected from the project view.
- Keep the UI lightweight, mobile-friendly, and easy to print.

## Core Flows

### Project Management

- Create a project record to act as a container for related technical documents.
- Generate a unique QR code for the project that resolves to its "isolated file system" view.
- Allow adding, removing, or updating documents within the project without changing the project QR code.

### Document Management

- Upload and store PDFs within the context of a project.
- Each document can have multiple revisions, but only the latest is shown in the project view.
- Store files in a structured way: `projects/{project_slug}/{document_slug}/...`.

### QR Code Routing & Viewer

- Each QR code resolves to an application route: `/p/{slug}`.
- This route serves a "Project Viewer" dashboard showing all active documents in that project.
- The viewer allows users to browse and open individual PDFs inline.
- Use high error correction level H for industrial durability.

## Recommended Data Model

### projects

| Field | Type | Purpose |
| --- | --- | --- |
| id | integer, auto-increment | Internal primary key |
| name | string | Human-readable project name |
| sop_number | string, nullable | SOP identifier for the entire project |
| slug | string, unique | Permanent identifier used in routes and QR codes |
| description | text, nullable | Optional notes or project context |
| created_at | timestamp | When the project was created |
| updated_at | timestamp | When the project was last updated |

### documents

| Field | Type | Purpose |
| --- | --- | --- |
| id | integer, auto-increment | Internal primary key |
| project_id | foreign key | Links the document to a parent project |
| title | string | Human-readable document name |
| slug | string | Internal identifier for file pathing |
| description | text, nullable | Optional admin notes |
| current_file_path | string | Stored server path to the active PDF |
| current_mime_type | string | MIME type used for browser handling |
| current_file_size | integer | Size of the active file for tracking |
| created_at | timestamp | When the document record was created |
| updated_at | timestamp | When the document record was last updated |

### document_revisions

| Field | Type | Purpose |
| --- | --- | --- |
| id | integer, auto-increment | Internal primary key |
| document_id | foreign key | Links revision to a document |
| version | integer | Incrementing revision number |
| file_path | string | Stored server path for the revision |
| mime_type | string | MIME type for the revision |
| file_size | integer | File size for the revision |
| original_name | string | Uploaded filename for audit/history |
| created_at | timestamp | Revision creation timestamp |
| updated_at | timestamp | Revision update timestamp |

## Behavioral Rules

- A Project is the main application entity for external identification (QR).
- A Project can contain many Documents.
- Each Document has one active file and many historical revisions.
- Scanning a Project QR always leads to the Project Viewer.
- Adding or updating documents within a project does not change the Project slug or QR.
- The system should serve PDFs inline for supported browsers.

## Implementation Notes

- Use a dedicated controller for the Project Viewer (`/p/{slug}`).
- Organize storage by project and document slugs.
- Ensure the Project Viewer is responsive and optimized for mobile/tablet use in industrial environments.
- Maintain existing revision logic to ensure file history is preserved.

## Permanent Infrastructure (Immutable)

### Permanent QR Routes
The following URL structures are **PERMANENT**. Do not change these patterns, as they are encoded into physical hardware/labels:
1. **Project Entry:** `/p/{slug}` (Resolves to Project Viewer)
2. **Direct Document:** `/d/{slug}` (Resolves to PDF file)

Changing these requires a domain-level redirect strategy to avoid breaking field labels.

## Security

### Simple Admin Gate
- The management dashboard (`/`), projects CRUD (`/projects/*`), and documents CRUD (`/documents/*`) are protected by a global password.
- No database-backed users; the password is set via `.env` (`ADMIN_PASSWORD`).
- Public project viewer routes (`/p/{slug}`) and direct document scans (`/d/{slug}`) remain accessible without a password.

## Implementation Tasks

### Phase 0: Security & Access Control
- [x] Add `ADMIN_PASSWORD` to `.env` and `.env.example`.
- [x] Create `AdminGateMiddleware` to check for a session-based authentication flag.
- [x] Create a "Simple Login" view and controller to verify the global password.
- [x] Apply the middleware to all management routes.


### Phase 1: Database & Models
- [ ] Create `projects` table migration (id, name, slug, description).
- [ ] Create `Project` model with `hasMany(Document)` relationship.
- [ ] Update `documents` table migration:
    - Add `project_id` foreign key.
    - Keep `slug` (for internal file pathing/structure).
- [ ] Update `Document` model with `belongsTo(Project)` relationship.
- [x] **Data Migration Script:** Create a script/seeder to:
    - Group existing documents by their `project_name`.
    - Create a `Project` for each unique name.
    - Link existing documents to these new projects.

### Phase 2: Project Management (Admin)
- [ ] Create `ProjectController` for CRUD operations.
- [ ] Build Project Index view (List all projects, show document counts).
- [ ] Build Project Create/Edit views.
- [ ] Implement Project QR code generation logic (targeting `/p/{slug}`).
- [ ] Update Navigation to prioritize Projects over individual Documents.

### Phase 3: Document Management Refactor (Admin)
- [ ] Update `DocumentController@store` and `update`:
    - Require `project_id`.
    - Update file storage pathing to `projects/{project_slug}/{document_slug}/`.
- [ ] Update Document creation/edit forms to include a Project selector.

### Phase 4: The Project Viewer (Public)
- [x] Create `ProjectViewerController` for the public route `/p/{slug}`.
- [x] Build a mobile-optimized "Isolated File System" landing page:
    - Display Project Name and Description.
    - List all documents in the project with titles and SOP numbers.
    - Provide "View" links for each document.
- [x] Update `QrScanController` to handle project-based routing if necessary.

### Phase 5: Cleanup & Validation
- [x] Remove legacy individual document QR routes/views if no longer needed.
- [x] Update "Bulk Print" to work with Project QRs.
- [x] Verify file storage integrity for new uploads.
- [x] Final UI polish and mobile testing.

