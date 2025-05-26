<?php
function get_certification_stages() {
    return [
        'ems' => [
            'draft'     => ['title' => 'Draft', 'group' => 'group_67dc014741369', 'next' => 'f01'],
            'f01'       => ['title' => 'F-01 EMS Application', 'group' => 'group_68222fbec9c41', 'next' => 'f02'],
            'f02'       => ['title' => 'F-02 EMS Application Review', 'group' => 'group_f02_technical_review', 'next' => 'f03'],
            'f03'       => ['title' => 'F-03 Certification Agreement', 'group' => 'group_67e6940d632ef', 'next' => 'f05'],
            'f05'       => ['title' => 'F-05 Audit Team Allocation Plan Stage 2', 'group' => 'group_f05_ems_audit_team_allocation_plan_stage_1', 'next' => 'f06'],
            'f06'       => ['title' => 'F-06 Document Review Report', 'group' => '', 'next' => 'f08'],
            'f08'       => ['title' => 'F-08 Audit Schedule Stage 2', 'group' => '', 'next' => 'f11'],
            'f11'       => ['title' => 'F-11 Stage 1 Audit Report', 'group' => '', 'next' => 'f09'],
            'f09'       => ['title' => 'F-09 Stage 2 Audit Report', 'group' => '', 'next' => 'f10'],
            'f10'       => ['title' => 'F-10 Non-Conformity', 'group' => '', 'next' => 'f12'],
            'f12'       => ['title' => 'F-12 Scope of Certification', 'group' => '', 'next' => 'f13'],
            'f13'       => ['title' => 'F-13 Attendance Sheet Stage 2', 'group' => '', 'next' => 'f14'],
            'f14'       => ['title' => 'F-14 Confidentiality', 'group' => '', 'next' => 'sheet6'],
            'sheet6'       => ['title' => 'Stage Sheet 6', 'group' => '', 'next' => 'f15'],
            'f15'       => ['title' => 'F-15 Communication & Correspondence', 'group' => '', 'next' => 'f16'],
            'f16'       => ['title' => 'F-16 Audit Programme', 'group' => '', 'next' => 'f19'],
            'f19'       => ['title' => 'F-19 Certification Decision Checklist', 'group' => '', 'next' => 'f24'],
            'f24'       => ['title' => 'F-24 Customer Feedback', 'group' => '', 'next' => 'completed'],
            'completed' => ['title' => 'Completed', 'group' => '', 'next' => null],
        ],
        'qms' => [
            'draft'     => ['title' => 'Draft', 'group' => 'group_67dc014741369', 'next' => 'f01'],
            'f01'       => ['title' => 'F-01 QMS Application', 'group' => 'group_68173ed286e57', 'next' => 'f02'],
            'f02'       => ['title' => 'F-02 QMS Application Review', 'group' => 'group_f02_technical_review', 'next' => 'f03'],
            'f03'       => ['title' => 'F-03 Certification Agreement', 'group' => 'group_67e6940d632ef', 'next' => 'f05'],
            'f05'       => ['title' => 'F-05 EMS_ Audit Team Allocation Plan Stage 1', 'group' => 'group_qms_f05', 'next' => 'f14'],
            'f14'       => ['title' => 'QMS - F14: Conflict of Interest Declaration', 'group' => 'group_qms_f14', 'next' => 'Sheet6'],
            'sheet6'       => ['title' => 'QMS - Sheet6: Audit Notification Email', 'group' => 'group_qms_sheet6', 'next' => ''],

            // Add more stages as needed
        ],
        // Add more certification types as needed
    ];
}


