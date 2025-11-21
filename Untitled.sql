CREATE TABLE `master_roles` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `slug` varchar(255) UNIQUE,
  `name` varchar(255),
  `description` text,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `master_permissions` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `slug` varchar(255) UNIQUE,
  `name` varchar(255),
  `description` text,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `master_statuses` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `category` varchar(255),
  `code` varchar(255),
  `label` varchar(255),
  `description` text,
  `sort_order` int,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `master_methods` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `category` varchar(255),
  `code` varchar(255),
  `name` varchar(255),
  `description` text,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `master_document_types` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `code` varchar(255),
  `name` varchar(255),
  `description` text,
  `retention_months` int,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `users` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `uuid` varchar(255) UNIQUE,
  `username` varchar(255) UNIQUE COMMENT 'optional login id',
  `email` varchar(255) UNIQUE,
  `phone` varchar(255),
  `password` varchar(255),
  `is_active` boolean DEFAULT true,
  `last_login` datetime,
  `created_by` int,
  `updated_by` int,
  `created_at` datetime,
  `updated_at` datetime,
  `deleted_at` datetime
);

CREATE TABLE `user_profiles` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `full_name` varchar(255),
  `nickname` varchar(255),
  `nik` varchar(255),
  `gender` varchar(255),
  `birth_place` varchar(255),
  `birth_date` date,
  `nationality` varchar(255),
  `avatar_file_id` int,
  `bio` text,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `user_contacts` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `type` varchar(255),
  `address` text,
  `city` varchar(255),
  `province` varchar(255),
  `postal_code` varchar(255),
  `phone` varchar(255),
  `emergency_contact_name` varchar(255),
  `emergency_contact_phone` varchar(255),
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `role_permission` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `role_id` int,
  `permission_id` int,
  `created_at` datetime
);

CREATE TABLE `user_role` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `role_id` int,
  `assigned_by` int,
  `assigned_at` datetime,
  `revoked_at` datetime
);

CREATE TABLE `lsp_profiles` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `code` varchar(255) UNIQUE,
  `name` varchar(255),
  `license_number` varchar(255),
  `license_issue_date` date,
  `license_expiry_date` date,
  `address` text,
  `phone` varchar(255),
  `email` varchar(255),
  `website` varchar(255),
  `logo_file_id` int,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `tuk` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `code` varchar(255) UNIQUE,
  `name` varchar(255),
  `type` varchar(255),
  `address` text,
  `city` varchar(255),
  `province` varchar(255),
  `contact_person` varchar(255),
  `contact_phone` varchar(255),
  `capacity` int,
  `is_active` boolean DEFAULT true,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `tuk_facilities` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `tuk_id` int,
  `name` varchar(255),
  `description` text,
  `quantity` int,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `tuk_documents` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `tuk_id` int,
  `file_id` int,
  `doc_type_id` int,
  `uploaded_by` int,
  `uploaded_at` datetime
);

CREATE TABLE `tuk_schedules` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `tuk_id` int,
  `date` date,
  `start_time` time,
  `end_time` time,
  `is_available` boolean,
  `note` text,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `org_settings` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `key` varchar(255) UNIQUE,
  `value` text,
  `description` text,
  `updated_at` datetime
);

CREATE TABLE `assessors` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `assessor_code` varchar(255) UNIQUE,
  `registration_number` varchar(255),
  `valid_from` date,
  `valid_until` date,
  `status_id` int,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `assessor_documents` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `assessor_id` int,
  `file_id` int,
  `doc_type_id` int,
  `issued_by` varchar(255),
  `issued_date` date,
  `expiry_date` date,
  `verified_by` int,
  `verified_at` datetime
);

CREATE TABLE `assessor_competency_scope` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `assessor_id` int,
  `scheme_id` int,
  `note` text,
  `created_at` datetime
);

CREATE TABLE `assessor_experience` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `assessor_id` int,
  `organization` varchar(255),
  `position` varchar(255),
  `start_date` date,
  `end_date` date,
  `description` text
);

CREATE TABLE `assessor_bank_info` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `assessor_id` int,
  `bank_name` varchar(255),
  `account_name` varchar(255),
  `account_number` varchar(255),
  `created_at` datetime
);

