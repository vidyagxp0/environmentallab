<?php

namespace Database\Seeders;

use App\Models\Department;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Corporate Quality Assurance
$department = new Department();
$department->id = 1; // Set the desired ID
$department->name = "Corporate Quality Assurance";
$department->dc = "CQA";
$department->save();

// Quality Assurance Biopharma
$department = new Department();
$department->id = 2; // Set the desired ID
$department->name = "Quality Assurance Biopharma";
$department->dc = "QAB";
$department->save();

// Central Quality Control
$department = new Department();
$department->id = 3; // Set the desired ID
$department->name = "Central Quality Control";
$department->dc = "CQC";
$department->save();

// Manufacturing
$department = new Department();
$department->id = 4; // Set the desired ID
$department->name = "Manufacturing";
$department->dc = "Manuf";
$department->save();

// Plasma Sourcing Group
$department = new Department();
$department->id = 5; // Set the desired ID
$department->name = "Plasma Sourcing Group";
$department->dc = "PSG";
$department->save();

// Central Stores
$department = new Department();
$department->id = 6; // Set the desired ID
$department->name = "Central Stores";
$department->dc = "CStores";
$department->save();

// Information Technology Group
$department = new Department();
$department->id = 7; // Set the desired ID
$department->name = "Information Technology Group";
$department->dc = "ITG";
$department->save();

// Molecular Medicine
$department = new Department();
$department->id = 8; // Set the desired ID
$department->name = "Molecular Medicine";
$department->dc = "MM";
$department->save();

// Central Laboratory
$department = new Department();
$department->id = 9; // Set the desired ID
$department->name = "Central Laboratory";
$department->dc = "CL";
$department->save();

// Tech Team
$department = new Department();
$department->id = 10; // Set the desired ID
$department->name = "Tech Team";
$department->dc = "Tech";
$department->save();

// Quality Assurance
$department = new Department();
$department->id = 11; // Set the desired ID
$department->name = "Quality Assurance";
$department->dc = "QA";
$department->save();

// Quality Management
$department = new Department();
$department->id = 12; // Set the desired ID
$department->name = "Quality Management";
$department->dc = "QM";
$department->save();

// IT Administration
$department = new Department();
$department->id = 13; // Set the desired ID
$department->name = "IT Administration";
$department->dc = "ITAdmin";
$department->save();

// Accounting
$department = new Department();
$department->id = 14; // Set the desired ID
$department->name = "Accounting";
$department->dc = "Acct";
$department->save();

// Logistics
$department = new Department();
$department->id = 15; // Set the desired ID
$department->name = "Logistics";
$department->dc = "Log";
$department->save();

// Senior Management
$department = new Department();
$department->id = 16; // Set the desired ID
$department->name = "Senior Management";
$department->dc = "SM";
$department->save();

// Business Administration
$department = new Department();
$department->id = 17; // Set the desired ID
$department->name = "Business Administration";
$department->dc = "BA";
$department->save();

// xyz-voom-bus
$department = new Department();
$department->id = 18; // Set the desired ID
$department->name = "xyz-voom-bus";
$department->dc = "XYZ";
$department->save();

    }
}