// Add email templates for EMS stages
function get_certification_emails() {
    return [
        'ems' => [
            'f03' => [
                'subject' => 'EMS Certification Agreement Ready',
                'message' => '
                    <p>Dear {{client_name}},</p>
                    <p>Your EMS Certification Agreement (F-03) is attached.</p>
                    <p>Download: <a href="{{pdf_link}}">{{pdf_name}}</a></p>
                    <p>Regards,<br>Certification Team</p>
                ',
                'pdf_field' => 'f03_pdf' // ACF field name for F-03 PDF
            ],
            'f06' => [
                'subject' => 'EMS Document Review Report',
                'message' => '
                    <p>Dear {{client_name}},</p>
                    <p>Your Document Review Report (F-06) is attached.</p>
                    <p>Download: <a href="{{pdf_link}}">{{pdf_name}}</a></p>
                ',
                'pdf_field' => 'f06_pdf' // ACF field name for F-06 PDF
            ],
            'f08' => [
                'subject' => 'EMS Audit Schedule Stage 2',
                'message' => '
                    <p>Dear {{client_name}},</p>
                    <p>Your Stage 2 Audit Schedule (F-08) is attached.</p>
                    <p>Download: <a href="{{pdf_link}}">{{pdf_name}}</a></p>
                ',
                'pdf_field' => 'f08_pdf' // ACF field name for F-08 PDF
            ],
            'f11' => [
                'subject' => 'EMS Stage 1 Audit Report',
                'message' => '
                    <p>Dear {{client_name}},</p>
                    <p>Your Stage 1 Audit Report (F-11) is attached.</p>
                    <p>Download: <a href="{{pdf_link}}">{{pdf_name}}</a></p>
                ',

                'pdf_field' => 'f11_pdf' // ACF field name for F-11 PDF
            ],
            'f13' => [
                'subject' => 'EMS Attendance Sheet Stage 2',
                'message' => '
                    <p>Dear {{client_name}},</p>
                    <p>Your Attendance Sheet (F-13) is attached.</p>
                    <p>Download: <a href="{{pdf_link}}">{{pdf_name}}</a></p>
                ',
                'pdf_field' => 'f13_pdf' // ACF field name for F-13 PDF
            ],
            'sheet6' => [
                'subject' => 'EMS Attendance Sheet Stage 2',
                'message' => '
                    <p>Dear {{client_name}},</p>
                    <p>Your Attendance Sheet (F-13) is attached.</p>
                    <p>Download: <a href="{{pdf_link}}">{{pdf_name}}</a></p>
                ',
                'pdf_field' => 'sheet6_pdf' // ACF field name for sheet6_pdf PDF
            ]
        ],
        'qms' => [
            'f03' => [
                'subject' => 'QMS Certification Agreement Ready',
                'message' => '
                    <p>Dear {{client_name}},</p>
                    <p>Your QMS Certification Agreement (F-03) is attached.</p>
                    <p>Download: <a href="{{pdf_link}}">{{pdf_name}}</a></p>
                    <p>Regards,<br>Certification Team</p>
                ',
                'pdf_field' => 'f03_pdf' // ACF field name for F-03 PDF
            ],
            'f06' => [
                'subject' => 'QMS Document Review Report',
                'message' => '
                    <p>Dear {{client_name}},</p>
                    <p>Your Document Review Report (F-06) is attached.</p>
                    <p>Download: <a href="{{pdf_link}}">{{pdf_name}}</a></p>
                ',
                'pdf_field' => 'f06_pdf' // ACF field name for F-06 PDF
            ],
            'f08' => [
                'subject' => 'QMS Audit Schedule Stage 2',
                'message' => '
                    <p>Dear {{client_name}},</p>
                    <p>Your Stage 2 Audit Schedule (F-08) is attached.</p>
                    <p>Download: <a href="{{pdf_link}}">{{pdf_name}}</a></p>
                ',
                'pdf_field' => 'f08_pdf' // ACF field name for F-08 PDF
            ],
            'f11' => [
                'subject' => 'QMS Stage 1 Audit Report',
                'message' => '
                    <p>Dear {{client_name}},</p>
                    <p>Your Stage 1 Audit Report (F-11) is attached.</p>
                    <p>Download: <a href="{{pdf_link}}">{{pdf_name}}</a></p>
                ',
                'pdf_field' => 'f11_pdf' // ACF field name for F-11 PDF
            ],
            'f13' => [
                'subject' => 'QMS Attendance Sheet Stage 2',
                'message' => '
                    <p>Dear {{client_name}},</p>
                    <p>Your Attendance Sheet (F-13) is attached.</p>
                    <p>Download: <a href="{{pdf_link}}">{{pdf_name}}</a></p>
                ',
                'pdf_field' => 'f13_pdf' // ACF field name for F-13 PDF
            ],
            'sheet6' => [
                'subject' => 'QMS Attendance Sheet Stage 2',
                'message' => '
                    <p>Dear {{client_name}},</p>
                    <p>Your Attendance Sheet (F-13) is attached.</p>
                    <p>Download: <a href="{{pdf_link}}">{{pdf_name}}</a></p>
                ',
                'pdf_field' => 'sheet6_pdf' // ACF field name for sheet6_pdf PDF
            ]
        ]
    ];
}