CREATE TABLE `schemes` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `code` varchar(255) UNIQUE,
  `name` varchar(255),
  `description` text,
  `standard_reference` varchar(255),
  `current_version_id` int,
  `is_active` boolean DEFAULT true,
  `created_by` int,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `scheme_versions` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `scheme_id` int,
  `version` varchar(255),
  `notes` text,
  `release_date` date,
  `created_by` int,
  `created_at` datetime
);

CREATE TABLE `scheme_units` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `scheme_id` int,
  `code` varchar(255),
  `title` varchar(255),
  `description` text,
  `order_index` int,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `scheme_elements` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `unit_id` int,
  `code` varchar(255),
  `description` text,
  `order_index` int,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `scheme_criteria` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `element_id` int,
  `code` varchar(255),
  `description` text,
  `evidence_guidance` text,
  `order_index` int
);

CREATE TABLE `scheme_requirements` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `scheme_id` int,
  `requirement_code` varchar(255),
  `description` text,
  `is_mandatory` boolean
);

CREATE TABLE `scheme_documents` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `scheme_id` int,
  `file_id` int,
  `document_type` varchar(255),
  `uploaded_at` datetime
);

CREATE TABLE `events` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `code` varchar(255) UNIQUE,
  `scheme_id` int,
  `name` varchar(255),
  `description` text,
  `start_date` date,
  `end_date` date,
  `status_id` int,
  `created_by` int,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `event_sessions` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `event_id` int,
  `session_code` varchar(255),
  `start_datetime` datetime,
  `end_datetime` datetime,
  `capacity` int,
  `notes` text,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `event_tuk` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `event_id` int,
  `tuk_id` int,
  `assigned_by` int
);

CREATE TABLE `event_assessors` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `event_id` int,
  `assessor_id` int,
  `role` varchar(255),
  `assigned_at` datetime,
  `confirmed_at` datetime
);

CREATE TABLE `event_materials` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `event_id` int,
  `file_id` int,
  `description` text,
  `uploaded_by` int,
  `uploaded_at` datetime
);

CREATE TABLE `event_attendance` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `event_id` int,
  `user_id` int,
  `role` varchar(255),
  `status_id` int,
  `checkin_time` datetime,
  `checkout_time` datetime,
  `remarks` text
);

CREATE TABLE `assessees` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `assessee_code` varchar(255) UNIQUE,
  `nik` varchar(255),
  `registration_date` date,
  `status_id` int,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `assessees_documents` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `assessee_id` int,
  `file_id` int,
  `doc_type_id` int,
  `uploaded_at` datetime,
  `verified_by` int,
  `verified_at` datetime
);

CREATE TABLE `assessees_employment_info` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `assessee_id` int,
  `employer_name` varchar(255),
  `employer_address` text,
  `position` varchar(255),
  `start_date` date,
  `end_date` date
);

CREATE TABLE `assessees_education_history` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `assessee_id` int,
  `institution` varchar(255),
  `degree` varchar(255),
  `major` varchar(255),
  `graduation_year` int
);

CREATE TABLE `assessees_experience` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `assessee_id` int,
  `organization` varchar(255),
  `role` varchar(255),
  `start_date` date,
  `end_date` date,
  `description` text
);

CREATE TABLE `apl01_forms` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `code` varchar(255) UNIQUE,
  `assessee_id` int,
  `scheme_id` int,
  `submitted_at` datetime,
  `status_id` int,
  `created_by` int,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `apl01_form_fields` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `scheme_id` int,
  `field_key` varchar(255),
  `label` varchar(255),
  `field_type` varchar(255),
  `options` json,
  `validation_rules` json,
  `is_required` boolean,
  `order_index` int
);

CREATE TABLE `apl01_answers` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `apl01_id` int,
  `field_id` int,
  `value` text,
  `file_id` int,
  `created_at` datetime
);

CREATE TABLE `apl01_review` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `apl01_id` int,
  `reviewer_id` int,
  `status_id` int,
  `notes` text,
  `reviewed_at` datetime
);

CREATE TABLE `apl02_units` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `apl01_id` int,
  `assessee_id` int,
  `unit_id` int,
  `created_at` datetime
);

CREATE TABLE `apl02_evidence` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `apl02_unit_id` int,
  `file_id` int,
  `evidence_type_id` int,
  `title` varchar(255),
  `description` text,
  `uploaded_by` int,
  `uploaded_at` datetime,
  `status_id` int
);

