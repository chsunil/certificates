<?php
function get_certification_stages() {
    return [
        'ems' => [
            'draft'     => ['title' => 'Draft', 'group' => 'group_67dc014741369', 'next' => 'f01'],
            'f01'       => ['title' => 'F-01 EMS Application', 'group' => 'group_f01_ems', 'next' => 'f02'],
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
            'f14'       => ['title' => 'F-14 Confidentiality', 'group' => '', 'next' => 'f15'],
            'f15'       => ['title' => 'F-15 Communication & Correspondence', 'group' => '', 'next' => 'f16'],
            'f16'       => ['title' => 'F-16 Audit Programme', 'group' => '', 'next' => 'f19'],
            'f19'       => ['title' => 'F-19 Certification Decision Checklist', 'group' => '', 'next' => 'f24'],
            'f24'       => ['title' => 'F-24 Customer Feedback', 'group' => '', 'next' => 'completed'],
            'completed' => ['title' => 'Completed', 'group' => '', 'next' => null],
        ],
    ];
}
