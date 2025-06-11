<?php
/**
 * certification-stages.php
 *
 * Updated to include a separate “qms” track alongside the existing “ems” track.
 * The EMS part is left untouched; QMS stages are defined in their own sub‐array.
 */

/**
 * Returns all certification stages for each track.
 * 
 * NOTE: The ‘ems’ array below must remain exactly as it was. We’ve simply added
 * a new ‘qms’ key, with its own stages and ACF group keys.
 */
function get_certification_stages() {
    return [

        /**
         * ──────────────────────────────────────────────────────────────────────
         * EXISTING EMS TRACK (UNCHANGED)
         * ──────────────────────────────────────────────────────────────────────
         */
        'ems' => [

            // Draft (no form)
            'draft' => [
                'title'   => 'Draft',
                'group'   => 'group_67dc014741369',
                'next'    => 'f01',
            ],

            // F-01: EMS Application
            'f01' => [
                'title'   => 'F-01 EMS Application',
                'group'   => 'group_68222fbec9c41',
                'next'    => 'f02',
            ],

            // F-02: EMS Application Review
            'f02' => [
                'title'   => 'F-02 EMS Application Review',
                'group'   => 'group_qms_f02',
                'next'    => 'f03',
            ],

            // F-03: Certification Agreement
            'f03' => [
                'title'   => 'F-03 Certification Agreement',
                'group'   => 'group_qms_f03',
                'next'    => 'f05',
            ],

            // F-05: Audit Team Allocation (Stage 1)
            'f05' => [
                'title'   => 'F-05 Audit Team Allocation',
                'group'   => 'group_f05_ems_audit_team_allocation_plan_stage_1',
                'next'    => 'f06',
            ],

            // F-06: Document Review Report
            'f06' => [
                'title'   => 'F-06 Document Review Report',
                'group'   => 'group_qms_f06',
                'next'    => 'f07',
            ],

            // F-07: Audit Schedule
            'f07' => [
                'title'   => 'F-07 Audit Schedule',
                'group'   => 'group_qms_f07',
                'next'    => 'f08',
            ],

            // F-08: Certificate Issuance
            'f08' => [
                'title'   => 'F-08 Certificate Issuance',
                'group'   => 'group_qms_f08',
                'next'    => 'f11',
            ],

            // F-11: Invoice / Billing Details
            'f11' => [
                'title'   => 'F-11 Invoice / Billing Details',
                'group'   => 'group_qms_f11',
                'next'    => 'f09',
            ],

            // F-09: Stage 1 Audit Report (no form yet)
            'f09' => [
                'title'   => 'F-09 Stage 1 Audit Report',
                'group'   => '',   // leave blank until an ACF group is created
                'next'    => 'f10',
            ],

            // F-10: Non‐Conformity (uses F-13 fields)
            'f10' => [
                'title'   => 'F-10 Non-Conformity',
                'group'   => 'group_qms_f13',
                'next'    => 'f12',
            ],

            // F-12: Scope of Certification (no form yet)
            'f12' => [
                'title'   => 'F-12 Scope of Certification',
                'group'   => '',   // leave blank until an ACF group is created
                'next'    => 'f13',
            ],

            // F-13: Corrective Action Request
            'f13' => [
                'title'   => 'F-13 Corrective Action Request',
                'group'   => 'group_qms_f13',
                'next'    => 'f14',
            ],

            // F-14: Conflict of Interest Declaration
            'f14' => [
                'title'   => 'F-14 Conflict of Interest Declaration',
                'group'   => 'group_67e69bef71256',
                'next'    => 'sheet6',
            ],

            // Sheet 6: Audit Notification Email (template‐only)
            'sheet6' => [
                'title'     => 'QMS – Audit Notification Email',
                'group'     => '',   // no ACF form; uses subject/message/pdf_field keys
                'next'      => null,
                'subject'   => 'QMS Attendance Sheet Stage 2',
                'message'   => '
                    <p>Dear {{client_name}},</p>
                    <p>Your Attendance Sheet (F-13) is attached.</p>
                    <p>Download: <a href="{{pdf_link}}">{{pdf_name}}</a></p>
                ',
                'pdf_field' => 'sheet6_pdf',
            ],

        ], // end 'ems'


        /**
         * ──────────────────────────────────────────────────────────────────────
         * NEW QMS TRACK (DO NOT DISTURB EMS)
         * ──────────────────────────────────────────────────────────────────────
         *
         * These stages use the same form‐keys as above (the “group_qms_fXX” keys),
         * but they live under a separate ‘qms’ key so EMS remains untouched.
         */
        'qms' => [

            // Draft (no form)
            'draft' => [
                'title'   => 'Draft',
                'group'   => 'group_67dc014741369',  // same “draft” stub if needed
                'next'    => 'f01',
            ],

            // F-01: QMS Application
            'f01' => [
                'title'   => 'F-01 QMS Application',
                'group'   => 'group_68173ed286e57',  // this is the same ACF group as EMS’s F-01,
                'next'    => 'f02',
            ],

            // F-02: QMS Application Review
            'f02' => [
                'title'   => 'F-02 QMS Application Review',
                'group'   => 'group_f02_technical_review',
                'next'    => 'f03',
            ],

            // F-03: QMS Certification Agreement
            'f03' => [
                'title'   => 'F-03 Certification Agreement',
                'group'   => 'group_qms_f03',
                'next'    => 'f05',
            ],

            // F-05: Audit Team Allocation (Stage 1)
            'f05' => [
                'title'   => 'F-05 Audit Team Allocation',
                'group'   => 'group_f05_ems_audit_team_allocation_plan_stage_1',
                'next'    => 'f06',
            ],

            // F-06: Document Review Report
            'f06' => [
                'title'   => 'F-06 Document Review Report',
                'group'   => 'group_qms_f06',
                'next'    => 'f07',
            ],

            // F-07: Audit Schedule
            'f07' => [
                'title'   => 'F-07 Audit Schedule',
                'group'   => 'group_qms_f07',
                'next'    => 'f08',
            ],

            // F-08: Certificate Issuance
            'f08' => [
                'title'   => 'F-08 Certificate Issuance',
                'group'   => 'group_qms_f08',
                'next'    => 'f11',
            ],

            // F-11: Invoice / Billing Details
            'f11' => [
                'title'   => 'F-11 Invoice / Billing Details',
                'group'   => 'group_qms_f11',
                'next'    => 'f09',
            ],

            // F-09: Stage 1 Audit Report (no form yet)
            'f09' => [
                'title'   => 'F-09 Stage 1 Audit Report',
                'group'   => '',   // leave blank until an ACF group is added
                'next'    => 'f10',
            ],

            // F-10: Non-Conformity (uses F-13 fields)
            'f10' => [
                'title'   => 'F-10 Non-Conformity',
                'group'   => 'group_qms_f13',
                'next'    => 'f12',
            ],

            // F-12: Scope of Certification (no form yet)
            'f12' => [
                'title'   => 'F-12 Scope of Certification',
                'group'   => '',   // leave blank until an ACF group is added
                'next'    => 'f13',
            ],

            // F-13: Corrective Action Request
            'f13' => [
                'title'   => 'F-13 Corrective Action Request',
                'group'   => 'group_qms_f13',
                'next'    => 'f14',
            ],

            // F-14: Conflict of Interest Declaration
            'f14' => [
                'title'   => 'F-14 Conflict of Interest Declaration',
                'group'   => 'group_67e69bef71256',
                'next'    => 'sheet6',
            ],

            // Sheet 6: Audit Notification Email (template‐only)
            'sheet6' => [
                'title'     => 'QMS – Audit Notification Email',
                'group'     => '',  // no ACF form; use subject/message/pdf_field keys
                'next'      => null,
                'subject'   => 'QMS Attendance Sheet Stage 2',
                'message'   => '
                    <p>Dear {{client_name}},</p>
                    <p>Your Attendance Sheet (F-13) is attached.</p>
                    <p>Download: <a href="{{pdf_link}}">{{pdf_name}}</a></p>
                ',
                'pdf_field' => 'sheet6_pdf',
            ],

        ], // end 'qms'

    ];
}