CREATE TABLE `apl02_evidence_map` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `evidence_id` int,
  `criteria_id` int
);

CREATE TABLE `apl02_assessor_review` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `evidence_id` int,
  `assessor_id` int,
  `status_id` int,
  `score` decimal,
  `notes` text,
  `reviewed_at` datetime
);

CREATE TABLE `assessments` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `code` varchar(255) UNIQUE,
  `assessee_id` int,
  `event_id` int,
  `session_id` int,
  `scheduled_at` datetime,
  `assessor_id` int,
  `method_id` int,
  `status_id` int,
  `started_at` datetime,
  `finished_at` datetime,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `assessment_units` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `assessment_id` int,
  `unit_id` int,
  `status_id` int,
  `notes` text
);

CREATE TABLE `assessment_criteria` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `assessment_unit_id` int,
  `criteria_id` int,
  `status_id` int,
  `score` decimal,
  `notes` text
);

CREATE TABLE `assessment_observations` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `assessment_id` int,
  `observer_id` int,
  `notes` text,
  `recorded_at` datetime
);

CREATE TABLE `assessment_documents` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `assessment_id` int,
  `file_id` int,
  `doc_type_id` int,
  `uploaded_by` int,
  `uploaded_at` datetime
);

CREATE TABLE `assessment_interviews` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `assessment_id` int,
  `question` text,
  `answer` text,
  `asked_by` int
);

CREATE TABLE `assessment_verification` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `assessment_id` int,
  `method` varchar(255),
  `verifier_id` int,
  `notes` text,
  `verified_at` datetime
);

CREATE TABLE `assessment_feedback` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `assessment_id` int,
  `by_user_id` int,
  `feedback` text,
  `created_at` datetime
);

CREATE TABLE `assessment_results` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `assessment_id` int,
  `final_result` varchar(255),
  `result_status_id` int,
  `calculated_score` decimal,
  `remarks` text,
  `decided_by` int,
  `decided_at` datetime
);

CREATE TABLE `result_approval` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `result_id` int,
  `approver_id` int,
  `level` int,
  `status_id` int,
  `notes` text,
  `approved_at` datetime
);

CREATE TABLE `result_revisions` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `result_id` int,
  `requested_by` int,
  `reason` text,
  `status_id` int,
  `requested_at` datetime
);

CREATE TABLE `result_history` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `result_id` int,
  `from_status_id` int,
  `to_status_id` int,
  `changed_by` int,
  `changed_at` datetime
);

CREATE TABLE `certificates` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `certificate_number` varchar(255) UNIQUE,
  `assessment_id` int,
  `assessee_id` int,
  `issued_by` int,
  `issued_at` datetime,
  `expires_at` datetime,
  `file_id` int,
  `status_id` int,
  `qr_code` varchar(255),
  `public_url` varchar(255),
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `certificate_qr_validation` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `certificate_id` int,
  `scanned_at` datetime,
  `scanned_by` varchar(255),
  `ip_address` varchar(255),
  `result` text
);

CREATE TABLE `certificate_logs` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `certificate_id` int,
  `action` varchar(255),
  `performed_by` int,
  `performed_at` datetime,
  `remarks` text
);

CREATE TABLE `certificate_revoke` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `certificate_id` int,
  `revoked_by` int,
  `revoked_at` datetime,
  `reason` text
);

CREATE TABLE `certificate_renewal` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `certificate_id` int,
  `old_certificate_id` int,
  `renewed_by` int,
  `renewed_at` datetime,
  `notes` text
);

CREATE TABLE `payments` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `payment_code` varchar(255) UNIQUE,
  `assessee_id` int,
  `amount` decimal,
  `currency` varchar(255) DEFAULT 'IDR',
  `transaction_id` varchar(255),
  `method_id` int,
  `status_id` int,
  `paid_at` datetime,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `payment_methods` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `code` varchar(255),
  `name` varchar(255),
  `provider_info` json,
  `created_at` datetime
);

CREATE TABLE `payment_items` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `payment_id` int,
  `description` varchar(255),
  `amount` decimal
);

CREATE TABLE `payment_status_history` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `payment_id` int,
  `status_id` int,
  `changed_at` datetime,
  `changed_by` int
);

