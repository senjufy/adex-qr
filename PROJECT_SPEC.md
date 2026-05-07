# QR Manager Project Specification

## Purpose

QR Manager is an internal Laravel 8 web platform for storing, managing, and serving customer-specific technical PDFs through QR codes. It runs on a local LAMPP server and is optimized for warehouse, factory, and admin workflows.

This document is the source of truth for the app's behavior, data structure, and implementation boundaries.

## Architecture Goals

- Make the document the primary business object.
- Keep every QR code tied to a stable document identifier.
- Route scans through Laravel, not directly to the file system.
- Serve PDFs inline in the browser when possible.
- Keep the UI lightweight, mobile-friendly, and easy to print.

## Core Flows

### Document Management

- Create a document record for each customer-specific PDF.
- Upload and store the PDF on the server.
- Allow document replacement through new revisions when needed.
- Preserve the same document identifier when a file is updated.

### QR Code Routing

- Generate QR codes from a human-readable document slug or code.
- Each QR code should resolve to an application route such as `/d/{slug}` or similar.
- The route should look up the document and serve the latest active PDF inline when possible.
- Use high error correction level H for industrial durability.

### Administration

- Show a dashboard of documents, current file status, and QR previews.
- Support bulk print views for labels and packaging.
- Keep the admin interface simple enough for fast operational use.

## Recommended Data Model

### documents

| Field | Type | Purpose |
| --- | --- | --- |
| id | integer, auto-increment | Internal primary key |
| slug | string, unique | Permanent public identifier used in routes and QR codes |
| title | string | Human-readable document name |
| sop_number | string, nullable | SOP identifier shown in admin and print labels |
| project_name | string, nullable | Project/customer name shown in admin and print labels |
| description | text, nullable | Optional admin notes or customer context |
| current_file_path | string | Stored server path to the active PDF |
| current_mime_type | string | MIME type used for browser handling |
| current_file_size | integer | Size of the active file for admin tracking |
| created_at | timestamp | When the document record was created |
| updated_at | timestamp | When the document record or active file was last updated |

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

### Optional supporting tables

- `qr_codes` if you want to cache generated QR image assets for print workflows.
- `document_views` or `audit_logs` if you want traceability for scans and document access.

## Behavioral Rules

- A document is the main application entity.
- Each document has one active file at a time.
- A document can have many revisions.
- Updating a document must not change the slug or QR route.
- QR routes should always resolve to the currently active file.
- The system should serve PDFs inline for supported browsers instead of forcing downloads.
- File storage must preserve MIME type and size metadata.

## Implementation Notes

- Prefer a dedicated controller/action for QR resolution and document serving.
- Use Laravel models and relationships to express `Document hasMany Revisions`.
- Store files in a predictable directory structure keyed by document and revision.
- Keep file access behind application logic so future permission checks and auditing remain possible.
- Keep the interface responsive and minimal for tablet and phone use.
