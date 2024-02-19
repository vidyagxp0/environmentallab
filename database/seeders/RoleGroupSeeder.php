<?php

namespace Database\Seeders;

use App\Models\RoleGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $group  = new RoleGroup();
        $group->name = 1;
        $group->name = "Approver";
        $group->description = "Approver";
        $group->permission = json_encode(['read' => true, 'create' => true, 'edit' => true, 'delete' => true]);
        $group->save();

        $group  = new RoleGroup();
        $group->name = 2;
        $group->name = "Reviewer";
        $group->description = "Reviewer";
        $group->permission = json_encode(['read' => true, 'create' => false, 'edit' => true, 'delete' => false]);
        $group->save();

        $group  = new RoleGroup();
        $group->name = 3;
        $group->name = "Originator";
        $group->description = "Originator";
        $group->permission = json_encode(['read' => true, 'create' => false, 'edit' => true, 'delete' => false]);
        $group->save();

        $group  = new RoleGroup();
        $group->name = 4;
        $group->name = "HOD";
        $group->description = "HOD";
        $group->permission = json_encode(['read' => true, 'create' => true, 'edit' => true, 'delete' => true]);
        $group->save();

        $group  = new RoleGroup();
        $group->name = 5;
        $group->name = "CFT";
        $group->description = "CFT";
        $group->permission = json_encode(['read' => true, 'create' => true, 'edit' => true, 'delete' => true]);
        $group->save();

        $group  = new RoleGroup();
        $group->name = 6;
        $group->name = "Trainer";
        $group->description = "Trainer";
        $group->permission = json_encode(['read' => true, 'create' => true, 'edit' => true, 'delete' => true]);
        $group->save();

        $group  = new RoleGroup();
        $group->name = 7;
        $group->name = "QA";
        $group->description = "QA";
        $group->permission = json_encode(['read' => true, 'create' => true, 'edit' => true, 'delete' => true]);
        $group->save();

        $group  = new RoleGroup();
        $group->name = 8;
        $group->name = "Action Owner";
        $group->description = "Action Owner";
        $group->permission = json_encode(['read' => true, 'create' => true, 'edit' => true, 'delete' => true]);
        $group->save();

        $group  = new RoleGroup();
        $group->name = 9;
        $group->name = "QA Head Designee";
        $group->description = "QA Head Designee";
        $group->permission = json_encode(['read' => true, 'create' => true, 'edit' => true, 'delete' => true]);
        $group->save();

        $group  = new RoleGroup();
        $group->name = 10;
        $group->name = "QC Head/ Designee";
        $group->description = "QC Head/ Designee";
        $group->permission = json_encode(['read' => true, 'create' => true, 'edit' => true, 'delete' => true]);
        $group->save();


        $group  = new RoleGroup();
        $group->name = 11;
        $group->name = "Lead Auditee";
        $group->description = "Lead Auditee";
        $group->permission = json_encode(['read' => true, 'create' => true, 'edit' => true, 'delete' => true]);
        $group->save();

        $group  = new RoleGroup();
        $group->name = 12;
        $group->name = "Lead Auditor";
        $group->description = "Lead Auditor";
        $group->permission = json_encode(['read' => true, 'create' => true, 'edit' => true, 'delete' => true]);
        $group->save();

        $group  = new RoleGroup();
        $group->name = 13;
        $group->name = "Audit Manager";
        $group->description = "Audit Manager";
        $group->permission = json_encode(['read' => true, 'create' => true, 'edit' => true, 'delete' => true]);
        $group->save();

        $group  = new RoleGroup();
        $group->name = 14;
        $group->name = "Supervisor";
        $group->description = "Supervisor";
        $group->permission = json_encode(['read' => true, 'create' => true, 'edit' => true, 'delete' => true]);
        $group->save();

        $group  = new RoleGroup();
        $group->name = "Responsible Person";
        $group->description = "Responsible Person";
        $group->permission = json_encode(['read' => true, 'create' => true, 'edit' => true, 'delete' => true]);
        $group->save();

        $group  = new RoleGroup();
        $group->name = "Work Group (Risk Management Head)";
        $group->description = "Work Group (Risk Management Head)";
        $group->permission = json_encode(['read' => true, 'create' => true, 'edit' => true, 'delete' => true]);
        $group->save();
    }
}