CREATE TABLE `files` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `storage_disk` varchar(255),
  `path` varchar(255),
  `filename` varchar(255),
  `mime_type` varchar(255),
  `size` int,
  `checksum` varchar(255),
  `uploaded_by` int,
  `uploaded_at` datetime,
  `created_at` datetime
);

CREATE TABLE `notifications` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `channel` varchar(255),
  `title` varchar(255),
  `message` text,
  `data` json,
  `is_read` boolean,
  `sent_at` datetime,
  `created_at` datetime
);

CREATE TABLE `email_logs` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `to_address` varchar(255),
  `subject` varchar(255),
  `body` text,
  `status` varchar(255),
  `sent_at` datetime
);

CREATE TABLE `audit_trails` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `action` varchar(255),
  `table_name` varchar(255),
  `record_id` int,
  `old_data` json,
  `new_data` json,
  `ip_address` varchar(255),
  `created_at` datetime
);

ALTER TABLE `users` ADD FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

ALTER TABLE `users` ADD FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

ALTER TABLE `user_profiles` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `user_profiles` ADD FOREIGN KEY (`avatar_file_id`) REFERENCES `files` (`id`);

ALTER TABLE `user_contacts` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `role_permission` ADD FOREIGN KEY (`role_id`) REFERENCES `master_roles` (`id`);

ALTER TABLE `role_permission` ADD FOREIGN KEY (`permission_id`) REFERENCES `master_permissions` (`id`);

ALTER TABLE `user_role` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `user_role` ADD FOREIGN KEY (`role_id`) REFERENCES `master_roles` (`id`);

ALTER TABLE `user_role` ADD FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`);

ALTER TABLE `lsp_profiles` ADD FOREIGN KEY (`logo_file_id`) REFERENCES `files` (`id`);

ALTER TABLE `tuk_facilities` ADD FOREIGN KEY (`tuk_id`) REFERENCES `tuk` (`id`);

ALTER TABLE `tuk_documents` ADD FOREIGN KEY (`tuk_id`) REFERENCES `tuk` (`id`);

ALTER TABLE `tuk_documents` ADD FOREIGN KEY (`file_id`) REFERENCES `files` (`id`);

ALTER TABLE `tuk_documents` ADD FOREIGN KEY (`doc_type_id`) REFERENCES `master_document_types` (`id`);

ALTER TABLE `tuk_documents` ADD FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`);

ALTER TABLE `tuk_schedules` ADD FOREIGN KEY (`tuk_id`) REFERENCES `tuk` (`id`);

ALTER TABLE `assessors` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `assessors` ADD FOREIGN KEY (`status_id`) REFERENCES `master_statuses` (`id`);

ALTER TABLE `assessor_documents` ADD FOREIGN KEY (`assessor_id`) REFERENCES `assessors` (`id`);

ALTER TABLE `assessor_documents` ADD FOREIGN KEY (`file_id`) REFERENCES `files` (`id`);

ALTER TABLE `assessor_documents` ADD FOREIGN KEY (`doc_type_id`) REFERENCES `master_document_types` (`id`);

ALTER TABLE `assessor_documents` ADD FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`);

ALTER TABLE `assessor_competency_scope` ADD FOREIGN KEY (`assessor_id`) REFERENCES `assessors` (`id`);

ALTER TABLE `assessor_competency_scope` ADD FOREIGN KEY (`scheme_id`) REFERENCES `schemes` (`id`);

ALTER TABLE `assessor_experience` ADD FOREIGN KEY (`assessor_id`) REFERENCES `assessors` (`id`);

ALTER TABLE `assessor_bank_info` ADD FOREIGN KEY (`assessor_id`) REFERENCES `assessors` (`id`);

ALTER TABLE `schemes` ADD FOREIGN KEY (`current_version_id`) REFERENCES `scheme_versions` (`id`);

ALTER TABLE `schemes` ADD FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

ALTER TABLE `scheme_versions` ADD FOREIGN KEY (`scheme_id`) REFERENCES `schemes` (`id`);

ALTER TABLE `scheme_versions` ADD FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

ALTER TABLE `scheme_units` ADD FOREIGN KEY (`scheme_id`) REFERENCES `schemes` (`id`);

ALTER TABLE `scheme_elements` ADD FOREIGN KEY (`unit_id`) REFERENCES `scheme_units` (`id`);

ALTER TABLE `scheme_criteria` ADD FOREIGN KEY (`element_id`) REFERENCES `scheme_elements` (`id`);

ALTER TABLE `scheme_requirements` ADD FOREIGN KEY (`scheme_id`) REFERENCES `schemes` (`id`);

ALTER TABLE `scheme_documents` ADD FOREIGN KEY (`scheme_id`) REFERENCES `schemes` (`id`);

ALTER TABLE `scheme_documents` ADD FOREIGN KEY (`file_id`) REFERENCES `files` (`id`);

ALTER TABLE `events` ADD FOREIGN KEY (`scheme_id`) REFERENCES `schemes` (`id`);

ALTER TABLE `events` ADD FOREIGN KEY (`status_id`) REFERENCES `master_statuses` (`id`);

ALTER TABLE `events` ADD FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

ALTER TABLE `event_sessions` ADD FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);

ALTER TABLE `event_tuk` ADD FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);

ALTER TABLE `event_tuk` ADD FOREIGN KEY (`tuk_id`) REFERENCES `tuk` (`id`);

ALTER TABLE `event_tuk` ADD FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`);