/**
 * Returns all email templates for each certification track.
 * 
 * We add a new 'qms' key without modifying the existing 'ems' definitions.
 */
function get_certification_emails() {
    return [

        /**
         * ──────────────────────────────────────────────────────────────────────
         * EXISTING EMS EMAIL TEMPLATES (UNCHANGED)
         * ──────────────────────────────────────────────────────────────────────
         */
        'ems' => [

            'f01'   => [
                'subject'   => 'EMS Application Received',
                'message'   => '
                    <p>Dear {{client_name}},</p>
                    <p>Thank you for submitting your EMS Application (F-01). Our team will review it and get back to you shortly.</p>
                    <p>Best regards,<br/>Certifications Team</p>
                ',
                'pdf_field' => '', // no PDF for F-01 in EMS
            ],

            'f02'   => [
                'subject'   => 'EMS Application Review Completed',
                'message'   => '
                    <p>Dear {{client_name}},</p>
                    <p>Your EMS Application Review (F-02) is complete. Please see the attached review summary.</p>
                    <p>Download: <a href="{{pdf_link}}">{{pdf_name}}</a></p>
                    <p>Regards,<br/>Certifications Team</p>
                ',
                'pdf_field' => 'f02_pdf',
            ],

            // … (other existing EMS email templates for f03, f05, f06, etc.) …

            'f13'   => [
                'subject'   => 'EMS Corrective Action Request (F-13)',
                'message'   => '
                    <p>Dear {{client_name}},</p>
                    <p>A Corrective Action Request (F-13) has been generated. Please review the attached document and address the non-conformities.</p>
                    <p>Download: <a href="{{pdf_link}}">{{pdf_name}}</a></p>
                    <p>Thank you,<br/>Certifications Team</p>
                ',
                'pdf_field' => 'f13_pdf',
            ],

            'sheet6' => [
                'subject'   => 'EMS Attendance Sheet Stage 2',
                'message'   => '
                    <p>Dear {{client_name}},</p>
                    <p>Your EMS Attendance Sheet (Stage 2) is attached.</p>
                    <p>Download: <a href="{{pdf_link}}">{{pdf_name}}</a></p>
                    <p>Regards,<br/>Certifications Team</p>
                ',
                'pdf_field' => 'sheet6_pdf',
            ],

        ], // end 'ems'


        /**
         * ──────────────────────────────────────────────────────────────────────
         * NEW QMS EMAIL TEMPLATES (DO NOT DISTURB EMS)
         * ──────────────────────────────────────────────────────────────────────
         */
        'qms' => [

            'f01'   => [
                'subject'   => 'QMS Application Received',
                'message'   => '
                    <p>Dear {{client_name}},</p>
                    <p>Thank you for submitting your QMS Application (F-01). Our review team will be in touch shortly.</p>
                    <p>Best regards,<br/>QMS Certifications Team</p>
                ',
                'pdf_field' => '', // no PDF for F-01
            ],

            'f02'   => [
                'subject'   => 'QMS Application Review Completed',
                'message'   => '
                    <p>Dear {{client_name}},</p>
                    <p>Your QMS Application Review (F-02) is complete. Please find the review summary attached.</p>
                    <p>Download: <a href="{{pdf_link}}">{{pdf_name}}</a></p>
                    <p>Regards,<br/>QMS Certifications Team</p>
                ',
                'pdf_field' => 'f02_pdf',
            ],

            'f03'   => [
                'subject'   => 'QMS Certification Agreement (F-03)',
                'message'   => '
                    <p>Dear {{client_name}},</p>
                    <p>Your Certification Agreement (F-03) has been processed. Please review and sign the attached agreement.</p>
                    <p>Download: <a href="{{pdf_link}}">{{pdf_name}}</a></p>
                    <p>Thank you,<br/>QMS Certifications Team</p>
                ',
                'pdf_field' => 'f03_pdf',
            ],

            'f05'   => [
                'subject'   => 'QMS Audit Team Allocation (F-05)',
                'message'   => '
                    <p>Dear {{client_name}},</p>
                    <p>Your Audit Team has been allocated. Find the details in the attached F-05 form.</p>
                    <p>Download: <a href="{{pdf_link}}">{{pdf_name}}</a></p>
                    <p>Regards,<br/>QMS Certifications Team</p>
                ',
                'pdf_field' => 'f05_pdf',
            ],

            'f06'   => [
                'subject'   => 'QMS Document Review Report (F-06)',
                'message'   => '
                    <p>Dear {{client_name}},</p>
                    <p>The Document Review Report (F-06) is ready. Please see the attached document for details.</p>
                    <p>Download: <a href="{{pdf_link}}">{{pdf_name}}</a></p>
                    <p>Thank you,<br/>QMS Certifications Team</p>
                ',
                'pdf_field' => 'f06_pdf',
            ],

            'f07'   => [
                'subject'   => 'QMS Audit Schedule (F-07)',
                'message'   => '
                    <p>Dear {{client_name}},</p>
                    <p>Your Audit Schedule (F-07) has been finalized. Please review the attached schedule.</p>
                    <p>Download: <a href="{{pdf_link}}">{{pdf_name}}</a></p>
                    <p>Regards,<br/>QMS Certifications Team</p>
                ',
                'pdf_field' => 'f07_pdf',
            ],

            'f08'   => [
                'subject'   => 'QMS Certificate Issuance (F-08)',
                'message'   => '
                    <p>Dear {{client_name}},</p>
                    <p>Your QMS Certificate (F-08) has been issued. Please download the certificate using the link below.</p>
                    <p>Download: <a href="{{pdf_link}}">{{pdf_name}}</a></p>
                    <p>Thank you,<br/>QMS Certifications Team</p>
                ',
                'pdf_field' => 'f08_pdf',
            ],

            'f11'   => [
                'subject'   => 'QMS Invoice / Billing Details (F-11)',
                'message'   => '
                    <p>Dear {{client_name}},</p>
                    <p>Your invoice (F-11) is attached. Please arrange payment as per the due date.</p>
                    <p>Download: <a href="{{pdf_link}}">{{pdf_name}}</a></p>
                    <p>Regards,<br/>QMS Billing Team</p>
                ',
                'pdf_field' => 'f11_pdf',
            ],

            'f13'   => [
                'subject'   => 'QMS Corrective Action Request (F-13)',
                'message'   => '
                    <p>Dear {{client_name}},</p>
                    <p>A Corrective Action Request (F-13) has been raised. Please review the attached request and close all non-conformities.</p>
                    <p>Download: <a href="{{pdf_link}}">{{pdf_name}}</a></p>
                    <p>Thank you,<br/>QMS Certifications Team</p>
                ',
                'pdf_field' => 'f13_pdf',
            ],

            'sheet6' => [
                'subject'   => 'QMS Attendance Sheet Stage 2',
                'message'   => '
                    <p>Dear {{client_name}},</p>
                    <p>Your QMS Attendance Sheet (Stage 2) is attached.</p>
                    <p>Download: <a href="{{pdf_link}}">{{pdf_name}}</a></p>
                    <p>Regards,<br/>QMS Certifications Team</p>
                ',
                'pdf_field' => 'sheet6_pdf',
            ],

        ], // end 'qms'

    ];
}
