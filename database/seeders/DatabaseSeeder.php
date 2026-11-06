<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;
use App\Models\Bug;

class DatabaseSeeder extends Seeder
{
 public function run(): void {
  $admin=User::firstOrCreate(['email'=>'admin@bugtrack.test'],['name'=>'BugTrack Admin','password'=>'ChangeMe123!','role'=>'admin','is_active'=>true]);
  $dev=User::firstOrCreate(['email'=>'developer@bugtrack.test'],['name'=>'Demo Developer','password'=>'ChangeMe123!','role'=>'developer','is_active'=>true]);
  $project=Project::firstOrCreate(['key'=>'DEMO'],['name'=>'Demo Website','description'=>'Sample project for BugTrack portfolio demonstration.','created_by'=>$admin->id,'is_active'=>true]);
  $samples=[
   ['title'=>'Login button is unresponsive on mobile','severity'=>'high','priority'=>'high','status'=>'open'],
   ['title'=>'Product filter returns incorrect results','severity'=>'medium','priority'=>'normal','status'=>'in_progress'],
   ['title'=>'Header overlaps page content on tablet','severity'=>'critical','priority'=>'urgent','status'=>'resolved'],
   ['title'=>'Validation message is missing for email field','severity'=>'low','priority'=>'low','status'=>'closed'],
  ];
  foreach($samples as $i=>$s){
   $bug=Bug::firstOrCreate(['bug_key'=>'DEMO-'.str_pad((string)($i+1),3,'0',STR_PAD_LEFT)],$s+[
    'project_id'=>$project->id,'description'=>'Demonstration bug report. Review the issue, reproduce it, and update its status.','steps_to_reproduce'=>"1. Open the application\n2. Navigate to the affected page\n3. Perform the described action",'expected_result'=>'The feature should work as intended.','actual_result'=>'The observed behaviour differs from the expected result.','environment'=>'Chrome / Windows 11','reported_by'=>$admin->id,'assigned_to'=>$dev->id,'resolved_at'=>in_array($s['status'],['resolved','closed'])?now():null
   ]);
   $bug->activities()->firstOrCreate(['action'=>'Bug created'],['user_id'=>$admin->id]);
  }
 }
}