ALTER TABLE `event_assessors` ADD FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);

ALTER TABLE `event_assessors` ADD FOREIGN KEY (`assessor_id`) REFERENCES `assessors` (`id`);

ALTER TABLE `event_materials` ADD FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);

ALTER TABLE `event_materials` ADD FOREIGN KEY (`file_id`) REFERENCES `files` (`id`);

ALTER TABLE `event_materials` ADD FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`);

ALTER TABLE `event_attendance` ADD FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);

ALTER TABLE `event_attendance` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `event_attendance` ADD FOREIGN KEY (`status_id`) REFERENCES `master_statuses` (`id`);

ALTER TABLE `assessees` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `assessees` ADD FOREIGN KEY (`status_id`) REFERENCES `master_statuses` (`id`);

ALTER TABLE `assessees_documents` ADD FOREIGN KEY (`assessee_id`) REFERENCES `assessees` (`id`);

ALTER TABLE `assessees_documents` ADD FOREIGN KEY (`file_id`) REFERENCES `files` (`id`);

ALTER TABLE `assessees_documents` ADD FOREIGN KEY (`doc_type_id`) REFERENCES `master_document_types` (`id`);

ALTER TABLE `assessees_documents` ADD FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`);

ALTER TABLE `assessees_employment_info` ADD FOREIGN KEY (`assessee_id`) REFERENCES `assessees` (`id`);

ALTER TABLE `assessees_education_history` ADD FOREIGN KEY (`assessee_id`) REFERENCES `assessees` (`id`);

ALTER TABLE `assessees_experience` ADD FOREIGN KEY (`assessee_id`) REFERENCES `assessees` (`id`);

ALTER TABLE `apl01_forms` ADD FOREIGN KEY (`assessee_id`) REFERENCES `assessees` (`id`);

ALTER TABLE `apl01_forms` ADD FOREIGN KEY (`scheme_id`) REFERENCES `schemes` (`id`);

ALTER TABLE `apl01_forms` ADD FOREIGN KEY (`status_id`) REFERENCES `master_statuses` (`id`);

ALTER TABLE `apl01_forms` ADD FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

ALTER TABLE `apl01_form_fields` ADD FOREIGN KEY (`scheme_id`) REFERENCES `schemes` (`id`);

ALTER TABLE `apl01_answers` ADD FOREIGN KEY (`apl01_id`) REFERENCES `apl01_forms` (`id`);

ALTER TABLE `apl01_answers` ADD FOREIGN KEY (`field_id`) REFERENCES `apl01_form_fields` (`id`);

ALTER TABLE `apl01_answers` ADD FOREIGN KEY (`file_id`) REFERENCES `files` (`id`);

ALTER TABLE `apl01_review` ADD FOREIGN KEY (`apl01_id`) REFERENCES `apl01_forms` (`id`);

