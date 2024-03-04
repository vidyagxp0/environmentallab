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
        $group->name = "Approver";
        $group->description = "Approver";
        $group->permission = json_encode(['read' => true, 'create' => true, 'edit' => true, 'delete' => true]);
        $group->save();

        $group  = new RoleGroup();
        $group->name = "Reviewer";
        $group->description = "Reviewer";
        $group->permission = json_encode(['read' => true, 'create' => false, 'edit' => true, 'delete' => false]);
        $group->save();

        $group  = new RoleGroup();
        $group->name = "Originator";
        $group->description = "Originator";
        $group->permission = json_encode(['read' => true, 'create' => false, 'edit' => true, 'delete' => false]);
        $group->save();

        $group  = new RoleGroup();
        $group->name = "HOD";
        $group->description = "HOD";
        $group->permission = json_encode(['read' => true, 'create' => true, 'edit' => true, 'delete' => true]);
        $group->save();

        $group  = new RoleGroup();
        $group->name = "CFT";
        $group->description = "CFT";
        $group->permission = json_encode(['read' => true, 'create' => true, 'edit' => true, 'delete' => true]);
        $group->save();

        $group  = new RoleGroup();
        $group->name = "Trainer";
        $group->description = "Trainer";
        $group->permission = json_encode(['read' => true, 'create' => true, 'edit' => true, 'delete' => true]);
        $group->save();

        $group  = new RoleGroup();
        $group->name = "QA";
        $group->description = "QA";
        $group->permission = json_encode(['read' => true, 'create' => true, 'edit' => true, 'delete' => true]);
        $group->save();

        $group  = new RoleGroup();
        $group->name = "Action Owner";
        $group->description = "Action Owner";
        $group->permission = json_encode(['read' => true, 'create' => true, 'edit' => true, 'delete' => true]);
        $group->save();
    }
}
