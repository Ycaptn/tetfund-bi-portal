<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Hasob\FoundationCore\Models\User;
use Illuminate\Support\Facades\Http;
use App\Models\BeneficiaryMember;
use Illuminate\Support\Facades\Redis;

class SyncUsersToBIMSJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $user;

    /**
     * The number of seconds after which the job's unique lock will be released.
     *
     * @var int
     */
    public $uniqueFor = 3600;
 
    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return $this->user->id;
    }

    public function __construct(User $user)
    {
        //
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id',$this->user->id)->first();
        $user = $this->user;

       // Redis::throttle('key')->block(0)->allow(1)->every(10)->then(function () use($user, $beneficiary_member){
       //     info('Lock obtained...');

            // if (env('APP_ENV') != 'local') {
                if (env('BIMS_CLIENT_ID') && env('BIMS_IS_ENABLED') == true && env('BIMS_REGISTERATION_URI') != null) {
                    //register user on bims
                  try {
                    //code...
                  } catch (\Throwable $th) {
                    //throw $th;
                  }
                    $gender = strtolower($user->gender) == "female" ? "F" : "M" ;
                    $response = Http::acceptJson()->post(env('BIMS_REGISTERATION_URI'), [
                        "client_id" => env('BIMS_CLIENT_ID'),
                        "first_name" => $user->first_name,
                        "last_name" => $user->last_name,
                        "phone" => $user->telephone,
                        "email" => $user->email,
                        "gender" => $gender,
                        "type" => strtolower(optional($beneficiary_member)->member_type) == "academic" ? "LECTURER" : "STAFF",
                    ])->throw();   

                    if($response->ok()){
                        info("migrated user $user->email");
                    }else{
                        info("error could not migrate user $user->email");
                    }
                    
                    
                }
           // }
     
            // Handle job...
     //   }, function () {
            // Could not obtain lock...
     
         //   return $this->release(10);
       // });

       

    }

    public function middleware()
    {
        return [new \Illuminate\Queue\Middleware\ThrottlesExceptions(5, 60)];
    }

}