ALTER TABLE `apl01_review` ADD FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`);

ALTER TABLE `apl01_review` ADD FOREIGN KEY (`status_id`) REFERENCES `master_statuses` (`id`);

ALTER TABLE `apl02_units` ADD FOREIGN KEY (`apl01_id`) REFERENCES `apl01_forms` (`id`);

ALTER TABLE `apl02_units` ADD FOREIGN KEY (`assessee_id`) REFERENCES `assessees` (`id`);

ALTER TABLE `apl02_units` ADD FOREIGN KEY (`unit_id`) REFERENCES `scheme_units` (`id`);

ALTER TABLE `apl02_evidence` ADD FOREIGN KEY (`apl02_unit_id`) REFERENCES `apl02_units` (`id`);

ALTER TABLE `apl02_evidence` ADD FOREIGN KEY (`file_id`) REFERENCES `files` (`id`);

ALTER TABLE `apl02_evidence` ADD FOREIGN KEY (`evidence_type_id`) REFERENCES `master_methods` (`id`);

ALTER TABLE `apl02_evidence` ADD FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`);

ALTER TABLE `apl02_evidence` ADD FOREIGN KEY (`status_id`) REFERENCES `master_statuses` (`id`);

ALTER TABLE `apl02_evidence_map` ADD FOREIGN KEY (`evidence_id`) REFERENCES `apl02_evidence` (`id`);

ALTER TABLE `apl02_evidence_map` ADD FOREIGN KEY (`criteria_id`) REFERENCES `scheme_criteria` (`id`);

ALTER TABLE `apl02_assessor_review` ADD FOREIGN KEY (`evidence_id`) REFERENCES `apl02_evidence` (`id`);

ALTER TABLE `apl02_assessor_review` ADD FOREIGN KEY (`assessor_id`) REFERENCES `assessors` (`id`);

ALTER TABLE `apl02_assessor_review` ADD FOREIGN KEY (`status_id`) REFERENCES `master_statuses` (`id`);

ALTER TABLE `assessments` ADD FOREIGN KEY (`assessee_id`) REFERENCES `assessees` (`id`);

ALTER TABLE `assessments` ADD FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);

ALTER TABLE `assessments` ADD FOREIGN KEY (`session_id`) REFERENCES `event_sessions` (`id`);

ALTER TABLE `assessments` ADD FOREIGN KEY (`assessor_id`) REFERENCES `assessors` (`id`);

ALTER TABLE `assessments` ADD FOREIGN KEY (`method_id`) REFERENCES `master_methods` (`id`);

ALTER TABLE `assessments` ADD FOREIGN KEY (`status_id`) REFERENCES `master_statuses` (`id`);

ALTER TABLE `assessment_units` ADD FOREIGN KEY (`assessment_id`) REFERENCES `assessments` (`id`);

ALTER TABLE `assessment_units` ADD FOREIGN KEY (`unit_id`) REFERENCES `scheme_units` (`id`);

ALTER TABLE `assessment_units` ADD FOREIGN KEY (`status_id`) REFERENCES `master_statuses` (`id`);

ALTER TABLE `assessment_criteria` ADD FOREIGN KEY (`assessment_unit_id`) REFERENCES `assessment_units` (`id`);

ALTER TABLE `assessment_criteria` ADD FOREIGN KEY (`criteria_id`) REFERENCES `scheme_criteria` (`id`);

ALTER TABLE `assessment_criteria` ADD FOREIGN KEY (`status_id`) REFERENCES `master_statuses` (`id`);

ALTER TABLE `assessment_observations` ADD FOREIGN KEY (`assessment_id`) REFERENCES `assessments` (`id`);

ALTER TABLE `assessment_observations` ADD FOREIGN KEY (`observer_id`) REFERENCES `users` (`id`);

ALTER TABLE `assessment_documents` ADD FOREIGN KEY (`assessment_id`) REFERENCES `assessments` (`id`);

ALTER TABLE `assessment_documents` ADD FOREIGN KEY (`file_id`) REFERENCES `files` (`id`);

ALTER TABLE `assessment_documents` ADD FOREIGN KEY (`doc_type_id`) REFERENCES `master_document_types` (`id`);

ALTER TABLE `assessment_documents` ADD FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`);

ALTER TABLE `assessment_interviews` ADD FOREIGN KEY (`assessment_id`) REFERENCES `assessments` (`id`);

ALTER TABLE `assessment_interviews` ADD FOREIGN KEY (`asked_by`) REFERENCES `users` (`id`);

ALTER TABLE `assessment_verification` ADD FOREIGN KEY (`assessment_id`) REFERENCES `assessments` (`id`);

ALTER TABLE `assessment_verification` ADD FOREIGN KEY (`verifier_id`) REFERENCES `users` (`id`);

ALTER TABLE `assessment_feedback` ADD FOREIGN KEY (`assessment_id`) REFERENCES `assessments` (`id`);

ALTER TABLE `assessment_feedback` ADD FOREIGN KEY (`by_user_id`) REFERENCES `users` (`id`);

ALTER TABLE `assessment_results` ADD FOREIGN KEY (`assessment_id`) REFERENCES `assessments` (`id`);

ALTER TABLE `assessment_results` ADD FOREIGN KEY (`result_status_id`) REFERENCES `master_statuses` (`id`);

ALTER TABLE `assessment_results` ADD FOREIGN KEY (`decided_by`) REFERENCES `users` (`id`);

ALTER TABLE `result_approval` ADD FOREIGN KEY (`result_id`) REFERENCES `assessment_results` (`id`);

ALTER TABLE `result_approval` ADD FOREIGN KEY (`approver_id`) REFERENCES `users` (`id`);

ALTER TABLE `result_approval` ADD FOREIGN KEY (`status_id`) REFERENCES `master_statuses` (`id`);

ALTER TABLE `result_revisions` ADD FOREIGN KEY (`result_id`) REFERENCES `assessment_results` (`id`);

ALTER TABLE `result_revisions` ADD FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`);

ALTER TABLE `result_revisions` ADD FOREIGN KEY (`status_id`) REFERENCES `master_statuses` (`id`);

ALTER TABLE `result_history` ADD FOREIGN KEY (`result_id`) REFERENCES `assessment_results` (`id`);

ALTER TABLE `result_history` ADD FOREIGN KEY (`from_status_id`) REFERENCES `master_statuses` (`id`);

ALTER TABLE `result_history` ADD FOREIGN KEY (`to_status_id`) REFERENCES `master_statuses` (`id`);

ALTER TABLE `result_history` ADD FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`);

ALTER TABLE `certificates` ADD FOREIGN KEY (`assessment_id`) REFERENCES `assessments` (`id`);

ALTER TABLE `certificates` ADD FOREIGN KEY (`assessee_id`) REFERENCES `assessees` (`id`);

ALTER TABLE `certificates` ADD FOREIGN KEY (`issued_by`) REFERENCES `users` (`id`);

ALTER TABLE `certificates` ADD FOREIGN KEY (`file_id`) REFERENCES `files` (`id`);

ALTER TABLE `certificates` ADD FOREIGN KEY (`status_id`) REFERENCES `master_statuses` (`id`);

ALTER TABLE `certificate_qr_validation` ADD FOREIGN KEY (`certificate_id`) REFERENCES `certificates` (`id`);

ALTER TABLE `certificate_logs` ADD FOREIGN KEY (`certificate_id`) REFERENCES `certificates` (`id`);

ALTER TABLE `certificate_logs` ADD FOREIGN KEY (`performed_by`) REFERENCES `users` (`id`);

ALTER TABLE `certificate_revoke` ADD FOREIGN KEY (`certificate_id`) REFERENCES `certificates` (`id`);

ALTER TABLE `certificate_revoke` ADD FOREIGN KEY (`revoked_by`) REFERENCES `users` (`id`);

ALTER TABLE `certificate_renewal` ADD FOREIGN KEY (`certificate_id`) REFERENCES `certificates` (`id`);

ALTER TABLE `certificate_renewal` ADD FOREIGN KEY (`old_certificate_id`) REFERENCES `certificates` (`id`);

ALTER TABLE `certificate_renewal` ADD FOREIGN KEY (`renewed_by`) REFERENCES `users` (`id`);

ALTER TABLE `payments` ADD FOREIGN KEY (`assessee_id`) REFERENCES `assessees` (`id`);

ALTER TABLE `payments` ADD FOREIGN KEY (`method_id`) REFERENCES `master_methods` (`id`);

ALTER TABLE `payments` ADD FOREIGN KEY (`status_id`) REFERENCES `master_statuses` (`id`);

ALTER TABLE `payment_items` ADD FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`);

ALTER TABLE `payment_status_history` ADD FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`);

ALTER TABLE `payment_status_history` ADD FOREIGN KEY (`status_id`) REFERENCES `master_statuses` (`id`);

ALTER TABLE `payment_status_history` ADD FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`);

ALTER TABLE `files` ADD FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`);

ALTER TABLE `notifications` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `email_logs` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `audit_trails` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
